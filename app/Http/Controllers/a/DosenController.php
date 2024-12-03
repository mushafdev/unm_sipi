<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Fakultas;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Dosen';
        $d['jurusans']=Jurusan::get();
        $d['prodis']=Prodi::get();
        return view('a.pages.dosen.index',$d);
    }

    public function get_dosen(Request $request)
    {
        if ($request->ajax()) {
            $data = Dosen::
            leftJoin('prodis','prodis.id','=','dosens.prodi_id')
            ->leftJoin('jurusans','jurusans.id','=','prodis.jurusan_id')
            ->select('dosens.*','prodis.prodi','jurusans.jurusan')
            ->orderBy('dosens.created_at','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        $btn.= '<li><a href="#" data-id="' . encrypt0($row->id) . '" title="Edit" class="dropdown-item edit" data-bs-toggle="modal" data-bs-target="#modal"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>
                        <li><a href="#" class="dropdown-item delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($row->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete</a></li>';
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('aktif', function($row){
                        if($row->aktif=='Y'){
                            $aktif='<span class="badge bg-success">Aktif</span>';
                        }else{
                            $aktif='<span class="badge bg-danger">Tidak Aktif</span>';
                        }
                        return $aktif;
                    })
                    ->addColumn('id_enc', function($row){
                        
                        return encrypt0($row->id);
                    })

                    ->rawColumns(['action','aktif','user_photo','role'])
                    ->make(true);
        }
    }

    public function get_data(Request $request)
    {
        $id = decrypt0($request->id);
        $q = Dosen::where('id', $id)->first();

        return response()->json([
            'id' => encrypt0($q->id),
            'result' => $q,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $d['title']='Tambah Dosen';
        return view('a.pages.dosen.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id='';
        if(!empty($request->id)){
            $id=decrypt0($request->id);
        }
        $rules = [
            'nama'=>'required',
            'email'=>'nullable|email:rfc,dns',
            'nip'=>'required|unique:dosens,nip,'.$id,
            'pangkat'=>'required',
            'golongan'=>'required',
            'jabatan'=>'required',
            'prodi_id'=>'required',
            'username'=>'nullable|unique:dosens|min:5|max:50',
            'password'=>'nullable|min:5|max:50',
            'aktif'=>'nullable',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            if ($request->param == 'add') {

                $field = new Dosen;
                $field->inserted_by =  $request->session()->get('id');
                $text = 'Data sukses disimpan';
            } else {

                $field = Dosen::find($id);
                $field->updated_by =  $request->session()->get('id');
                $text = 'Data sukses diupdate';
            }
            

            $field->nama = $request->nama;
            $field->email = $request->email;
            $field->nip = $request->nip;
            $field->pangkat = $request->pangkat;
            $field->golongan = $request->golongan;
            $field->jabatan = $request->jabatan;
            $field->prodi_id = $request->prodi_id;
            $field->aktif = 'Y';
            $field->role = 'dosen';
            if(!empty($request->username)){
                $field->username = $request->username;
            }
            if(!empty($request->password)){
                $field->password = bcrypt($request->password);
            }

            $field->save();
            return response()->json([
                'text' => $text,
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $field = Dosen::find(decrypt0($id));
            $field->delete();
            return response()->json([
                'text' => 'Data sukses dihapus',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
}

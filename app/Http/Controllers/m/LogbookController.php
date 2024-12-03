<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logbook;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Logbook';
        return view('u.pages.logbook.index',$d);
    }

    public function get_logbook(Request $request)
    {
        if ($request->ajax()) {
            $id=$request->session()->get('id');
            $data = Logbook::where('mahasiswa_id',$id)
            ->orderBy('tanggal','desc')
            ->orderBy('jam','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        $btn.= '<li><a href="'.route('logbook.edit',encrypt0($row->id)).'" title="Edit" class="dropdown-item edit"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>
                        <li><span class="dropdown-item  delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($row->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete</span></li>';
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('waktu', function($row){
                        
                        return convertYmdToMdy2($row->tanggal).'<span class="badge bg-light">'.$row->jam.'</span>';
                    })
                    ->addColumn('verify', function($row){
                        if($row->verify=='N'){
                            $verify='<span class="badge bg-success">Belum diverifikasi</span>';
                        }else{
                            $verify='<span class="badge bg-danger">Terverifikasi</span>';
                        }
                        return $verify;
                    })

                    ->rawColumns(['action','verify','waktu','role'])
                    ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $d['title']='Tambah Kegiatan';
        return view('u.pages.logbook.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'tanggal'=>'required',
            'jam'=>'required',
            'kegiatan'=>'required',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $field = new Logbook;
            $field->tanggal = $request->tanggal;
            $field->jam = $request->jam;
            $field->kegiatan = $request->kegiatan;
            $field->mahasiswa_id =  $request->session()->get('id');
            $field->inserted_by =  $request->session()->get('id');

            $field->save();
            return response()->json([
                'text' => 'Data sukses disimpan',
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
        $d['title']='Edit Kegiatan';
        $d['data'] = Logbook::find(decrypt0($id));
        return view('u.pages.logbook.edit',$d);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'tanggal'=>'required',
            'jam'=>'required',
            'kegiatan'=>'required',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $field = Logbook::find(decrypt0($id));
            $field->tanggal = $request->tanggal;
            $field->jam = $request->jam;
            $field->kegiatan = $request->kegiatan;
            $field->updated_by =  $request->session()->get('id');

            $field->save();
            return response()->json([
                'text' => 'Data sukses diupdate',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $field = Logbook::find(decrypt0($id));
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

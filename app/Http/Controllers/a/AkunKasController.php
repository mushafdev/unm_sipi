<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AkunKas;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class AkunKasController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:akun kas-list', only : ['index','get_akun_kas','get_data']),
            new Middleware('permission:akun kas-create', only : ['store']),
            new Middleware('permission:akun kas-edit', only : ['store']),
            new Middleware('permission:akun kas-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Akun Kas';
        return view('a.pages.akun_kas.index',$d);
    }

    public function get_akun_kas(Request $request)
    {
        if ($request->ajax()) {
            $data = AkunKas::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        if (Gate::allows('akun kas-edit')) {
                            $btn.= '<li><a href="#" data-id="' . encrypt0($row->id) . '" title="Edit" class="dropdown-item edit" data-bs-toggle="modal" data-bs-target="#modal"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>';
                        }
                        if (Gate::allows('akun kas-delete')) {
                            $btn.= '<li><a href="#" class="dropdown-item delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($row->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete</a></li>';
                        }
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })

                    ->rawColumns(['action','aktif','user_photo','role'])
                    ->make(true);
        }
    }

    public function get_data(Request $request)
    {
        $id = decrypt0($request->id);
        $q = AkunKas::find($id);

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
        $d['title']='Tambah AkunKas';
        return view('a.pages.akun_kas.create',$d);
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
            'nama_akun'=>'required|max:20',
            'nomor_akun'=>'nullable|max:20',
            'nomor_rekening'=>'nullable|max:20',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            $text='';
            DB::transaction(function() use ($request,$id, &$text ) {
                if ($request->param == 'add') {

                    $field = new AkunKas;
                    $field->inserted_by =  $request->session()->get('id');
                    $text = 'Data sukses disimpan';
                } else {

                    $field = AkunKas::find($id);
                    $field->updated_by =  $request->session()->get('id');
                    $text = 'Data sukses diupdate';
                }

                $field->nama_akun = $request->nama_akun;
                $field->nomor_akun = $request->nomor_akun;
                $field->nomor_rekening = $request->nomor_rekening;
                $field->save();

            });
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
            $field = AkunKas::find(decrypt0($id));
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

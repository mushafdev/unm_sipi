<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\KategoriItem;
use App\Models\Satuan;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:item-list', only : ['index','get_item','get_data']),
            new Middleware('permission:item-create', only : ['store']),
            new Middleware('permission:item-edit', only : ['store']),
            new Middleware('permission:item-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Item';
        $d['satuans']=Satuan::select('id','satuan')->get();
        $d['kategories']=KategoriItem::select('id','kategori_item')->get();
        return view('a.pages.item.index',$d);
    }

    public function get_item(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('items')->join('kategori_items','kategori_items.id','=','items.kategori_item_id')->select('items.*','kategori_items.kategori_item')
            ->orderBy('items.created_at','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        if (Gate::allows('item-edit')) {
                            $btn.= '<li><a href="#" data-id="' . encrypt0($row->id) . '" title="Edit" class="dropdown-item edit" data-bs-toggle="modal" data-bs-target="#modal"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>';
                        }
                        if (Gate::allows('item-delete')) {
                            $btn.= '<li><a href="#" class="dropdown-item delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($row->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete</a></li>';
                        }
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })


                    ->addColumn('harga_jual', function($row){
                        return toCurrency($row->harga_jual);
                    })
                    
                    ->addColumn('isi', function($row){
                        return $row->isi.' '.$row->satuan;
                    })
                    
                    ->addColumn('reorder_point', function($row){
                        return $row->reorder_point.' '.$row->satuan;
                    })

                    ->rawColumns(['action','aktif','user_photo','role'])
                    ->make(true);
        }
    }

    public function get_data(Request $request)
    {
        $id = decrypt0($request->id);
        $q = Item::find($id);
        
        return response()->json([
            'id' => encrypt0($q->id),
            'kategori' => encrypt1($q->kategori_item_id),
            'result' => $q,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $d['title']='Tambah Item';
        return view('a.pages.item.create',$d);
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
            'nama_item'=>'required',
            'kategori'=>'required',
            'besaran'=>'required',
            'isi'=>'required',
            'satuan'=>'required',
            'hpp'=>'required',
            'harga_jual'=>'required',
            'reorder_point'=>'required',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            $text='';
            DB::transaction(function() use ($request,$id, &$text ) {
                if ($request->param == 'add') {

                    $field = new Item;
                    $field->inserted_by =  $request->session()->get('id');
                    $text = 'Data sukses disimpan';
                } else {

                    $field = Item::find($id);
                    $field->updated_by =  $request->session()->get('id');
                    $text = 'Data sukses diupdate';
                }

                $field->nama_item = $request->nama_item;
                $field->kategori_item_id = decrypt1($request->kategori);
                $field->besaran = $request->besaran;
                $field->isi = $request->isi;
                $field->satuan = $request->satuan;
                $field->hpp = CurrencytoDb($request->hpp);
                $field->harga_jual = CurrencytoDb($request->harga_jual);
                $field->reorder_point = $request->reorder_point;
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
            $field = Item::find(decrypt0($id));
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

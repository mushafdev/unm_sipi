<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemStok;
use App\Models\ItemStokAwal;
use App\Models\ItemStokAwalDetail;
use App\Models\Gudang;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\ItemHelper;
use App\Helpers\TransactionHelper;



class ItemStokAwalController extends Controller implements HasMiddleware
{ 
    public static function middleware(): array
    {
        return [
            new Middleware('permission:stok awal-list', only : ['index','get_pasien','show']),
            new Middleware('permission:stok awal-create', only : ['create','store']),
            new Middleware('permission:stok awal-edit', only : ['edit','update']),
            new Middleware('permission:stok awal-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Stok Awal';
        $d['gudangs']=DB::table('gudangs')->select('id','gudang')->get();
        return view('a.pages.stok_awal.index',$d);
    }

    public function get_stok_awal(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        $columns = [
            0 => 'item_stok_awals.id',
            1 => 'item_stok_awals.waktu',
            2 => 'gudangs.gudang',
            3 => 'item_stok_awals.penanggung_jawab',
            4 => 'item_stok_awals.catatan',
            5 => 'item_stok_awals.is_locked',
        ];

        $query = DB::table('item_stok_awals')
            ->join('gudangs','gudangs.id','=','item_stok_awals.gudang_id')
            ->select(
                'item_stok_awals.*','gudangs.gudang'
            );

        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('gudangs.gudang', 'like', "%$search%")
                ->orWhere('item_stok_awals.penanggung_jawab', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');
        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'item_stok_awals.nama'; 
        $orderDir = $request->input('order.0.dir', 'asc');

        $lists = $query
            ->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        $index = $start + 1;
        
        foreach ($lists as $dt) {
            $action ='';
            $action='<div class="btn-group">
            <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu">';
            $action.='<li><h6 class="dropdown-header">Action </h6></li>';
            if (Gate::allows('stok awal-list')) {
                $action.= '<li><a href="'.route('stok-awal.show',encrypt0($dt->id)).'" title="Detail" class="dropdown-item detail text-info"><i class="icon-mid bi bi-eye me-2" aria-hidden="true"></i> Detail</a></li>';
                
            }
            
            $action.='</ul>';
            $action.='</div>';

            $data[] = [
                'DT_RowIndex' => $index++,
                'id_enc' => encrypt0($dt->id),
                'no_transaksi' => $dt->no_transaksi,
                'waktu' => toDatetime($dt->waktu),
                'gudang' => $dt->gudang,
                'penanggung_jawab' => $dt->penanggung_jawab,
                'catatan' => $dt->catatan,
                'is_locked' => '<span class="badge '.TransactionHelper::StokAwalisLocked($dt->is_locked,'class').'">'.TransactionHelper::StokAwalisLocked($dt->is_locked,'text').'</span>',
                'action' => $action,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $gudang_id=decrypt0($request->gudang);
        $d['title']='Stok Awal';
        $gudangs=DB::table('gudangs')->select('id','gudang')->where('id',$gudang_id)->first();
        if(!$gudangs){
            return redirect()->route('stok-awal.index');
        }
        $d['gudangs'] = $gudangs;
        return view('a.pages.stok_awal.create',$d);
    }
    
    public function get_item_stok_awal(Request $request)
    {
        abort_unless($request->ajax(), 403, 'Akses tidak sah');

        /** ----------------------------------------------------------------
         *  PARAMETER DASAR
         *  ----------------------------------------------------------------*/
        $gudang_id       = decrypt0($request->gudang_id);        // gudang aktif
        $item_stok_awal_id       = decrypt0($request->item_stok_awal_id);        // gudang aktif
       
        /** ----------------------------------------------------------------
         *  BASE QUERY  (item_stoks  ←→  stok_awal_drafts)
         *  ----------------------------------------------------------------*/
        $query = DB::table('item_stok_awal_details')
            ->leftJoin('item_stok_awals','item_stok_awals.id', '=', 'item_stok_awal_details.item_stok_awal_id')
            ->join('items', 'items.id', '=', 'item_stok_awal_details.item_id')
            ->where('item_stok_awals.gudang_id', $gudang_id)
            ->where('item_stok_awals.id', $item_stok_awal_id)
            ->selectRaw('
                item_stok_awal_details.id,
                items.nama_item                                               AS item,
                item_stok_awal_details.no_batch,
                item_stok_awal_details.tgl_kadaluarsa,
                item_stok_awal_details.stok_awal,                       
                item_stok_awals.is_locked                      
            ');

        if ($request->kadaluarsa_filter) {
            $now = now();

            if ($request->kadaluarsa_filter == 'kadaluarsa') {
                $query->whereDate('item_stoks.tgl_kadaluarsa', '<', $now);
            } elseif ($request->kadaluarsa_filter == 'akan_kadaluarsa') {
                $query->whereBetween('item_stoks.tgl_kadaluarsa', [$now, $now->copy()->addDays(30)]);
            } elseif ($request->kadaluarsa_filter == 'aman') {
                $query->whereDate('item_stoks.tgl_kadaluarsa', '>', $now->copy()->addDays(30));
            }
        }

        $recordsTotal = (clone $query)->count();

        /* pencarian global */
        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.nama_item', 'LIKE', "%{$search}%")
                ->orWhere('item_stoks.no_batch', 'LIKE', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        /** ----------------------------------------------------------------
         *  ORDER, LIMIT, OFFSET
         *  ----------------------------------------------------------------*/
        $columns = [
            0 => 'item',              // DT_RowIndex tidak dipakai sortir
            1 => 'item',              // nama item
            2 => 'item_stoks.no_batch',
            3 => 'item_stoks.tgl_kadaluarsa',
            4 => 'stok_awal', // aksi
            5=> 'aksi', // aksi
        ];

        $orderColumn = $columns[$request->input('order.0.column', 1)] ?? 'item';
        $orderDir    = $request->input('order.0.dir', 'asc');

        $dataList = $query
            ->orderBy($orderColumn, $orderDir)
            ->offset($request->input('start'))
            ->limit($request->input('length') == -1 ? 100000000 : $request->input('length'))
            ->get();

        /** ----------------------------------------------------------------
         *  BENTUKKAN ARRAY RESPONSE
         *  ----------------------------------------------------------------*/
        $data   = [];
        $index  = $request->input('start') + 1;

        foreach ($dataList as $row) {
            $today = now();
            $expDate = \Carbon\Carbon::parse($row->tgl_kadaluarsa);

            if ($today->gt($expDate)) {
                $expiredBadge = '<span class="badge bg-light-danger text-danger">Kadaluarsa</span>';
            } elseif ($today->diffInDays($expDate, false) <= 31) {
                $expiredBadge = '<span class="badge bg-light-warning text-warning">Akan Kadaluarsa</span>';
            } else {
                $expiredBadge = '<span class="badge bg-light-success text-success">Aman</span>';
            }


            $stokAwal = $row->stok_awal;
            $stokAwalFormatted = (fmod($stokAwal, 1) == 0) 
                ? number_format($stokAwal, 0, ',', '.') 
                : number_format($stokAwal, 2, ',', '.');

            
            $aksi='';
            if($row->is_locked==='N'){
                $aksi.='<button type="button" class="btn icon2 btn-danger delete_item" data-id="' . encrypt1($row->id) . '"><i class="icon-mid bi bi-trash"></i></button>';
            }
            $data[] = [
                'DT_RowIndex'  => $index++,
                'item'         => e($row->item),
                'batch_exp'    => e(Carbon::parse($row->tgl_kadaluarsa)->format('d/m/Y')),
                'expired'      => $expiredBadge,
                'stok_awal'      => $stokAwalFormatted,
                'aksi'         => $aksi,
            ];
        }

        /** ----------------------------------------------------------------
         *  RETURN JSON DATATABLES
         *  ----------------------------------------------------------------*/
        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
    
   

    public function saveItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'item_stok_awal_id' => 'required',
            'stok_awal' => 'required|numeric',
            'tgl_kadaluarsa' => 'required',
        ]);

        try {
            $itemStokAwalId=decrypt0($request->item_stok_awal_id);
            $itemId=$request->item_id;
            $gudangId=decrypt0($request->gudang_id);
            $tglKadaluarsa=$request->tgl_kadaluarsa;
            $stokAwal=$request->stok_awal;
            $error='';
            $id='';
            $cekStokAwal = DB::table('item_stok_awal_details')->where([
                ['item_id', '=', $itemId],
                ['tgl_kadaluarsa', '=', $tglKadaluarsa]
            ])->first();

            if ($cekStokAwal) {
                return response()->json(['message' => 'Item sudah ada'], 500); 
            }
            DB::transaction(function() use ($request,&$itemStokAwalId,&$itemId,&$tglKadaluarsa,&$gudangId,&$stokAwal) {

              

                DB::table('item_stok_awal_details')->insert(
                    [
                        'item_id' => $itemId,
                        'item_stok_awal_id' => $itemStokAwalId,
                        'tgl_kadaluarsa' => $tglKadaluarsa,
                        'stok_awal' => $stokAwal,
                    ],
                );

            
            });
            return response()->json([
                'text' => 'Data sukses disimpan',
                'id' => $id,
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
    
    public function deleteItem(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $Id=decrypt1($request->id);
            DB::transaction(function() use ($request,&$Id) {

                DB::table('item_stok_awal_details')->where('id',$Id)->delete();

            
            });
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
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gudang_id'        => 'required',         
            'waktu'            => 'required',
            'penanggung_jawab' => 'required|string|max:255',
            'catatan'          => 'nullable|string',
        ]);

        $gudangId = decrypt0($request->gudang_id);
        $userId   = $request->session()->get('id');

        DB::beginTransaction();
        try {
            $awal               = new ItemStokAwal();
            $awal->no_transaksi = strtoupper(Str::random(10));
            $awal->waktu        = $request->waktu ?? now();
            $awal->gudang_id    = $gudangId;
            $awal->penanggung_jawab = $request->penanggung_jawab;
            $awal->catatan      = $request->catatan;
            $awal->inserted_by  = $userId; 
            $awal->save();
        DB::commit();

            return response()->json([
                'text'   => 'Data Sukses disimpan.',
                'id'     => encrypt0($awal->id),
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('item_stok_awals')
            ->select(
                'item_stok_awals.*',
                'gudangs.gudang',
                'users.username',
            )
            ->leftJoin('gudangs', 'item_stok_awals.gudang_id', '=', 'gudangs.id')
            ->leftJoin('users', 'users.id', '=', 'item_stok_awals.inserted_by')
            ->where('item_stok_awals.id', decrypt0($id))
            ->first();

        if (!$data) {
            abort(404, 'Stok awal tidak ditemukan');
        }

        $d['title'] = 'Detail Stok Awal';
        $d['data'] = $data;

        return view('a.pages.stok_awal.show', $d);
    }

    public function posting(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $stokAwalId = decrypt0($request->id);

            DB::transaction(function () use ($stokAwalId, $request) {

                /* ── 1. AMBIL HEADER STOK AWAL ─────────────────────────── */
                $stokAwal = ItemStokAwal::find($stokAwalId);

                if (!$stokAwal) {
                    throw new \Exception('Data stok awal tidak ditemukan', 404);
                }

                if ($stokAwal->is_locked === 'Y') {
                    throw new \Exception('Stok awal sudah diposting & terkunci', 422);
                }

                /* ── 2. KUNCI HEADER ───────────────────────────────────── */
                $stokAwal->is_locked   = 'Y';
                $stokAwal->inserted_by = $request->session()->get('id');
                $stokAwal->save();

                /* ── 3. LOOP DETAIL ───────────────────────────────────── */
                $details = DB::table('item_stok_awal_details')
                    ->where('item_stok_awal_id', $stokAwal->id)
                    ->get();

                foreach ($details as $detail) {

                    ItemStok::updateOrCreate(
                        [
                            'item_id'        => $detail->item_id,
                            'gudang_id'      => $stokAwal->gudang_id,
                            'tgl_kadaluarsa' => $detail->tgl_kadaluarsa,
                
                        ],
                        [
                            'no_batch'       => $detail->no_batch,
                            'stok'        => $detail->stok_awal,
                            'hpp'         => $detail->hpp ?? 0,
                            'waktu_masuk' => $stokAwal->waktu,
                        ]
                    );

                    /* 3b. Simpan histori */
                    ItemHelper::createHistory(
                        $stokAwal->no_transaksi,
                        $stokAwal->id,
                        'item_stok_awals',
                        'Stok Awal',
                        $detail->item_id,
                        $detail->stok_awal,
                        $detail->hpp ?? 0,
                        'reset',                        // tipe
                        $stokAwal->waktu,
                        $stokAwal->catatan ?? 'Input stok awal',
                        $detail->no_batch,
                        $detail->tgl_kadaluarsa,
                        $stokAwal->gudang_id
                    );
                }
            });

            return response()->json([
                'text'   => 'Data stok awal berhasil diposting & dikunci.',
                'status' => 200
            ]);

        } catch (\Throwable $e) {

            $code = ($e->getCode() === 404 || $e->getCode() === 422) ? $e->getCode() : 500;

            return response()->json([
                'message' => $e->getMessage()
            ], $code ?: 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string$id)
    // {
    //     try {
    //         DB::transaction(function () use ($id) {
    //             $field = ItemTransaction::find(decrypt0($id));
    //             $jenis = $field->jenis_transaksi;
    //             switch ($jenis) {
    //                 case 'Masuk':
    //                     $jenis_table = 'item_transactions';
    //                     $jenis_text = 'Batal Item Masuk';
    //                     $catatan = NULL;
    //                     $type='keluar';
    //                     break;
                
    //                 case 'Keluar':
    //                     $jenis_table = 'item_transactions';
    //                     $jenis_text = 'Batal Item Keluar';
    //                     $catatan = NULL;
    //                     $type='masuk';
    //                     break;
                
    //                 case 'Transfer':
    //                     $jenis_table = 'item_transactions';
    //                     $jenis_text = 'Transfer Item';
    //                     $catatan = NULL;
    //                     $type='transfer';
    //                     break;
                
    //                 default:
    //                     throw new \Exception("Jenis transaksi tidak valid");
    //             }
    //             $details = ItemTransactionDetail::where('item_transaction_id', $field->id)->get();
    //             foreach ($details as $detail) {
    //                 $itemId = $detail->item_id;
    //                 $qty = $detail->qty;
    //                 $no_batch = $detail->no_batch;
    //                 $tgl_kadaluarsa = $detail->tgl_kadaluarsa;

    //                 if ($jenis === 'Masuk') {
    //                     $stok = ItemStok::where([
    //                         'item_id' => $itemId,
    //                         'gudang_id' => $field->gudang_id,
    //                         'tgl_kadaluarsa' => $tgl_kadaluarsa,
    //                     ])->first();

    //                     if ($stok) {
    //                         $stok->decrement('stok', $qty);
    //                     }

    //                 } elseif ($jenis === 'Keluar') {
    //                     ItemStok::updateOrCreate(
    //                         [
    //                             'item_id' => $itemId,
    //                             'gudang_id' => $field->gudang_id,
    //                             'tgl_kadaluarsa' => $tgl_kadaluarsa,
    //                         ],
    //                         [
    //                             'no_batch' => $no_batch,
    //                             'waktu_masuk' => now(),
    //                             'hpp' => 0,
    //                         ]
    //                     )->increment('stok', $qty);

    //                 } elseif ($jenis === 'Transfer') {
    //                     // Tambahkan stok kembali ke gudang asal
    //                     ItemStok::updateOrCreate(
    //                         [
    //                             'item_id' => $itemId,
    //                             'gudang_id' => $field->gudang_id,
    //                             'tgl_kadaluarsa' => $tgl_kadaluarsa,
    //                         ],
    //                         [
    //                             'no_batch' => $no_batch,
    //                             'waktu_masuk' => now(),
    //                             'hpp' => 0,
    //                         ]
    //                     )->increment('stok', $qty);

    //                     // Kurangi stok dari gudang tujuan
    //                     $stokTujuan = ItemStok::where([
    //                         'item_id' => $itemId,
    //                         'gudang_id' => $field->gudang_tujuan_id,
    //                         'tgl_kadaluarsa' => $tgl_kadaluarsa,
    //                     ])->first();

    //                     if ($stokTujuan) {
    //                         $stokTujuan->decrement('stok', $qty);
    //                     }
    //                 }
    //                 if($jenis==='Masuk' || $jenis==='Keluar'){
    //                     ItemHelper::createHistory(
    //                         $field->no_transaksi,
    //                         $field->id,
    //                         $jenis_table,
    //                         $jenis_text,
    //                         $itemId,
    //                         $qty,
    //                         $detail->hpp,
    //                         $type,
    //                         $field->waktu,
    //                         $catatan,
    //                         $no_batch,
    //                         $tgl_kadaluarsa,
    //                         $field->gudang_id
    //                     );
    //                 }else{
    //                     // Dari gudang asal
    //                     ItemHelper::createHistory(
    //                         $field->no_transaksi,
    //                         $field->id,
    //                         $jenis_table,
    //                         $jenis_text,
    //                         $itemId,
    //                         $qty,
    //                         $detail->hpp,
    //                         'masuk',
    //                         $field->waktu,
    //                         'Batal Transfer keluar ke '.Gudang::find($field->gudang_id)->gudang. $catatan,
    //                         $no_batch,
    //                         $tgl_kadaluarsa,
    //                         $field->gudang_id
    //                     );

    //                     // Ke gudang tujuan
    //                     ItemHelper::createHistory(
    //                         $field->no_transaksi,
    //                         $field->id,
    //                         $jenis_table,
    //                         $jenis_text,
    //                         $itemId,
    //                         $qty,
    //                         $detail->hpp,
    //                         'keluar',
    //                         $field->waktu,
    //                         'Batal Transfer masuk dari '.Gudang::find($field->gudang_tujuan_id)->gudang. $catatan,
    //                         $no_batch,
    //                         $tgl_kadaluarsa,
    //                         $field->gudang_tujuan_id
    //                     );
    //                 }
    //             }

    //             $field->cancel='Y';
    //             $field->save();
    //         });

    //         return response()->json([
    //             'text' => 'Transaksi berhasil dibatalkan.',
    //             'status' => 200
    //         ]);
    //     } catch (Throwable $e) {
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}

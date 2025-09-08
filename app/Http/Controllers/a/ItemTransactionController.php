<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemStok;
use App\Models\ItemTransaction;
use App\Models\ItemTransactionDetail;
use App\Models\Gudang;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Enums\ItemTransactionEnum;
use App\Rules\StokCukup;
use App\Helpers\ItemHelper;
use App\Helpers\TransactionHelper;



class ItemTransactionController extends Controller implements HasMiddleware
{ 
    public static function middleware(): array
    {
        return [
            new Middleware('permission:item transaction-list', only : ['index','get_pasien','show']),
            new Middleware('permission:item transaction-create', only : ['create','store']),
            new Middleware('permission:item transaction-edit', only : ['edit','update']),
            new Middleware('permission:item transaction-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Transaksi Stok';
        $d['gudangs']=DB::table('gudangs')->select('id','gudang')->get();
        $d['item_transactions'] = ItemTransactionEnum::cases();
        return view('a.pages.item_transaction.index',$d);
    }

    public function get_item_transaction(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        
        $start_date= Carbon::parse($request->start_date)->startOfDay();
        $end_date=Carbon::parse($request->end_date)->endOfDay();

        $columns = [
            0 => 'item_transactions.id',
            1 => 'item_transactions.no_transaksi',
            3 => 'item_transactions.waktu',
            4 => 'item_transactions.no_referensi',
            5 => 'item_transactions.penanggung_jawab',
            6 => 'item_transactions.jenis_transaksi',
            7 => 'item_transactions.status',
        ];

        $query = DB::table('item_transactions')
            ->select(
                'item_transactions.*',
            )->where(function ($q) use ($start_date,$end_date) {
           
                $q->whereBetween('item_transactions.waktu', [$start_date, $end_date]);
            });

        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('item_transactions.no_transaksi', 'like', "%$search%")
                ->orWhere('item_transactions.penanggung_jawab', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');
        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'item_transactions.nama'; 
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
            if (Gate::allows('item transaction-list')) {
                $action.= '<li><a href="'.route('item-transaction.show',encrypt0($dt->id)).'" title="Detail" class="dropdown-item detail text-info"><i class="icon-mid bi bi-eye me-2" aria-hidden="true"></i> Detail</a></li>';
                
            }
            if($dt->cancel==='N'){
                if (Gate::allows('item transaction-delete')) {
                    $action.= '<li><span class="dropdown-item  delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($dt->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Cancel</span></li>';
                    
                }
            }
            
            $action.='</ul>';
            $action.='</div>';

            $data[] = [
                'DT_RowIndex' => $index++,
                'id_enc' => encrypt0($dt->id),
                'no_transaksi' => $dt->no_transaksi,
                'waktu' => toDatetime($dt->waktu),
                'no_referensi' => $dt->no_referensi,
                'penanggung_jawab' => $dt->penanggung_jawab,
                'jenis_transaksi' => '<span class="badge '.TransactionHelper::jenis_transaksi($dt->jenis_transaksi).'">'.$dt->jenis_transaksi.'</span>',
                'cancel' => '<span class="badge '.TransactionHelper::cancel($dt->cancel,'class').'">'.TransactionHelper::cancel($dt->cancel,'text').'</span>',
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
        $d['title']='Tambah Transaksi Stok';
        $d['gudangs']=DB::table('gudangs')->select('id','gudang')->get();
        $d['jenis_transaksi'] = $request->jenis??'Masuk';
        return view('a.pages.item_transaction.create',$d);
    }

    public function generateNoTransaksi()
    {
        $prefix = 'TRSTOK';
        $now = Carbon::now();
        $ym = $now->format('ym'); // YYMM format

        // Cari no_transaksi terakhir berdasarkan pola
        $last = DB::table('item_transactions')
            ->whereNotNull('waktu') // Hindari data waktu null
            ->where('no_transaksi', 'like', "{$prefix}-{$ym}%")
            ->orderByDesc(DB::raw("CAST(SUBSTRING(no_transaksi, -7) AS UNSIGNED)"))
            ->first();

        if ($last && preg_match('/(\d{7})$/', $last->no_transaksi, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        $number = str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        return "{$prefix}-{$ym}{$number}";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'no_referensi'       => 'nullable|string|max:255',
            'waktu'              => 'nullable',
            'gudang_id'          => 'required',
            'jenis_transaksi'    => 'required|in:Masuk,Keluar,Transfer',
            'penanggung_jawab'   => 'nullable|string|max:255',
            'grand_total'        => 'nullable|numeric|min:0',
            'catatan'            => 'nullable|string',
            'item_id.*'        => 'required',
            'qty.*'          => 'required|numeric|min:1',
            'tgl_kadaluarsa.*' => 'nullable|required|date_format:Y-m-d',
            'no_batch.*'      => 'nullable|string|max:255',
        ];

        if ($request->jenis_transaksi === 'Transfer') {
            $rules['gudang_tujuan_id'] = 'required';
        }

        if (in_array($request->jenis_transaksi, ['Keluar', 'Transfer'])) {
            foreach ($request->item_id as $i => $item_id) {
                $rules["qty.$i"][] = new StokCukup(
                    $item_id,
                    decrypt1($request->gudang_id),
                    $request->tgl_kadaluarsa[$i] ?? null,
                    $request->no_batch[$i] ?? null,
                );
            }
        }

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            $id='';
            DB::transaction(function() use ($request,&$id) {
                $jenis=$request->jenis_transaksi;

                $field = new ItemTransaction;
                $field->no_transaksi       = $this->generateNoTransaksi(); // atau pakai UUID atau nomor urut
                $field->no_referensi       = $request->no_referensi;
                $field->waktu              = $request->waktu ?? Carbon::now();
                $field->gudang_id          = decrypt1($request->gudang_id);
                if ($jenis === 'Transfer') {
                    $field->gudang_tujuan_id   = decrypt1($request->gudang_tujuan_id);
                }
                $field->jenis_transaksi    = $jenis;
                $field->penanggung_jawab   = $request->penanggung_jawab;
                $field->grand_total        = $request->grand_total ?? 0;
                $field->catatan            = $request->catatan;
                $field->inserted_by =  $request->session()->get('id');
                $field->save();

                $id=encrypt0($field->id);

                switch ($jenis) {
                    case 'Masuk':
                        $jenis_table = 'item_transactions';
                        $jenis_text = 'Item Masuk';
                        $catatan = $field->catatan;
                        $type='masuk';
                        break;
                
                    case 'Keluar':
                        $jenis_table = 'item_transactions';
                        $jenis_text = 'Item Keluar';
                        $catatan = $field->catatan;
                        $type='keluar';
                        break;
                
                    case 'Transfer':
                        $jenis_table = 'item_transactions';
                        $jenis_text = 'Transfer Item';
                        $catatan = $field->catatan;
                        $type='transfer';
                        break;
                
                    default:
                        throw new \Exception("Jenis transaksi tidak valid");
                }

                $count=count($request->item_id);
                for($i=0;$i<$count;$i++){
                    $itemId = $request->item_id[$i];
                    $qty = $request->qty[$i];
                    $no_batch = $request->no_batch[$i] ?? null;
                    $tgl_kadaluarsa = $request->tgl_kadaluarsa[$i] ?? null;

                    // Simpan transaksi detail
                    $item = new ItemTransactionDetail();
                    $item->item_id = $itemId;
                    $item->item_transaction_id = $field->id;
                    $item->qty = $qty;
                    $item->no_batch = $no_batch;
                    $item->tgl_kadaluarsa = $tgl_kadaluarsa;
                    $item->hpp = 0; // isi sesuai kebutuhan
                    $item->save();

                    // Untuk jenis transaksi: masuk, keluar, transfer
                    if ($jenis === 'Masuk') {
                        ItemStok::updateOrCreate(
                            [
                                'item_id' => $itemId,
                                'gudang_id' => decrypt1($request->gudang_id),
                                'gudang_id' => $field->gudang_id,
                                'tgl_kadaluarsa' => $tgl_kadaluarsa,
                            ],
                            [
                                'no_batch' => $no_batch,
                                'waktu_masuk' => $field->waktu, 
                                'hpp' => 0, 
                            ]
                        )->increment('stok', $qty);

                    } elseif ($jenis === 'Keluar') {
                        $stok = ItemStok::where([
                            ['item_id', '=', $itemId],
                            ['gudang_id', '=', decrypt1($request->gudang_id)],
                            // ['no_batch', '=', $no_batch],
                            ['tgl_kadaluarsa', '=', $tgl_kadaluarsa]
                        ])->first();

                        if (!$stok || $stok->stok < $qty) {
                            throw new \Exception("Stok tidak cukup untuk item $itemId, batch $no_batch");
                        }

                        $stok->decrement('stok', $qty);

                    } elseif ($jenis === 'Transfer') {
                        // Kurangi stok di gudang asal
                        ItemStok::where([
                            ['item_id', '=', $itemId],
                            ['gudang_id', '=', decrypt1($request->gudang_id)],
                            // ['no_batch', '=', $no_batch],
                            ['tgl_kadaluarsa', '=', $tgl_kadaluarsa]
                        ])->decrement('stok', $qty);

                        // Tambah stok ke gudang tujuan
                        ItemStok::updateOrCreate(
                            [
                                'item_id' => $itemId,
                                'gudang_id' => decrypt1($request->gudang_tujuan_id),
                                // 'no_batch' => $no_batch,
                                'tgl_kadaluarsa' => $tgl_kadaluarsa,
                            ],
                            [
                                'no_batch' => $no_batch,
                                'waktu_masuk' => $field->waktu,
                                'hpp' => 0,
                            ]
                        )->increment('stok', $qty);
                    }

                    if($jenis==='Masuk' || $jenis==='Keluar'){
                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            $jenis_table,
                            $jenis_text,
                            $item->item_id,
                            $item->qty,
                            $item->hpp,
                            $type,
                            $field->waktu,
                            $catatan,
                            $item->no_batch,
                            $item->tgl_kadaluarsa,
                            $field->gudang_id
                        );
                    }else{
                        // Dari gudang asal
                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            $jenis_table,
                            $jenis_text,
                            $item->item_id,
                            $item->qty,
                            $item->hpp,
                            'keluar',
                            $field->waktu,
                            'Transfer keluar ke '.Gudang::find($field->gudang_id)->gudang. $catatan,
                            $item->no_batch,
                            $item->tgl_kadaluarsa,
                            $field->gudang_id
                        );

                        // Ke gudang tujuan
                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            $jenis_table,
                            $jenis_text,
                            $item->item_id,
                            $item->qty,
                            $item->hpp,
                            'masuk',
                            $field->waktu,
                            'Transfer masuk dari '.Gudang::find($field->gudang_tujuan_id)->gudang. $catatan,
                            $item->no_batch,
                            $item->tgl_kadaluarsa,
                            $field->gudang_tujuan_id
                        );
                    }
                }


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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data=DB::table('item_transactions')
        ->select(
            'item_transactions.*',
            'gudang_asal.gudang as gudang_asal',
            'gudang_tujuan.gudang as gudang_tujuan',
        )
        ->leftJoin('gudangs as gudang_asal','item_transactions.gudang_id','=','gudang_asal.id')
        ->leftJoin('gudangs as gudang_tujuan','item_transactions.gudang_tujuan_id','=','gudang_tujuan.id')
        ->where('item_transactions.id',decrypt0($id))
        ->first();
        if (!$data) {
            abort(404, 'User not found');
        }
        $d['title']='Detail Transaksi Stok';
        $d['data'] = $data;
        $d['details'] = DB::table('item_transaction_details')
        ->join('items','item_transaction_details.item_id','=','items.id')
        ->select('item_transaction_details.*','items.nama_item as item_nama','items.satuan as item_satuan')
        ->where('item_transaction_details.item_transaction_id',$data->id)
        ->get();
        return view('a.pages.item_transaction.show',$d);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string$id)
    {
        try {
            DB::transaction(function () use ($id) {
                $field = ItemTransaction::find(decrypt0($id));
                $jenis = $field->jenis_transaksi;
                switch ($jenis) {
                    case 'Masuk':
                        $jenis_table = 'item_transactions';
                        $jenis_text = 'Batal Item Masuk';
                        $catatan = NULL;
                        $type='keluar';
                        break;
                
                    case 'Keluar':
                        $jenis_table = 'item_transactions';
                        $jenis_text = 'Batal Item Keluar';
                        $catatan = NULL;
                        $type='masuk';
                        break;
                
                    case 'Transfer':
                        $jenis_table = 'item_transactions';
                        $jenis_text = 'Transfer Item';
                        $catatan = NULL;
                        $type='transfer';
                        break;
                
                    default:
                        throw new \Exception("Jenis transaksi tidak valid");
                }
                $details = ItemTransactionDetail::where('item_transaction_id', $field->id)->get();
                foreach ($details as $detail) {
                    $itemId = $detail->item_id;
                    $qty = $detail->qty;
                    $no_batch = $detail->no_batch;
                    $tgl_kadaluarsa = $detail->tgl_kadaluarsa;

                    if ($jenis === 'Masuk') {
                        $stok = ItemStok::where([
                            'item_id' => $itemId,
                            'gudang_id' => $field->gudang_id,
                            'tgl_kadaluarsa' => $tgl_kadaluarsa,
                        ])->first();

                        if ($stok) {
                            $stok->decrement('stok', $qty);
                        }

                    } elseif ($jenis === 'Keluar') {
                        ItemStok::updateOrCreate(
                            [
                                'item_id' => $itemId,
                                'gudang_id' => $field->gudang_id,
                                'tgl_kadaluarsa' => $tgl_kadaluarsa,
                            ],
                            [
                                'no_batch' => $no_batch,
                                'waktu_masuk' => now(),
                                'hpp' => 0,
                            ]
                        )->increment('stok', $qty);

                    } elseif ($jenis === 'Transfer') {
                        // Tambahkan stok kembali ke gudang asal
                        ItemStok::updateOrCreate(
                            [
                                'item_id' => $itemId,
                                'gudang_id' => $field->gudang_id,
                                'tgl_kadaluarsa' => $tgl_kadaluarsa,
                            ],
                            [
                                'no_batch' => $no_batch,
                                'waktu_masuk' => now(),
                                'hpp' => 0,
                            ]
                        )->increment('stok', $qty);

                        // Kurangi stok dari gudang tujuan
                        $stokTujuan = ItemStok::where([
                            'item_id' => $itemId,
                            'gudang_id' => $field->gudang_tujuan_id,
                            'tgl_kadaluarsa' => $tgl_kadaluarsa,
                        ])->first();

                        if ($stokTujuan) {
                            $stokTujuan->decrement('stok', $qty);
                        }
                    }
                    if($jenis==='Masuk' || $jenis==='Keluar'){
                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            $jenis_table,
                            $jenis_text,
                            $itemId,
                            $qty,
                            $detail->hpp,
                            $type,
                            $field->waktu,
                            $catatan,
                            $no_batch,
                            $tgl_kadaluarsa,
                            $field->gudang_id
                        );
                    }else{
                        // Dari gudang asal
                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            $jenis_table,
                            $jenis_text,
                            $itemId,
                            $qty,
                            $detail->hpp,
                            'masuk',
                            $field->waktu,
                            'Batal Transfer keluar ke '.Gudang::find($field->gudang_id)->gudang. $catatan,
                            $no_batch,
                            $tgl_kadaluarsa,
                            $field->gudang_id
                        );

                        // Ke gudang tujuan
                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            $jenis_table,
                            $jenis_text,
                            $itemId,
                            $qty,
                            $detail->hpp,
                            'keluar',
                            $field->waktu,
                            'Batal Transfer masuk dari '.Gudang::find($field->gudang_tujuan_id)->gudang. $catatan,
                            $no_batch,
                            $tgl_kadaluarsa,
                            $field->gudang_tujuan_id
                        );
                    }
                }

                $field->cancel='Y';
                $field->save();
            });

            return response()->json([
                'text' => 'Transaksi berhasil dibatalkan.',
                'status' => 200
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

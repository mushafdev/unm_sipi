<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemStok;
use App\Models\StokOpname;
use App\Models\StokOpnameDetail;
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



class ItemStokOpnameController extends Controller implements HasMiddleware
{ 
    public static function middleware(): array
    {
        return [
            new Middleware('permission:stok opname-list', only : ['index','get_pasien','show']),
            new Middleware('permission:stok opname-create', only : ['create','store']),
            new Middleware('permission:stok opname-edit', only : ['edit','update']),
            new Middleware('permission:stok opname-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Stok Opname';
        $d['gudangs']=DB::table('gudangs')->select('id','gudang')->get();
        return view('a.pages.stok_opname.index',$d);
    }

    public function get_stok_opname(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        
        $start_date= Carbon::parse($request->start_date)->startOfDay();
        $end_date=Carbon::parse($request->end_date)->endOfDay();

        $columns = [
            0 => 'stok_opnames.id',
            1 => 'stok_opnames.no_transaksi',
            3 => 'stok_opnames.waktu',
            4 => 'gudangs.gudang',
            5 => 'stok_opnames.penanggung_jawab',
            6 => 'stok_opnames.catatan',
            7 => 'stok_opnames.cancel',
        ];

        $query = DB::table('stok_opnames')
            ->join('gudangs','gudangs.id','=','stok_opnames.gudang_id')
            ->select(
                'stok_opnames.*','gudangs.gudang'
            )->where(function ($q) use ($start_date,$end_date) {
           
                $q->whereBetween('stok_opnames.waktu', [$start_date, $end_date]);
            });

        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('stok_opnames.no_transaksi', 'like', "%$search%")
                ->orWhere('stok_opnames.penanggung_jawab', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');
        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'stok_opnames.nama'; 
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
            if (Gate::allows('stok opname-list')) {
                $action.= '<li><a href="'.route('stok-opname.show',encrypt0($dt->id)).'" title="Detail" class="dropdown-item detail text-info"><i class="icon-mid bi bi-eye me-2" aria-hidden="true"></i> Detail</a></li>';
                
            }
            // if($dt->status!=='batal'){
            //     if (Gate::allows('stok opname-delete')) {
            //         $action.= '<li><span class="dropdown-item  delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($dt->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Cancel</span></li>';
                    
            //     }
            // }
            
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
                'cancel' => '<span class="badge '.TransactionHelper::statusOpname($dt->status,'class').'">'.TransactionHelper::statusOpname($dt->status,'text').'</span>',
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
        $d['title']='Stok Opname';
        $gudangs=DB::table('gudangs')->select('id','gudang')->where('id',$gudang_id)->first();
        if(!$gudangs){
            return redirect()->route('stok-opname.index');
        }
        $d['gudangs'] = $gudangs;
        return view('a.pages.stok_opname.create',$d);
    }
    
    public function get_item_stok_opname(Request $request)
    {
        abort_unless($request->ajax(), 403, 'Akses tidak sah');

        /** ----------------------------------------------------------------
         *  PARAMETER DASAR
         *  ----------------------------------------------------------------*/
        $gudang_id       = decrypt0($request->gudang_id);        // gudang aktif
        // $stok_opname_id  = decrypt0($request->stok_opname_id);   // transaksi opname aktif
        $status          = $request->input('status', 'belum');   // 'belum' | 'sudah'

        /** ----------------------------------------------------------------
         *  BASE QUERY  (item_stoks  ←→  stok_opname_drafts)
         *  ----------------------------------------------------------------*/
        $query = DB::table('item_stoks')
            ->leftJoin('stok_opname_drafts','item_stoks.id', '=', 'stok_opname_drafts.item_stok_id')
            ->join('items', 'items.id', '=', 'item_stoks.item_id')
            ->where('item_stoks.gudang_id', $gudang_id)
            ->selectRaw('
                item_stoks.id                                            AS item_stok_id,
                items.nama_item                                               AS item,
                item_stoks.no_batch,
                item_stoks.tgl_kadaluarsa,
                item_stoks.stok                                          AS stok_system,
                COALESCE(stok_opname_drafts.stok_fisik, 0)               AS stok_fisik,
                COALESCE(stok_opname_drafts.id, 0)                       AS draft_id
            ');

        if ($status === 'belum') {
            $query->whereNull('stok_opname_drafts.id');
        } else { 
            $query->whereNotNull('stok_opname_drafts.id'); 
        }

        if ($request->kadaluarsa_filter) {
            $now = now();

            if ($request->kadaluarsa_filter == 'kadaluarsa') {
                $query->whereDate('item_stoks.tgl_kadaluarsa', '<', $now);
            } elseif ($request->kadaluarsa_filter == 'akan_kadaluarsa') {
                $query->whereBetween('item_stoks.tgl_kadaluarsa', [$now, $now->copy()->addDays(90)]);
            } elseif ($request->kadaluarsa_filter == 'aman') {
                $query->whereDate('item_stoks.tgl_kadaluarsa', '>', $now->copy()->addDays(90));
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
            4 => 'stok_system',
            5 => 'stok_fisik',
            6 => 'draft_id',          // aksi
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

            $selisih = $row->stok_fisik - $row->stok_system;

            $stokSystem = $row->stok_system;
            $stokSystemFormatted = (fmod($stokSystem, 1) == 0) 
                ? number_format($stokSystem, 0, ',', '.') 
                : number_format($stokSystem, 2, ',', '.');

            // Ambil stok fisik, jika kosong tetap kosong
            $stokFisikRaw = ($row->stok_fisik == 0 || is_null($row->stok_fisik)) ? '' : $row->stok_fisik;

            // Format stok fisik jika tidak kosong
            $stokFisikFormatted = ($stokFisikRaw === '') ? '' : (
                (fmod($stokFisikRaw, 1) == 0)
                    ? number_format($stokFisikRaw, 0, ',', '.')
                    : number_format($stokFisikRaw, 2, ',', '.')
            );

            $data[] = [
                'DT_RowIndex'  => $index++,
                'item'         => e($row->item),
                'batch_exp'    => e(Carbon::parse($row->tgl_kadaluarsa)->format('d/m/Y')),
                'expired'      => $expiredBadge,
                'stok_sistem'  => '<input class="form-control text-center" type="text" readonly value="' . $stokSystemFormatted . '">',
                'stok_fisik'   => '<input class="form-control text-center stok-fisik-input" type="text" name="stok_fisik[]" value="' . $stokFisikFormatted . '" placeholder="?">',
                'selisih'      => '<input class="form-control text-center selisih-field" type="number" readonly value="' . $selisih . '">',
                'aksi'         => '<button type="button" class="btn icon2 btn-primary update_item d-none" data-id="' . $row->draft_id . '" data-stok-id="' . $row->item_stok_id . '"><i class="bi bi-check"></i></button>',
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
    
    public function get_item_stok_opname_detail(Request $request)
    {
        abort_unless($request->ajax(), 403, 'Akses tidak sah');

        $stok_opname_id       = decrypt0($request->stok_opname_id);        // gudang aktif
        $query = DB::table('stok_opname_details')
            ->join('stok_opnames', 'stok_opnames.id', '=', 'stok_opname_details.stok_opname_id')
            ->join('items', 'items.id', '=', 'stok_opname_details.item_id')
            ->where('stok_opname_details.stok_opname_id', $stok_opname_id)
            ->select('stok_opname_details.*','items.nama_item AS item','items.satuan');

        $recordsTotal = (clone $query)->count();

        /* pencarian global */
        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.nama_item', 'LIKE', "%{$search}%")
                ->orWhere('stok_opnames.no_batch', 'LIKE', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        /** ----------------------------------------------------------------
         *  ORDER, LIMIT, OFFSET
         *  ----------------------------------------------------------------*/
        $columns = [
            0 => 'item',              // DT_RowIndex tidak dipakai sortir
            1 => 'satuan',              // nama item
            2 => 'stok_opnames.no_batch',
            3 => 'stok_opnames.tgl_kadaluarsa',
            4 => 'stok_system',
            5 => 'stok_fisik',
            6 => 'selisih',
            7 => 'status',          // aksi
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

            $stokSystem = $row->stok_system;
            $stokSystemFormatted = (fmod($stokSystem, 1) == 0) 
                ? number_format($stokSystem, 0, ',', '.') 
                : number_format($stokSystem, 2, ',', '.');

            $stokFisikRaw = $row->stok_fisik;
            $stokFisikFormatted = ($stokFisikRaw === '') ? '' : (
                (fmod($stokFisikRaw, 1) == 0)
                    ? number_format($stokFisikRaw, 0, ',', '.')
                    : number_format($stokFisikRaw, 2, ',', '.')
            );
            
            $selisihRaw = $row->selisih;
            $selisihFormatted = ($selisihRaw === '') ? '' : (
                (fmod($selisihRaw, 1) == 0)
                    ? number_format($selisihRaw, 0, ',', '.')
                    : number_format($selisihRaw, 2, ',', '.')
            );

            $data[] = [
                'DT_RowIndex'  => $index++,
                'item'         => e($row->item),
                'satuan'         => e($row->satuan),
                'batch_exp'    => e($row->no_batch),
                'expired'      => e(Carbon::parse($row->tgl_kadaluarsa)->format('d/m/Y')),
                'stok_sistem'  => $stokSystemFormatted,
                'stok_fisik'   => $stokFisikFormatted,
                'selisih'      =>  $selisihFormatted,
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

    public function updateItemDraft(Request $request)
    {
        $request->validate([
            'stok_fisik' => 'required|numeric',
            'item_stok_id' => 'required|exists:item_stoks,id',
        ]);

        try {

            $error='';
            $id='';
            $itemStok = DB::table('item_stoks')->where('id', $request->item_stok_id)->first();
            if (!$itemStok) {
                return response()->json(['message' => 'Item stok tidak ditemukan'], 404);
            }
            DB::transaction(function() use ($request,&$itemStok,&$id) {

                // Hitung selisih dan total
                $selisih = $request->stok_fisik - $itemStok->stok;
                $hpp = $itemStok->hpp ?? 0;
                $total = $selisih * $hpp;

                // Simpan atau update ke stok_opname_drafts
                DB::table('stok_opname_drafts')->updateOrInsert(
                    [
                        'item_id' => $itemStok->item_id,
                        'item_stok_id' => $itemStok->id,
                        'gudang_id' => $itemStok->gudang_id,
                        'no_batch' => $itemStok->no_batch,
                        'tgl_kadaluarsa' => $itemStok->tgl_kadaluarsa,
                    ],
                    [
                        'stok_fisik' => $request->stok_fisik,
                        'stok_system' => $itemStok->stok,
                        'selisih' => $selisih,
                        'hpp' => $hpp,
                        'total' => $total,
                        'user_id' => $request->session()->get('id'),
                        'updated_at' => now(),
                    ]
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

        $drafts = DB::table('stok_opname_drafts')
            ->where('gudang_id', $gudangId)
            ->get();

        if ($drafts->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada draft yang dapat disimpan.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $opname               = new StokOpname();
            $opname->no_transaksi = strtoupper(Str::random(10));
            $opname->waktu        = $request->waktu ?? now();
            $opname->gudang_id    = $gudangId;
            $opname->penanggung_jawab = $request->penanggung_jawab;
            $opname->catatan      = $request->catatan;
            $opname->status       = 'selesai';
            $opname->inserted_by  = $userId; 
            $opname->save();

            /* ---------- DETAIL & PENYESUAIAN STOK ---------- */
            foreach ($drafts as $draft) {
                // 1. Simpan ke stok_opname_details
                StokOpnameDetail::create([
                    'stok_opname_id'  => $opname->id,
                    'item_id'         => $draft->item_id,
                    'item_stok_id'    => $draft->item_stok_id,
                    'no_batch'        => $draft->no_batch,
                    'tgl_kadaluarsa'  => $draft->tgl_kadaluarsa,
                    'stok_system'     => $draft->stok_system,
                    'stok_fisik'      => $draft->stok_fisik,
                    'selisih'         => $draft->selisih,
                    'hpp'             => $draft->hpp,
                    'total'           => $draft->total,
                ]);

                // 2. Sesuaikan stok fisik di item_stoks
                $itemStok = ItemStok::find($draft->item_stok_id);
                if ($itemStok) {
                    $itemStok->stok = $draft->stok_fisik;
                    $itemStok->save();
                }

                // 3. (Opsional) histori
                ItemHelper::createHistory(
                    $opname->no_transaksi,
                    $opname->id,
                    'stok_opnames',
                    'Stok Opname',
                    $draft->item_id,
                    $draft->stok_fisik,
                    $draft->hpp,
                    'reset',
                    $opname->waktu,
                    $request->catatan,
                    $draft->no_batch,
                    $draft->tgl_kadaluarsa,
                    $gudangId,
                    $draft->selisih
                );
            }

            /* ---------- HAPUS DRAFT ---------- */
            DB::table('stok_opname_drafts')
                ->where('gudang_id', $gudangId)
                ->delete();

            DB::commit();

            return response()->json([
                'text'   => 'Stok opname berhasil diposting.',
                'id'     => encrypt0($opname->id),
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
        $data = DB::table('stok_opnames')
            ->select(
                'stok_opnames.*',
                'gudangs.gudang',
            )
            ->leftJoin('gudangs', 'stok_opnames.gudang_id', '=', 'gudangs.id')
            ->where('stok_opnames.id', decrypt0($id))
            ->first();

        if (!$data) {
            abort(404, 'Stok opname tidak ditemukan');
        }

        $d['title'] = 'Detail Stok Opname';
        $d['data'] = $data;

        return view('a.pages.stok_opname.show', $d);
    }

}

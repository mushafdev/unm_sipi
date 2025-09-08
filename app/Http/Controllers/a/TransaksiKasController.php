<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemStok;
use App\Models\TransaksiKas;
use App\Models\TransaksiKasDetail;
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
use App\Enums\TransaksiKasEnum;
use App\Rules\StokCukup;
use App\Helpers\ItemHelper;
use App\Helpers\TransactionHelper;



class TransaksiKasController extends Controller implements HasMiddleware
{ 
    public static function middleware(): array
    {
        return [
            new Middleware('permission:transaksi kas-list', only : ['index','get_transaksi_kas','show']),
            new Middleware('permission:transaksi kas-create', only : ['create','store']),
            new Middleware('permission:transaksi kas-edit', only : ['edit','update']),
            new Middleware('permission:transaksi kas-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Transaksi Kas';
        $d['akun_kas']=DB::table('akun_kas')->select('id','nama_akun','nomor_rekening')->get();
        $d['item_transactions'] = TransaksiKasEnum::cases();
        return view('a.pages.transaksi_kas.index',$d);
    }

    public function get_transaksi_kas(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();
        $type = $request->type;

        $columns = [
            0 => 'transaksi_kas.id',
            1 => 'transaksi_kas.terkunci',
            2 => 'transaksi_kas.waktu',
            3 => 'transaksi_kas.keterangan',
            4 => 'transaksi_kas.type',
            5 => 'transaksi_kas.jumlah',
            6 => 'transaksi_kas.cancel',
        ];

        $query = DB::table('transaksi_kas')
            ->leftJoin('kategori_kas', 'kategori_kas.id', '=', 'transaksi_kas.kategori_kas_id')
            ->leftJoin('akun_kas', 'akun_kas.id', '=', 'transaksi_kas.akun_kas_id')
            ->leftJoin('metode_pembayarans', 'metode_pembayarans.id', '=', 'transaksi_kas.metode_pembayaran_id')
            ->select(
                'transaksi_kas.*',
                'kategori_kas.kategori_kas as kategori',
                'akun_kas.nama_akun as akun_kas',
                'metode_pembayarans.metode_pembayaran as metode'
            )
            ->whereBetween('transaksi_kas.waktu', [$start_date, $end_date]);
        if ($type) {
            $query->where('transaksi_kas.type', $type);
        }


        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('transaksi_kas.no_transaksi', 'like', "%$search%")
                ->orWhere('transaksi_kas.keterangan', 'like', "%$search%")
                ->orWhere('kategori_kas.kategori_kas', 'like', "%$search%")
                ->orWhere('akun_kas.nama_akun', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');
        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'transaksi_kas.waktu';
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
            if((is_null($dt->closing_kas_id) || $dt->closing_kas_id == '')){
                $action .= '<div class="btn-group">
                    <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Action</h6></li>';

                if ($dt->cancel === 'N' && Gate::allows('transaksi kas-delete')) {
                    $action .= '<li><span class="dropdown-item text-danger delete" data-id="' . encrypt0($dt->id) . '"><i class="bi bi-trash me-2"></i> Batalkan</span></li>';
                }

                $action .= '</ul></div>';
            }

            $data[] = [
                'DT_RowIndex' => $index++,
                'terkunci' => $dt->terkunci,
                'id_enc' => encrypt0($dt->id),
                'no_transaksi' => $dt->terkunci === 'Y'? '<span class=""><i class="bi bi-lock-fill text-warning me-1"></i>' . $dt->no_transaksi.'</span>': $dt->no_transaksi,
                'waktu' => toDatetime($dt->waktu),
                'kategori' => $dt->kategori ?? '-',
                'akun_kas' => $dt->akun_kas ?? '-',
                'keterangan' => $dt->keterangan ?? '-',
                'type' => '<span class="badge '.TransactionHelper::transaksi_kas($dt->type).'">'.ucfirst($dt->type).'</span>',
                'jumlah' => number_format($dt->jumlah, 2, ',', '.'),
                'cancel' => '<span class="badge '.TransactionHelper::cancel($dt->cancel, 'class').'">'.TransactionHelper::cancel($dt->cancel, 'text').'</span>',
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
        $d['title']='Tambah Transaksi Kas';
        $d['gudangs']=DB::table('gudangs')->select('id','gudang')->get();
        $d['jenis_transaksi'] = $request->jenis??'Masuk';
        return view('a.pages.transaksi_kas.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     */

     public function generateNoTransaksi($type = 'income')
    {
        $prefix = $type === 'income' ? 'IN' : 'EX'; // IN untuk income, EX untuk expense
        $now = Carbon::now();
        $ym = $now->format('ym'); // Format YYMM

        $last = DB::table('transaksi_kas')
            ->where('type', $type)
            ->whereRaw("DATE_FORMAT(waktu, '%y%m') = ?", [$ym])
            ->where('no_transaksi', 'like', "$prefix-$ym%")
            ->orderBy('no_transaksi', 'desc')
            ->first();

        if ($last && preg_match('/\d+$/', $last->no_transaksi, $matches)) {
            $nextNumber = (int)$matches[0] + 1;
        } else {
            $nextNumber = 1;
        }

        $number = str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        return "{$prefix}-{$ym}{$number}";
    }

    public function store(Request $request)
    {
        $id='';
        if(!empty($request->id)){
            $id=decrypt0($request->id);
        }
        
        $rules = [
            'type'=>'required|max:30',
            'akun_kas_id'=>'required',
            'kategori_kas_id'=>'required',
            'jumlah'=>'required',
            'keterangan'=>'nullable|max:255',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            $text='';
            DB::transaction(function() use ($request,$id, &$text ) {
                if ($request->param == 'add') {

                    $field = new TransaksiKas;
                    $field->inserted_by =  $request->session()->get('id');
                    $text = 'Data sukses disimpan';
                } else {

                    $field = TransaksiKas::find($id);
                    $field->updated_by =  $request->session()->get('id');
                    $text = 'Data sukses diupdate';
                }

                $field->no_transaksi = $this->generateNoTransaksi($request->type);
                $field->waktu = $request->waktu ? Carbon::parse($request->waktu) : now();
                $field->type = $request->type;
                $field->akun_kas_id = decrypt1($request->akun_kas_id);
                $field->kategori_kas_id = decrypt1($request->kategori_kas_id);
                $field->jumlah = CurrencytoDb($request->jumlah);
                $field->keterangan = $request->keterangan;
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
        $d['title']='Detail Transaksi Kas';
        $d['data'] = $data;
        $d['details'] = DB::table('item_transaction_details')
        ->join('items','item_transaction_details.item_id','=','items.id')
        ->select('item_transaction_details.*','items.nama_item as item_nama','items.satuan as item_satuan')
        ->where('item_transaction_details.item_transaction_id',$data->id)
        ->get();
        return view('a.pages.transaksi_kas.show',$d);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string$id)
    {
        try {
            DB::transaction(function () use ($id) {
                $field = TransaksiKas::find(decrypt0($id));
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

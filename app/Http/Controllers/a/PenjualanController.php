<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemStok;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionService;
use App\Models\TransactionPayment;
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
use App\Enums\PenjualanEnum;
use App\Rules\StokCukup;
use App\Helpers\ItemHelper;
use App\Helpers\TransactionHelper;
use App\Models\Pendaftaran;

class PenjualanController extends Controller implements HasMiddleware
{ 
    public static function middleware(): array
    {
        return [
            new Middleware('permission:penjualan-list', only : ['index','get_pasien','show']),
            new Middleware('permission:penjualan-create', only : ['create','store']),
            new Middleware('permission:penjualan-edit', only : ['edit','update']),
            new Middleware('permission:penjualan-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Penjualan';
        return view('a.pages.penjualan.index',$d);
    }

    public function get_penjualan(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $columns = [
            0 => 'transactions.id',
            1 => 'transactions.no_transaksi',
            2 => 'transactions.waktu',
            3 => 'users.username',           // kasir (inserted_by)
            4 => 'pasiens.nama',         // pasien
            5 => 'pasiens.no_hp',        // no hp
            6 => 'transactions.grand_total',
            7 => 'transactions.bayar',
            8 => 'transactions.cancel',
        ];

        $query = DB::table('transactions')
            ->select(
                'transactions.id',
                'transactions.no_transaksi',
                'transactions.waktu',
                'transactions.grand_total',
                'transactions.bayar',
                'transactions.closing_kas_id',
                'transactions.cancel',
                'admins.nama as kasir',
                'pasiens.nama as pasien',
                'pasiens.no_hp'
            )
            ->leftJoin('admins', 'transactions.inserted_by', '=', 'admins.user_id')
            
            ->leftJoin('pasiens', 'pasiens.id', '=', 'transactions.pasien_id')
            ->whereBetween('transactions.waktu', [$start_date, $end_date]);

        // 🔍 Filtering
        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('transactions.no_transaksi', 'like', "%$search%")
                    ->orWhere('users.name', 'like', "%$search%")
                    ->orWhere('pasiens.nama', 'like', "%$search%")
                    ->orWhere('pasiens.no_hp', 'like', "%$search%");
            });
        }

        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;

        $limit = $request->input('length') == -1 ? 1000000 : $request->input('length');
        $start = $request->input('start');
        $orderColIndex = $request->input('order.0.column');
        $orderColumn = $columns[$orderColIndex] ?? 'transactions.id';
        $orderDir = $request->input('order.0.dir', 'desc');

        $lists = $query
            ->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        $index = $start + 1;

        foreach ($lists as $dt) {
            $action = '<div class="btn-group">
                <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><h6 class="dropdown-header">Action</h6></li>';

            if (Gate::allows('penjualan-list')) {
                $action .= '<li><a href="' . route('penjualan.show', encrypt0($dt->id)) . '" class="dropdown-item text-info"><i class="bi bi-eye me-2"></i> Detail</a></li>';
            }

            if ($dt->cancel === 'N' && is_null($dt->closing_kas_id) && Gate::allows('penjualan-delete')) {
                $action .= '<li><span class="dropdown-item text-danger delete" data-id="' . encrypt0($dt->id) . '"><i class="bi bi-trash me-2"></i> Batalkan</span></li>';
            }

            $action .= '</ul></div>';

            $data[] = [
                'DT_RowIndex'   => $index++,
                'no_transaksi'  => $dt->no_transaksi,
                'waktu'         => toDatetime($dt->waktu),
                'kasir'         => $dt->kasir,
                'pasien'        => $dt->pasien,
                'no_hp'         => $dt->no_hp,
                'grand_total'         => toCurrency($dt->grand_total),
                'bayar' => ($dt->bayar >= $dt->grand_total)
                ? '<span class="badge bg-light-success">' . toCurrency($dt->bayar) . '</span>'
                : '<span class="badge bg-light-warning">' . toCurrency($dt->bayar) . '</span>',
                
                'cancel'        => '<span class="badge ' . TransactionHelper::cancel($dt->cancel, 'class') . '">' . TransactionHelper::cancel($dt->cancel, 'text') . '</span>',
                'action'        => $action,
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
    public function pracreate(Request $request)
    {
        $d['title']='Sumber Penjualan';
        return view('a.pages.penjualan.pracreate',$d);
    }

    public function create(Request $request)
    {
        $sumber='langsung';   
        if($request->pendaftaran){
            $pendaftaranId=decrypt1($request->pendaftaran);
            $pendaftaran=Pendaftaran::
            leftJoin('pasiens','pasiens.id','=','pendaftarans.pasien_id')
            ->select(
                'pendaftarans.id',
                'pendaftarans.pasien_id',
                'pasiens.no_rm',
                'pendaftarans.no_antrian',
                'pasiens.nama',
                'pasiens.tgl_lahir',
                'pasiens.no_hp',
            )->findOrFail($pendaftaranId);
            $sumber='pendaftaran';
            $d['pendaftaran']=$pendaftaran;
        }
        $d['title']='Tambah Penjualan';
        $d['sumber']=$sumber;
        $d['metode_pembayarans']=DB::table('metode_pembayarans')->select('id','metode_pembayaran')->get();
        $d['sumber_penjualans']=DB::table('sumber_penjualans')->select('id','sumber_penjualan')->get();
        return view('a.pages.penjualan.create',$d);
    }

    public function getPendaftaranItems($id)
    {
        try {
            $pendaftaranId = decrypt0($id);

            $items = DB::table('pendaftaran_items')
                ->join('items', 'items.id', '=', 'pendaftaran_items.item_id')
                ->where('pendaftaran_id', $pendaftaranId)
                ->selectRaw("
                    items.id, 
                    items.nama_item as name, 
                    'items' as type,
                    pendaftaran_items.qty as quantity,
                    pendaftaran_items.harga_jual as price,
                    pendaftaran_items.harga_jual as price_ppn,
                    pendaftaran_items.diskon,
                    pendaftaran_items.diskon_rp,
                    pendaftaran_items.ppn,
                    pendaftaran_items.ppn_rp,
                    pendaftaran_items.sub_total
                ");

            $services = DB::table('pendaftaran_services')
                ->join('services', 'services.id', '=', 'pendaftaran_services.service_id')
                ->where('pendaftaran_id', $pendaftaranId)
                ->selectRaw("
                    services.id, 
                    services.nama_service as name, 
                    'services' as type,
                    pendaftaran_services.qty as quantity,
                    pendaftaran_services.harga_jual as price,
                    pendaftaran_services.harga_jual_ppn as price_ppn,
                    pendaftaran_services.diskon,
                    pendaftaran_services.diskon_rp,
                    pendaftaran_services.ppn,
                    pendaftaran_services.ppn_rp,
                    pendaftaran_services.sub_total
                ");

            $cart = $items->unionAll($services)->get();

            // Tambahkan diskonType berdasarkan diskon dan diskon_rp
            $cart->transform(function ($item) {
                $item->discountType = $item->diskon > 0 ? 'percentage' : ($item->diskon_rp > 0 ? 'rupiah' : 'percentage');
                $item->discount = $item->diskon > 0 ? $item->diskon : $item->diskon_rp;
                return $item;
            });

            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json([], 400);
        }
    }

    public function generateNoTransaksi()
    {
        $prefix = 'TRX';
        $now = Carbon::now();
        $ym = $now->format('ym'); // YYMM format

        // Cari no_transaksi terakhir berdasarkan pola
        $last = DB::table('transactions')
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
            'pendaftaran_id' => 'nullable',
            'pasien_id' => 'nullable',
            'sumber_penjualan_id' => 'nullable',
            'total_harga_sebelum_diskon' => 'required|numeric|min:0',
            'total_diskon' => 'nullable|numeric|min:0',
            'total_ppn' => 'nullable|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
            'metode_pembayaran_id' => 'required|exists:metode_pembayarans,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.type' => 'required|in:items,services',
            'items.*.harga_jual' => 'required|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0',
            'items.*.diskon_rp' => 'nullable|numeric|min:0',
            'items.*.ppn_rp' => 'nullable|numeric|min:0',
            'items.*.sub_total' => 'required|numeric|min:0',
        ];

        $request->validate($rules);

        try {
            $id = '';

            DB::transaction(function () use ($request, &$id) {
                $pendaftaranId = $request->filled('pendaftaran_id') ? decrypt0($request->pendaftaran_id) : null;

                $field = new Transaction();
                $field->no_transaksi = $this->generateNoTransaksi();
                $field->pendaftaran_id = $pendaftaranId;
                $field->sumber_penjualan_id = $request->filled('sumber_penjualan_id') ? decrypt1($request->sumber_penjualan_id) : null;
                $field->pasien_id = $request->filled('pasien_id') ? decrypt0($request->pasien_id) : null;
                $field->waktu = now();
                $field->sub_total = $request->total_harga_sebelum_diskon;
                $field->diskon_rp = $request->total_diskon ?? 0;
                $field->diskon = 0;
                $field->ppn = $request->total_ppn ?? 0;
                $field->grand_total = $request->grand_total;
                $field->total = $request->grand_total;

                // Hitung nilai pembayaran dan kelebihan
                $bayarInput = (float) $request->bayar;
                $bayarDicatat = min($bayarInput, $request->grand_total);
                $kelebihanBayar = max(0, $bayarInput - $request->grand_total);

                $field->bayar = $bayarDicatat;
                $field->kembalian = $kelebihanBayar;
                $field->sisa = max(0, $field->grand_total - $bayarDicatat);
                $field->inserted_by = $request->session()->get('id');
                $field->cancel = 'N';
                $field->terkunci = 'N';
                $field->closing_kas_id = null;
                $field->catatan = null;
                $field->save();

                $gudangId = 1;

                // Simpan pembayaran awal
                $payment = new TransactionPayment();
                $payment->transaction_id = $field->id;
                $payment->metode_pembayaran_id = $request->metode_pembayaran_id;
                $payment->jumlah = $bayarDicatat;
                $payment->kelebihan_bayar = $kelebihanBayar;
                $payment->cancel = 'N';
                $payment->inserted_by = $request->session()->get('id');
                $payment->save();

                $id = encrypt0($field->id);

                // Simpan detail item/service
                foreach ($request->items as $item) {
                    if ($item['type'] === 'items') {
                        $detail = new TransactionItem();
                        $detail->transaction_id = $field->id;
                        $detail->item_id = $item['item_id'];

                        $stok = ItemStok::where('item_id', $detail->item_id)
                            ->where('gudang_id', $gudangId)
                            ->orderBy('tgl_kadaluarsa', 'asc')
                            ->firstOrFail();

                        $detail->item_stok_id = $stok->id;
                    } elseif ($item['type'] === 'services') {
                        $detail = new TransactionService();
                        $detail->transaction_id = $field->id;
                        $detail->service_id = $item['item_id'];
                    } else {
                        throw new \Exception("Tipe item tidak valid: {$item['type']}");
                    }

                    $qty = $item['qty'];
                    $hargaJual = $item['harga_jual'];
                    $hargaJualPpn = $item['harga_jual_ppn'];
                    $diskonRp = $item['diskon_rp'] ?? 0;
                    $hargaDiskon = max(0, $hargaJual - $diskonRp);
                    $totalDiskon = $diskonRp * $qty;

                    $detail->qty = $qty;
                    $detail->hpp = 0;
                    $detail->harga_jual = $hargaJual;
                    $detail->harga_jual_ppn = $hargaJualPpn;
                    $detail->diskon = $item['diskon'] ?? 0;
                    $detail->diskon_rp = $diskonRp;
                    $detail->total_diskon = $totalDiskon;
                    $detail->harga_diskon = $hargaDiskon;
                    $detail->ppn = $item['ppn'] ?? 0;
                    $detail->ppn_rp = $item['ppn_rp'] ?? 0;
                    $detail->sub_total = $item['sub_total'];
                    $detail->save();

                    // Update stok jika barang
                    if ($item['type'] === 'items') {
                        $stok->decrement('stok', $detail->qty);

                        ItemHelper::createHistory(
                            $field->no_transaksi,
                            $field->id,
                            'transactions',
                            'Penjualan',
                            $detail->item_id,
                            $detail->qty,
                            $detail->hpp,
                            'keluar',
                            $field->waktu,
                            null,
                            $detail->no_batch,
                            $detail->tgl_kadaluarsa,
                            $gudangId
                        );
                    }
                }

                // Tandai pendaftaran sebagai selesai
                if ($field->pendaftaran_id) {
                    $pendaftaran = Pendaftaran::find($field->pendaftaran_id);
                    if ($pendaftaran) {
                        $pendaftaran->status = 'Selesai';
                        $pendaftaran->save();
                    }
                }
            });

            return response()->json([
                'text' => 'Transaksi berhasil disimpan!',
                'id' => $id,
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('transactions')
            ->leftJoin('pendaftarans', 'transactions.pendaftaran_id', '=', 'pendaftarans.id')
            ->leftJoin('sumber_penjualans', 'transactions.sumber_penjualan_id', '=', 'sumber_penjualans.id')
            ->leftJoin('pasiens', 'transactions.pasien_id', '=', 'pasiens.id')
            ->leftJoin('admins', 'transactions.inserted_by', '=', 'admins.user_id')
            ->select(
                'transactions.*',
                'pendaftarans.id as pendaftaran_id',
                'sumber_penjualans.sumber_penjualan',
                'pasiens.nama as pasien',
                'pasiens.no_rm',
                'pasiens.no_hp',
                'pasiens.alamat',
                'admins.nama as kasir',
            )
            ->where('transactions.id', decrypt0($id))
            ->first();

        if (!$data) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $d['title'] = 'Detail Penjualan';
        $d['data'] = $data;
        $d['metode_pembayarans']=DB::table('metode_pembayarans')->select('id','metode_pembayaran')->get();
        $items = DB::table('transaction_items')
            ->join('items', 'items.id', '=', 'transaction_items.item_id')
            ->where('transaction_id', $data->id)
            ->selectRaw("
                items.id, 
                items.nama_item as name, 
                items.satuan, 
                'items' as type,
                transaction_items.qty as quantity,
                transaction_items.harga_jual as price,
                transaction_items.harga_jual_ppn as price_ppn,
                transaction_items.diskon,
                transaction_items.diskon_rp,
                transaction_items.ppn,
                transaction_items.ppn_rp,
                transaction_items.sub_total
            ");

        $services = DB::table('transaction_services')
            ->join('services', 'services.id', '=', 'transaction_services.service_id')
            ->where('transaction_id', $data->id)
            ->selectRaw("
                services.id, 
                services.nama_service as name, 
                services.satuan, 
                'services' as type,
                transaction_services.qty as quantity,
                transaction_services.harga_jual as price,
                transaction_services.harga_jual as price_ppn,
                transaction_services.diskon,
                transaction_services.diskon_rp,
                transaction_services.ppn,
                transaction_services.ppn_rp,
                transaction_services.sub_total
            ");

        $cart = $items->unionAll($services)->get();

        // Tambahkan diskonType berdasarkan diskon dan diskon_rp
        $cart->transform(function ($item) {
            $item->discountType = $item->diskon > 0 ? 'percentage' : ($item->diskon_rp > 0 ? 'rupiah' : 'percentage');
            $item->discount = $item->diskon > 0 ? $item->diskon : $item->diskon_rp;
            return $item;
        });
        $d['cart'] = $cart;
        $d['payments'] = DB::table('transaction_payments')
            ->join('metode_pembayarans', 'metode_pembayarans.id', '=', 'transaction_payments.metode_pembayaran_id')
            ->where('transaction_payments.transaction_id', $data->id)
            ->select('transaction_payments.*', 'metode_pembayarans.metode_pembayaran')
            ->orderBy('transaction_payments.created_at', 'asc')
            ->get();

        return view('a.pages.penjualan.show', $d);
    }

    public function pembayaranStore(Request $request)
    {
        $rules = [
            'transaction_id' => 'required',
            'bayar' => 'required|numeric|min:0.01',
            'metode_pembayaran_id' => 'required|exists:metode_pembayarans,id',
            'catatan' => 'nullable|string',
        ];

        $request->validate($rules);

        try {
            $id = '';

            DB::transaction(function () use ($request, &$id) {
                $transactionId = decrypt0($request->transaction_id);
                $transaction = Transaction::findOrFail($transactionId);

                // Hitung total pembayaran aktif
                $totalBayarSaatIni = TransactionPayment::where('transaction_id', $transaction->id)
                                        ->where('cancel', 'N')
                                        ->sum('jumlah');

                $sisaTagihan = max(0, $transaction->grand_total - $totalBayarSaatIni);

                $bayarBaru = (float) $request->bayar;
                $jumlahDicatat = min($bayarBaru, $sisaTagihan);
                $kelebihan = max(0, $bayarBaru - $sisaTagihan);

                // Catat pembayaran
                $payment = new TransactionPayment();
                $payment->transaction_id = $transaction->id;
                $payment->metode_pembayaran_id = $request->metode_pembayaran_id;
                $payment->jumlah = $jumlahDicatat; // hanya sebesar sisa tagihan
                $payment->kelebihan_bayar = $kelebihan;
                $payment->catatan = $request->catatan ?? null;
                $payment->cancel = 'N';
                $payment->inserted_by = auth()->id() ?? $request->session()->get('id');
                $payment->save();

                // Hitung ulang total pembayaran aktual
                $totalBayarFinal = TransactionPayment::where('transaction_id', $transaction->id)
                                        ->where('cancel', 'N')
                                        ->sum('jumlah');
                $totalKelebihan = TransactionPayment::where('transaction_id', $transaction->id)
                                        ->where('cancel', 'N')
                                        ->sum('kelebihan_bayar');

                // Update transaksi
                $transaction->bayar = $totalBayarFinal;
                $transaction->sisa = max(0, $transaction->grand_total - $totalBayarFinal);
                $transaction->kembalian = $totalKelebihan; // Kelebihan pembayaran ditampilkan sebagai kembalian
                $transaction->updated_by = auth()->id() ?? $request->session()->get('id');
                $transaction->save();

                $id = encrypt0($transaction->id);
            });


            return response()->json([
                'text' => 'Pembayaran berhasil dicatat!',
                'id' => $id,
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function pembayaranDestroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $id = decrypt0($request->id);
                $payment = TransactionPayment::findOrFail($id);

                // Tandai sebagai dibatalkan
                $payment->cancel = 'Y';
                $payment->updated_by = auth()->id() ?? $request->session()->get('id');
                $payment->save();

                // Rehitung total pembayaran untuk transaksi induknya
                $transaction = Transaction::findOrFail($payment->transaction_id);

                $totalBayar = TransactionPayment::where('transaction_id', $transaction->id)
                                ->where('cancel', 'N')
                                ->sum('jumlah');
                $totalKelebihan = TransactionPayment::where('transaction_id', $transaction->id)
                                ->where('cancel', 'N')
                                ->sum('kelebihan_bayar');

                $transaction->bayar = $totalBayar;
                $transaction->sisa = max(0, $transaction->grand_total - $totalBayar);
                $transaction->kembalian = $totalKelebihan;
                $transaction->updated_by = auth()->id() ?? $request->session()->get('id');
                $transaction->save();
            });

            return response()->json([
                'message' => 'Pembayaran berhasil dibatalkan.',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal membatalkan pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $field = Transaction::find(decrypt0($id));

                if (!$field) {
                    throw new \Exception("Transaksi tidak ditemukan.");
                }

                // Set default atribut history
                $jenis_table = 'transactions';
                $jenis_text = 'Pembatalan Penjualan';
                $type = 'masuk';
                $catatan = 'Pembatalan transaksi penjualan';
                $gudangId = 1; 

                $details = TransactionItem::where('transaction_id', $field->id)->get();

                foreach ($details as $detail) {
                    $itemId = $detail->item_id;
                    $qty = $detail->qty;
                    $no_batch = $detail->no_batch;
                    $tgl_kadaluarsa = $detail->tgl_kadaluarsa;

                    // Tambahkan kembali stok ke gudang
                    ItemStok::where('id',$detail->item_stok_id)->increment('stok', $qty);

                    // Catat histori pembatalan
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
                        $gudangId
                    );
                }

                // Tandai transaksi sebagai dibatalkan
                $field->cancel = 'Y';
                $field->save();

                Pendaftaran::where('id', $field->pendaftaran_id)
                    ->update(['status' => 'Pembayaran', 'updated_by' => auth()->id() ?? request()->session()->get('id')]);

                // Hapus semua pembayaran terkait
                TransactionPayment::where('transaction_id', $field->id)->update(['cancel' => 'Y', 'updated_by' => auth()->id() ?? request()->session()->get('id')]);
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

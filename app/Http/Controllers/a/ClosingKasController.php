<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClosingKas;
use App\Models\ClosingKasDetail;
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
use App\Helpers\TransactionHelper;



class ClosingKasController extends Controller implements HasMiddleware
{ 
    public static function middleware(): array
    {
        return [
            new Middleware('permission:closing kas-list', only : ['index','get_transaksi_kas','show']),
            new Middleware('permission:closing kas-create', only : ['create','store']),
            new Middleware('permission:closing kas-edit', only : ['edit','update']),
            new Middleware('permission:closing kas-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Closing Kas';
        $d['akun_kas']=DB::table('akun_kas')->select('id','nama_akun','nomor_rekening')->get();
        $d['item_transactions'] = TransaksiKasEnum::cases();
        return view('a.pages.closing_kas.index',$d);
    }

    public function get_closing_kas(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $columns = [
            0 => 'closing_kas.id',
            1 => 'closing_kas.no_transaksi',
            2 => 'closing_kas.waktu',
            3 => 'closing_kas.total_system',
            4 => 'closing_kas.total_aktual',
            5 => 'closing_kas.selisih',
            6 => 'closing_kas.cancel',
            7 => 'admins.nama'
        ];

        $query = DB::table('closing_kas')
            ->leftJoin('admins', 'admins.user_id', '=', 'closing_kas.inserted_by')
            ->select(
                'closing_kas.*',
                'admins.nama as dibuat_oleh'
            )
            ->whereBetween('closing_kas.waktu', [$start_date, $end_date]);

        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('closing_kas.no_transaksi', 'like', "%$search%")
                ->orWhere('closing_kas.keterangan', 'like', "%$search%")
                ->orWhere('admins.nama', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');
        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'closing_kas.waktu';
        $orderDir = $request->input('order.0.dir', 'asc');

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

            if (Gate::allows('closing kas-list')) {
                $action .= '<li><a href="' . route('closing-kas.show', encrypt0($dt->id)) . '" class="dropdown-item text-info"><i class="bi bi-eye me-2"></i> Detail</a></li>';
            }

            if ($dt->cancel === 'N' && Gate::allows('closing kas-delete')) {
                $action .= '<li><span class="dropdown-item text-danger delete" data-id="' . encrypt0($dt->id) . '"><i class="bi bi-trash me-2"></i> Batalkan</span></li>';
            }

            $action .= '</ul></div>';
            $data[] = [
                'DT_RowIndex'     => $index++,
                'id_enc'          => encrypt0($dt->id),
                'no_transaksi'    => $dt->no_transaksi,
                'waktu'           => toDatetime($dt->waktu),
                'total_system'    => number_format($dt->total_system, 2, ',', '.'),
                'total_aktual'    => number_format($dt->total_aktual, 2, ',', '.'),
                'selisih'         => number_format($dt->selisih, 2, ',', '.'),
                'dibuat_oleh'     => $dt->dibuat_oleh ?? '-',
                'cancel'          => '<span class="badge '.TransactionHelper::cancel($dt->cancel, 'class').'">'.TransactionHelper::cancel($dt->cancel, 'text').'</span>',
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
    public function create(Request $request)
    {
        $tanggal = date('Y-m-d');

        // Ambil metode pembayaran
        $metode = DB::table('metode_pembayarans')->select('id', 'metode_pembayaran')->get();

        // Ambil total penjualan yang belum direkap berdasarkan metode pembayaran
        $penjualans = DB::table('transaction_payments')
            ->join('transactions', 'transaction_payments.transaction_id', '=', 'transactions.id')
            ->select('transaction_payments.metode_pembayaran_id', DB::raw('SUM(transaction_payments.jumlah) as total_system'))
            ->whereNull('transactions.closing_kas_id')
            ->groupBy('transaction_payments.metode_pembayaran_id')
            ->pluck('total_system', 'metode_pembayaran_id');

        // Gabungkan ke data metode
        $metode_pembayarans = $metode->map(function ($item) use ($penjualans) {
            $item->total_system = $penjualans[$item->id] ?? 0;
            return $item;
        });

        // Ambil data akun kas
        $akuns = DB::table('akun_kas')
            ->select('id', 'nama_akun', 'nomor_akun', 'nomor_rekening')
            ->get();

        // Ambil data penerimaan & pengeluaran kas yang belum closing
        $kas = DB::table('transaksi_kas')
            ->select(
                'akun_kas_id',
                DB::raw("SUM(CASE WHEN type = 'income' THEN jumlah ELSE 0 END) as income"),
                DB::raw("SUM(CASE WHEN type = 'expense' THEN jumlah ELSE 0 END) as expense")
            )
            ->whereDate('waktu', $tanggal)
            ->whereNull('closing_kas_id')
            ->groupBy('akun_kas_id')
            ->get()
            ->keyBy('akun_kas_id');

        // Gabungkan data kas ke akun
        $akun_kas = $akuns->map(function ($akun) use ($kas) {
            $akun->penerimaan_system = $kas[$akun->id]->income ?? 0;
            $akun->pengeluaran_system = $kas[$akun->id]->expense ?? 0;
            return $akun;
        });

        return view('a.pages.closing_kas.create', [
            'title' => 'Tambah Closing Kas',
            'metode_pembayarans' => $metode_pembayarans,
            'akun_kas' => $akun_kas
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function generateNoTransaksiClosingKas()
    {
        $prefix = 'CK';
        $now = \Carbon\Carbon::now();
        $ym = $now->format('ym'); // YYMM

        $last = DB::table('closing_kas')
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
        $rules = [
            'penjualan_metode_id' => 'required|array',
            'penjualan_aktual' => 'required|array',
            'penjualan_catatan' => 'nullable|array',
            'akun_kas_id' => 'required|array',
            'kas_income_aktual' => 'required|array',
            'kas_expense_aktual' => 'required|array',
            'kas_catatan' => 'nullable|array',
        ];

        $request->validate($rules);

        try {
            $id='';
            DB::transaction(function () use ($request,&$id) {
                $user_id = session('id');
                $tanggal = date('Y-m-d');

                // ========== Simpan data utama: closing_kas ==========
                $closing = new ClosingKas;
                $closing->no_transaksi = 'CK-' . date('Ymd-His');
                $closing->waktu = now();
                $closing->cancel = 'N';
                $closing->inserted_by = $user_id;
                $closing->save();

                $total_system = 0;
                $total_aktual = 0;

                // ========== Simpan detail penjualan ==========
                foreach ($request->penjualan_metode_id as $i => $value) {
                    $metode_id = decrypt1($value);
                    $aktual = CurrencytoDb($request->penjualan_aktual[$i]);
                    $catatan = $request->penjualan_catatan[$i] ?? null;

                    // Total sistem dihitung ulang dari database
                    $system = DB::table('transaction_payments')
                        ->join('transactions', 'transaction_payments.transaction_id', '=', 'transactions.id')
                        ->where('transaction_payments.metode_pembayaran_id', $metode_id)
                        ->whereDate('transactions.created_at', $tanggal)
                        ->whereNull('transactions.closing_kas_id')
                        ->sum('transaction_payments.jumlah');

                    $selisih = $aktual - $system;

                    DB::table('closing_kas_penjualans')->insert([
                        'closing_kas_id' => $closing->id,
                        'metode_pembayaran_id' => $metode_id,
                        'total_system' => $system,
                        'total_aktual' => $aktual,
                        'selisih' => $selisih,
                        'catatan' => $catatan,
                        'cancel' => 'N',
                        'inserted_by' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $total_system += $system;
                    $total_aktual += $aktual;
                }

                // ========== Simpan detail transaksi kas ==========
                foreach ($request->akun_kas_id as $i => $value) {
                    $akun_id = decrypt1($value);
                    $income_aktual = CurrencytoDb($request->kas_income_aktual[$i]);
                    $expense_aktual = CurrencytoDb($request->kas_expense_aktual[$i]);
                    $catatan = $request->kas_catatan[$i] ?? null;

                    // Hitung ulang system dari DB
                    $system_income = DB::table('transaksi_kas')
                        ->where('akun_kas_id', $akun_id)
                        ->whereDate('waktu', $tanggal)
                        ->where('type', 'income')
                        ->whereNull('closing_kas_id')
                        ->sum('jumlah');

                    $system_expense = DB::table('transaksi_kas')
                        ->where('akun_kas_id', $akun_id)
                        ->whereDate('waktu', $tanggal)
                        ->where('type', 'expense')
                        ->whereNull('closing_kas_id')
                        ->sum('jumlah');

                    $selisih_income = $income_aktual - $system_income;
                    $selisih_expense = $expense_aktual - $system_expense;

                    DB::table('closing_kas_trkas')->insert([
                        'closing_kas_id' => $closing->id,
                        'akun_kas_id' => $akun_id,
                        'penerimaan_system' => $system_income,
                        'penerimaan_aktual' => $income_aktual,
                        'penerimaan_selisih' => $selisih_income,
                        'pengeluaran_system' => $system_expense,
                        'pengeluaran_aktual' => $expense_aktual,
                        'pengeluaran_selisih' => $selisih_expense,
                        'catatan' => $catatan,
                        'cancel' => 'N',
                        'inserted_by' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $total_system += $system_income - $system_expense;
                    $total_aktual += $income_aktual - $expense_aktual;
                }

                // ========== Update closing_kas ==========
                $closing->total_system = $total_system;
                $closing->total_aktual = $total_aktual;
                $closing->selisih = $total_aktual - $total_system;
                $closing->save();

                // ========== Tandai transaksi sudah direkap ==========
                DB::table('transactions')
                    ->whereNull('closing_kas_id')
                    ->update([
                        'closing_kas_id' => $closing->id,
                        'updated_by' => $user_id,
                        'updated_at' => now()
                    ]);

                DB::table('transaksi_kas')
                    ->whereDate('waktu', $tanggal)
                    ->whereNull('closing_kas_id')
                    ->update([
                        'closing_kas_id' => $closing->id,
                        'updated_by' => $user_id,
                        'updated_at' => now()
                    ]);

                    $id=encrypt0($closing->id);
            });

            return response()->json([
                'text' => 'Closing kas berhasil disimpan',
                'status' => 200,
                'id' => $id
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt0($id);

        // Ambil data utama closing kas
        $closing = DB::table('closing_kas')
        ->leftJoin('admins', 'admins.user_id', '=', 'closing_kas.inserted_by')
        ->select(
            'closing_kas.*',
            'admins.nama as dibuat_oleh'
        )
            ->where('closing_kas.id', $id)
            ->first();

        if (!$closing) {
            abort(404);
        }

        // Ambil detail penjualan
        $penjualans = DB::table('closing_kas_penjualans')
            ->join('metode_pembayarans', 'metode_pembayarans.id', '=', 'closing_kas_penjualans.metode_pembayaran_id')
            ->where('closing_kas_id', $id)
            ->select(
                'closing_kas_penjualans.*',
                'metode_pembayarans.metode_pembayaran'
            )
            ->get();

        // Ambil detail transaksi kas
        $trkas = DB::table('closing_kas_trkas')
            ->join('akun_kas', 'akun_kas.id', '=', 'closing_kas_trkas.akun_kas_id')
            ->where('closing_kas_id', $id)
            ->select(
                'closing_kas_trkas.*',
                'akun_kas.nama_akun',
                'akun_kas.nomor_akun'
            )
            ->get();

        return view('a.pages.closing_kas.show', [
            'title' => 'Detail Closing Kas',
            'data' => $closing,
            'penjualans' => $penjualans,
            'trkas' => $trkas,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $closing_id = decrypt0($id);

                // Set cancel = Y pada tabel utama
                $closing = ClosingKas::findOrFail($closing_id);
                $closing->cancel = 'Y';
                $closing->updated_by = session('id');
                $closing->save();

                // Unlink transaksi dari closing kas (rollback)
                DB::table('transactions')
                ->where('closing_kas_id', $closing_id)
                ->update([
                    'closing_kas_id' => null,
                    'updated_by' => session('id'),
                    'updated_at' => now(),
                ]);
                DB::table('transaksi_kas')
                ->where('closing_kas_id', $closing_id)
                ->update([
                    'closing_kas_id' => null,
                    'updated_by' => session('id'),
                    'updated_at' => now(),
                ]);
            });

            return response()->json([
                'text' => 'Data closing kas berhasil dibatalkan.',
                'status' => 200
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


}

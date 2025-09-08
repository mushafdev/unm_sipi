<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:dashboard-view', only : ['index']),
        ];
    }

    public function index()
    {
        $d['title']='Dashboard';
        $d['total_pasien']=DB::table('pasiens')->count();
        $d['total_produk']=DB::table('items')->count();
        $d['transaksi_hari_ini']=DB::table('transactions')
            ->whereDate('waktu', Carbon::today())
            ->where('cancel', 'N')
            ->sum('grand_total');
        $d['transaksi_bulan_ini']=DB::table('transactions')
            ->whereYear('waktu', Carbon::now()->year)
            ->whereMonth('waktu', Carbon::now()->month)
            ->where('cancel', 'N')
            ->sum('grand_total');
        return view('a.pages.dashboard',$d);
    }

    public function getTransaksiPerBulan()
    {
        $data = DB::table('transactions')
            ->select(
                DB::raw("MONTH(waktu) as bulan"),
                DB::raw("SUM(grand_total) as jumlah")
            )
            ->whereYear('waktu', now()->year)
            ->where('cancel', 'N')
            ->groupBy(DB::raw("MONTH(waktu)"))
            ->orderBy(DB::raw("MONTH(waktu)"))
            ->get()
            ->map(function ($item) {
                $bulanNama = [
                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                    5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                    9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                ];
                return [
                    'bulan' => $bulanNama[$item->bulan],
                    'jumlah' => (float) $item->jumlah
                ];
            });

        return response()->json($data);
    }

    public function getTransaksiPerItem()
    {
         $data = DB::table('transaction_items as ti')
            ->join('transactions as t', 'ti.transaction_id', '=', 't.id')
            ->join('items as i', 'ti.item_id', '=', 'i.id')
            ->select(
                'i.nama_item as category', // digunakan sebagai label slice pie
                DB::raw('SUM(ti.qty) as value')
            )
            ->where('t.cancel', 'N')
            ->groupBy('i.nama_item')
            ->orderByDesc('value')
            ->limit(8)
            ->get();

        return response()->json($data);
    }

    public function getTransaksiPerService()
    {
        $data = DB::table('transaction_services as ts')
            ->join('transactions as t', 'ts.transaction_id', '=', 't.id')
            ->join('services as s', 'ts.service_id', '=', 's.id')
            ->select(
                's.nama_service as category',
                DB::raw('SUM(ts.qty) as value')
            )
            ->where('t.cancel', 'N')
            ->groupBy('s.nama_service')
            ->orderByDesc('value')
            ->limit(8)
            ->get();

        return response()->json($data);
    }

}

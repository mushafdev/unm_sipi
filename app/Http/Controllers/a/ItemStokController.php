<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Gudang;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ItemStokController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:item stok-list', only : ['index','get_item','get_data']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Stok Item';
        $d['gudangs']=Gudang::select('id','gudang')->get();
        return view('a.pages.item.stok',$d);
    }


    public function get_item_stok(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }
        $gudang_id = decrypt1($request->gudang);
        $stokSub = DB::table('item_stoks')
            ->select('item_id', DB::raw('SUM(stok) as total_stok'))
            ->when(!empty($gudang_id), function ($query) use ($gudang_id) {
                $query->where('gudang_id', $gudang_id);
            })
            ->groupBy('item_id');

        $query = DB::table('items')
            ->join('kategori_items', 'kategori_items.id', '=', 'items.kategori_item_id')
            ->leftJoinSub($stokSub, 'stok_gudang', 'items.id', '=', 'stok_gudang.item_id')
            ->select(
                'items.*',
                'kategori_items.kategori_item',
                DB::raw('COALESCE(stok_gudang.total_stok, 0) as stok')
            )
            ->orderBy('items.created_at', 'desc');

        $columns = [
            0 => 'items.id',
            1 => 'items.nama_item',
            2 => 'items.nama_item',
            3 => 'items.nama_item',
            4 => 'items.nama_item',
            5 => 'items.nama_item',
            6 => 'items.nama_item',
            7 => 'items.nama_item',
        ];

        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('items.nama_item', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');
        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'items.nama_item'; 
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
            $action.='<div class="btn-group">
            <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu">';
            $action.='<li><h6 class="dropdown-header">Action </h6></li>';
            if (Gate::allows('item stok-kartu stok')) {
                $action.= '<li><a href="#" data-id="' . encrypt0($dt->id) . '" title="Kartu Stok" class="dropdown-item text-primary kartu-stok" data-bs-toggle="modal" data-bs-target="#modal-kartu-stok"><i class="icon-mid bi bi-card-list me-2" aria-hidden="true"></i> Kartu Stok</a></li>';
            }
            $action.='</ul>';
            $action.='</div>';

            $data[] = [
                'DT_RowIndex' => $index++,
                'nama_item' => $dt->nama_item,
                'kategori_item' => $dt->kategori_item,
                'besaran' => $dt->besaran,
                'isi' => $dt->isi.' '.$dt->satuan,
                'reorder_point' => $dt->reorder_point.' '.$dt->satuan,
                'stok' => $dt->stok,
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

    public function get_kartu_stok(Request $request)
    {
        $itemId = decrypt0($request->id);
        $gudangId = $request->filled('gudang_id') ? decrypt1($request->gudang_id) : null;
        $start = $request->start_date;
        $end = $request->end_date;

        $item = DB::table('items')->find($itemId);

        // Saldo awal
        $histAwal = DB::table('item_histories')
            ->where('item_id', $itemId)
            ->when($gudangId, fn($q) => $q->where('gudang_id', $gudangId))
            ->whereDate('waktu', '<', $start)
            ->orderBy('waktu')
            ->orderBy('id')
            ->get();

        $saldo = 0;
        $nilaiPersediaan = 0;

        foreach ($histAwal as $h) {
            $subTotal = $h->hpp * $h->qty;
            if ($h->type == 'masuk') {
                $saldo += $h->qty;
                $nilaiPersediaan += $subTotal;
            } elseif ($h->type == 'keluar') {
                $saldo -= $h->qty;
                $nilaiPersediaan -= $subTotal;
            } elseif ($h->type == 'reset') {
                $saldo = $h->qty;
                $nilaiPersediaan = $h->hpp * $h->qty;
            }
        }

        // Histori utama
        $hist = DB::table('item_histories AS h')
            ->leftJoin('admins', 'h.user_id', '=', 'admins.user_id')
            ->select('h.*', 'admins.nama AS user')
            ->where('h.item_id', $itemId)
            ->when($gudangId, fn($q) => $q->where('h.gudang_id', $gudangId))
            ->when($start, fn($q) => $q->whereDate('h.waktu', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('h.waktu', '<=', $end))
            ->where('h.qty', '<>', 0)
            ->orderBy('h.waktu')
            ->orderBy('h.id')
            ->get();

        // Bangun tabel HTML
        $table = '';

        if ($saldo !== 0) {
            $table .= '<tr class="bg-light-info text-info">';
            $table .= '<td colspan="7">Saldo Awal</td>';
            // $table .= '<td class="text-right">' . number_format($nilaiPersediaan, 2) . '</td>';
            $table .= '<td class="text-center">' . $saldo . '</td>';
            $table .= '</tr>';
        }

        foreach ($hist as $h) {
            $subTotal = $h->hpp * $h->qty;
            $rowClass = '';
            $textQtyIn = '';
            $textQtyOut = '';

            if ($h->type === 'masuk') {
                $saldo += $h->qty;
                $nilaiPersediaan += $subTotal;
                $textQtyIn = $h->qty;
            } elseif ($h->type === 'keluar') {
                $saldo -= $h->qty;
                $nilaiPersediaan -= $subTotal;
                $subTotal *= -1;
                $textQtyOut = '<span class="text-danger">' . $h->qty . '</span>';
            } elseif ($h->type === 'reset') {
                $saldo = $h->qty;
                $nilaiPersediaan = $h->hpp * $h->qty;
                $rowClass = 'bg-light-warning text-warning';
            }

            $table .= '<tr class="' . $rowClass . '">';
            $table .= '<td>' . $h->waktu . '</td>';
            $table .= '<td>' . $h->no_referensi . '</td>';
            $table .= '<td>' . $h->jenis_text . '</td>';
            $table .= '<td>' . ($h->user ?? '-') . '</td>';
            $table .= '<td class="text-right">' . $h->tgl_kadaluarsa . '</td>';
            $table .= '<td class="text-center">' . $textQtyIn . '</td>';
            $table .= '<td class="text-center">' . $textQtyOut . '</td>';
            $table .= '<td class="text-center">' . $saldo . '</td>';
            $table .= '</tr>';
        }

        // Row akhir
        $table .= '<tr class="bg-light-success fw-bold">';
        $table .= '<td colspan="7" class="text-end">SALDO AKHIR</td>';
        // $table .= '<td class="text-end">' . number_format($nilaiPersediaan, 2) . '</td>';
        $table .= '<td class="text-center">' . $saldo . '</td>';
        $table .= '</tr>';

        return response()->json([
            'detail_item' => $item,
            'table' => $table,
            'gudang' => $gudangId ? DB::table('gudangs')->find($gudangId)?->gudang : 'Semua Gudang'
        ]);
    }



}

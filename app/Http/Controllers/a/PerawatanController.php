<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Item;
use App\Models\Service;
use App\Models\PasienTag;
use App\Models\PendaftaranService;
use App\Models\PendaftaranServiceKaryawan;
use App\Models\PendaftaranServiceDokter;
use App\Models\PendaftaranItem;
use App\Models\PendaftaranFoto;
use Throwable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Enums\PrioritasPasienEnum;
use App\Enums\StatusPendaftaranEnum;
use Carbon\Carbon;
use App\Helpers\TransactionHelper;
use App\Enums\FotoBeforeEnum;
use Intervention\Image\Facades\Image;


class PerawatanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:perawatan-list', only : ['index','get_perawatan','show']),
            new Middleware('permission:perawatan-create', only : ['create','store']),
            new Middleware('permission:perawatan-edit', only : ['edit','update']),
            new Middleware('permission:perawatan-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Perawatan';
        $d['status_pendaftarans'] = StatusPendaftaranEnum::cases();
        $d['jenis_layanans'] = DB::table('jenis_layanans')->select('id','jenis_layanan')->get();
        return view('a.pages.perawatan.index',$d);
    }

    public function get_perawatan(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }
        $tgl_daftar=$request->tgl_daftar;
        $status=$request->status_pendaftaran;
        $jenis_layanan_id=decrypt1($request->jenis_layanan);

        $columns = [
            0 => 'pendaftarans.id',
            1 => 'pendaftarans.no_antrian',
        ];

        $query = DB::table('pendaftarans')
            ->leftJoin('pasiens','pasiens.id','=','pendaftarans.pasien_id')
            ->leftJoin('dokters','dokters.id','=','pendaftarans.dokter_id')
            ->select(
                'pendaftarans.id',
                'pasiens.no_rm',
                'pendaftarans.no_antrian',
                'pasiens.nama',
                'pasiens.tgl_lahir',
                'pasiens.no_hp',
                'dokters.nama as dokter',
                'pendaftarans.tgl_pendaftaran',
                'pendaftarans.status',
                'pendaftarans.prioritas',
                'pendaftarans.created_at',
                'pendaftarans.cancel',
            )->where(function ($q) use ($tgl_daftar,$status,$jenis_layanan_id) {
                if (!empty($jenis_layanan_id)) {
                    $q->where('pendaftarans.jenis_layanan_id', '=', $jenis_layanan_id);
                }
                if (!empty($tgl_daftar)) {
                    $q->whereDate('pendaftarans.tgl_pendaftaran', '=', $tgl_daftar);
                }
                if (!empty($status)) {
                    $q->where('pendaftarans.status', '=', $status);
                }
            })->where('cancel','N')->orderBy('pendaftarans.created_at','asc');

        $totalData = clone $query;
        $recordsTotal = $totalData->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('pasiens.nama', 'like', "%$search%")
                ->orWhere('pasiens.no_hp', 'like', "%$search%");
            });
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length') == -1 ? 100000000 : $request->input('length');
        $start = $request->input('start');

        $columnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$columnIndex] ?? 'pasiens.nama'; 
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
            if (Gate::allows('perawatan-create')) {
                $action.= '<li><a href="'.route('perawatan.create').'?id='.encrypt0($dt->id).'" title="Pendaftaran" class="dropdown-item perawatan text-primary"><i class="icon-mid bi bi-star me-2" aria-hidden="true"></i>Perawatan</a></li>';
                
            }
            $action.='</ul>';
            $action.='</div>';

            $data[] = [
                'id_enc' => encrypt1($dt->id),
                'DT_RowIndex' => $index++,
                'no_antrian' => $dt->no_antrian,
                'tgl_daftar' => convertYmdToMdy2($dt->tgl_pendaftaran),
                'jam' => DatetimeToHour($dt->created_at),
                'no_rm' => $dt->no_rm,
                'nama' => $dt->nama,
                'no_hp' => $dt->no_hp,
                'dokter' => $dt->dokter,
                'status' =>  '<span class="badge '.TransactionHelper::status_pendaftaran($dt->status).'">'.$dt->status.'</span>',
                'cancel' => $dt->cancel,
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
        $pendaftaran = Pendaftaran::
            leftJoin('beds', 'beds.id', '=', 'pendaftarans.bed_id')
            ->leftJoin('jenis_layanans','jenis_layanans.id','=','pendaftarans.jenis_layanan_id')
            ->leftJoin('rooms', 'beds.room_id', '=', 'rooms.id')
            ->select(
                'pendaftarans.*',
                'jenis_layanans.jenis_layanan',
                'beds.bed_number',
                'rooms.id as room_id',
                'rooms.name as room_name',
                'rooms.lantai'
            )
            ->where('pendaftarans.id', decrypt0($request->id))
            ->first();

        if (!$pendaftaran) {
            abort(404);
        }

        $d['title'] = 'Perawatan';
        $d['pendaftaran'] = $pendaftaran;
        $d['jenis_layanans'] = DB::table('jenis_layanans')->select('id','jenis_layanan')->get();

        return view('a.pages.perawatan.create', $d);
    }

    public function getGrandTotal($id)
    {
        $id=decrypt0($id);
        $totalService = DB::table('pendaftaran_services')
            ->where('pendaftaran_id', $id)
            ->sum('sub_total');

        $totalItem = DB::table('pendaftaran_items')
            ->where('pendaftaran_id', $id)
            ->sum('sub_total');

        return response()->json([
            'total' => $totalService + $totalItem
        ]);
    }

    public function loadPasien($id)
    {
        $pendaftaran = Pendaftaran::findOrFail(decrypt0($id));
        $pasien = Pasien::findOrFail($pendaftaran->pasien_id);
        return view('a.pages.perawatan.tab.pasien', compact('pasien','pendaftaran'));
    }

    public function loadFoto($id)
    {
        $fotoList = PendaftaranFoto::where('pendaftaran_id', decrypt0($id))
        ->select('foto', 'position')
        ->get()
        ->map(function ($item) {
            return [
                'foto' => $item->foto,
                'position' => strtoupper($item->position), // optional: langsung uppercase
            ];
        })
        ->values(); // reset key
        return view('a.pages.perawatan.tab.foto', compact('id','fotoList'));
    }

    public function loadService($id)
    {
        $pendaftaran= Pendaftaran::findOrFail(decrypt0($id));
        return view('a.pages.perawatan.tab.service', compact('id','pendaftaran'));
    }

    public function getServiceData($pendaftaranId)
    {
        $pendaftaranId=decrypt0($pendaftaranId);
        // Ambil data utama layanan
        $services = DB::table('pendaftaran_services AS ps')
            ->join('pendaftarans AS p', 'ps.pendaftaran_id', '=', 'p.id')
            ->join('services AS s', 'ps.service_id', '=', 's.id')
            ->select(
                'ps.id',
                'ps.qty',
                'ps.harga_jual',
                'ps.diskon',
                'ps.diskon_rp',
                'ps.total_diskon',
                'ps.ppn_rp',
                'ps.sub_total',
                'ps.catatan',
                'p.status',
                's.nama_service AS nama_tindakan'
            )
            ->where('ps.pendaftaran_id', $pendaftaranId)
            ->orderBy('ps.id','asc')
            ->get();

        // Ambil dokter dan perawat terpisah, lalu kelompokkan berdasarkan service_id
        $dokters = DB::table('pendaftaran_service_dokters AS d')
            ->join('dokters', 'd.dokter_id', '=', 'dokters.id')
            ->select('d.pendaftaran_service_id', 'dokters.nama')
            ->get()
            ->groupBy('pendaftaran_service_id');

        $perawats = DB::table('pendaftaran_service_karyawans AS p')
            ->join('karyawans', 'p.karyawan_id', '=', 'karyawans.id')
            ->select('p.pendaftaran_service_id', 'karyawans.nama')
            ->get()
            ->groupBy('pendaftaran_service_id');

        // Gabungkan ke setiap service
        $result = $services->map(function ($item) use ($dokters, $perawats) {
            $item->dokters = isset($dokters[$item->id]) ? $dokters[$item->id]->pluck('nama')->all() : [];
            $item->perawats = isset($perawats[$item->id]) ? $perawats[$item->id]->pluck('nama')->all() : [];

            return $item;
        });

        return response()->json($result);
    }   
    
    public function serviceStore(Request $request)
    {
         // Validasi data utama
            $request->validate([
                'pendaftaran_id' => 'required',
                'service_id' => 'required|exists:services,id',
                'qty' => 'required|numeric|min:1',
                'harga_jual' => 'required|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'diskon_rp' => 'nullable|numeric|min:0',
                'sub_total' => 'required|numeric|min:0',
                'catatan' => 'nullable|string',
                'dokters' => 'array',
                'perawats' => 'array',
            ]);

            try {

                $error='';
                $text='';
                DB::transaction(function() use ($request,&$id,&$text) {
                    if ($request->param == 'add') {

                        $field = new PendaftaranService;
                        $field->inserted_by =  $request->session()->get('id');
                        $text = 'Data sukses disimpan';
                    } else {
                        $field = PendaftaranService::find($request->id);
                        $field->updated_by =  $request->session()->get('id');
                        PendaftaranServiceDokter::where('pendaftaran_service_id',$field->id)->delete();
                        PendaftaranServiceKaryawan::where('pendaftaran_service_id',$field->id)->delete();
                        $text = 'Data sukses diupdate';
                    }
                     $service = Service::select('ppn')->findOrFail($request->service_id);

                    $harga_jual = $request->harga_jual;
                    $diskon_persen = $request->diskon ?? 0;
                    $diskon_rp = $request->diskon_rp ?? 0;
                    $qty = $request->qty;

                    // Harga setelah diskon (per service)
                    $harga_diskon = $harga_jual - $diskon_rp;

                    // PPN per service
                    $ppn_persen = $service->ppn ?? 0;
                    $ppn_per_item = ($ppn_persen / 100) * $harga_diskon;

                    // PPN total
                    $ppn_rp = $ppn_per_item * $qty;

                    // Subtotal = total harga diskon + total ppn
                    $sub_total = ($harga_diskon * $qty) + $ppn_rp;

                    $field->pendaftaran_id = decrypt0($request->pendaftaran_id);
                    $field->service_id = $request->service_id;
                    $field->qty = $qty;
                    $field->harga_jual = $harga_jual;
                    $field->diskon = $diskon_persen;
                    $field->diskon_rp = $diskon_rp;
                    $field->total_diskon = $diskon_rp * $qty;
                    $field->harga_diskon = $harga_diskon;
                    $field->ppn = $ppn_persen;
                    $field->ppn_rp = $ppn_rp;
                    $field->sub_total = $sub_total;
                    $field->catatan = $request->catatan;
                    $field->save();

                    if ($request->has('dokters')) {
                        foreach ($request->dokters as $dokterId) {
                            PendaftaranServiceDokter::firstOrCreate([
                                'pendaftaran_service_id' => $field->id,
                                'dokter_id' => decrypt1($dokterId),
                            ]);
                        }
                    }

                    if ($request->has('perawats')) {
                        foreach ($request->perawats as $perawatId) {
                            PendaftaranServiceKaryawan::firstOrCreate([
                                'pendaftaran_service_id' => $field->id,
                                'karyawan_id' => decrypt1($perawatId),
                            ]);
                        }
                    }
                });
                return response()->json([
                    'text' => $text,
                    'id' => $id,
                    'status' => 200,
                ]);
            } catch (Throwable $e) {
                return response()->json([
                    'message' =>$e->getMessage()
                ],500);
            }
    }

    public function serviceGetData($id)
    {
        $q = PendaftaranService::join('services', 'services.id', '=', 'pendaftaran_services.service_id')
        ->select(
            'pendaftaran_services.*',
            'services.nama_service as nama_tindakan'
        )
        ->findOrFail($id);
        $dokters = DB::table('pendaftaran_service_dokters')
            ->join('dokters', 'dokters.id', '=', 'pendaftaran_service_dokters.dokter_id')
            ->where('pendaftaran_service_id', $id)
            ->select('dokters.id', 'dokters.nama')
            ->get()
            ->map(function ($dokter) {
                return [
                    'id' => encrypt1($dokter->id),
                    'nama' => $dokter->nama
                ];
            });

        $perawats = DB::table('pendaftaran_service_karyawans')
            ->join('karyawans', 'karyawans.id', '=', 'pendaftaran_service_karyawans.karyawan_id')
            ->where('pendaftaran_service_id', $id)
            ->select('karyawans.id', 'karyawans.nama')
            ->get()
            ->map(function ($perawat) {
                return [
                    'id' => encrypt1($perawat->id),
                    'nama' => $perawat->nama
                ];
            });

        return response()->json([
            'id' => encrypt0($q->id),
            'perawats' => $perawats,
            'dokters' => $dokters,
            'result' => $q,
        ]);
    }

    public function serviceDestroy($id)
    {
        $field = PendaftaranService::findOrFail($id);
        $field->delete();
        return response()->json([
            'text' => 'Data sukses dihapus',
            'status' => 200,
        ]);
    }
    
    public function loadItem($id)
    {
        $pendaftaran= Pendaftaran::findOrFail(decrypt0($id));
        return view('a.pages.perawatan.tab.item', compact('id','pendaftaran'));
    }

    public function getItemData($pendaftaranId)
    {
        $pendaftaranId=decrypt0($pendaftaranId);
        // Ambil data utama layanan
        $items = DB::table('pendaftaran_items AS ps')
            ->join('pendaftarans AS p', 'ps.pendaftaran_id', '=', 'p.id')
            ->join('items AS s', 'ps.item_id', '=', 's.id')
            ->select(
                'ps.id',
                'ps.qty',
                'ps.harga_jual',
                'ps.harga_jual_ppn',
                'ps.diskon',
                'ps.diskon_rp',
                'ps.harga_diskon',
                'ps.total_diskon',
                'ps.ppn',
                'ps.ppn_rp',
                'ps.sub_total',
                'ps.catatan',
                's.nama_item',
                's.satuan',
                'p.status'
            )
            ->where('ps.pendaftaran_id', $pendaftaranId)
            ->orderBy('ps.id','asc')
            ->get();

        return response()->json($items);
    }   
    
    public function itemStore(Request $request)
    {
        // Validasi data utama
        $request->validate([
            'pendaftaran_id' => 'required',
            'item_id'        => 'required|exists:items,id',
            'qty'            => 'required|numeric|min:1',
            'harga_jual'     => 'required|numeric|min:0',
            'diskon'         => 'nullable|numeric|min:0',
            'diskon_rp'      => 'nullable|numeric|min:0',
            'sub_total'      => 'required|numeric|min:0',
            'catatan'        => 'nullable|string',
        ]);

        try {
            $text = '';
            $id = null;

            DB::transaction(function () use ($request, &$id, &$text) {
                if ($request->param === 'add') {
                    $field = new PendaftaranItem;
                    $field->inserted_by = $request->session()->get('id');
                    $text = 'Data sukses disimpan';
                } else {
                    $field = PendaftaranItem::findOrFail($request->id);
                    $field->updated_by = $request->session()->get('id');
                    $text = 'Data sukses diupdate';
                }

                // Ambil data input
                $qty            = $request->qty;
                $harga_jual     = $request->harga_jual;
                $diskon_persen  = $request->diskon ?? 0;
                $diskon_rp      = $request->diskon_rp ?? 0;

                // Harga setelah diskon per item
                $harga_diskon = max(0, $harga_jual - $diskon_rp);

                // Ambil nilai PPN dari setting
                $ppn_persen     = setting()->ppn ?? 0;
                $ppn_per_item   = $harga_diskon * ($ppn_persen / 100);
                $ppn_rp         = $ppn_per_item * $qty;

                // Harga jual + ppn (dibulatkan)
                $harga_jual_ppn = pembulatan_ke_atas($harga_jual + ($harga_jual * $ppn_persen / 100));

                // Subtotal (dibulatkan): total harga setelah diskon + total PPN
                $sub_total = pembulatan_ke_atas(($harga_diskon * $qty) + $ppn_rp);

                // Simpan ke database
                $field->pendaftaran_id = decrypt0($request->pendaftaran_id);
                $field->item_id        = $request->item_id;
                $field->qty            = $qty;
                $field->harga_jual     = $harga_jual;
                $field->harga_jual_ppn = $harga_jual_ppn;
                $field->diskon         = $diskon_persen;
                $field->diskon_rp      = $diskon_rp;
                $field->total_diskon   = $diskon_rp * $qty;
                $field->harga_diskon   = $harga_diskon;
                $field->ppn            = $ppn_persen;
                $field->ppn_rp         = $ppn_rp;
                $field->sub_total      = $sub_total;
                $field->catatan        = $request->catatan;
                $field->save();

                $id = $field->id;
            });

            return response()->json([
                'text'   => $text,
                'id'     => $id,
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function itemGetData($id)
    {
        $q = PendaftaranItem::join('items', 'items.id', '=', 'pendaftaran_items.item_id')
        ->select(
            'pendaftaran_items.*',
            'items.nama_item'
        )
        ->findOrFail($id);
       

        return response()->json([
            'id' => encrypt0($q->id),
            'result' => $q,
        ]);
    }

    public function itemDestroy($id)
    {
        $field = PendaftaranItem::findOrFail($id);
        $field->delete();
        return response()->json([
            'text' => 'Data sukses dihapus',
            'status' => 200,
        ]);
    }

    public function fotoBeforeUpload(Request $request)
    {
        $id = decrypt0($request->pendaftaran_id);

        $request->validate([
            'foto' => 'required|file|image|max:5120',
            'position' => 'required',
            'pendaftaran_id' => 'required'
        ]);


        $foto = $request->file('foto');
        // $ext=$foto->getClientOriginalExtension();
        $ext='jpg';
        $filename = uniqid() . '.' . $ext;
        $folder = "images/foto_before/{$id}";
        $path = "{$folder}/{$filename}";

        // Cek dan hapus file lama jika ada
        $existing = PendaftaranFoto::where('pendaftaran_id', $id)
            ->where('position', $request->position)
            ->first();

        if ($existing && $existing->foto && Storage::disk('public')->exists("{$folder}/{$existing->foto}")) {
            Storage::disk('public')->delete("{$folder}/{$existing->foto}");
        }

        // Kompresi dan simpan gambar baru
        $image = Image::make($foto)
            ->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($ext, 75);

        Storage::disk('public')->put($path, (string) $image);

        // Simpan ke DB
        $record = PendaftaranFoto::updateOrCreate(
            ['pendaftaran_id' => $id, 'position' => $request->position],
            ['foto' => $filename]
        );

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::url($path),
            'position' => $request->position,
        ]);
    }

    public function fotoBeforeDelete(Request $request)
    {
        $id = decrypt0($request->pendaftaran_id);

        $request->validate([
            'position' => 'required',
            'pendaftaran_id' => 'required'
        ]);

        $foto = PendaftaranFoto::where('pendaftaran_id', $id)
            ->where('position', $request->position)
            ->first();

        if ($foto) {
            $folder = "images/foto_before/{$id}";
            $filePath = "{$folder}/{$foto->foto}";

            if ($foto->foto && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath); // hapus file fisik
            }

            $foto->delete(); // hapus data dari DB

            return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus']);
        }

        return response()->json(['success' => false, 'message' => 'Foto tidak ditemukan'], 404);
    }
    

    public function resep($pendaftaranId)
    {
        $pendaftaran_id=decrypt0($pendaftaranId);
        $pendaftaran = Pendaftaran::findOrFail($pendaftaran_id);
        $reseps = DB::table('pendaftaran_reseps')
            ->leftJoin('dokters', 'pendaftaran_reseps.dokter_id', '=', 'dokters.id')
            ->select('pendaftaran_reseps.*', 'dokters.nama as nama_dokter')
            ->where('pendaftaran_reseps.pendaftaran_id', $pendaftaran_id)
            ->orderByDesc('pendaftaran_reseps.waktu')
            ->get();

        $dokters = DB::table('dokters')->get();

        return view('a.pages.perawatan.tab.resep', compact('reseps', 'dokters', 'pendaftaranId','pendaftaran'));
    }

    public function resepStore(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required',
            'dokter_id' => 'required',
            'resep' => 'required'
        ]);

        $pendaftaranId = decrypt0($request->pendaftaran_id);
        $dokterId = decrypt1($request->dokter_id);

        DB::table('pendaftaran_reseps')->updateOrInsert(
            ['pendaftaran_id' => $pendaftaranId],
            [
                'dokter_id' => $dokterId,
                'waktu' => Carbon::now(),
                'resep' => $request->resep,
                'updated_by' => auth()->id(),
                'updated_at' => now(),
                'inserted_by' => DB::raw('COALESCE(inserted_by, ' . auth()->id() . ')'),
                'created_at' => DB::raw('COALESCE(created_at, NOW())')
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil disimpan atau diperbarui.'
        ]);
    }

    public function resepDelete($id)
    {
        $deleted = DB::table('pendaftaran_reseps')->where('id', decrypt0($id))->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Resep berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Gagal menghapus resep.'], 422);
    }

    public function updateBed(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required',
            'bed_id' => 'required|integer|exists:beds,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $pendaftaran_id = decrypt0($request->pendaftaran_id);
                $user_id = $request->session()->get('id');

                // Ambil data pendaftaran
                $field = Pendaftaran::findOrFail($pendaftaran_id);

                // Lock baris bed baru agar tidak diakses bersamaan
                $bedBaru = DB::table('beds')->where('id', $request->bed_id)->lockForUpdate()->first();

                // Jika bed sudah tidak available, batalkan update
                if ($bedBaru->status !== 'available') {
                    throw new \Exception('Bed sudah tidak tersedia.');
                }

                // Kosongkan bed lama (jika ada)
                if ($field->bed_id) {
                    DB::table('beds')->where('id', $field->bed_id)->update([
                        'status' => 'available',
                        'pendaftaran_id' => null
                    ]);
                }

                // Update pendaftaran
                $field->bed_id = $request->bed_id;
                $field->status = 'Dilayani';
                $field->called_by = $user_id;
                $field->updated_by = $user_id;
                $field->save();

                // Tandai bed baru sebagai occupied
                DB::table('beds')->where('id', $request->bed_id)->update([
                    'status' => 'occupied',
                    'pendaftaran_id' => $pendaftaran_id
                ]);
            });

            return response()->json(['success' => true, 'message' => 'Bed berhasil diperbarui.']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409); // 409 Conflict
        }
    }
    
    public function pembayaran(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $pendaftaran_id = decrypt0($request->pendaftaran_id);
                $user_id = $request->session()->get('id');
                $field = Pendaftaran::findOrFail($pendaftaran_id);
                $field->status = 'Pembayaran';
                $field->updated_by = $user_id;
                $field->save();

                DB::table('beds')->where('id', $field->bed_id)->update([
                    'status' => 'available',
                    'pendaftaran_id' => null
                ]);

            });

            return response()->json(['success' => true, 'message' => 'Berhasil ke pembayaran']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409); // 409 Conflict
        }
    }





    function buatNomorAntrian(string $jenisLayananId,$tglDaftar): string
    {

        $jumlahHariIni = DB::table('pendaftarans')
            ->where('jenis_layanan_id', $jenisLayananId)
            ->where('tgl_pendaftaran', $tglDaftar)
            ->count();

        $nomorUrut = $jumlahHariIni + 1;

        $noFormat = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        $kodeLayanan = DB::table('jenis_layanans')
            ->where('id', $jenisLayananId)
            ->value('kode');

        // Gabungkan kode layanan dan nomor
        return $kodeLayanan . $noFormat;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'tgl_daftar'=>'required',
            'jenis_layanan_id'=>'required',
            'dokter_id'=>'nullable',
            'perawat_id'=>'nullable',
            'prioritas'=>'required',
            'catatan'=>'nullable',
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            $id='';
            DB::transaction(function() use ($request,&$id) {


                $field = new Pendaftaran;
                $jenisLayananId = decrypt1($request->jenis_layanan_id);
                $field->tgl_pendaftaran = $request->tgl_daftar;
                $field->pasien_id = decrypt0($request->pasien_id);
                $field->jenis_layanan_id = $jenisLayananId;
                if(!empty($request->dokter_id)){
                    $field->dokter_id = decrypt1($request->dokter_id);
                }
                if(!empty($request->perawat_id)){
                    $field->karyawan_id = decrypt1($request->perawat_id);
                }
                $field->prioritas = $request->prioritas;
                $field->catatan = $request->catatan;
                $field->no_antrian = $this->buatNomorAntrian($jenisLayananId,$request->tgl_daftar);
                $field->inserted_by =  $request->session()->get('id');
                $field->save();
                $id=encrypt0($field->id);


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
        $data=DB::table('pasiens')
        ->leftJoin('agamas','agamas.id','=','pasiens.agama_id')
        ->leftJoin('pendidikans','pendidikans.id','=','pasiens.pendidikan_id')
        ->leftJoin('pekerjaans','pekerjaans.id','=','pasiens.pekerjaan_id')
        ->leftJoin('provinsis','provinsis.id','=','pasiens.prov_id')
        ->leftJoin('kabupatens','kabupatens.id','=','pasiens.kab_id')
        ->leftJoin('kecamatans','kecamatans.id','=','pasiens.kec_id')
        ->leftJoin('kelurahans','kelurahans.id','=','pasiens.kel_id')
        ->select(
            'pasiens.*','agamas.agama',
            'pendidikans.pendidikan',
            'pekerjaans.pekerjaan',
            'provinsis.provinsi',
            'kabupatens.kabupaten',
            'kecamatans.kecamatan',
            'kelurahans.kelurahan',
        )
        ->where('pasiens.id',decrypt0($id))
        ->first();
        if (!$data) {
            abort(404, 'User not found');
        }
        $d['title']='Detail Pasien';
        $d['data'] = $data;
        $d['tags'] = PasienTag::leftJoin('tags','tags.id','=','pasien_tags.tag_id')
        ->select('tags.tag')
        ->where('pasien_tags.pasien_id',$data->id)
        ->get();
        return view('a.pages.pendaftaran.show',$d);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data=DB::table('pendaftarans')
        ->where('pendaftarans.id',decrypt0($id))
        ->first();
        if (!$data) {
            abort(404, 'User not found');
        }
        $d['title']='Edit Pendaftaran';
        $d['data'] =$data;
        $pasien_id='';
        if(!empty($data->pasien_id)){
            $pasien_id =encrypt0($data->pasien_id);
        }
        $d['pasien_id']=$pasien_id;
        $d['prioritas'] = PrioritasPasienEnum::cases();
        $d['jenis_layanans'] = DB::table('jenis_layanans')->select('id','jenis_layanan')->get();
        $d['dokters']= DB::table('dokters')->select('id','nama')->get();
        $d['perawats']= DB::table('karyawans')
        ->join('jabatans','jabatans.id','=','karyawans.jabatan_id')
        ->where('jabatans.jabatan','Perawat')
        ->orWhere('jabatans.jabatan','Beautician')
        ->select('karyawans.id','karyawans.nama')->get();

        return view('a.pages.pendaftaran.edit',$d);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id=decrypt0($id);
        $rules = [
            // 'tgl_daftar'=>'required',
            // 'jenis_layanan_id'=>'required',
            'dokter_id'=>'nullable',
            'perawat_id'=>'nullable',
            'prioritas'=>'required',
            'catatan'=>'nullable',
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            DB::transaction(function() use ($request,&$id) {


                $field =Pendaftaran::find($id);
                if(!empty($request->dokter_id)){
                    $field->dokter_id = decrypt1($request->dokter_id);
                }
                if(!empty($request->perawat_id)){
                    $field->karyawan_id = decrypt1($request->perawat_id);
                }
                $field->prioritas = $request->prioritas;
                $field->catatan = $request->catatan;
                $field->updated_by =  $request->session()->get('id');
                $field->save();
                $id=encrypt0($field->id);


            });
            return response()->json([
                'text' => 'Data sukses diUpdate',
                'id' => $id,
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
        
    }

    public function ubahLayanan(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'jenis_layanan_id' => 'required|exists:jenis_layanans,id',
        ]);

        DB::table('pendaftarans')
            ->where('id', $request->pendaftaran_id)
            ->update([
                'jenis_layanan_id' => $request->jenis_layanan_id
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil diubah'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $field = Pendaftaran::find(decrypt0($id));
            $field->cancel = 'Y';
            $field->save();
            return response()->json([
                'text' => 'Data sukses dibatalkan',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
}

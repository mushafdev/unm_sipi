<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\PendaftaranService;
use App\Models\PendaftaranServiceDokter;
use App\Models\PendaftaranFoto;
use App\Models\PendaftaranResep;
use App\Models\Pasien;
use App\Models\PasienTag;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Tag;
use Spatie\Permission\Models\Role;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Enums\SatuanPendidikanEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\DisplayNamaEnum;


class PasienController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pasien-list', only : ['index','get_pasien','show']),
            new Middleware('permission:pasien-create', only : ['create','store']),
            new Middleware('permission:pasien-edit', only : ['edit','update']),
            new Middleware('permission:pasien-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Pasien';
        return view('a.pages.pasien.index',$d);
    }

    public function get_pasien(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }

        $filters = ['filter', 'no_rm', 'nama', 'panggilan', 'tgl_lahir', 'no_hp', 'nik', 'alamat'];
        
        $isAllEmpty = true;
        foreach ($filters as $field) {
            if ($request->filled($field)) {
                $isAllEmpty = false;
                break;
            }
        }

        $filter = $request->filter;

        $columns = [
            0 => 'pasiens.id',
            1 => 'pasiens.no_rm',
            2 => 'pasiens.nama',
        ];

        $query = DB::table('pasiens')
            ->select(
                'pasiens.id',
                'pasiens.no_rm',
                'pasiens.nama',
                'pasiens.tgl_lahir',
                'pasiens.no_hp',
                'pasiens.nik',
                'pasiens.alamat'
            );

        // Jika ada filter, apply filter
        if (!$isAllEmpty) {
            $query->where(function ($q) use ($filter, $request) {
                if (!empty($filter)) {
                    $q->where('pasiens.nama', 'like', "%$filter%")
                        ->orWhere('pasiens.no_rm', 'like', "%$filter%")
                        ->orWhere('pasiens.no_hp', 'like', "%$filter%")
                        ->orWhere('pasiens.nik', 'like', "%$filter%")
                        ->orWhere('pasiens.alamat', 'like', "%$filter%");
                }

                if ($request->filled('no_rm')) {
                    $q->where('no_rm', 'like', '%' . $request->no_rm . '%');
                }

                if ($request->filled('nama')) {
                    $q->where('nama', 'like', '%' . $request->nama . '%');
                }

                if ($request->filled('panggilan')) {
                    $q->where('panggilan', 'like', '%' . $request->panggilan . '%');
                }

                if ($request->filled('tgl_lahir')) {
                    $q->whereDate('tgl_lahir', $request->tgl_lahir);
                }

                if ($request->filled('no_hp')) {
                    $q->where('no_hp', 'like', '%' . $request->no_hp . '%');
                }

                if ($request->filled('nik')) {
                    $q->where('nik', 'like', '%' . $request->nik . '%');
                }

                if ($request->filled('alamat')) {
                    $q->where('alamat', 'like', '%' . $request->alamat . '%');
                }
            });
        }

        // Handle global search dari DataTables
        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('pasiens.nama', 'like', "%$search%")
                    ->orWhere('pasiens.nik', 'like', "%$search%");
            });
        }
        
        $totalData = clone $query;
        $recordsTotal = $totalData->count();
        if ($isAllEmpty) {
            $recordsFiltered = 50;
        } else {
            $recordsFiltered = $query->count();
        }
        
        $limit = ($request->input('length') == -1 ? 100000000 : ($request->input('length') ?? 10));
        $start = $request->input('start') ?? 0;
        

        if ($request->has('order')) {
            $columnIndex = $request->input('order.0.column');
            $orderColumn = $columns[$columnIndex] ?? 'pasiens.created_at';
            $orderDir = $request->input('order.0.dir', 'desc');
            $query->orderBy($orderColumn, $orderDir);
        } else {
            $query->orderBy('pasiens.created_at', 'desc');
        }

        $lists = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        $index = $start + 1;
        foreach ($lists as $dt) {
            $action = '<div class="btn-group">
                <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu">';
            $action .= '<li><h6 class="dropdown-header">Action </h6></li>';

            if (Gate::allows('pasien-list')) {
                $action .= '<li><a href="' . route('pasien.show', encrypt0($dt->id)) . '" class="dropdown-item detail text-info"><i class="icon-mid bi bi-eye me-2"></i> Detail</a></li>';
            }
            if (Gate::allows('pasien-edit')) {
                $action .= '<li><a href="' . route('pasien.edit', encrypt0($dt->id)) . '" class="dropdown-item edit"><i class="icon-mid bi bi-pencil-square me-2"></i> Edit</a></li>';
            }
            if (Gate::allows('pasien-delete')) {
                $action .= '<li><span class="dropdown-item delete text-danger" data-id="' . encrypt0($dt->id) . '"><i class="icon-mid bi bi-trash me-2"></i> Delete</span></li>';
            }

            $action .= '</ul></div>';

            $data[] = [
                'DT_RowIndex' => $index++,
                'id_enc' => encrypt0($dt->id),
                'no_rm' => $dt->no_rm,
                'nama' => $dt->nama,
                'tgl_lahir' => convertYmdToMdy2($dt->tgl_lahir),
                'no_hp' => $dt->no_hp,
                'nik' => $dt->nik,
                'alamat' => $dt->alamat,
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


    public function getRiwayat($id) {
        $riwayat = DB::table('pendaftarans')
        ->leftJoin('pasiens','pasiens.id','=','pendaftarans.pasien_id')
        ->leftJoin('jenis_layanans','jenis_layanans.id','=','pendaftarans.jenis_layanan_id')
        ->where('pendaftarans.pasien_id', decrypt0($id))
        ->where('pendaftarans.cancel','N')
        ->where('pendaftarans.status','selesai')
        ->select(
            'pendaftarans.id',
            'pasiens.nama as pasien_nama',
            'jenis_layanans.jenis_layanan',
            'pendaftarans.tgl_pendaftaran',
            'pendaftarans.status'
        )
        ->orderBy('pendaftarans.tgl_pendaftaran', 'desc')->paginate(10);
        return view('a.component.riwayat_pasien', compact('riwayat'));
    }
    
    public function getRiwayatDetail($id) {
        $pendaftaran = DB::table('pendaftarans')
        ->where('id', decrypt0($id))
        ->first(); 
        

        if (!$pendaftaran) {
            abort(404, 'Riwayat tidak ditemukan');
        }
        
        $services=DB::table('pendaftaran_services')
        ->join('pendaftarans','pendaftarans.id','=','pendaftaran_services.pendaftaran_id')
        ->join('services','services.id','=','pendaftaran_services.service_id')
        ->where('pendaftarans.id', $pendaftaran->id)
        ->select('pendaftaran_services.*','services.nama_service as service')->get();
        $services = $services->map(function ($service) {
            $service->dokters= DB::table('pendaftaran_service_dokters')
            ->join('dokters','dokters.id','=','pendaftaran_service_dokters.dokter_id')
            ->where('pendaftaran_service_dokters.pendaftaran_service_id', $service->id)
            ->select('dokters.nama as dokter_nama')
            ->get();
            $service->karyawans= DB::table('pendaftaran_service_karyawans')
            ->join('karyawans','karyawans.id','=','pendaftaran_service_karyawans.karyawan_id')
            ->where('pendaftaran_service_karyawans.pendaftaran_service_id', $service->id)
            ->select('karyawans.nama as karyawan_nama')
            ->get();
            return $service;
        });

        $transaction = DB::table('transactions')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->where('cancel', 'N')
            ->first();

        $items = collect(); // default: kosong

        if ($transaction) {
            $items = DB::table('transaction_items')
                ->join('items', 'items.id', '=', 'transaction_items.item_id')
                ->where('transaction_items.transaction_id', $transaction->id)
                ->select('transaction_items.*', 'items.nama_item as item', 'items.satuan')
                ->get();
        }

        $fotos = DB::table('pendaftaran_fotos')
        ->where('pendaftaran_fotos.pendaftaran_id', $pendaftaran->id)
        ->select('pendaftaran_fotos.*')
        ->get();

        $resep= DB::table('pendaftaran_reseps')
        ->leftJoin('dokters','dokters.id','=','pendaftaran_reseps.dokter_id')
        ->where('pendaftaran_reseps.pendaftaran_id', $pendaftaran->id)
        ->select('pendaftaran_reseps.*','dokters.nama as dokter_nama')
        ->get();
        
        return view('a.component.riwayat_pasien_detail', compact('services', 'items', 'fotos', 'resep','pendaftaran'));
    }
    
    public function getRiwayatGetDetail(Request $request) {
        $id=decrypt0($request->id);
        $pendaftaran = DB::table('pendaftarans')
        ->where('id', $id)
        ->first(); 
        

        if (!$pendaftaran) {
            abort(404, 'Riwayat tidak ditemukan');
        }
        
        $services=DB::table('pendaftaran_services')
        ->join('pendaftarans','pendaftarans.id','=','pendaftaran_services.pendaftaran_id')
        ->join('services','services.id','=','pendaftaran_services.service_id')
        ->where('pendaftarans.id', $pendaftaran->id)
        ->select('pendaftaran_services.*','services.nama_service as service')->get();
        $services = $services->map(function ($service) {
            $service->dokters= DB::table('pendaftaran_service_dokters')
            ->join('dokters','dokters.id','=','pendaftaran_service_dokters.dokter_id')
            ->where('pendaftaran_service_dokters.pendaftaran_service_id', $service->id)
            ->select('dokters.nama as dokter_nama','dokters.id')
            ->get()
             ->map(function ($dokter) {
                    $dokter->id = encrypt1($dokter->id);
                    return $dokter;
                });
            $service->karyawans= DB::table('pendaftaran_service_karyawans')
            ->join('karyawans','karyawans.id','=','pendaftaran_service_karyawans.karyawan_id')
            ->where('pendaftaran_service_karyawans.pendaftaran_service_id', $service->id)
            ->select('karyawans.nama as karyawan_nama','karyawans.id')
            ->get()
            ->map(function ($karyawan) {
                $karyawan->id = encrypt1($karyawan->id);
                return $karyawan;
            });
            return $service;
        });

        $transaction = DB::table('transactions')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->where('cancel', 'N')
            ->first();

        $items = collect(); // default: kosong

        if ($transaction) {
            $items = DB::table('transaction_items')
                ->join('items', 'items.id', '=', 'transaction_items.item_id')
                ->where('transaction_items.transaction_id', $transaction->id)
                ->select('transaction_items.*', 'items.nama_item as item', 'items.satuan')
                ->get();
        }

        $fotos = DB::table('pendaftaran_fotos')
        ->where('pendaftaran_fotos.pendaftaran_id', $pendaftaran->id)
        ->select('pendaftaran_fotos.*')
        ->get();

        $resep= DB::table('pendaftaran_reseps')
        ->leftJoin('dokters','dokters.id','=','pendaftaran_reseps.dokter_id')
        ->where('pendaftaran_reseps.pendaftaran_id', $pendaftaran->id)
        ->select('pendaftaran_reseps.*','dokters.nama as dokter_nama')
        ->get()
        ->map(function ($resep) {
            $resep->dokter_id = encrypt1($resep->dokter_id);
            return $resep;
        });
        
        return response()->json([
            'id'=>$request->id,
            'items'=>$items,
            'pendaftaran'=>$pendaftaran,
            'services'=>$services,
            'fotos'=>$fotos,
            'resep'=>$resep,
        ]);
    }

    public function getResep($id) {
        $resep = DB::table('pendaftaran_reseps')
        ->leftJoin('dokters','dokters.id','=','pendaftaran_reseps.dokter_id')
        ->leftJoin('pendaftarans','pendaftarans.id','=','pendaftaran_reseps.pendaftaran_id')
        ->leftJoin('pasiens','pasiens.id','=','pendaftarans.pasien_id')
        ->where('pendaftarans.pasien_id', decrypt0($id))
        ->where('pendaftarans.cancel','N')
        ->where('pendaftarans.status','selesai')
        ->select(
            'pendaftaran_reseps.id',
            'dokters.nama as dokter',
            'pasiens.nama as pasien',
            'pendaftarans.tgl_pendaftaran',
            'pendaftaran_reseps.resep',
        )
        ->orderBy('pendaftarans.tgl_pendaftaran', 'desc')->paginate(10);
        return view('a.component.riwayat_resep', compact('resep'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $d['title']='Tambah Pasien';
        $d['jenis_kelamins'] = JenisKelaminEnum::cases();
        $d['satuan_pendidikans'] = SatuanPendidikanEnum::cases();
        $d['golongan_darahs'] = GolonganDarahEnum::cases();
        $d['status_kawins'] = StatusKawinEnum::cases();
        $d['display_namas'] = DisplayNamaEnum::cases();
        $d['agamas'] = Agama::select('id','agama')->get();
        $d['pekerjaans'] = Pekerjaan::select('id','pekerjaan')->get();
        $d['pendidikans'] = Pendidikan::select('id','pendidikan')->get();
        return view('a.pages.pasien.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     */
    function generateNoRM($nama)
    {
        $initial = strtoupper(substr(trim($nama), 0, 1));

        $last = DB::table('pasiens')
            ->where('no_rm', 'like', $initial . '%')
            ->orderBy('no_rm', 'desc')
            ->value('no_rm');

        if (!$last || !preg_match('/^[A-Z]\d+$/', $last)) {
            $number = 1;
        } else {
            $number = intval(substr($last, 1)) + 1;
        }

        return $initial . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $rules = [
            'no_rm'=>'unique:pasiens,no_rm',
            'nama'=>'required',
            'photo'=>'max:2048|mimes:jpeg,png',
            'panggilan'=>'required',
            'gelar_depan'=>'nullable',
            'gelar_belakang'=>'nullable',
            'display_nama'=>'nullable',
            'tgl_lahir'=>'required',
            'nik'=>'nullable|unique:pasiens,nik',
            'jenis_kelamin'=>'required',
            'agama_id'=>'required',
            'no_hp'=>'required|unique:pasiens,no_hp',
            'alamat'=>'required',
            'email'=>'nullable|email:rfc,dns',
            'status_kawin'=>'nullable',
            'golongan_darah'=>'nullable',
            'pendidikan_id'=>'nullable',
            'pekerjaan_id'=>'nullable',
            'prov_id'=>'nullable',
            'kab_id'=>'nullable',
            'kec_id'=>'nullable',
            'kel_id'=>'nullable',
            'kode_pos'=>'nullable',
            'keterangan'=>'nullable',
            'tags.*'=>'nullable',
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            $id='';
            DB::transaction(function() use ($request,&$id) {


                $field = new Pasien;
                if(!empty($request->photo)){
                    $imgName = time().'.'.$request->photo->extension();
                    Storage::disk('pasien_photo')->put($imgName, file_get_contents($request->photo));
                    $field->photo = $imgName;
                }
                if(!empty($request->no_rm)){
                    $field->no_rm = $request->no_rm;
                }else{
                    $field->no_rm = $this->generateNoRM($request->nama);
                }
                $field->tgl_daftar = date('Y-m-d');
                $field->nama = $request->nama;
                $field->panggilan = $request->panggilan;
                $field->gelar_depan = $request->gelar_depan ;
                $field->gelar_belakang = $request->gelar_belakang ;
                $field->display_nama = $request->display_nama;
                $field->tgl_lahir = $request->tgl_lahir;
                $field->nik = $request->nik ;
                $field->jenis_kelamin = $request->jenis_kelamin;
                $field->agama_id = $request->agama_id;
                $field->no_hp = $request->no_hp;
                $field->alamat = $request->alamat;
                $field->email = $request->email ;
                $field->status_kawin = $request->status_kawin;
                $field->golongan_darah = $request->golongan_darah;
                $field->pendidikan_id = $request->pendidikan_id ;
                $field->pekerjaan_id = $request->pekerjaan_id ;
                $field->prov_id = $request->prov_id ;
                $field->kab_id = $request->kab_id ;
                $field->kec_id = $request->kec_id ;
                $field->kel_id = $request->kel_id ;
                $field->kode_pos = $request->kode_pos ;
                $field->keterangan = $request->keterangan ;
                $field->inserted_by =  $request->session()->get('id');
                $field->save();

                if (!empty($request->tags) && is_array($request->tags)) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tag = Tag::firstOrCreate(['tag' => $tagName]);
                        $tagIds[] = $tag->id;
                    }
                    
                    PasienTag::where('pasien_id',$field->id)->delete();
                    foreach ($tagIds as $tagId) {
                        PasienTag::create([
                            'pasien_id' => $field->id,
                            'tag_id' => $tagId,
                        ]);
                    }
                }

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
        $d['jenis_layanans'] = DB::table('jenis_layanans')->select('id','jenis_layanan')->get();
        
        return view('a.pages.pasien.show',$d);
    }

    public function detail_data(Request $request)
    {
        $id = decrypt0($request->id);
        $q = Pasien::find($id);
        
        return response()->json([
            'id' => encrypt0($q->id),
            'result' => $q,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
        $d['title']='Edit Pasien';
        $d['data'] =$data;

        $d['jenis_kelamins'] = JenisKelaminEnum::cases();
        $d['satuan_pendidikans'] = SatuanPendidikanEnum::cases();
        $d['golongan_darahs'] = GolonganDarahEnum::cases();
        $d['status_kawins'] = StatusKawinEnum::cases();
        $d['display_namas'] = DisplayNamaEnum::cases();
        $d['agamas'] = Agama::select('id','agama')->get();
        $d['pekerjaans'] = Pekerjaan::select('id','pekerjaan')->get();
        $d['pendidikans'] = Pendidikan::select('id','pendidikan')->get();
        $d['tags'] = PasienTag::leftJoin('tags','tags.id','=','pasien_tags.tag_id')
        ->select('tags.tag')
        ->where('pasien_tags.pasien_id',$data->id)
        ->get();

        return view('a.pages.pasien.edit',$d);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id=decrypt0($id);
        $rules = [
            'no_rm'=>'unique:pasiens,no_rm,'.$id,
            'nama'=>'required',
            'photo'=>'max:2048|mimes:jpeg,png',
            'panggilan'=>'required',
            'gelar_depan'=>'nullable',
            'gelar_belakang'=>'nullable',
            'display_nama'=>'nullable',
            'tgl_lahir'=>'required',
            'nik'=>'nullable|unique:pasiens,nik,'.$id,
            'jenis_kelamin'=>'required',
            'agama_id'=>'required',
            'no_hp'=>'required|unique:pasiens,no_hp,'.$id,
            'alamat'=>'required',
            'email'=>'nullable|email:rfc,dns',
            'status_kawin'=>'nullable',
            'golongan_darah'=>'nullable',
            'pendidikan_id'=>'nullable',
            'pekerjaan_id'=>'nullable',
            'prov_id'=>'nullable',
            'kab_id'=>'nullable',
            'kec_id'=>'nullable',
            'kel_id'=>'nullable',
            'kode_pos'=>'nullable',
            'keterangan'=>'nullable',
            'tags.*'=>'nullable',
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            DB::transaction(function() use ($request,$id) {
                $field = Pasien::find($id);
                if(!empty($request->photo)){
                    $imgName = time().'.'.$request->photo->extension();
                    Storage::disk('pasien_photo')->put($imgName, file_get_contents($request->photo));
                    $field->photo = $imgName;
                }

                if(!empty($request->no_rm)){
                    $field->no_rm = $request->no_rm;
                }else{
                    $field->no_rm = uniqid();
                }
                $field->tgl_daftar = date('Y-m-d');
                $field->nama = $request->nama;
                $field->panggilan = $request->panggilan;
                $field->gelar_depan = $request->gelar_depan ;
                $field->gelar_belakang = $request->gelar_belakang ;
                $field->display_nama = $request->display_nama;
                $field->tgl_lahir = $request->tgl_lahir;
                $field->nik = $request->nik ;
                $field->jenis_kelamin = $request->jenis_kelamin;
                $field->agama_id = $request->agama_id;
                $field->no_hp = $request->no_hp;
                $field->alamat = $request->alamat;
                $field->email = $request->email ;
                $field->status_kawin = $request->status_kawin;
                $field->golongan_darah = $request->golongan_darah;
                $field->pendidikan_id = $request->pendidikan_id ;
                $field->pekerjaan_id = $request->pekerjaan_id ;
                $field->prov_id = $request->prov_id ;
                $field->kab_id = $request->kab_id ;
                $field->kec_id = $request->kec_id ;
                $field->kel_id = $request->kel_id ;
                $field->kode_pos = $request->kode_pos ;
                $field->keterangan = $request->keterangan ;
                $field->updated_by =  $request->session()->get('id');
                $field->save();

                if (!empty($request->tags) && is_array($request->tags)) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tag = Tag::firstOrCreate(['tag' => $tagName]);
                        $tagIds[] = $tag->id;
                    }
                    
                    PasienTag::where('pasien_id',$field->id)->delete();
                    foreach ($tagIds as $tagId) {
                        PasienTag::create([
                            'pasien_id' => $field->id,
                            'tag_id' => $tagId,
                        ]);
                    }
                }

            
            });
            return response()->json([
                'text' => 'Data sukses diupdate',
                'id' => encrypt0($id),
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $field = Admin::find(decrypt0($id));
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

    public function riwayatStore(Request $request)
    {
        $id = '';
        if (!empty($request->id)) {
            $id = decrypt0($request->id);
        }

        $request->validate([
            'tgl_pendaftaran' => 'required|date',
            'jenis_layanan_id' => 'required|integer',
            'resep_dokter_id' => 'nullable',
            'resep' => 'nullable|string',
            'tindakan.*' => 'required',
            'dokters' => 'nullable',
            'dokters.*' => 'nullable',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string',
        ]);

        $text='';
        try {
            DB::transaction(function () use ($request, $id, &$text) {
                if ($request->param === 'add') {
                    $field = new Pendaftaran;
                    $field->inserted_by = session('id');
                    $text = 'Data sukses disimpan';
                } else {
                    $field = Pendaftaran::findOrFail($id);
                    $field->updated_by = session('id');
                    $text = 'Data sukses diupdate';
                }

                $field->tgl_pendaftaran = $request->tgl_pendaftaran;
                $field->pasien_id = decrypt0($request->pasien_id);
                $field->jenis_layanan_id = $request->jenis_layanan_id;
                $field->status = 'Selesai';
                $field->old = 'Y';
                $field->save();

                // Hapus data lama
                PendaftaranResep::where('pendaftaran_id', $field->id)->delete();
                PendaftaranFoto::where('pendaftaran_id', $field->id)->delete();
                PendaftaranService::where('pendaftaran_id', $field->id)->delete();

                // Simpan resep jika ada
                if ($request->filled('resep_dokter_id')) {
                    $fieldResep = new PendaftaranResep;
                    $fieldResep->pendaftaran_id = $field->id;
                    $fieldResep->dokter_id = decrypt1($request->resep_dokter_id);
                    $fieldResep->resep = $request->resep;
                    $fieldResep->save();
                }

                foreach ($request->tindakan as $k => $tindakanEncrypted) {
                    $fieldService = new PendaftaranService();
                    $fieldService->pendaftaran_id = $field->id;
                    $fieldService->qty = 1;
                    $fieldService->service_id = ($tindakanEncrypted);
                    $fieldService->catatan = $request->catatan[$k] ?? null;
                    $fieldService->save();

                    // Dokter untuk baris ke-k
                    if (!empty($request->dokters[$k])) {
                        foreach ($request->dokters[$k] as $dokterEncrypted) {
                            PendaftaranServiceDokter::create([
                                'pendaftaran_service_id' => $fieldService->id,
                                'dokter_id' => decrypt1($dokterEncrypted),
                            ]);
                        }
                    }
                }
            });

            return response()->json([
                'text' => $text,
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function riwayatDelete(Request $request)
    {
        try {
            $id = decrypt0($request->id);

            DB::transaction(function () use ($id) {
                $pendaftaran = Pendaftaran::find($id);

                if (!$pendaftaran) {
                    abort(404,'Data tidak ditemukan');
                }

                // Hapus relasi resep dan service
                PendaftaranResep::where('pendaftaran_id', $id)->delete();
                PendaftaranService::where('pendaftaran_id', $id)->delete();

                // Hapus foto dari storage
                $fotos = PendaftaranFoto::where('pendaftaran_id', $id)->get();
                $folder = "images/foto_before/{$id}";

                foreach ($fotos as $foto) {
                    Storage::disk('public')->delete("{$folder}/{$foto->foto}");
                }

                // Hapus entri foto
                PendaftaranFoto::where('pendaftaran_id', $id)->delete();

                // Hapus pendaftaran utama
                $pendaftaran->delete();
            });

            return response()->json([
                'text' => 'Riwayat berhasil dihapus',
                'status' => 200,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }



}

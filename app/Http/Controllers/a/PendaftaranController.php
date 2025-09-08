<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\PasienTag;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Tag;
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


class PendaftaranController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pendaftaran-list', only : ['index','get_pendaftaran','show']),
            new Middleware('permission:pendaftaran-create', only : ['create','store']),
            new Middleware('permission:pendaftaran-edit', only : ['edit','update']),
            new Middleware('permission:pendaftaran-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Pendaftaran';
        $d['status_pendaftarans'] = StatusPendaftaranEnum::cases();
        $d['jenis_layanans'] = DB::table('jenis_layanans')->select('id','jenis_layanan')->get();
        return view('a.pages.pendaftaran.index',$d);
    }

    public function get_pendaftaran(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Akses tidak sah');
        }
        $tgl_daftar=$request->tgl_daftar;
        $status=$request->status;
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
            })->orderBy('pendaftarans.created_at','asc');

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
            if (Gate::allows('pendaftaran-edit')) {
                $action.= '<li><a href="'.route('pendaftaran.edit',encrypt0($dt->id)).'" title="Edit" class="dropdown-item edit"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>';
                
            }
            if (Gate::allows('pendaftaran-delete')) {
                $action.= '<li><span class="dropdown-item  delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($dt->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Batalkan</span></li>';
                
            }
            $action.='</ul>';
            $action.='</div>';

            $data[] = [
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
        $d['pasien_id']=$request->pasien;
        $d['title']='Pendaftaran';
        $d['prioritas'] = PrioritasPasienEnum::cases();
        $d['jenis_layanans'] = DB::table('jenis_layanans')->select('id','jenis_layanan')->get();
        $d['dokters']= DB::table('dokters')->select('id','nama')->get();
        $d['perawats']= DB::table('karyawans')
        ->join('jabatans','jabatans.id','=','karyawans.jabatan_id')
        ->where('jabatans.jabatan','Perawat')
        ->orWhere('jabatans.jabatan','Beautician')
        ->select('karyawans.id','karyawans.nama')->get();
        return view('a.pages.pendaftaran.create',$d);
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

<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function search_item(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('items')
         ->join('kategori_items','kategori_items.id','=','items.kategori_item_id')
         ->where('items.kode', 'like', "%$searchTerm%")
         ->orWhere('items.nama_item', 'like', "%$searchTerm%")
         ->orderBy('items.nama_item')
         ->select('items.*','kategori_items.kategori_item')
         ->selectSub(function ($query) {
            $query->from('item_stoks')
                    ->selectRaw('COALESCE(SUM(stok), 0)')
                    ->whereColumn('item_stoks.item_id', 'items.id');
            }, 'stok')
            ->get();
         $response = array();
         foreach($data as $dt){
            $ppn=setting()->ppn;
            $harga_incl_ppn = $dt->harga_jual + ($dt->harga_jual * ($ppn / 100));
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->nama_item,
             "kategori"=>$dt->kategori_item,
             "satuan"=>$dt->satuan,
             "hpp"=>0,
             "harga_jual"=>$dt->harga_jual,
             "harga_jual_ppn"=>pembulatan_ke_atas($harga_incl_ppn),
             "ppn"=>$ppn,
             "stok" => (fmod($dt->stok, 1) == 0 ? number_format($dt->stok, 0) : number_format($dt->stok, 2)) . ' ' . $dt->satuan,
             "harga_jual_show"=>'Rp.'.toCurrency(pembulatan_ke_atas($harga_incl_ppn)),
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_items(Request $request)
     {
    
        $term = $request->get('q');
        $type = $request->get('type');
        if($type == 'services'){
            $items = DB::table('services')->where('nama_service', 'LIKE', '%' . $term . '%')
                ->orWhere('kode', 'LIKE', '%' . $term . '%')
                ->select('id', 'nama_service as name', 'harga_jual as price', 'harga_jual as price_ppn','ppn',DB::raw("'services' as type"))
                ->orderBy('total_dibeli','desc')->limit(12)
                ->get();
        } else {
            $ppn=setting()->ppn;
            $items = DB::table('items')
            ->where('nama_item', 'LIKE', '%' . $term . '%')
            ->orWhere('kode', 'LIKE', '%' . $term . '%')
            ->select('id', 'nama_item as name', 'harga_jual as price', 'ppn',DB::raw("'items' as type"),'satuan')
            ->selectSub(function ($query) {
                $query->from('item_stoks')
                    ->selectRaw('COALESCE(SUM(stok), 0)')
                    ->whereColumn('item_stoks.item_id', 'items.id');
            }, 'stok')
            ->orderBy('total_dibeli', 'desc')
            ->limit(12)
            ->get();

            foreach ($items as $item) {
                $harga_incl_ppn = $item->price + ($item->price * ($ppn / 100));
                $item->ppn= $ppn;
                $item->price_ppn = pembulatan_ke_atas($harga_incl_ppn);
                
            }
        }

        return response()->json($items);
 
     }

     public function search_service(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('services')
         ->join('kategori_services','kategori_services.id','=','services.kategori_service_id')
         ->where('services.kode', 'like', "%$searchTerm%")
         ->orWhere('services.nama_service', 'like', "%$searchTerm%")
         ->orderBy('services.nama_service')
         ->select('services.*','kategori_services.kategori_service')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->nama_service,
             "kategori"=>$dt->kategori_service,
             "satuan"=>$dt->satuan,
             "hpp"=>0,
             "harga_jual"=>$dt->harga_jual,
             "harga_jual_show"=>'Rp.'.toCurrency($dt->harga_jual),
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_pasien(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('pasiens')
         ->where('pasiens.nama', 'like', "%$searchTerm%")
            ->orWhere('pasiens.no_rm', 'like', "%$searchTerm%")
            ->orWhere('pasiens.no_hp', 'like', "%$searchTerm%")
            ->orWhere('pasiens.nik', 'like', "%$searchTerm%")
            ->orWhere('pasiens.alamat', 'like', "%$searchTerm%")
         ->select('pasiens.id', 'pasiens.nama', 'pasiens.no_rm', 'pasiens.no_hp', 'pasiens.nik', 'pasiens.alamat', 'pasiens.tgl_lahir', 'pasiens.jenis_kelamin', 'pasiens.panggilan')
         ->orderBy('created_at')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>encrypt0($dt->id),
             "text"=>$dt->no_rm.' - '.$dt->nama,
             "no_rm"=>$dt->no_rm,
             "nama"=>$dt->nama,
             "panggilan"=>$dt->panggilan,
             "tgl_lahir"=>$dt->tgl_lahir,
             "no_hp"=>$dt->no_hp,
             "nik"=>$dt->nik,
             "alamat"=>$dt->alamat,
            );
         }
 
         echo json_encode($response);
 
     }

     public function search_dokter(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('dokters')
         ->where('nama', 'like', "%$searchTerm%")
         ->orderBy('nama')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>encrypt1($dt->id),
             "text"=>$dt->nama,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_perawat(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('karyawans')
         ->where('nama', 'like', "%$searchTerm%")
         ->orderBy('nama')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>encrypt1($dt->id),
             "text"=>$dt->nama,
            );
         }
 
         echo json_encode($response);
 
     }

     public function search_kategori_kas(Request $request)
     {
    
         $type = $request->x; 
         if (!$type) {
             $type = 'income'; 
         }
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kategori_kas')
         ->where('kategori_kas', 'like', "%$searchTerm%")
         ->Where('type', "$type")
         ->orderBy('kategori_kas')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>encrypt1($dt->id),
             "text"=>$dt->kategori_kas,
            );
         }
 
         echo json_encode($response);
 
     }

     public function search_tag(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('tags')
         ->where('tag', 'like', "%$searchTerm%")
         ->orderBy('tag')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->tag,
             "text"=>$dt->tag,
            );
         }
 
         echo json_encode($response);
 
     }

     public function search_provinsi(Request $request)
     {
    
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('provinsis')
         ->where('provinsi', 'like', "%$searchTerm%")
         ->orderBy('provinsi')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->provinsi,
            );
         }
 
         echo json_encode($response);
 
     }

     public function search_kabupaten(Request $request)
     {
    
         $prov_id =$request->x;
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kabupatens')
         ->where('kabupaten', 'like', "%$searchTerm%")
         ->where('prov_id', $prov_id)
         ->orderBy('kabupaten')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kabupaten,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_kecamatan(Request $request)
     {
        
         $kab_id =$request->x;
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kecamatans')
         ->where('kecamatan', 'like', "%$searchTerm%")
         ->where('kab_id', $kab_id)
         ->orderBy('kecamatan')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kecamatan,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function search_kelurahan(Request $request)
     {

         $kec_id =$request->x;
         $searchTerm = addslashes($request->searchTerm);
         $data = DB::table('kelurahans')
         ->where('kelurahan', 'like', "%$searchTerm%")
         ->where('kec_id', $kec_id)
         ->orderBy('kelurahan')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>$dt->id,
             "text"=>$dt->kelurahan,
            );
         }
 
         echo json_encode($response);
 
     }

    public function status_beds(Request $request)
    {
        $status = $request->status;
        $floor = $request->floor;

        // Step 1: Ambil bed + pendaftaran_service_ids
        $bedsRaw = DB::table('beds')
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->leftJoin('pendaftarans', 'beds.pendaftaran_id', '=', 'pendaftarans.id')
            ->leftJoin('pendaftaran_services', 'pendaftarans.id', '=', 'pendaftaran_services.pendaftaran_id')
            ->select(
                'beds.id as bed_id',
                'beds.bed_number',
                'beds.status as bed_status',
                'beds.notes',
                'beds.pendaftaran_id',
                'rooms.id as room_id',
                'rooms.name as room_name',
                'rooms.lantai',
                'pendaftaran_services.id as pendaftaran_service_id'
            )
            ->when($status && $status !== 'all', fn($q) => $q->where('beds.status', $status))
            ->when($floor && $floor !== 'all', fn($q) => $q->where('rooms.lantai', $floor))
            ->get();

        // Step 2: Gabungkan pendaftaran_service_id per bed (hindari duplikasi)
        $beds = collect();
        $serviceMap = [];

        foreach ($bedsRaw as $row) {
            $bedId = $row->bed_id;

            if (!$beds->has($bedId)) {
                $beds->put($bedId, (object)[
                    'bed_id' => $bedId,
                    'bed_number' => $row->bed_number,
                    'bed_status' => $row->bed_status,
                    'notes' => $row->notes,
                    'pendaftaran_id' => $row->pendaftaran_id,
                    'room_id' => $row->room_id,
                    'room_name' => $row->room_name,
                    'lantai' => $row->lantai,
                    'pendaftaran_service_ids' => [],
                ]);
            }

            if ($row->pendaftaran_service_id) {
                $beds[$bedId]->pendaftaran_service_ids[] = $row->pendaftaran_service_id;
            }
        }

        // Step 3: Ambil dokter berdasarkan pendaftaran_service_id
        $dokterMap = DB::table('pendaftaran_service_dokters')
            ->join('dokters', 'pendaftaran_service_dokters.dokter_id', '=', 'dokters.id')
            ->select(
                'pendaftaran_service_dokters.pendaftaran_service_id',
                'dokters.id as dokter_id',
                'dokters.nama as dokter_nama'
            )
            ->get()
            ->groupBy('pendaftaran_service_id');

        // Step 4: Ambil karyawan
        $karyawanMap = DB::table('pendaftaran_service_karyawans')
            ->join('karyawans', 'pendaftaran_service_karyawans.karyawan_id', '=', 'karyawans.id')
            ->select(
                'pendaftaran_service_karyawans.pendaftaran_service_id',
                'karyawans.id as karyawan_id',
                'karyawans.nama as karyawan_nama'
            )
            ->get()
            ->groupBy('pendaftaran_service_id');

        // Step 5: Susun data terstruktur dan unik
        $grouped = [];
        foreach ($beds as $bed) {
            $lantai = $bed->lantai;
            $roomId = $bed->room_id;

            if (!isset($grouped[$lantai])) {
                $grouped[$lantai] = [];
            }

            if (!isset($grouped[$lantai][$roomId])) {
                $grouped[$lantai][$roomId] = [
                    'room_id' => $bed->room_id,
                    'room_name' => $bed->room_name,
                    'beds' => [],
                ];
            }

            $dokters = [];
            $karyawans = [];

            foreach (array_unique($bed->pendaftaran_service_ids) as $psid) {
                foreach ($dokterMap[$psid] ?? [] as $dok) {
                    $dokters[$dok->dokter_id] = [
                        'dokter_id' => $dok->dokter_id,
                        'dokter_nama' => $dok->dokter_nama,
                    ];
                }
                foreach ($karyawanMap[$psid] ?? [] as $kar) {
                    $karyawans[$kar->karyawan_id] = [
                        'karyawan_id' => $kar->karyawan_id,
                        'karyawan_nama' => $kar->karyawan_nama,
                    ];
                }
            }

            $grouped[$lantai][$roomId]['beds'][] = [
                'id' => $bed->bed_id,
                'bed_number' => $bed->bed_number,
                'status' => $bed->bed_status,
                'notes' => $bed->notes,
                'dokters' => array_values($dokters),
                'karyawans' => array_values($karyawans),
            ];
        }

        return response()->json($grouped);
    }






}

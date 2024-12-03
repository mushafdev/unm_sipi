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
     
     public function search_mahasiswa(Request $request)
     {

         $searchTerm = addslashes($request->searchTerm);
         $isExist=$request->isExist;
         $isExist=array_filter($isExist, fn($value) => !is_null($value) && $value !== '');
         foreach($isExist as &$i){
            $i=decrypt0($i);
         }
         $data = DB::table('mahasiswas')
         ->select('mahasiswas.id','mahasiswas.nim','mahasiswas.nama','mahasiswas.kelas')
         ->whereRaw("((nama like '%".$searchTerm."%') OR (nim like '%".$searchTerm."%'))");
         if(count($isExist)>0){
            $data=$data->whereNotIn('mahasiswas.id', $isExist);
         }
         $data=$data->orderBy('mahasiswas.nama')->get();
         $response = array();
         foreach($data as $dt){
            $response[] = array(
             "id"=>encrypt0($dt->id),
             "text"=>$dt->nim,
             "nama"=>$dt->nama,
             "nim"=>$dt->nim,
             "kelas"=>$dt->kelas,
            );
         }
 
         echo json_encode($response);
 
     }
     
     public function logbook(Request $request)
     {

         $id = decrypt0($request->id);
         $detail=DB::table('group_details')
         ->join('groups','groups.id','=','group_details.group_id')
         ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
         ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
         ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
         ->where('group_details.id',$id)
         ->select('group_details.*','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi','lokasi_pis.lokasi_pi')->first();
         $logbooks=DB::table('logbooks')->where('mahasiswa_id',$detail->mahasiswa_id)
         ->orderBy('tanggal','desc')
         ->orderBy('jam','desc')->get();
         return response()->json([
            'detail' => $detail,
            'logbooks' => $logbooks,
            'status' => 200,
        ]);
 
     }




}

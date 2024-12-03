<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupLokasi;
use App\Models\GroupDetail;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GroupLokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id=$request->session()->get('id');
        $group_id=GroupDetail::where('mahasiswa_id',$id)->first()->group_id;
        $lokasi=Group::join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
            ->select('lokasi_pis.lokasi_pi','lokasi_pis.kota','lokasi_pis.alamat')
            ->where('groups.id',$group_id)->first();
        $cek=GroupLokasi::where('group_id',$group_id)->count();
        $detail=[];
        if($cek>0){
            $detail=GroupLokasi::where('group_lokasis.group_id',$group_id)->first();
        }
        $d['title']='Info Lokasi PI';
        $d['lokasi']=$lokasi;
        $d['data']=$detail;
        return view('u.pages.group_lokasi.index',$d);
    }



   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'deskripsi'=>'nullable',
            'kebutuhan_pekerjaan'=>'nullable',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request,&$error) {
        
                $mahasiswa_id=$request->session()->get('id');
                $group_id=GroupDetail::where('mahasiswa_id',$mahasiswa_id)->first()->group_id;
                GroupLokasi::where('group_id',$group_id)->delete();
                $field = new GroupLokasi();
                $field->kebutuhan_pekerjaan = $request->kebutuhan_pekerjaan;
                $field->deskripsi = $request->deskripsi;
                $field->group_id = $group_id;
                $field->inserted_by =  $mahasiswa_id;

                $field->save();

            });
            return response()->json([
                'text' => 'Data sukses disimpan',
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
}

<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupDetail;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Rules\CheckGroupMahasiswa;
use Illuminate\Support\Str;

class VerifikasiPiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $d['title']='Verifikasi Selesai Praktik Industri';
        return view('a.pages.verifikasi_pi.index',$d);
    }

    public function get_verifikasi_pi(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::leftJoin('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
            ->leftJoin('dosens','groups.pembimbing_id','=','dosens.id')
            ->select('groups.*','lokasi_pis.lokasi_pi','dosens.nama as pembimbing','dosens.nip as pembimbing_nip')
            ->where('groups.send','Y')
            ->where('groups.admin_verify','Y')
            ->orderByRaw('FIELD(done, "N", "Y")')
            ->orderBy('groups.created_at','desc')->get();
            $data=$data->map(function($row){
                $mahasiswas=DB::table('group_details')->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
                ->select('group_details.*','mahasiswas.nama','mahasiswas.nim')->where('group_id',$row->id)->get();
                $row->mahasiswas='';
                foreach($mahasiswas as $dt){
                    $row->mahasiswas.='<span class="badge bg-info me-1">'.$dt->nama.'-'.$dt->nim.'</span>';
                }
                
                
                return $row;
            });
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        $btn.= '<li><a href="'.route('verifikasi-pi.show',encrypt0($row->id)).'" title="Edit" class="dropdown-item detail"><i class="icon-mid bi bi-eye me-2" aria-hidden="true"></i> Detail</a></li>';
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('pembimbing', function($row){
                        
                        return $row->pembimbing?$row->pembimbing:'-';
                    })
                    ->addColumn('mahasiswas', function($row){
                        
                        return $row->mahasiswas;
                    })
                    ->addColumn('done', function($row){
                        if ($row->done=='N'){
                           $result= '<span class="badge bg-danger">Belum diverifikasi</span>';
                        }else{
                            $result= '<span class="badge bg-success">Telah diverifikasi</span>';
                        }
                        return $result;
                    })

                    ->rawColumns(['action','aktif','mahasiswas','done'])
                    ->make(true);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $d['title']='Detail Verifikasi Praktik Industri';
        $groups=Group::join('lokasi_pis','groups.lokasi_pi_id','=','lokasi_pis.id')
        ->leftJoin('dosens','groups.pembimbing_id','=','dosens.id')
        ->leftJoin('group_lokasis','groups.id','=','group_lokasis.group_id')
        ->where('groups.id',decrypt0($id))
        ->select('groups.*','lokasi_pis.lokasi_pi','lokasi_pis.alamat','lokasi_pis.kota','dosens.nama as pembimbing','dosens.nip as pembimbing_nip','group_lokasis.kebutuhan_pekerjaan')->first();
        $group_details=GroupDetail::join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->where('group_details.group_id',$groups->id)
        ->select('group_details.*','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
        $d['data']=$groups;
        $d['detail']=$group_details;

        $d['admin_verifys']=array('N'=>'Belum Diverifikasi','Y'=>'Disetujui','X'=>'Ditolak');
        return view('a.pages.verifikasi_pi.show',$d);
    }

    public function verifikasi(Request $request)
    {
        
        $rules = [
            'id'=>'required',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request) {
        
                $field = Group::find(decrypt0($request->id));
                $field->done = $request->done;
                $field->catatan_done = $request->catatan_done;
                $field->updated_by =  $request->session()->get('id');
                $field->save();
            });
            return response()->json([
                'text' => 'Data sukses diverifikasi',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }

}

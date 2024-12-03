<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupDetail;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Rules\CheckGroupMahasiswa;
use App\Rules\CheckGroup;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $d['title']='Kelompok';
        $role=$request->session()->get('role');
        if($role=='mahasiswa'){
            return view('u.pages.group.index',$d);
        }elseif($role=='superadmin'){
            return view('a.pages.group.index',$d);
        }
    }

    public function get_group(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::leftJoin('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
            ->leftJoin('dosens','groups.pembimbing_id','=','dosens.id')
            ->select('groups.*','lokasi_pis.lokasi_pi','dosens.nama as pembimbing','dosens.nip as pembimbing_nip')->where('groups.send','Y')
            ->orderByRaw('FIELD(admin_verify, "N", "Y", "X")')
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
                        $btn.= '<li><a href="'.route('group.show',encrypt0($row->id)).'" title="Edit" class="dropdown-item detail"><i class="icon-mid bi bi-eye me-2" aria-hidden="true"></i> Detail</a></li>';
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
                    ->addColumn('admin_verify', function($row){
                        
                        return '<span class="badge '.adminVerifyStatus($row->admin_verify,'badge').'">'.Str::remove('admin', adminVerifyStatus($row->admin_verify,'text')).'</span>';
                    })

                    ->rawColumns(['action','aktif','mahasiswas','admin_verify'])
                    ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $role=$request->session()->get('role');
        if($role=='mahasiswa'){

         $mahasiswa_id=$request->session()->get('id');
         $ifCreate=GroupDetail::where('mahasiswa_id',$mahasiswa_id);
         if($ifCreate->count()>0){
            return redirect()->route('group.show', encrypt0($ifCreate->first()->group_id));
         }   
        }

        $d['title']='Buat Kelompok';
        return view('u.pages.group.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'lokasi_pi'=>'nullable',
            'lokasi_pi_id'=>[
                'required',
                new CheckGroup('')
            ],
            'start_month'=>'required',
            'end_month'=>'required',
            'year'=>'required',
            'mahasiswa_id.*'=>[
                'required',
                new CheckGroupMahasiswa
            ],
        ];

        $messages = [
            'mahasiswa_id.*.required' => 'The nim field is required',
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request,&$error) {
        
                $field = new Group;

                $field->lokasi_pi_id = decrypt0($request->lokasi_pi_id);
                $field->start_month = $request->start_month;
                $field->end_month = $request->end_month;
                $field->year = $request->year;
                $field->mahasiswa_id =  $request->session()->get('id');
                $field->save();
                $count=count($request->mahasiswa_id);
                if($count>0){
                    for($i=0;$i<$count;$i++){
                        $detail = new GroupDetail;
                        $detail->group_id = $field->id;
                        $detail->mahasiswa_id = decrypt0($request->mahasiswa_id[$i]);
                        if($detail->mahasiswa_id==$field->mahasiswa_id){
                            $detail->verify='Y';
                        }
                        $detail->save();
                    }
                }
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
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $d['title']='Detail Kelompok';
        $groups=Group::join('lokasi_pis','groups.lokasi_pi_id','=','lokasi_pis.id')
        ->leftJoin('dosens','groups.pembimbing_id','=','dosens.id')
        ->where('groups.id',decrypt0($id))
        ->select('groups.*','lokasi_pis.lokasi_pi','lokasi_pis.alamat','lokasi_pis.kota','dosens.nama as pembimbing','dosens.nip as pembimbing_nip')->first();
        $group_details=GroupDetail::join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->where('group_details.group_id',$groups->id)
        ->select('group_details.*','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
        $d['data']=$groups;
        $d['detail']=$group_details;

        $role=$request->session()->get('role');
        if($role=='mahasiswa'){
            return view('u.pages.group.show',$d);
        }elseif($role=='superadmin'){
            $d['admin_verifys']=array('N'=>'Belum Diverifikasi','Y'=>'Disetujui','X'=>'Ditolak');
            return view('a.pages.group.show',$d);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,string $id)
    {

        $role=$request->session()->get('role');

        $d['title']='Edit Kelompok';
        $groups=Group::join('lokasi_pis','groups.lokasi_pi_id','=','lokasi_pis.id')
        ->leftJoin('dosens','groups.pembimbing_id','=','dosens.id')
        ->where('groups.id',decrypt0($id))
        ->select('groups.*','lokasi_pis.lokasi_pi','lokasi_pis.alamat','lokasi_pis.kota','dosens.nama as pembimbing','dosens.nip as pembimbing_nip')->first();
       $group_details=GroupDetail::join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')->where('group_details.group_id',$groups->id)
        ->select('group_details.*','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas')->get();
        $d['data']=$groups;
        $d['detail']=$group_details;

        if($role=='mahasiswa'){
            return view('u.pages.group.edit',$d);
        }elseif($role=='superadmin'){
            return view('a.pages.group.edit',$d);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $rules = [
            'lokasi_pi'=>'nullable',
            'lokasi_pi_id'=>[
                'required',
                new CheckGroup($id)
            ],
            'pembimbing_id'=>'nullable',
            'start_month'=>'required',
            'end_month'=>'required',
            'year'=>'required',
            'mahasiswa_id.*'=>[
                'required',
                new CheckGroupMahasiswa
            ],
        ];

        $messages = [
            'mahasiswa_id.*.required' => 'The nim field is required',
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request,&$error,&$id) {
        
                $field = Group::find(decrypt0($id));

                $field->lokasi_pi_id = decrypt0($request->lokasi_pi_id);
                if(!empty($request->pembimbing_id)){
                    $field->pembimbing_id = decrypt0($request->pembimbing_id);
                }
                $field->start_month = $request->start_month;
                $field->end_month = $request->end_month;
                $field->year = $request->year;
                $field->mahasiswa_id =  $request->session()->get('id');
                $field->save();
                if(!empty($request->mahasiswa_id)){
                    $count=count($request->mahasiswa_id);
                    if($count>0){
                        for($i=0;$i<$count;$i++){
                            $detail = new GroupDetail;
                            $detail->group_id = $field->id;
                            $detail->mahasiswa_id = decrypt0($request->mahasiswa_id[$i]);
                            $detail->save();
                        }
                    }
                }
                
            });
            return response()->json([
                'text' => 'Data sukses diupdate',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
    
    public function send(Request $request)
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
                $field->send = 'Y';
                $field->admin_verify = 'N';
                $field->save();
            });
            return response()->json([
                'text' => 'Data sukses dikirim',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
    
    public function verifikasi(Request $request)
    {
        
        $rules = [
            'id'=>'required',
            'pembimbing_id'=>'required',
            'admin_verify'=>'required',
            'catatan'=>'nullable',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request) {
        
                $field = Group::find(decrypt0($request->id));

                $field->pembimbing_id = decrypt0($request->pembimbing_id);
                $field->catatan = $request->catatan;
                $field->admin_verify = $request->admin_verify;
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
    
    public function verifikasi_mhs(Request $request)
    {
        
        $rules = [
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request) {
                $mahasiswa_id=$request->session()->get('id');
                $field = GroupDetail::where('mahasiswa_id',$mahasiswa_id);
                $field->update([
                    'verify'=>'Y'
                ]) ;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_item(Request $request)
    {
        $id=decrypt0($request->id);
        try {
            $fieldDetail = GroupDetail::find($id);
            $fieldDetail->delete();
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
    
    public function destroy(string $id)
    {
        try {
            DB::transaction(function() use (&$id) {
            $id=decrypt0($id);
            $fieldDetail = GroupDetail::where('group_id',$id);
            $fieldDetail->delete();
            $field = Group::find($id);
            $field->delete();

        });
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
}

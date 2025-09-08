<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-list', only : ['index','get_users']),
            new Middleware('permission:user-create', only : ['create','store']),
            new Middleware('permission:user-edit', only : ['edit','update']),
            new Middleware('permission:user-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d['title']='Pengguna';
        return view('a.pages.users.index',$d);
    }

    public function get_users(Request $request)
    {
        if ($request->ajax()) {
            $data = Admin::leftJoin('users','users.id','=','admins.user_id')
            ->select('admins.*','users.email','users.role','users.aktif')
            ->whereNotIn('users.role',['superadmin'])->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn='';
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        if (Gate::allows('user-edit')) {
                            $btn.= '<li><a href="'.route('users.edit',encrypt0($row->id)).'" title="Edit" class="dropdown-item edit"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>';
                            
                        }
                        if (Gate::allows('user-delete')) {
                            $btn.= '<li><span class="dropdown-item  delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($row->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete</span></li>';
                            
                        }
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('role', function($row){
                        $result='<span class="badge bg-primary">'.Str::ucfirst($row->role).'</span>';
                        return $result;
                    })
                    ->addColumn('aktif', function($row){
                        if($row->aktif=='Y'){
                            $aktif='<span class="badge bg-success">Aktif</span>';
                        }else{
                            $aktif='<span class="badge bg-danger">Tidak Aktif</span>';
                        }
                        return $aktif;
                    })

                    ->rawColumns(['action','aktif','user_photo','role'])
                    ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $d['title']='Tambah Pengguna';
        $d['roles'] = Role::whereNotIn('name',['peserta', 'superadmin'])->get();
        return view('a.pages.users.create',$d);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama'=>'required',
            'email'=>'required|email:rfc,dns',
            'photo'=>'max:2048|mimes:jpeg,png',
            'telp'=>'required',
            'alamat'=>'required',
            'username'=>'required|unique:users|min:5|max:50',
            'password'=>'required|min:5|max:50',
            'aktif'=>'required',
            'role'=>'required'
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            DB::transaction(function() use ($request) {

                $user= new User;
                $user->type='admins';
                $user->email=$request->email;
                $user->aktif = $request->aktif;
                $user->role = $request->role;
                $user->username = $request->username;
                $user->password = bcrypt($request->password);
                $user->save();

                $field = new Admin;
                if(!empty($request->photo)){
                    $imgName = time().'.'.$request->photo->extension();
                    Storage::disk('users_photo')->put($imgName, file_get_contents($request->photo));
                    $field->photo = $imgName;
                }

                $field->user_id = $user->id;
                $field->nama = $request->nama;
                $field->alamat = $request->alamat;
                $field->telp = $request->telp;
                $field->inserted_by =  $request->session()->get('id');
                $field->save();


                $user->assignRole($user->role);



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
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $d['title']='Edit Pengguna';
        $d['data'] = Admin::leftJoin('users','users.id','=','admins.user_id')
        ->select('admins.*','users.email','users.role','users.aktif')->find(decrypt0($id));
        $d['roles'] = Role::whereNotIn('name',['peserta', 'superadmin'])->get();
        $d['status_aktif']=array('Y'=>"Aktif",'N'=>"Tidak Aktif");
        return view('a.pages.users.edit',$d);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'nama'=>'required',
            'email'=>'required|email:rfc,dns',
            'photo'=>'max:2048|mimes:jpeg,png',
            'telp'=>'required',
            'alamat'=>'required',
            'username'=>'nullable|unique:users|min:5|max:50',
            'password'=>'nullable|min:5|max:50',
            'aktif'=>'required',
            'role'=>'required'
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {

            $error='';
            DB::transaction(function() use ($request,$id) {
                $field = Admin::find(decrypt0($id));
                if(!empty($request->photo)){
                    $imgName = time().'.'.$request->photo->extension();
                    Storage::disk('users_photo')->put($imgName, file_get_contents($request->photo));
                    $field->photo = $imgName;
                }

                $field->nama = $request->nama;
                $field->alamat = $request->alamat;
                $field->telp = $request->telp;
               
                $field->updated_by =  $request->session()->get('id');

                $field->save();

                $user=User::find($field->user_id);
                $user->email=$request->email;
                $user->aktif = $request->aktif;
                $user->role = $request->role;
                if(!empty($request->username)){
                    $user->username = $request->username;
                }
                if(!empty($request->password)){
                    $user->password = bcrypt($request->password);
                }

                $user->save();

                $user->assignRole($user->role);
            
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
}

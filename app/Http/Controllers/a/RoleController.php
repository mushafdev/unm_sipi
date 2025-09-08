<?php
    
namespace App\Http\Controllers\a;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
    
class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:role-list', only : ['index','get_roles','show']),
            new Middleware('permission:role-create', only : ['create','store']),
            new Middleware('permission:role-edit', only : ['edit','update']),
            new Middleware('permission:role-delete', only : ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $d['title']="Manejemen Role";

        return view('a.pages.roles.index',$d);
    }
    
    public function get_roles(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn='<div class="btn-group">
                        <button class="btn" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu">';
                        $btn.='<li><h6 class="dropdown-header">Action </h6></li>';
                        if (Gate::allows('role-edit')) {
                            $btn.= '<li><a href="'.route('roles.edit',encrypt0($row->id)).'" title="Edit" class="dropdown-item edit"><i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit</a></li>';
                        }
                        if (Gate::allows('role-delete')) {
                            $btn.= '<li><span class="dropdown-item  delete text-danger" title="Hapus" title="Hapus" data-id="'.encrypt0($row->id).'"><i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete</span></li>';
                        }
                        $btn.='</ul>';
                        $btn.='</div>';
                        return $btn;
                    })
                    ->addColumn('name', function($row){
                        $result=$row->name;
                        return $result;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $d['title']="Tambah Role";
        DB::statement("SET SQL_MODE=''");;
        $role_permission = Permission::select('name','id')->groupBy('name')->get();

        $permissions = array();

        foreach($role_permission as $per){

            $key = substr($per->name, 0, strpos($per->name, "-"));

            if(str_starts_with($per->name, $key)){
            
                $permissions[$key][] = $per;
            }

        }
        return view('a.pages.roles.create',$d)->with('permissions',$permissions);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|unique:roles,name',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request) {
                $role = Role::create(['name' => $request->input('nama')]);

                if($request->permissions){

                    foreach ($request->permissions as $key => $value) {
                        $role->givePermissionTo((int)$value);
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('roles.show',compact('role','rolePermissions'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $role = Role::with('permissions')->find(decrypt0($id));
        $d['role'] = $role;
        $d['title']="Edit Role";

        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::select('name','id')->groupBy('name')->get();


        $permissions = array();

        foreach($role_permission as $per){

            $key = substr($per->name, 0, strpos($per->name, "-"));

            if(str_starts_with($per->name, $key)){
            
                $permissions[$key][] = $per;
            }

        }

    
        return view('a.pages.roles.edit',$d)->with('permissions',$permissions);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, string $id)
    {

        $id=decrypt0($id);
        $role = Role::where('id',$id)->first();

        $rules = [
            'nama'=>'required|unique:roles,name,'.$id,
            'permissions' => 'array|nullable',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $error='';
            DB::transaction(function() use ($request,$role) {

                $role->update([
                    "name" => $request->nama
                ]);

                $numericPermissionArray = []; 
                if($request->permissions){
                    foreach ($request->permissions as $key => $value) {
                        $numericPermissionArray[] = intval($value);
                    }
            
                }
                $role->syncPermissions($numericPermissionArray);
    
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = Role::where('id',decrypt0($id))->first();

            if(isset($role)){
                
                $role->permissions()->detach();
                $role->delete();
        
                return response()->json([
                    'text' => 'Data sukses dihapus',
                    'status' => 200,
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
    }
}

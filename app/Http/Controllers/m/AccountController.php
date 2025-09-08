<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $role=$request->session()->get('role');
        $id=$request->session()->get('detailUser')->id;
        $type=auth()->user()->type;
        $user_id=auth()->user()->id;
        $d['title']='My Account';
        $d['data'] =DB::table($type)->join('users','users.id','=',$type.'.user_id')->where('user_id',$user_id)->first();
        if($role=='mahasiswa'){
            return view('u.pages.account',$d);
        }else{
            return view('a.pages.account',$d);
        }
    }

    public function update(Request $request)
    {
        $rules = [
            
            'username'=>'nullable|unique:users|min:5|max:50',
            'password'=>'nullable|min:5|max:50',
        ];

        $messages = [
            'telp.required' => 'Kontak field is required',
        ];

        $request->validate($rules,$messages);

        try {
            $field = User::find($request->session()->get('id'));
            if(!empty($request->photo)){
                $imgName = time().'.'.$request->photo->extension();
                Storage::disk('mahasiswas_photo')->put($imgName, file_get_contents($request->photo));
                $field->photo = $imgName;
            }

            if(!empty($request->username)){
                $field->username = $request->username;
            }
            if(!empty($request->password)){
                $field->password = bcrypt($request->password);
            }
            $field->updated_by =  $request->session()->get('id');

            $field->save();
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

}

<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{

    public function index()
    {
        return view('a.pages.login');
    }

    public function reload_captcha()
    {
        return response()->json(['captcha'=> captcha_src()]);
    }

    public function authenticate(Request $request){

            $rules = [
                'username'=>'required|min:5|max:50',
                'password'=>'required|min:5|max:50',
                'captcha' => 'required|captcha'
            ];

            $messages = [
                // 'username.required' => 'Username kosong',
                // 'username.min' => 'Username tidak boleh lebih kecil dari 5 karakter',
                // 'password.min' => 'Password tidak boleh lebih kecil dari 8 karakter',
                // 'password.required' => 'Password kosong',
                // 'captcha.required' => 'Captcha kosong',
                'captcha.captcha' => 'Please check captcha',
            ];

            $request->validate($rules,$messages);

            if (Auth::attempt(['username' => addslashes($request->username), 'password' => $request->password, 'aktif' => 'Y'],false)) {
                $user=Auth::user();
                $request->session()->put('id',$user->id);
                $request->session()->put('role',$user->role);
                return redirect()->intended('/admin/dashboard');
                
                
            }

            return back()->withErrors([
                'password' => 'Username and Password is not valid',
            ])->onlyInput('password');
    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('admin-login');
    }
}

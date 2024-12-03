<?php

namespace App\Http\Controllers\u;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiEmail;
use Illuminate\Support\Facades\Hash;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function index()
    {
        return view('u.pages.login');
    }
    
    public function registrasi()
    {

        $d['jurusans']=Jurusan::get();
        $d['prodis']=Prodi::get();
        return view('u.pages.registrasi',$d);
    }
    
    public function forgot_password()
    {
        return view('u.pages.forgot_password');
    }
    
    
    public function forgot_password_email(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
     
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }


    public function reset_password(string $token)
    {
        return view('u.pages.reset_password', ['token' => $token]);
    }
    
    public function reset_password_update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required|min:5|unique:mahasiswas',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Mahasiswa $user, string $password) use ($request) {
                $user->forceFill([
                    'username' => $request->username,
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    public function registrasi_store(Request $request){

        $rules = [
            'nim'=>'required|unique:mahasiswas',
            'nama'=>'required',
            'kelas'=>'required',
            'prodi_id'=>'required',
            'email'=>'required|email:rfc,dns|unique:mahasiswas',
            'username'=>'required|min:5|unique:mahasiswas',
            'password'=>'required|min:5|max:50|same:password_confirmation',
            'captcha' => 'required|captcha'
        ];

        $messages = [
            'prodi.required' => 'Prodi is required',
            'captcha.captcha' => 'Please check captcha',
        ];

        $request->validate($rules,$messages);

        
        $field = new Mahasiswa;
        $field->inserted_by =  $request->session()->get('id');
        $field->nama = $request->nama;
        $field->email = $request->email;
        $field->nim = $request->nim;
        $field->kelas = $request->kelas;
        $field->prodi_id = $request->prodi_id;
        $field->aktif = 'N';
        $field->role = 'mahasiswa';
        $field->username = $request->username;
        $field->password = bcrypt($request->password);
        

        $metaEmail = [
            'subject' => 'Verifikasi Email '.identity()['singkat'],
            'nama' => $request->nama,
            'link_verifikasi' => url('email/verify/'.(encrypt0($request->email)).'/'.base64_encode(Hash::make($request->email))),
            'time' => encrypt0(base64_encode(Carbon::now()))
        ];
        try {
            Mail::to($request->email)->send(new VerifikasiEmail($metaEmail));
            $saved=$field->save();
            if($saved){
                return redirect('/')->with('success','Data berhasil disimpan. Silahkan cek email anda untuk verifikasi !');                   
            }
                
            
        }catch (Exception $e) {
            return back()->withErrors([
                'error_message' => 'Terjadi Kesalahan. Silahkan diulangi',
            ]);
        }

       
    }

    public function verify_email(Request $request)
    {
        try {
            $id1=decrypt0($request->segment(3));
            $id2=base64_decode($request->segment(4));
            if (Hash::check($id1, $id2)) {
                $data=Mahasiswa::where('email',$id1);
                if($data->count()>0){

                    $field=$data->first();
                    if(empty($field->email_verified_at)){
                        $data->update([
                            'email_verified_at'=>Carbon::now(),
                            'aktif' => 'Y',
                        ]);
                        return redirect('/')->with('success','Email berhasil diverifikasi. Silahkan login.');   
                    }
                }
            }
                
            
        }catch (Exception $e) {
            return back()->withErrors([
                'error_message' => 'Terjadi Kesalahan. Silahkan diulangi',
            ]);
        }
       


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
            $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            if (Auth::guard('mahasiswa')->attempt([$fieldType => addslashes($request->username), 'password' => $request->password, 'aktif' => 'Y'],false)) {
                $user=Auth::guard('mahasiswa')->user();
                $request->session()->put('id',$user->id);
                $request->session()->put('nama',$user->nama);
                $request->session()->put('nim',$user->nim);
                $request->session()->put('kelas',$user->kelas);
                $request->session()->put('role',$user->role);
                return redirect()->intended('/account/home');
                
                
            }

            return back()->withErrors([
                'password' => 'Username and Password is not valid',
            ])->onlyInput('password');
    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::guard('mahasiswa')->logout();
        return redirect('login');
    }
}

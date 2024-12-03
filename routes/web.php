<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\u\LoginController;
use \App\Http\Controllers\u\UDashboardController;

use \App\Http\Controllers\a\AdminLoginController;
use \App\Http\Controllers\a\DashboardController;
use \App\Http\Controllers\a\SettingController;
use \App\Http\Controllers\a\UserController;
use \App\Http\Controllers\a\MahasiswaController;
use \App\Http\Controllers\a\DosenController;
use \App\Http\Controllers\a\LokasiPiController;
use \App\Http\Controllers\a\VerifikasiPiController;


use \App\Http\Controllers\m\GroupController;
use \App\Http\Controllers\m\LogbookController;
use \App\Http\Controllers\m\GroupLokasiController;
use \App\Http\Controllers\m\DataController;
use \App\Http\Controllers\m\PageStaticController;
use \App\Http\Controllers\m\DocumentController;


Route::get('/email/verify', [LoginController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [LoginController::class, 'verify_email'])->name('verification.verify');
Route::post('/email/resend', [LoginController::class, 'resend'])->name('verification.resend');
Route::post('/forgot-password', [LoginController::class, 'resend'])->name('login.forgot_password');
 

Route::get('/', [LoginController::class, 'index'])->name('home');
Route::get('/registrasi', [LoginController::class, 'registrasi'])->name('registrasi');
Route::post('/registrasi', [LoginController::class, 'registrasi_store'])->name('login.registrasi_store');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/login/reload-captcha', [LoginController::class, 'reload_captcha'])->name('login.reload_captcha');
Route::post('/login/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

Route::get('/forgot-password', [LoginController::class, 'forgot_password'])->name('login.forgot_password')->middleware('guest');
Route::post('/forgot-password', [LoginController::class, 'forgot_password_email'])->name('login.forgot_password_email')->middleware('guest');
Route::get('/reset-password/{token}', [LoginController::class, 'reset_password'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [LoginController::class, 'reset_password_update'])->name('password.update')->middleware('guest');

Route::get('/admin-login', [AdminLoginController::class, 'index'])->name('admin_login');
Route::get('/admin-login/reload-captcha', [AdminLoginController::class, 'reload_captcha'])->name('admin_login.reload_captcha');
Route::post('/admin-login/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin_login.authenticate');
Route::get('/admin-logout', [AdminLoginController::class, 'logout'])->name('admin_login.logout');


// use Illuminate\Support\Facades\Mail; // tambahkan ini
// use App\Mail\VerifikasiEmail; // tambahkan ini

// Route::get('/mail/send', function () {
//     $data = [
//         'subject' => 'Testing Kirim Email',
//         'title' => 'Testing Kirim Email',
//         'body' => 'Ini adalah email uji coba dari Tutorial Laravel: Send Email Via SMTP GMAIL @ qadrLabs.com'
//     ];

//     Mail::to('mushafdev@gmail.com')->send(new VerifikasiEmail($data));

// });

Route::group(['middleware' => ['auth-check:multi']], function () {

    //Data

    Route::prefix('data')->group(function () {
        Route::get('/search-mahasiswa', [DataController::class, 'search_mahasiswa'])->name('data.search_mahasiswa');
        Route::get('/logbook', [DataController::class, 'logbook'])->name('data.logbook');
    });
    //End Data


    // Group
    Route::get('/group/get-group', [GroupController::class, 'get_group'])->name('group.get_group');
    Route::post('/group/destroy-item', [GroupController::class, 'destroy_item'])->name('group.destroy_item');
    Route::post('/group/send', [GroupController::class, 'send'])->name('group.send');
    Route::post('/group/verifikasi', [GroupController::class, 'verifikasi'])->name('group.verifikasi');
    Route::post('/group/verifikasi-mhs', [GroupController::class, 'verifikasi_mhs'])->name('group.verifikasi_mhs');
    Route::resource('group', GroupController::class);
    // End Group

    // LokasiPi
    Route::get('/lokasi-pi/get-lokasi-pi', [LokasiPiController::class, 'get_lokasi_pi'])->name('lokasi-pi.get_lokasi_pi');
    Route::get('/lokasi-pi/get-data', [LokasiPiController::class, 'get_data'])->name('lokasi-pi.get_data');
    // End LokasiPi

    // Logbook
    Route::get('/logbook/get-logbook', [LogbookController::class, 'get_logbook'])->name('logbook.get_logbook');
    Route::resource('logbook', LogbookController::class);
    // End Logbook

    // GroupLokasi
    Route::get('/group-lokasi/get-group-lokasi', [GroupLokasiController::class, 'get_group_lokasi'])->name('group-lokasi.get_group_lokasi');
    Route::resource('group-lokasi', GroupLokasiController::class);
    // End GroupLokasi


    // Page
    Route::prefix('files')->group(function () {
        Route::get('/logbook/{any}', [PageStaticController::class, 'logbook'])->name('page.logbook');
        Route::get('/sertifikat-pembekalan/{any}', [PageStaticController::class, 'sertifikat_pembekalan'])->name('page.sertifikat_pembekalan');
        Route::get('/permohonan-izin-observasi/{any}', [PageStaticController::class, 'permohonan_izin_observasi'])->name('page.permohonan_izin_observasi');
        Route::get('/permohonan-izin-pi/{any}', [PageStaticController::class, 'permohonan_izin_pi'])->name('page.permohonan_izin_pi');
        Route::get('/permohonan-pembimbing/{any}', [PageStaticController::class, 'permohonan_pembimbing'])->name('page.permohonan_pembimbing');
        
        Route::get('/daftar-hadir/{any}', [PageStaticController::class, 'daftar_hadir'])->name('page.daftar_hadir');
        Route::get('/daftar-nilai/{any}', [PageStaticController::class, 'daftar_nilai'])->name('page.daftar_nilai');
        Route::get('/tanda-selesai-pi/{any}', [PageStaticController::class, 'tanda_selesai_pi'])->name('page.tanda_selesai_pi');
        
        Route::get('/persetujuan-hari/{any}', [PageStaticController::class, 'persetujuan_hari'])->name('page.persetujuan_hari');
        Route::get('/pengesahan/{any}', [PageStaticController::class, 'pengesahan'])->name('page.pengesahan');
        Route::get('/persetujuan-pembimbing/{any}', [PageStaticController::class, 'persetujuan_pembimbing'])->name('page.persetujuan_pembimbing');
        Route::get('/daftar-hadir-seminar/{any}', [PageStaticController::class, 'daftar_hadir_seminar'])->name('page.daftar_hadir_seminar');
    });
    // End Page

    // Document
    Route::prefix('document')->group(function () {
        Route::get('/permohonan', [DocumentController::class, 'permohonan'])->name('document.permohonan');
        Route::get('/berkas-pi', [DocumentController::class, 'berkas_pi'])->name('document.berkas_pi');
        Route::get('/berkas-seminar', [DocumentController::class, 'berkas_seminar'])->name('document.berkas_seminar');
    });
    // End Document
});

Route::group(['middleware' => ['auth-check:admin']], function () {
    
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa
    Route::get('/mahasiswa/get-mahasiswa', [MahasiswaController::class, 'get_mahasiswa'])->name('mahasiswa.get_mahasiswa');
    Route::get('/mahasiswa/get-data', [MahasiswaController::class, 'get_data'])->name('mahasiswa.get_data');
    Route::resource('mahasiswa', MahasiswaController::class)->except([
        'show','edit','create','update'
    ]);
    // End Mahasiswa
    
    // Dosen
    Route::get('/dosen/get-dosen', [DosenController::class, 'get_dosen'])->name('dosen.get_dosen');
    Route::get('/dosen/get-data', [DosenController::class, 'get_data'])->name('dosen.get_data');
    Route::resource('dosen', DosenController::class)->except([
        'show','edit','create','update'
    ]);
    // End Dosen
   
    // LokasiPi
    Route::resource('lokasi-pi', LokasiPiController::class)->except([
        'show'
    ]);
    // End LokasiPi
    
    // VerifikasiPi
    Route::get('/verifikasi-pi/get-verifikasi-pi', [VerifikasiPiController::class, 'get_verifikasi_pi'])->name('verifikasi-pi.get_verifikasi_pi');
    Route::post('/verifikasi-pi/verifikasi', [VerifikasiPiController::class, 'verifikasi'])->name('verifikasi-pi.verifikasi');
    Route::resource('verifikasi-pi', VerifikasiPiController::class)->except([
        'create','store','edit','create','update'
    ]);
    // End VerifikasiPi

    // Users
    Route::get('/users/get-users', [UserController::class, 'get_users'])->name('users.get_users');
    Route::resource('users', UserController::class)->except([
        'show'
    ]);
    // End Users


    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('setting.update');
});

Route::group(['middleware' => ['auth-check:mahasiswa']], function () {
    
    Route::get('/account/home', [UDashboardController::class, 'index'])->name('u.dashboard');
    

});

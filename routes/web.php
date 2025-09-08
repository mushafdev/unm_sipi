<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\u\LoginController;
use \App\Http\Controllers\u\UjianController;
use \App\Http\Controllers\u\UDashboardController;

use \App\Http\Controllers\a\RoleController;
use \App\Http\Controllers\a\PermissionController;

use \App\Http\Controllers\a\AdminLoginController;
use \App\Http\Controllers\a\DashboardController;
use \App\Http\Controllers\a\SettingController;
use \App\Http\Controllers\a\UserController;
use \App\Http\Controllers\a\PasienController;
use \App\Http\Controllers\a\PendaftaranController;
use \App\Http\Controllers\a\PerawatanController;
use \App\Http\Controllers\a\ServiceController;
use \App\Http\Controllers\a\ItemController;
use \App\Http\Controllers\a\SatuanController;
use \App\Http\Controllers\a\KategoriItemController;
use \App\Http\Controllers\a\KategoriKasController;
use \App\Http\Controllers\a\KategoriServiceController;
use \App\Http\Controllers\a\GudangController;
use \App\Http\Controllers\a\MetodePembayaranController;
use \App\Http\Controllers\a\AkunKasController;
use \App\Http\Controllers\a\BedController;
use \App\Http\Controllers\a\RoomController;
use \App\Http\Controllers\a\TagController;
use \App\Http\Controllers\a\DokterController;
use \App\Http\Controllers\a\KaryawanController;
use \App\Http\Controllers\a\ItemStokController;
use \App\Http\Controllers\a\ItemTransactionController;
use \App\Http\Controllers\a\ItemStokOpnameController;
use \App\Http\Controllers\a\ItemStokAwalController;
use \App\Http\Controllers\a\TransaksiKasController;
use \App\Http\Controllers\a\ClosingKasController;
use \App\Http\Controllers\a\PenjualanController;
use \App\Http\Controllers\m\AccountController;
use \App\Http\Controllers\m\DataController;

Route::get('/', [AdminLoginController::class, 'index'])->name('login');
Route::get('login', [AdminLoginController::class, 'index'])->name('login');
Route::get('login/reload-captcha', [AdminLoginController::class, 'reload_captcha'])->name('admin.reload_captcha');
Route::post('login/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
Route::get('login/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


Route::group(['middleware' => ['auth']], function () {

     //Data

    Route::prefix('data')->group(function () {
        Route::get('/search-item', [DataController::class, 'search_item'])->name('data.search_item');
        Route::get('/search-items', [DataController::class, 'search_items'])->name('data.search_items');
        Route::get('/search-service', [DataController::class, 'search_service'])->name('data.search_service');
        Route::get('/search-pasien', [DataController::class, 'search_pasien'])->name('data.search_pasien');
        Route::get('/search-dokter', [DataController::class, 'search_dokter'])->name('data.search_dokter');
        Route::get('/search-perawat', [DataController::class, 'search_perawat'])->name('data.search_perawat');
        Route::get('/search-kategori-kas', [DataController::class, 'search_kategori_kas'])->name('data.search_kategori_kas');
        Route::get('/search-tag', [DataController::class, 'search_tag'])->name('data.search_tag');
        Route::get('/search-provinsi', [DataController::class, 'search_provinsi'])->name('data.search_provinsi');
        Route::get('/search-kabupaten', [DataController::class, 'search_kabupaten'])->name('data.search_kabupaten');
        Route::get('/search-kecamatan', [DataController::class, 'search_kecamatan'])->name('data.search_kecamatan');
        Route::get('/search-kelurahan', [DataController::class, 'search_kelurahan'])->name('data.search_kelurahan');
        Route::get('status-beds', [DataController::class, 'status_beds'])->name('data.status_beds');
       
        
    });
    //End Data

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/get-transaksi-per-bulan', [DashboardController::class, 'getTransaksiPerBulan'])->name('dashboard.get_transaksi_per_bulan');
    Route::get('dashboard/get-transaksi-per-item', [DashboardController::class, 'getTransaksiPerItem'])->name('dashboard.get_transaksi_per_item');
    Route::get('dashboard/get-transaksi-per-service', [DashboardController::class, 'getTransaksiPerService'])->name('dashboard.get_transaksi_per_service');

    Route::prefix('penjualan')
    ->group(function () {

        // Penjualan
        Route::get('penjualan/get-penjualan', [PenjualanController::class, 'get_penjualan'])->name('penjualan.get_penjualan');
        Route::get('penjualan/create-opsi', [PenjualanController::class, 'pracreate'])->name('penjualan.pracreate');
        Route::get('penjualan/pendaftaran/{id}/keranjang', [PenjualanController::class, 'getPendaftaranItems'])->name('penjualan.pendaftaran.items');
        Route::post('penjualan/pembayaran/store', [PenjualanController::class, 'pembayaranStore'])->name('penjualan.pembayaran.store');
        Route::post('penjualan/pembayaran/delete', [PenjualanController::class, 'pembayaranDestroy'])->name('penjualan.pembayaran.delete');

        Route::resource('penjualan', PenjualanController::class);
        // End Penjualan
        
        
    });

    Route::prefix('pelayanan')
    ->group(function () {

        // Perawatan
        Route::get('perawatan/get-perawatan', [PerawatanController::class, 'get_perawatan'])->name('perawatan.get_perawatan');
        Route::get('perawatan/total/{id}', [PerawatanController::class, 'getGrandTotal'])->name('perawatan.get_grand_total');
        
        Route::prefix('perawatan')->group(function () {
            Route::get('pasien/{id}', [PerawatanController::class, 'loadPasien']);
            Route::get('foto/{id}', [PerawatanController::class, 'loadFoto']);

            Route::get('service/{id}', [PerawatanController::class, 'loadService'])->name('perawatan.service.index');
            Route::get('service/data/{pendaftaranId}', [PerawatanController::class, 'getServiceData'])->name('perawatan.service.data');
            Route::post('service/store', [PerawatanController::class, 'serviceStore'])->name('perawatan.service.store');
            Route::delete('service/{id}', [PerawatanController::class, 'serviceDestroy'])->name('perawatan.service.destory');
            Route::get('service/{id}/edit', [PerawatanController::class, 'serviceGetData'])->name('perawatan.service.get_data');
            
            Route::get('item/{id}', [PerawatanController::class, 'loadItem'])->name('perawatan.item.index');
            Route::get('item/data/{pendaftaranId}', [PerawatanController::class, 'getItemData'])->name('perawatan.item.data');
            Route::post('item/store', [PerawatanController::class, 'itemStore'])->name('perawatan.item.store');
            Route::delete('item/{id}', [PerawatanController::class, 'itemDestroy'])->name('perawatan.item.destory');
            Route::get('item/{id}/edit', [PerawatanController::class, 'itemGetData'])->name('perawatan.item.get_data');

            Route::post('/foto/upload-before-photo', [PerawatanController::class, 'fotoBeforeUpload'])->name('perawatan.foto.upload');
            Route::post('/foto/delete-before-photo', [PerawatanController::class, 'fotoBeforeDelete'])->name('perawatan.foto.delete');
            
            Route::post('/bed/update', [PerawatanController::class, 'updateBed'])->name('perawatan.bed.update');
            Route::post('/pembayaran', [PerawatanController::class, 'pembayaran'])->name('perawatan.pembayaran');

            Route::get('/resep/{pendaftaranId}', [PerawatanController::class, 'resep'])->name('perawatan.resep.index');
            Route::post('/resep/store', [PerawatanController::class, 'resepStore'])->name('perawatan.resep.store');
            Route::delete('/resep/delete/{id}', [PerawatanController::class, 'resepDelete'])->name('perawatan.resep.delete');

            Route::post('/ubah-layanan', [PerawatanController::class, 'ubahLayanan'])->name('perawatan.ubah_layanan');
    
        });

        Route::resource('perawatan', PerawatanController::class);
        // End Perawatan

        // Pendaftaran
        Route::get('pendaftaran/get-pendaftaran', [PendaftaranController::class, 'get_pendaftaran'])->name('pendaftaran.get_pendaftaran');
        Route::resource('pendaftaran', PendaftaranController::class);
        // End Pendaftaran
        
        // Pasien
        Route::get('pasien/get-pasien', [PasienController::class, 'get_pasien'])->name('pasien.get_pasien');
        Route::get('pasien/detail-data', [PasienController::class, 'detail_data'])->name('pasien.detail_data');
        Route::get('pasien/{id}/riwayat', [PasienController::class, 'getRiwayat'])->name('pasien.riwayat');
        Route::post('pasien/riwayat/store', [PasienController::class, 'riwayatStore'])->name('pasien.riwayat.store');
        Route::get('pasien/riwayat/{id}/detail', [PasienController::class, 'getRiwayatDetail'])->name('pasien.riwayat.detail');
        Route::get('pasien/riwayat/get-detail', [PasienController::class, 'getRiwayatGetDetail'])->name('pasien.riwayat.get_detail');
        Route::post('pasien/riwayat/delete', [PasienController::class, 'riwayatDelete'])->name('pasien.riwayat.delete');
        Route::get('pasien/{id}/resep', [PasienController::class, 'getResep'])->name('pasien.resep');
        Route::resource('pasien', PasienController::class);
        // End Pasien
        
    });
    
    Route::prefix('keuangan')
    ->group(function () {

      
        // Transaksi
        Route::get('transaksi-kas/get-transaksi-kas', [TransaksiKasController::class, 'get_transaksi_kas'])->name('transaksi-kas.get_transaksi_kas');
        Route::resource('transaksi-kas', TransaksiKasController::class);
        // End Transaksi
      
        // Closing
        Route::get('closing-kas/get-closing-kas', [ClosingKasController::class, 'get_closing_kas'])->name('closing-kas.get_closing_kas');
        Route::resource('closing-kas', ClosingKasController::class);
        // End Closing
       
        
    });

    Route::prefix('persediaan')
    ->group(function () {

        // StokItem
        Route::get('item-stok/get-item-stok', [ItemStokController::class, 'get_item_stok'])->name('item-stok.get_item_stok');
        Route::get('item-stok/get-kartu-stok', [ItemStokController::class, 'get_kartu_stok'])->name('item-stok.get_kartu_stok');
        Route::get('item-stok/print-kartu-stok', [ItemStokController::class, 'print_kartu_stok'])->name('item-stok.print_kartu_stok');
        Route::resource('item-stok', ItemStokController::class);
        // End StokItem

        // Transaksi
        Route::get('item-transaction/get-item-transaction', [ItemTransactionController::class, 'get_item_transaction'])->name('item-transaction.get_item_transaction');
        Route::resource('item-transaction', ItemTransactionController::class);
        // End Transaksi
       
        // Stok Opname
        Route::get('stok-opname/get-stok-opname', [ItemStokOpnameController::class, 'get_stok_opname'])->name('stok-opname.get_stok_opname');
        Route::get('stok-opname/get-item-stok-opname', [ItemStokOpnameController::class, 'get_item_stok_opname'])->name('stok-opname.get_item_stok_opname');
        Route::get('stok-opname/get-item-stok-opname-detail', [ItemStokOpnameController::class, 'get_item_stok_opname_detail'])->name('stok-opname.get_item_stok_opname_detail');
        Route::post('stok-opname/update-item', [ItemStokOpnameController::class, 'updateItemDraft'])->name('stok-opname.update_item');
        Route::resource('stok-opname', ItemStokOpnameController::class);
        // End Stok Opname
        
        // Stok Awal
        Route::get('stok-awal/get-stok-awal', [ItemStokAwalController::class, 'get_stok_awal'])->name('stok-awal.get_stok_awal');
        Route::get('stok-awal/get-item-stok-awal', [ItemStokAwalController::class, 'get_item_stok_awal'])->name('stok-awal.get_item_stok_awal');
        Route::get('stok-awal/get-item-stok-awal-detail', [ItemStokAwalController::class, 'get_item_stok_awal_detail'])->name('stok-awal.get_item_stok_awal_detail');
        Route::post('stok-awal/save-item', [ItemStokAwalController::class, 'saveItem'])->name('stok-awal.save_item');
        Route::delete('stok-awal/delete-item', [ItemStokAwalController::class, 'deleteItem'])->name('stok-awal.delete_item');
        Route::post('stok-awal/posting', [ItemStokAwalController::class, 'posting'])->name('stok-awal.posting');
        Route::resource('stok-awal', ItemStokAwalController::class);
        // End Stok Awal
        
        
    });

    Route::prefix('item')
    ->group(function () {

        // Item
        Route::get('item/get-item', [ItemController::class, 'get_item'])->name('item.get_item');
        Route::get('item/get-data', [ItemController::class, 'get_data'])->name('item.get_data');
        Route::resource('item', ItemController::class)->except([
            'show','edit','create','update'
        ]);
        // End Item

        // KategoriItem
        Route::get('kategori-item/get-kategori-item', [KategoriItemController::class, 'get_kategori_item'])->name('kategori-item.get_kategori_item');
        Route::get('kategori-item/get-data', [KategoriItemController::class, 'get_data'])->name('kategori-item.get_data');
        Route::resource('kategori-item', KategoriItemController::class)->except([
            'show','edit','create','update'
        ]);
        // End KategoriItem


        // Satuan
        Route::get('satuan/get-satuan', [SatuanController::class, 'get_satuan'])->name('satuan.get_satuan');
        Route::get('satuan/get-data', [SatuanController::class, 'get_data'])->name('satuan.get_data');
        Route::resource('satuan', SatuanController::class)->except([
            'show','edit','create','update'
        ]);
        // End Satuan
        
    });

    Route::prefix('service')
    ->group(function () {

        // Service
        Route::get('service/get-service', [ServiceController::class, 'get_service'])->name('service.get_service');
        Route::get('service/get-data', [ServiceController::class, 'get_data'])->name('service.get_data');
        Route::resource('service', ServiceController::class)->except([
            'show','edit','create','update'
        ]);
        // End Service

        // KategoriService
        Route::get('kategori-service/get-kategori-service', [KategoriServiceController::class, 'get_kategori_service'])->name('kategori-service.get_kategori_service');
        Route::get('kategori-service/get-data', [KategoriServiceController::class, 'get_data'])->name('kategori-service.get_data');
        Route::resource('kategori-service', KategoriServiceController::class)->except([
            'show','edit','create','update'
        ]);
        // End KategoriService

        
    });
    
    Route::prefix('sdm')
    ->group(function () {

        // Dokter
        Route::get('dokter/get-dokter', [DokterController::class, 'get_dokter'])->name('dokter.get_dokter');
        Route::get('dokter/get-data', [DokterController::class, 'get_data'])->name('dokter.get_data');
        Route::resource('dokter', DokterController::class)->except([
            'show','edit','create','update'
        ]);
        // End Dokter

        // Karyawan
        Route::get('karyawan/get-karyawan', [KaryawanController::class, 'get_karyawan'])->name('karyawan.get_karyawan');
        Route::get('karyawan/get-data', [KaryawanController::class, 'get_data'])->name('karyawan.get_data');
        Route::resource('karyawan', KaryawanController::class)->except([
            'show','edit','create','update'
        ]);
        // End Karyawan


        
    });

    Route::prefix('master')
    ->group(function () {
        
        // Gudang
        Route::get('gudang/get-gudang', [GudangController::class, 'get_gudang'])->name('gudang.get_gudang');
        Route::get('gudang/get-data', [GudangController::class, 'get_data'])->name('gudang.get_data');
        Route::resource('gudang', GudangController::class)->except([
            'show','edit','create','update'
        ]);
        // End Gudang

         // KategoriKas
        Route::get('kategori-kas/get-kategori-kas', [KategoriKasController::class, 'get_kategori_kas'])->name('kategori-kas.get_kategori_kas');
        Route::get('kategori-kas/get-data', [KategoriKasController::class, 'get_data'])->name('kategori-kas.get_data');
        Route::resource('kategori-kas', KategoriKasController::class)->except([
            'show','edit','create','update'
        ]);
        // End KategoriKas
        
        // MetodePembayaran
        Route::get('metode-pembayaran/get-metode-pembayaran', [MetodePembayaranController::class, 'get_metode_pembayaran'])->name('metode-pembayaran.get_metode_pembayaran');
        Route::get('metode-pembayaran/get-data', [MetodePembayaranController::class, 'get_data'])->name('metode-pembayaran.get_data');
        Route::resource('metode-pembayaran', MetodePembayaranController::class)->except([
            'show','edit','create','update'
        ]);
        // End MetodePembayaran
        
        // AkunKas
        Route::get('akun-kas/get-akun-kas', [AkunKasController::class, 'get_akun_kas'])->name('akun-kas.get_akun_kas');
        Route::get('akun-kas/get-data', [AkunKasController::class, 'get_data'])->name('akun-kas.get_data');
        Route::resource('akun-kas', AkunKasController::class)->except([
            'show','edit','create','update'
        ]);
        // End AkunKas
        
        // Room
        Route::get('rooms/get-rooms', [RoomController::class, 'get_rooms'])->name('rooms.get_rooms');
        Route::get('rooms/get-data', [RoomController::class, 'get_data'])->name('rooms.get_data');
        Route::resource('rooms', RoomController::class)->parameters([
            'rooms' => 'id'
        ]);
        // End Room
    });
    

    // Permission
    Route::get('permissions/get-permissions', [PermissionController::class, 'get_permissions'])->name('permissions.get_permissions');
    Route::get('permissions/get-data', [PermissionController::class, 'get_data'])->name('permissions.get_data');
    Route::resource('permissions', PermissionController::class)->except([
        'show','edit','create','update'
    ]);
    // End Permission

    // Role
    Route::get('roles/get-roles', [RoleController::class, 'get_roles'])->name('roles.get_roles');
    Route::resource('roles', RoleController::class);
    // End Role


    // Users
    Route::get('users/get-users', [UserController::class, 'get_users'])->name('users.get_users');
    Route::resource('users', UserController::class)->except([
        'show'
    ]);
    // End Users


    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting/update', [SettingController::class, 'update'])->name('setting.update');


    Route::get('home', [UDashboardController::class, 'index'])->name('u.dashboard');
    Route::get('account', [AccountController::class, 'index'])->name('account');
    Route::post('account', [AccountController::class, 'update'])->name('account.update');
});


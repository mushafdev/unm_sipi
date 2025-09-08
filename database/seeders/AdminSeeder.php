<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard-view',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            'gudang-list',
            'gudang-create',
            'gudang-edit',
            'gudang-delete',


            'kategori kas-list',
            'kategori kas-create',
            'kategori kas-edit',
            'kategori kas-delete',


            'akun kas-list',
            'akun kas-create',
            'akun kas-edit',
            'akun kas-delete',

            'room-list',
            'room-create',
            'room-edit',
            'room-delete',
            

            'metode pembayaran-list',
            'metode pembayaran-create',
            'metode pembayaran-edit',
            'metode pembayaran-delete',

            'satuan-list',
            'satuan-create',
            'satuan-edit',
            'satuan-delete',
            
            'kategori item-list',
            'kategori item-create',
            'kategori item-edit',
            'kategori item-delete',
            
            'item-list',
            'item-create',
            'item-edit',
            'item-delete',
            
            'kategori service-list',
            'kategori service-create',
            'kategori service-edit',
            'kategori service-delete',
            
            'service-list',
            'service-create',
            'service-edit',
            'service-delete',

            'dokter-list',
            'dokter-create',
            'dokter-edit',
            'dokter-delete',

            'karyawan-list',
            'karyawan-create',
            'karyawan-edit',
            'karyawan-delete',

            'pasien-list',
            'pasien-create',
            'pasien-edit',
            'pasien-delete',
            
            'pendaftaran-list',
            'pendaftaran-create',
            'pendaftaran-edit',
            'pendaftaran-delete',

            'item stok-list',

            'item transaction-list',
            'item transaction-create',
            'item transaction-delete',

            'stok opname-list',
            'stok opname-create',

            'stok awal-list',
            'stok awal-create',

            'transaksi kas-list',
            'transaksi kas-create',
            'transaksi kas-delete',


            'penjualan-list',
            'penjualan-create',
            'penjualan-edit',
            'penjualan-delete',

            'perawatan-list',
            'perawatan-create',
            'perawatan-edit',
            'perawatan-delete',

            'closing kas-list',
            'closing kas-create',
            'closing kas-edit',
            'closing kas-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'dashboard-view',
            
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ]);

        $users=User::create(
        ['type'=>'admins','email'=>'mushafdev@gmail.com','role'=>'superadmin', 'username'=>'superadmin','password' => Hash::make('#Super123'),'created_at' => now(), 'updated_at' => now(),'aktif'=>'Y'],
        );
        DB::table('admins')->insert([
            ['user_id'=> $users->id ,'nama'=>'Mushaf','alamat'=>'Jl. Alamat','telp'=>'085423444567','photo'=>NULL,'created_at' => now(), 'updated_at' => now()],
        ]);
        $users->assignRole($superadmin);

        $users=User::create(
        ['type'=>'admins','email'=>'leonardo@gmail.com','role'=>'admin', 'username'=>'admin123','password' => Hash::make('#Pass123'),'created_at' => now(), 'updated_at' => now(),'aktif'=>'Y'],
        );
        DB::table('admins')->insert([
            ['user_id'=> $users->id ,'nama'=>'Leonardo','alamat'=>'Jl. Jalan','telp'=>'0854331212','photo'=>NULL,'created_at' => now(), 'updated_at' => now()],
        ]);

        $users->assignRole($admin);

    }
}

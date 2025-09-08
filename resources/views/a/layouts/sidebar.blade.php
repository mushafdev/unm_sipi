
<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center" style="flex-flow: column;">
                <div class="logo">
                    <img src="{{asset('app/assets/static/images/logo/logo.png')}}">
                    {{-- <h2 class="mb-0 text-warning font-bold fs-4">CBT<span class="text-primary">System</span></h2> --}}
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--system-uicons" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                

                @can('dashboard-view')
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}" class='sidebar-link'>
                        <i class="bi bi-bar-chart"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @else
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}" class='sidebar-link'>
                        <i class="bi bi-bar-smile"></i>
                        <span>Welcome</span>
                    </a>
                </li>
                @endcan
                @if(auth()->user()->hasAnyPermission(['penjualan-list','penjalan']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub  {{ menu_active(['penjualan*']) }} ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-cart4"></i>
                        <span>Penjualan</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('penjualan-create')
                        <li class="submenu-item {{ menu_active(['penjualan/penjualan/create*']) }} ">
                            <a href="{{route('penjualan.pracreate')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Input Penjualan</a>
                        </li>
                        @endcan

                        @can('penjualan-list')
                        <li class="submenu-item {{ menu_active(['penjualan/penjualan','penjualan/penjualan/show*']) }}">
                            <a href="{{route('penjualan.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Daftar Penjualan</a>
                        </li>
                        @endcan
                        
                        
                    </ul>
                </li>
                @endif
                @if(auth()->user()->hasAnyPermission(['pendaftaran-list','pelayanan-list','pasien-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub {{ menu_active(['pelayanan*']) }} ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-hospital"></i>
                        <span>Pelayanan</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('pendaftaran-list')
                        <li class="submenu-item {{ menu_active(['pelayanan/pendaftaran*']) }}">
                            <a href="{{route('pendaftaran.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Pendaftaran</a>
                        </li>
                        @endcan
                        @can('perawatan-list')
                        <li class="submenu-item {{ menu_active(['pelayanan/perawatan*']) }}">
                            <a href="{{route('perawatan.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Perawatan</a>
                        </li>
                        @endcan
                        @can('pasien-list')
                        <li class="submenu-item {{ menu_active(['pelayanan/pasien*']) }}">
                            <a href="{{route('pasien.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Pasien</a>
                        </li>
                        @endcan
                        
                    </ul>
                </li>
                @endif

                @if(auth()->user()->hasAnyPermission(['item stok-list','stok opname-list','stok awal-list','item transaction-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub {{ menu_active(['persediaan*']) }} ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-basket"></i>
                        <span>Persediaan</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('item stok-list')
                        <li class="submenu-item  {{ menu_active(['persediaan/item-stok*']) }}">
                            <a href="{{route('item-stok.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Stok Item</a>
                        </li>
                        @endcan

                        @can('item transaction-list')
                        <li class="submenu-item {{ menu_active(['persediaan/item-transaction*']) }}">
                            <a href="{{route('item-transaction.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Transaksi Stok</a>
                        </li>
                        @endcan

                        @can('stok opname-list')
                        <li class="submenu-item {{ menu_active(['persediaan/stok-opname*']) }}">
                            <a href="{{route('stok-opname.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Stok Opname</a>
                        </li>
                        @endcan

                        @can('stok awal-list')
                        <li class="submenu-item {{ menu_active(['persediaan/stok-awal*']) }}">
                            <a href="{{route('stok-awal.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Stok Awal</a>
                        </li>
                        @endcan
                        
                        
                    </ul>
                </li>
                @endif
                @if(auth()->user()->hasAnyPermission(['rekap kas-list','transaksi kas-list','closing kas-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub {{ menu_active(['keuangan*']) }} ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-wallet2"></i>
                        <span>Keuangan</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('transaksi kas-list')
                        <li class="submenu-item {{ menu_active(['keuangan/transaksi-kas*']) }}">
                            <a href="{{route('transaksi-kas.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Transaksi Kas</a>
                        </li>
                        @endcan

                        @can('closing kas-list')
                        <li class="submenu-item {{ menu_active(['keuangan/closing-kas*']) }}">
                            <a href="{{route('closing-kas.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Closing Kas</a>
                        </li>
                        @endcan
                        
                        
                        
                    </ul>
                </li>
                @endif
                {{-- <li class="sidebar-item has-sub ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-bag-check"></i>
                        <span>Pembelian</span>
                    </a>
                    <ul class="submenu">
                        
                        <li class="submenu-item">
                            <a href="" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Input Pembelian</a>
                        </li>
                        <li class="submenu-item">
                            <a href="" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Daftar Pembelian</a>
                        </li>
                        
                        
                    </ul>
                </li>
                 --}}
                @if(auth()->user()->hasAnyPermission(['dokter-list', 'karyawan-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub  {{ menu_active(['sdm*']) }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person-video2"></i>
                        <span>SDM</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('dokter-list')
                        <li class="submenu-item {{ menu_active('sdm/dokter*') }}">
                            <a href="{{route('dokter.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Dokter</a>
                        </li>
                        @endcan
                        
                        @can('karyawan-list')
                        <li class="submenu-item {{ menu_active('sdm/karyawan*') }}">
                            <a href="{{route('karyawan.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Karyawan</a>
                        </li>
                        @endcan
                        
                        
                    </ul>
                </li>
                @endif
                @if(auth()->user()->hasAnyPermission(['item-list','kategori item-list','satuan-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub {{ menu_active(['item*']) }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-card-list"></i>
                        <span>Data Item</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('item-list')
                        <li class="submenu-item {{ menu_active('item/item*') }}">
                            <a href="{{route('item.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Daftar Item</a>
                        </li>
                        @endcan
                        @can('kategori item-list')
                        <li class="submenu-item {{ menu_active('item/kategori-item*') }}">
                            <a href="{{route('kategori-item.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Kategori</a>
                        </li>
                        @endcan
                        @can('satuan-list')
                        <li class="submenu-item {{ menu_active('item/satuan*') }}">
                            <a href="{{route('satuan.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Satuan</a>
                        </li>
                        @endcan
                        
                        
                    </ul>
                </li>
                @endif
                @if(auth()->user()->hasAnyPermission(['service-list','kategori service-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item has-sub {{ menu_active(['service*']) }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-shield-check"></i>
                        <span>Data Tindakan</span>
                    </a>
                    <ul class="submenu">
                        
                        @can('service-list')
                        <li class="submenu-item {{ menu_active('service/service*') }}">
                            <a href="{{route('service.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Daftar Tindakan</a>
                        </li>
                        @endcan

                        @can('kategori service-list')
                        <li class="submenu-item {{ menu_active('service/kategori-service*') }}">
                            <a href="{{route('kategori-service.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Kategori</a>
                        </li>
                        @endcan
                        
                        
                    </ul>
                </li>
                @endif
                @if(auth()->user()->hasAnyPermission(['metode pembayaran-list', 'gudang-list','akun kas-list','kategori kas-list']) || auth()->user()->role=='superadmin')
                <li class="sidebar-item  has-sub {{ menu_active(['master*']) }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Master</span>
                    </a>
                    <ul class="submenu {{ menu_active(['master*', 'master*'], 'active submenu-open') }}">
                       
                        {{-- <li class="submenu-item">
                            <a href="" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Supplier</a>
                        </li> --}}

                        @can('kategori kas-list')
                        <li class="submenu-item {{ menu_active('master/kategori-kas*') }}">
                            <a href="{{route('kategori-kas.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Kategori Kas</a>
                        </li>
                        @endcan

                        @can('metode pembayaran-list')
                        <li class="submenu-item {{ menu_active('master/metode-pembayaran*') }}">
                            <a href="{{route('metode-pembayaran.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Metode Pembayaran</a>
                        </li>
                        @endcan

                        @can('akun kas-list')
                        <li class="submenu-item {{ menu_active('master/akun-kas*') }}">
                            <a href="{{route('akun-kas.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Akun Kas</a>
                        </li>
                        @endcan
                        
                        @can('gudang-list')
                        <li class="submenu-item  {{ menu_active('master/gudang*') }}">
                            <a href="{{route('gudang.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Gudang</a>
                        </li>
                        @endcan
                        
                        @can('rooms-list')
                        <li class="submenu-item  {{ menu_active('master/rooms*') }}">
                            <a href="{{route('rooms.index')}}" class="submenu-link"><i class="bi bi-chevron-right me-2"></i>Room</a>
                        </li>
                        @endcan
                        
                    </ul>
                </li>
                @endif
                @can('user-list')
                <li class="sidebar-item {{ request()->is('users','users/*') ? 'active' : '' }}">
                    <a href="{{route('users.index')}}" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                @endcan
                @role('superadmin')
                <li class="sidebar-item {{ request()->is('roles','roles/*') ? 'active' : '' }}">
                    <a href="{{route('roles.index')}}" class='sidebar-link'>
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Role</span>
                    </a>
                </li>   
                <li class="sidebar-item {{ request()->is('permissions','permissions/*') ? 'active' : '' }}">
                    <a href="{{route('permissions.index')}}" class='sidebar-link'>
                        <i class="bi bi-person-check"></i>
                        <span>Permission</span>
                    </a>
                </li>
                @endrole
                
                
                {{-- <li class="sidebar-item {{ request()->is('setting','setting/*') ? 'active' : '' }}">
                    <a href="{{route('setting.index')}}" class='sidebar-link'>
                        <i class="bi bi-gear"></i>
                        <span>Pengaturan</span>
                    </a>
                </li> --}}
                <li class="sidebar-item {{ request()->is('account') ? 'active' : '' }}">
                    <a href="{{route('account')}}" class='sidebar-link'>
                        <i class="bi bi-person-bounding-box"></i>
                        <span>My Account</span>
                    </a>
                </li>
                <li class="sidebar-item ">
                    <a href="{{route('admin.logout')}}" class='sidebar-link text-danger'>
                        <i class="bi bi-box-arrow-left text-danger"></i>
                        <span>Logout</span>
                    </a>
                </li>
                
            </ul>
        </div>
    </div>
</div>
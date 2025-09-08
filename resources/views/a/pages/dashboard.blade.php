
@extends('a.layouts.master')
@section('content')
@can('dashboard-view')
<style>
    .dashboard-card {
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        height: 100px;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .card-pink {
        background: #e91e63;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
    }

    .card-warning {
        background: #ff9800;
        background-image: 
            radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 70% 30%, rgba(255,255,255,0.1) 0%, transparent 50%);
    }

    .card-success {
        background: #4caf50;
        background-image: 
            radial-gradient(circle at 25% 75%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 75% 25%, rgba(255,255,255,0.1) 0%, transparent 50%);
    }

    .card-primary {
        background: #2196f3;
        background-image: 
            radial-gradient(circle at 40% 60%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 60% 40%, rgba(255,255,255,0.1) 0%, transparent 50%);
    }

    .card-icon {
        position: absolute;
        right: 82px;
        top: 0%;
        transform: translateY(-50%);
        font-size: 7rem;
        opacity: 0.2;
        transition: all 0.3s ease;
    }
    .dashboard-card:hover .card-icon {
        opacity: 0.3;
        transform: translateY(-50%) scale(1.1);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        animation: countUp 0.8s ease-out;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.9;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-body {
        position: relative;
        z-index: 2;
    }

    .trend-indicator {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-top: 4px;
    }

    .trend-up {
        color: rgba(255,255,255,0.9);
    }

    .trend-down {
        color: rgba(255,255,255,0.7);
    }
</style>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Dashboard</h3>
            <p class="text-subtitle text-muted">{{identity()['title']}}</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12">

                    {{-- <a href="#">
                      <div class="alert alert-light-danger">
                          <i class="bi bi-info-circle me-2"></i><b>12</b> Item <b>telah kadaluarsa</b>           
                      </div>
                    </a>
                    <a href="#">
                      <div class="alert alert-light-danger">
                          <i class="bi bi-info-circle me-2"></i><b>12</b> Item <b>akan atau sudah habis</b>           
                      </div>
                    </a>
                    <a href="#">
                      <div class="alert alert-light-warning">
                          <i class="bi bi-info-circle me-2"></i><b>12</b> Item <b>akan kadaluarsa</b>           
                      </div>
                    </a> --}}
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                  <div class="card dashboard-card card-pink">
                      <div class="card-body px-4 py-4">
                          <div class="row align-items-center">
                              <div class="col-8">
                                  <h6 class="text-white stat-label">Total Pasien</h6>
                                  <h3 class="text-white stat-number">{{$total_pasien}}</h3>
                           
                              </div>
                          </div>
                      </div>
                      <i class="bi bi-people card-icon text-white"></i>
                  </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card dashboard-card card-warning">

                        <div class="card-body px-4 py-4">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="text-white stat-label">Total Produk</h6>
                                    <h3 class="text-white stat-number">{{$total_produk}}</h3>
                                    {{-- <div class="trend-indicator trend-up">
                                        <i class="fas fa-arrow-up"></i> +8% dari bulan lalu
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-box-seam-fill card-icon text-white"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card dashboard-card card-success">

                        <div class="card-body px-4 py-4">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h6 class="text-white stat-label">Transaksi Hari Ini</h6>
                                    <h3 class="text-white stat-number">Rp.{{toCurrency($transaksi_hari_ini)}}</h3>
                             
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-cart4 card-icon text-white"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card dashboard-card card-primary">

                        <div class="card-body px-4 py-4">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h6 class="text-white stat-label">Transaksi Bulan Ini</h6>
                                    <h3 class="text-white stat-number">Rp.{{toCurrency($transaksi_bulan_ini)}}</h3>
                                  
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-wallet2 card-icon text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Grafik Penjualan Bulanan {{date('Y')}}</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-penjualan" data-url="{{route('dashboard.get_transaksi_per_bulan')}}" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Transaksi Per Produk</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-kategori-produk" data-url="{{route('dashboard.get_transaksi_per_item')}}" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Transaksi Per Tindakan</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-kategori-tindakan" data-url="{{route('dashboard.get_transaksi_per_service')}}" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="{{asset('app/assets/pages/dashboard/dashboard.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>

@else
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-60">
        <div class="col-md-8 text-center">
            <img src="{{ asset('app/assets/static/images/samples/undraw_hello_ccwj.svg') }}" alt="Welcome" class="img-fluid mb-4" style="max-height: 250px;">
            <h1 class="fw-bold text-pink mb-3">Selamat Datang 👋</h1>
            <p class="lead text-muted">
                Anda berhasil masuk ke sistem dashboard. Silakan jelajahi fitur yang tersedia sesuai dengan hak akses Anda.
            </p>
            <hr class="my-4" style="max-width: 200px; margin: auto;">
        </div>
    </div>
</div>
@endcan

@endsection
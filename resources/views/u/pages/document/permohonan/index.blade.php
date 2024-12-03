@extends('u.layouts.master')
@section('content')
<style>
    .document-icon {
      font-size: 3rem;
      color: #007bff;
    }
    .card-document {
      border: none;
      transition: transform 0.3s;
    }
    .card-document:hover {
      transform: scale(1.05);
    }
</style>
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted">Kelompok praktek industri telah dibentuk, silahkan cetak file permohonan dibawah</p>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
                           
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <div class="col-6 col-lg-3">
                <a href="{{route('page.sertifikat_pembekalan',encrypt0(Session::get('id')))}}" target="_blank" class="card text-center shadow-sm card-document">
                    <div class="card-body">
                        <div class="document-icon">
                        <i class="bi bi-printer"></i>
                        </div>
                        <h6 class="card-title mt-3 display-7">Sertifikat Pembekalan</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="{{route('page.permohonan_izin_observasi',encrypt0(Session::get('id')))}}" target="_blank" class="card text-center shadow-sm card-document">
                    <div class="card-body">
                        <div class="document-icon">
                        <i class="bi bi-printer"></i>
                        </div>
                        <h6 class="card-title mt-3">Permohonan Izin Observasi</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="{{route('page.permohonan_izin_pi',encrypt0(Session::get('id')))}}" target="_blank" class="card text-center shadow-sm card-document">
                    <div class="card-body">
                        <div class="document-icon">
                        <i class="bi bi-printer"></i>
                        </div>
                        <h6 class="card-title mt-3">Permohonan Izin PI</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="{{route('page.permohonan_pembimbing',encrypt0(Session::get('id')))}}" target="_blank" class="card text-center shadow-sm card-document">
                    <div class="card-body">
                        <div class="document-icon">
                        <i class="bi bi-printer"></i>
                        </div>
                        <h6 class="card-title mt-3">Permohonan Pembimbing</h6>
                    </div>
                </a>
            </div>
        </div>
                  
    </section>
</div>

<script src="{{asset('app/assets/pages/group_lokasi/group_lokasi.js')}}"></script>
@endsection
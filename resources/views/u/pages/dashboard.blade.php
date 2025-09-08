
@extends('u.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Home</h3>
            <p class="text-subtitle text-muted">Halaman home</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="alert alert-info"><i class="bi bi-info-circle"></i> Selamat Datang Di <b>Sistem Informasi Praktek Industri</b></div>
        </div>
    </section>
</div>

@endsection
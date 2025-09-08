
@extends('u.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><span class="text-danger">Detail</span> {{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="alert alert-info"><i class="bi bi-info-circle"></i> Selamat Datang Di <b>CBT System</b></div>
            <div class="alert alert-light-warning text-danger">
                Silahkan perhatikan kesesuaian ujian yang akan diikuti. <br>
                <b>Pastikan anda sudah siap mengikuti ujian, karena setelah memulai ujian tidak bisa diulang kembali</b><br>
                Jika ada kesalahan dalam ujian silahkan hubungi admin
            </div>
        </div>
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5>Info Ujian</h5>
                    <table>
                        <tr>
                            <td>Nama Ujian</td>
                            <td width="5%">:</td>
                            <td><b>{{$ujian->judul}}</b></td>
                        </tr>
                        <tr>
                            <td>Satuan Pendidikan</td>
                            <td>:</td>
                            <td><b>{{$ujian->satuan_pendidikan}}</b></td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td>:</td>
                            <td><b>{{$ujian->mapel}}</b></td>
                        </tr>
                        <tr>
                            <td>Durasi Pengerjaan</td>
                            <td>:</td>
                            <td><b>{{($ujian->durasi)}} Menit</b></td>
                        </tr>
                        <tr>
                            <td>Waktu Mulai Ujian</td>
                            <td>:</td>
                            <td><b>{{toDatetime($ujian->start_time)}}</b></td>
                        </tr>
                        <tr>
                            <td>Waktu Selesai Ujian</td>
                            <td>:</td>
                            <td><b>{{toDatetime($ujian->end_time)}}</b></td>
                        </tr>
                    </table>
                    
                    @if ($ujian->start_time > $now || $ujian->end_time < $now)
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-exclamation-triangle"></i> Ujian belum dimulai atau sudah berakhir
                        </div>
                    @else
                    <form action="{{route('ujian.start')}}" method="POST">
                        @csrf
                        <input type="hidden" name="ujian_id" value="{{encrypt0($ujian->id)}}">
                        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-clock"></i> Mulai Ujian</button>
                    </form>
                    @endif
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
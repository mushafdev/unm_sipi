
@extends('u.layouts.master')
@section('content')
<script src="{{asset('app/assets/extensions/apexcharts/apexcharts.js')}}"></script>
<style>
    .skor-space {
        border-radius: 100%;
        height: 160px;
        width: 160px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 11px solid #0c6a96;
        box-shadow: 0px 0px 17px 0px;
        padding: 10px;
    }
    .skor-space h1{
        font-size: 35pt;
        margin-bottom: 0px;
    }
    .skor-space p{
        margin-bottom: 0px;
    }

    .progress-container {
      margin-bottom: 0.5rem;
    }
    .level-label {
      display: flex;
      align-items: center;
      margin-bottom: 0.1rem;
      font-weight: 500; 
      font-size: 10pt;
    }
    .level-label i {
      margin-right: 0.5rem;
    }
    .progress {
      height: 17px;
      border-radius: 20px;
      overflow: hidden;
    }

</style>
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><span class="text-danger">Hasil</span> Ujian</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="alert alert-light-warning"><i class="bi bi-info-circle"></i> Selamat <b>telah menyelesaikan ujian</b></div>
        </div>
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column mb-3">
                        <div class="avatar avatar-2xl">
                            @if (!empty($peserta->photo))
                            <img src="{{asset('images/users_photo/'.$peserta->photo)}}" id="photo-show" alt="Avatar">
                            @else
                            <img src="{{asset('app/assets/compiled/jpg/2.jpg')}}" id="photo-show" alt="Avatar">
                            @endif
                        </div>
                    </div>
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td width="5%">:</td>
                            <td><b>{{$peserta->nama}}</b></td>
                        </tr>
                        <tr>
                            <td>ID Peserta</td>
                            <td width="5%">:</td>
                            <td><b>{{$peserta->id_peserta}}</b></td>
                        </tr>
                        <tr>
                            <td>Waktu Test</td>
                            <td width="5%">:</td>
                            <td><b>{{toDatetime($ujian->start_time)}}</b></td>
                        </tr>
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
                    </table>
                    
                </div>
            </div>
        </div>
        <div class="col-md-8 ">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row" id="hasil" data-url="{{route('ujian.hasil.get_hasil')}}" data-id="{{encrypt0($ujian->id)}}">
                        
                        <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                            <div class="skor-space mb-4">
                                <p>Skor</p>
                                <h1 class="skor">-</h1>
                                <p><b class="text-success jawaban_benar">-</b>/<span class="total_soal">-</span> Soal</p>
                            </div>
                            
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="w-100 mb-4">
                                        <h5>Skor Per Level Kompetensi</h5>
                                        <div class="progress-wrapper"></div>
                                      
                                    </div>
                                </div>
                                <div class="col-md-7" style="height: 350px;">
                                    <div id="radar-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Rekomendasi Peningkatan Level Kompetensi</h5>
                    <div class="rekomendasi_kompetensi"></div>
                </div>
            </div>
        </div>

    </section>
</div>

<script src="{{asset('app/assets/pages/ujian/hasil.js')}}"></script>
@endsection
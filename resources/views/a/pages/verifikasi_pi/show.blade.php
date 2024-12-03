@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('verifikasi-pi.index')}}">Daftar Verifikasi Selesai PI</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <form name="form" id="form" data-index="{{route('verifikasi-pi.index')}}" data-verifikasi="{{route('verifikasi-pi.verifikasi')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Info Lokasi PI</h5>
                        <table class="table w-100">
                            <tr>
                                <th class="align-top">Lokasi PI </th>
                                <td class="align-top">{{$data->lokasi_pi}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Alamat</th>
                                <td class="align-top">{{$data->alamat.', '.$data->kota}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Waktu</th>
                                <td class="align-top">{{getMonthFromNumber($data->start_month).' s/d '.getMonthFromNumber($data->end_month).' '.$data->year}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Kebutuhan Pekerjaan</th>
                                @php
                                    $explodedArray = explode(',', $data->kebutuhan_pekerjaan);

                                    $kebutuhan_pekerjaan = '';
                                    foreach ($explodedArray as $index => $value) {
                                        $kebutuhan_pekerjaan.= '<span class="badge bg-info me-1">'.trim($value).'</span>'; 
                                    }
                                @endphp     
                                <td class="align-top">{!!$kebutuhan_pekerjaan!!}</td>
                            </tr>
                            {{-- <tr>
                                <th class="align-top">Info Lokasi Pi</th>
                                <td class="align-top">
                                    <a href="#" class="btn btn-light btn-sm rounded-pill icon icon-left btn-info"><i class="bi bi-eye"></i> Lihat</a>
                                </td>
                            </tr> --}}
                        </table>

                        <h5>Pembimbing</h5>
                        <table class="table w-100">
                            <tr>
                                <th class="align-top">Nama</th>
                                <td class="align-top">{{$data->pembimbing?$data->pembimbing:'-'}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">NIP</th>
                                <td class="align-top">{{$data->pembimbing_nip?$data->pembimbing_nip:'-'}}</td>
                            </tr>
                        </table>

                        <h5>Status Verifikasi</h5>
                        <label for="" class="form-label">
                            @if ($data->done=='N')
                                <span class="badge bg-danger">Belum diverifikasi admin</span>
                            @else
                                <span class="badge bg-success">Telah diverifikasi admin</span>
                            @endif
                        </label>
                        @if (!empty($data->catatan_done))
                        <div class="alert alert-light-warning color-warning"><i class="bi bi-exclamation-triangle"></i>
                            <strong>Catatan : </strong>{{$data->catatan_done}}</div>

                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Data Kelompok</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0" id="t_group">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Kelas</th>
                                                <th>LogBook</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detail as $dt)
                                            <tr>
                                                <td class="text-bold-500">
                                                    {{$dt->nim}}
                                                </td>
                                                <td> {{$dt->nama}}</td>
                                                <td> {{$dt->prodi}}</td>
                                                <td> {{$dt->kelas}}</td>
                                                
                                                <td>
                                                    <a href="#" class="btn btn-light btn-sm rounded-pill icon icon-left btn-info detail-logbook" data-id="{{encrypt0($dt->id)}}"><i class="bi bi-eye"></i> Lihat</a>
                                                </td>
                                            </tr>   
                                            @endforeach
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                           
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Info Verifikasi</h5>
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label for="done" class="form-label">Status Verifikasi<span class="text-danger ms-0">*</span></label>
                                            
                                            <div class="input-group mb-3">
                                                <select class="form-select" name="done" id="done"  data-parsley-errors-container="#done-errors" required>
                                                    <option value="">Pilih</option>
                                                    <option value="N">Belum Diverifikasi</option>
                                                    <option value="Y">Telah Diverifikasi</option>
                                                </select>
                                            </div>
                                            
                                            <span id="done-errors"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="catatan_done" class="form-label">Catatan</label>
                                            <textarea type="text" name="catatan_done" id="catatan_done" class="form-control" placeholder="Catatan" value=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-end">
                    <a href="{{route('verifikasi-pi.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                    
                    <button type="button" id="verifikasi" class="btn icon icon-left btn-primary" data-id="{{encrypt0($data->id)}}"><i class="bi bi-check"></i> Verifikasi</button>
                    
            </div> 
        </div>
        </form>
    </section>
</div>

@include('u.component.modal_logbook')
<script src="{{asset('app/assets/pages/verifikasi_pi/verifikasi_pi.js')}}"></script>
@endsection
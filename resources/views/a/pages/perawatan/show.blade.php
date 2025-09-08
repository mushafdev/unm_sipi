@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 text-end mt-0">
            <a href="{{route('pasien.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
            @can('pasien-create')
            <a href="{{route('pasien.create')}}" class="btn icon icon-left btn-success"><i class="bi bi-plus-circle"></i> Tambah</a>    
            @endcan
            @can('pasien-edit')
            <a href="{{route('pasien.edit',encrypt0($data->id))}}" class="btn icon icon-left btn-info text-white"><i class="bi bi-pencil"></i> Edit</a>    
            @endcan
            @can('perawatan-create')
            <a href="{{route('perawatan.create')}}?pasien={{encrypt0($data->id)}}" class="btn icon icon-left btn-primary"> Lanjutkan Ke Pelayanan</a>          
            @endcan
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                @if (!empty($data->photo))
                                    <img src="{{asset('images/pasien_photo/'.$data->photo)}}" id="photo-show" alt="Avatar">
                                @else
                                    <img src="{{asset('app/assets/compiled/jpg/3.jpg')}}" id="photo-show" alt="Avatar">
                                @endif
                            </div>
                            <h5 class="mt-3">{{$data->no_rm}}</h5>
                            <p class="text-small">{{$data->nama}}</p>
                            <div class="d-flex justify-content-center align-items-center">
                                @foreach ($tags as $tag)
                                    <span class="badge bg-light-primary text-primary m-1">{{$tag->tag}}</span>  
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info Pasien</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="rekam-medis-tab" data-bs-toggle="tab" href="#rekam-medis" role="tab" aria-controls="rekam-medis" aria-selected="false" tabindex="-1">Rekam Medis</a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3" id="myTabContent">
                                    <div class="tab-pane fade active show" id="info" role="tabpanel" aria-labelledby="info-tab">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-semibold" width="20%">No. RM</td>
                                                    <td class="">{{$data->no_rm}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Tgl. Daftar</td>
                                                    <td class="">{{convertYmdToMdy2($data->tgl_daftar)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Nama Lengkap</td>
                                                    <td class="">{{$data->gelar_depan.' '.$data->nama.' '.$data->gelar_belakang}}</td> 
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Nama Panggilan</td>
                                                    <td class="">{{$data->panggilan}}</td> 
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Tgl. Lahir</td>
                                                    <td class="">{{convertYmdToMdy2($data->tgl_lahir)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Jenis Kelamin</td>
                                                    <td class="">
                                                        @if(!empty($data->jenis_kelamin))
                                                        {{\App\Enums\JenisKelaminEnum::tryFrom($data->jenis_kelamin)->label()}}
                                                        @endif
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">No. HP</td>
                                                    <td class="">{{$data->no_hp}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Email</td>
                                                    <td class="">{{$data->email}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">NIK</td>
                                                    <td class="">{{$data->nik}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Agama</td>
                                                    <td class="">{{$data->agama}}</td>
                                                </tr>

                                                <tr>
                                                    <td class="fw-semibold">Golongan Darah</td>
                                                    <td class="">
                                                        @if(!empty($data->golongan_darah))
                                                        {{\App\Enums\GolonganDarahEnum::tryFrom($data->golongan_darah)->label()}}
                                                        @endif
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Status Kawin</td>
                                                    <td class="">
                                                        @if(!empty($data->status_kawin))
                                                        {{\App\Enums\StatusKawinEnum::tryFrom($data->status_kawin)->label()}}
                                                        @endif
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Pekerjaan</td>
                                                    <td class="">{{$data->pekerjaan}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Pendidikan</td>
                                                    <td class="">{{$data->pendidikan}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Alamat</td>
                                                    <td class="">{{$data->alamat}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Provinsi</td>
                                                    <td class="">{{$data->provinsi}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Kabupaten</td>
                                                    <td class="">{{$data->kabupaten}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Kecamatan</td>
                                                    <td class="">{{$data->kecamatan}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Kelurahan</td>
                                                    <td class="">{{$data->kelurahan}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Kode Pos</td>
                                                    <td class="">{{$data->kode_pos}}</td>
                                                </tr>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="rekam-medis" role="tabpanel" aria-labelledby="rekam-medis-tab">
                                        Integer interdum diam eleifend metus lacinia, quis gravida eros mollis. Fusce non sapien
                                        sit amet magna dapibus
                                        ultrices. Morbi tincidunt magna ex, eget faucibus sapien bibendum non. Duis a mauris ex.
                                        Ut finibus risus sed massa
                                        mattis porta. Aliquam sagittis massa et purus efficitur ultricies. Integer pretium dolor
                                        at sapien laoreet ultricies.
                                        Fusce congue et lorem id convallis. Nulla volutpat tellus nec molestie finibus. In nec
                                        odio tincidunt eros finibus
                                        ullamcorper. Ut sodales, dui nec posuere finibus, nisl sem aliquam metus, eu accumsan
                                        lacus felis at odio. Sed lacus
                                        quam, convallis quis condimentum ut, accumsan congue massa. Pellentesque et quam vel
                                        massa pretium ullamcorper vitae eu
                                        tortor.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="{{asset('app/assets/pages/pasien/pasien.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
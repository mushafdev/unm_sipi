@extends('a.layouts.master')
@section('content')
<link rel="stylesheet" href="{{ asset('app/assets/compiled/css/riwayat-pasien.css') }}">

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
            @can('pendaftaran-create')
            <a href="{{route('pendaftaran.create')}}?pasien={{encrypt0($data->id)}}" class="btn icon icon-left btn-primary"> Lanjutkan Ke Pelayanan</a>          
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
                            @if (identity()['migration']=='Y')
                                <div class="col-md-12 text-end">
                                    <span class="btn btn-link text-secondary" id="add-riwayat"  ><i class="bi bi-plus-lg"></i> Tambah Riwayat</span>
                                </div>    
                            @endif
                            
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" id="pasienTab" role="tablist" data-riwayat="{{route('pasien.riwayat', encrypt0($data->id))}}" data-resep="{{route('pasien.resep', encrypt0($data->id))}}">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info Pasien</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="riwayat-perawatan-tab" data-bs-toggle="tab" href="#riwayat-perawatan" role="tab">Riwayat Perawatan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="riwayat-resep-tab" data-bs-toggle="tab" href="#riwayat-resep" role="tab">Resep</a>
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
                                    <div class="tab-pane fade" id="riwayat-perawatan" role="tabpanel">
                                        <div id="riwayatPerawatanContent" data-get-detail="{{route('pasien.riwayat.get_detail')}}" data-delete="{{route('pasien.riwayat.delete')}}">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="riwayat-resep" role="tabpanel">
                                        <div id="riwayatResepContent">
                                        </div>
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

<div class="modal fade text-left" id="modalAddRiwayat" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form  id="form-add-riwayat" name="form-add-riwayat" data-store="{{route('pasien.riwayat.store')}}" data-id="{{encrypt0($data->id)}}">
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" readonly />
                <input type="hidden" id="param" name="param" readonly />
                <input type="hidden" id="pasien_id" name="pasien_id" value="{{encrypt0($data->id)}}" readonly />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">  
                            <div class="mb-3">
                                <h5>Info Pendaftaran</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tgl_pendaftaran" name="tgl_pendaftaran" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Jenis Layanan <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis_layanan_id" id="jenis_layanan_id" required>
                                            <option value="">Pilih</option>
                                            @foreach ($jenis_layanans as $dt )
                                                <option value="{{$dt->id}}">{{$dt->jenis_layanan}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>

                                </div>
                            </div>
                            <div class="mb-3">
                                <h5>Resep</h5>
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <label>Dokter</label>
                                        <select class="form-select select2-dokter" name="resep_dokter_id" id="resep_dokter_id">
                                            <option value="">Pilih</option>
                                        </select>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <label>Resep</label>
                                        <textarea class="form-control" id="resep" name="resep"></textarea>
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h5>Tindakan</h5>
                                <div id="tindakan-wrapper">
                                    <!-- Row pertama -->
                                    <div class="tindakan-row border p-3 mb-2">
                                    <div class="row">
                                        <div class="col-3">
                                            <label>Tindakan <span class="text-danger">*</span></label>
                                            <select class="form-select select2-tindakan" name="tindakan[]" required></select>
                                            <span id="error-tindakan"></span>
                                        </div>
                                        <div class="col-4">
                                            <label>Dokter</label>
                                            <select class="form-select select2-dokter" name="dokters[][]" multiple></select>
                                        </div>
                                        <div class="col-4">
                                            <label>Catatan</label>
                                            <textarea class="form-control" name="catatan[]" rows="2"></textarea>
                                        </div>
                                        <div class="col-1 text-end align-middle">
                                            <span type="button" class="remove-row text-danger"><i class="bi bi-x-lg"></i></span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="text-center mb-3">
                                    <button type="button" id="add-tindakan-row" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Tambah Tindakan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                </div?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                        <span class="">Close</span>
                    </button>
                    <button type="button" id="save-riwayat" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{asset('app/assets/pages/pasien/pasien_info.js')}}?v={{identity()['assets_version']}}"></script>
<script src="{{asset('app/assets/pages/pasien/pasien_detail.js')}}?v={{identity()['assets_version']}}"></script>

@endsection
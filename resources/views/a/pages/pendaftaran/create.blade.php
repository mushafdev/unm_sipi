@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
    </div>
</div>
<div class="page-content"> 
    
    <section class="section">
        <form name="form" id="form" data-store="{{route('pendaftaran.store')}}" data-index="{{route('pendaftaran.index')}}" data-detail-pasien="{{route('pasien.detail_data')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3 flex-nowrap">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <select type="text" class="form-control select2-icon select2-pasien" placeholder="Ketik No. RM/Nama/No.HP/Alamat" id="filter-key" aria-label="Ketik No. RM/Nama/No.HP/Alamat">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <button class="btn btn-light text-secondary" type="button" id="btn-filter" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">Advance Search</button>
                                @can('pasien-create')
                                <a class="btn btn-success" href="{{route('pasien.create')}}" id="btn-add"><i class="bi bi-plus-circle"></i> Pasien Baru</a>
                                @endcan
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="modal-load">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <h5 class="text-center">Info Pasien</h5>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        <img src="{{asset('app/assets/compiled/jpg/3.jpg')}}" id="photo-show" alt="Avatar">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>No. RM</th>
                                            <td class="no_rm"></td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td class="nama"></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Lahir</th>
                                            <td class="tgl_lahir"></td>
                                        </tr>
                                        <tr>
                                            <th>No. HP</th>
                                            <td class="no_hp"></td>
                                        </tr>
                                        <tr>
                                            <th>NIK</th>
                                            <td class="nik"></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td class="jenis_kelamin"></td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td class="alamat"></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="pasien_id" id="pasien_id" class="form-control" placeholder="" value="{{$pasien_id}}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_daftar" class="form-label">Tanggal Daftar <span class="text-danger ms-0">*</span> </label>
                                    <input type="date" name="tgl_daftar" id="tgl_daftar" class="form-control" placeholder="No. RM" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="jenis_layanan_id" class="form-label">Jenis Layanan</label>
                                    <select type="text" name="jenis_layanan_id" id="jenis_layanan_id" class="form-control" required>
                                        <option value="">Pilih Layanan</option>
                                        @foreach ($jenis_layanans as $dt )
                                            <option value="{{encrypt1($dt->id)}}">{{$dt->jenis_layanan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dokter_id" class="form-label">Request Dokter</label>
                                    <select type="text" name="dokter_id" id="dokter_id" class="form-control select2" data-parsley-errors-container="#dokter-errors">
                                        <option value="">Pilih Dokter</option>
                                        @foreach ($dokters as $dt )
                                            <option value="{{encrypt1($dt->id)}}">{{$dt->nama}}</option>
                                        @endforeach
                                    </select>
                                    <span id="dokter-errors"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="perawat_id" class="form-label">Request Perawat</label>
                                    <select type="text" name="perawat_id" id="perawat_id" class="form-control select2" data-parsley-errors-container="#perawat-errors">
                                        <option value="">Pilih Perawat</option>
                                        @foreach ($perawats as $dt )
                                            <option value="{{encrypt1($dt->id)}}">{{$dt->nama}}</option>
                                        @endforeach
                                    </select>
                                    <span id="perawat-errors"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Prioritas</label>
                                    <div class="d-flex mt-2">
                                        @foreach ($prioritas as $dt )
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="prioritas" id="prioritas_{{$dt->value}}" value="{{$dt->value}}" {{$dt->value=='N'?'checked':''}}>
                                                <label class="form-check-label fw-normal" for="prioritas_{{$dt->value}}">
                                                    {{$dt->label()}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Catatan" value="">
                                </div>
                            </div>
                
                            <div class="col-md-12 mt-3 text-end">

                                <a href="{{ session('back_url', url('/')) }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                <button type="button" id="save" class="btn btn-primary" ><i class="bi bi-save"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>
@include('a.component.search_pasien')
<script src="{{asset('app/assets/pages/pendaftaran/pendaftaran.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
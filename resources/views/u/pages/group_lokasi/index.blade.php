@extends('u.layouts.master')
@section('content')

<link rel="stylesheet" href="{{asset('app/assets/extensions/summernote/summernote-lite.css')}}">
<script src="{{asset('app/assets/extensions/summernote/summernote-lite.js')}}"></script>
<link rel="stylesheet" href="{{asset('app/assets/extensions/input-tags/inputTags.min.css')}}">
<script src="{{asset('app/assets/extensions/input-tags/inputTags.jquery.min.js')}}"></script>

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
        <form name="form" id="form" data-store="{{route('group-lokasi.store')}}" data-index="{{route('group-lokasi.index')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama_lokasi" class="form-label">Nama Perusahaan/Instansi</label>
                                                <h5>{{isset($lokasi->lokasi_pi)?$lokasi->lokasi_pi:''}}</h5>
                                            </div>
                                            <div class="form-group">
                                                <label for="kota" class="form-label">Kota</label>
                                                <h5>{{isset($lokasi->kota)?$lokasi->kota:''}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <h5>{{isset($lokasi->alamat)?$lokasi->alamat:''}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <div class="form-group">
                                                <label for="deskripsi" class="form-label">Deskripsi Perusahaan</label>
                                                <textarea  name="deskripsi" id="deskripsi" class="summernote" rows="10" cols="50" placeholder="Nama" value="">{{isset($data->deskripsi)?$data->deskripsi:''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="kebutuhan_pekerjaan" class="form-label">Kebutuhan Pekerjaan</label>
                                                <textarea  name="kebutuhan_pekerjaan" id="kebutuhan_pekerjaan" class="form-control" rows="10" cols="50" placeholder="Kegiatan" value="" required data-parsley-errors-container="#kebutuhan-pekerjaan-errors">{{isset($data->kebutuhan_pekerjaan)?$data->kebutuhan_pekerjaan:''}}</textarea>
                                                <span id="kebutuhan-pekerjaan-errors"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/group_lokasi/group_lokasi.js')}}"></script>
@endsection
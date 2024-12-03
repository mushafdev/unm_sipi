@extends('a.layouts.master')
@section('content')
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
    <form name="form" id="form" data-update="{{route('lokasi-pi.update',encrypt0($data->id))}}" data-index="{{route('lokasi-pi.index')}}" enctype="multipart/form-data" method="POST">
    @method('PUT')
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
                                        <label for="lokasi_pi">Nama<span class="text-danger ms-0">*</span> </label>
                                        <input id="lokasi_pi" name="lokasi_pi" type="text" placeholder="Nama" class="form-control" value="{{$data->lokasi_pi}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kota">Kota<span class="text-danger ms-0">*</span> </label>
                                        <input id="kota" name="kota" type="text" placeholder="Kota" class="form-control" value="{{$data->kota}}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="telp">Telp.<span class="text-danger ms-0">*</span> </label>
                                        <input id="telp" name="telp" type="text" placeholder="Telp." class="form-control" value="{{$data->telp}}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="alamat">Alamat<span class="text-danger ms-0">*</span> </label>
                                        <textarea id="alamat" name="alamat" placeholder="Alamat" class="form-control" required>{{$data->alamat}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="kebutuhan_pekerjaan" class="form-label">Kebutuhan Pekerjaan</label>
                                        <textarea  name="kebutuhan_pekerjaan" id="kebutuhan_pekerjaan" class="form-control" rows="10" cols="50" placeholder="Kegiatan" value="" required data-parsley-errors-container="#kebutuhan-pekerjaan-errors">{{$data->kebutuhan_pekerjaan}}</textarea>
                                        <span id="kebutuhan-pekerjaan-errors"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan<span class="text-danger ms-0">*</span> </label>
                                        <textarea id="keterangan" name="keterangan" placeholder="Keterangan" class="form-control" required>{{$data->keterangan}}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="col-md-12 text-end">
                            <a href="{{route('lokasi-pi.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                            <button type="button" id="update" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/lokasi_pi/lokasi_pi.js')}}"></script>
@endsection
@extends('u.layouts.master')
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
        <form name="form" id="form" data-store="{{route('logbook.store')}}" data-index="{{route('logbook.index')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Waktu Kegiatan</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal" class="form-label">Tanggal<span class="text-danger ms-0">*</span></label>
                                                <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Nama" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jam" class="form-label">Jam<span class="text-danger ms-0">*</span></label>
                                                <input type="time" name="jam" id="jam" class="form-control" placeholder="Nama" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-8">
                                    <h5>Info Kegiatan</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="kegiatan" class="form-label">Deskripsi Kegiatan<span class="text-danger ms-0">*</span></label>
                                                <textarea  name="kegiatan" id="kegiatan" class="form-control" rows="10" cols="50" placeholder="Kegiatan" value="" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                                <div class="col-md-12 text-end">
                                    <a href="{{route('logbook.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
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

<script src="{{asset('app/assets/pages/logbook/logbook.js')}}"></script>
@endsection
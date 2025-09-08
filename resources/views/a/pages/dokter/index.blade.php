@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3 class="title">{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">
                        Daftar {{$title}}
                    </h5>
                </div>
                <div>
                    @can('dokter-create')
                    <button class="btn icon icon-left btn-success" id="add" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="bi bi-plus-circle"></i> Tambah</button>
                    @endcan

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('dokter.get_dokter')}}" data-url="{{route('dokter.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. STR</th>
                                <th>Nama</th>
                                <th>Spesialis</th>
                                <th>No. HP</th>
                                <th>email</th>
                                <th>nik</th>
                                <th>Alamat</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<div class="modal fade text-left" id="modal" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-md"
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
            <form  id="form" name="form" data-index="{{route('dokter.index')}}" data-get-data="{{route('dokter.get_data')}}">
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" readonly />
                <input type="hidden" id="param" name="param" readonly />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="no_str">No. STR<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="no_str" name="no_str" type="text" placeholder="No. STR" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="nama">Nama Dokter<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="nama" name="nama" type="text" placeholder="Nama Dokter" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="spesialis">Spesialis<span class="text-danger ms-0"></span> </label>
                            <div class="form-group">
                                <input id="spesialis" name="spesialis" type="text" placeholder="Spesialis" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="no_hp">No. HP<span class="text-danger ms-0"></span> </label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" type="text" aria-label="" placeholder="No. HP" >
                        </div>
                        <div class="col-md-12">
                            <label for="nik">NIK<span class="text-danger ms-0"></span> </label>
                            <input type="text" class="form-control" id="nik" name="nik" type="text" aria-label="" placeholder="NIK" >
                        </div>
                        <div class="col-md-12">
                            <label for="email">Email<span class="text-danger ms-0"></span> </label>
                            <input type="text" class="form-control" id="email" name="email" type="email" aria-label="" placeholder="Email" >
                        </div>
                        <div class="col-md-12">
                            <label for="alamat">Alamat<span class="text-danger ms-0"></span> </label>
                            <textarea class="form-control" id="alamat" name="alamat" aria-label="" placeholder="Alamat" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                        <span class="">Close</span>
                    </button>
                    <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/dokter/dokter_list.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
@extends('a.layouts.master')
@section('content')

<link rel="stylesheet" href="{{asset('app/assets/extensions/cropper/cropper.min.css')}}">
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

    <form name="form" id="form" data-update="{{route('setting.update')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Logo</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        @if (!empty($data->logo))
                                        <img src="{{asset('images/'.$data->logo)}}" id="logo-show" alt="Avatar">
                                        @else
                                        <img src="{{asset('app/assets/compiled/jpg/2.jpg')}}" id="logo-show" alt="Avatar">
                                        @endif
                                        <label for="logo" role="button">
                                            <i class="bi bi-pencil"></i>
                                        </label>
                                        <input type="file" class="d-none" id="logo" name="logo" accept="image/*">
                                    </div>
                                    {{-- <h3 class="mt-3">John Doe</h3>
                                    <p class="text-small">Junior Software Engineer</p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- <h5>Info Web</h5> --}}
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="nama_web" id="nama_web" class="form-control" placeholder="Nama Web" value="{{$data->nama_web}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Deskripsi</label>
                                        <textarea type="text" name="deskripsi" id="deskripsi" class="form-control" rows="5" placeholder="Deskripsi">{{$data->deskripsi}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat">{{$data->alamat}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- <h5>Info Web</h5> --}}

                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="user@email.com" value="{{$data->email}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="telp" class="form-label">Telp.</label>
                                        <input type="text" name="telp" id="telp" class="form-control" placeholder="085xxxxxxxxx" value="{{$data->telp}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Whatsapp</label>
                                        <input type="text" name="wa" id="wa" class="form-control" placeholder="Whatsapp" value="{{$data->wa}}" >
                                    </div>
                                    <div class="form-group">
                                        <label for="telp" class="form-label">Tax.</label>
                                        <input type="text" name="tax" id="tax" class="form-control" placeholder="0411***" value="{{$data->tax}}" >
                                    </div>
                                    <div class="form-group">
                                        <label for="maps" class="form-label">Map</label>
                                        <textarea type="text" name="maps" id="maps" class="form-control" placeholder="Link Map" >{{$data->maps}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
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

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image" src="" style="height:400px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Apply</button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/extensions/cropper/cropper.min.js')}}"></script>
<script src="{{asset('app/assets/pages/setting.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
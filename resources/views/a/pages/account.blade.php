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
    <form name="form" id="form" data-update="{{route('account.update')}}" enctype="multipart/form-data" method="POST">
    @method('POST')
    {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                @if (!empty($data->photo))
                                <img src="{{asset('images/users_photo/'.$data->photo)}}" id="photo-show" alt="Avatar">
                                @else
                                <img src="{{asset('app/assets/compiled/jpg/2.jpg')}}" id="photo-show" alt="Avatar">
                                @endif
                                <label for="photo" role="button">
                                    <i class="bi bi-pencil"></i>
                                </label>
                                <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                            </div>
                            <h5 class="mt-3">{{$data->nama}}</h5>
                            <p class="text-small">{{$data->role}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Info Pengguna</h5>
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="{{$data->nama}}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email<span class="text-danger ms-0">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="user@email.com" value="{{$data->email}}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="telp" class="form-label">Kontak<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="telp" id="telp" class="form-control" placeholder="085xxxxxxxxx" value="{{$data->telp}}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat<span class="text-danger ms-0">*</span></label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" disabled>{{$data->alamat}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Security</h5>
                                    <div class="form-group">
                                        <label for="username" class="form-label">Username Baru</label>
                                        <small class="text-primary w-100">*Abaikan jika tidak ingin diubah</small>
                                        <input type="text" name="username" id="username" minlength="5" class="form-control" placeholder="Username" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <small class="text-primary w-100">*Abaikan jika tidak ingin diubah</small>
                                        <input type="text" name="password" id="password" minlength="5" class="form-control" placeholder="Password" value="">
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                   
                                    <button type="button" id="update" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Update</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/account/account.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
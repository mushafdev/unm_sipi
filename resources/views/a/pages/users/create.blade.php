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
        <form name="form" id="form" data-store="{{route('users.store')}}" data-index="{{route('users.index')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img src="{{asset('app/assets/compiled/jpg/2.jpg')}}" id="photo-show" alt="Avatar">
                                <label for="photo" role="button">
                                    <i class="bi bi-pencil"></i>
                                </label>
                                <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                            </div>
                            {{-- <h3 class="mt-3">John Doe</h3>
                            <p class="text-small">Junior Software Engineer</p> --}}
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
                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email<span class="text-danger ms-0">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="user@email.com" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telp" class="form-label">Kontak<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="telp" id="telp" class="form-control" placeholder="085xxxxxxxxx" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat<span class="text-danger ms-0">*</span></label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Security</h5>
                                    <div class="form-group">
                                        <label for="role" class="form-label" >Role<span class="text-danger ms-0">*</span></label>
                                        <select name="role" id="role" class="form-control" required>
                                            @foreach ($roles as $dt)
                                                <option value="{{$dt->name}}">{{Str::ucfirst($dt->name)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="aktif" class="form-label" >Status<span class="text-danger ms-0">*</span></label>
                                        <select name="aktif" id="aktif" class="form-control" required>
                                            <option value="Y">Aktif</option>
                                            <option value="N">Tidak Aktif</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="username" class="form-label">Username<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="username" id="username" minlength="5" class="form-control" placeholder="Username" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="password" id="password" minlength="5" class="form-control" placeholder="Password" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <a href="{{route('users.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
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

<script src="{{asset('app/assets/pages/users/users.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
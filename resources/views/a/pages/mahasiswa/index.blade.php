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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">
                        Data {{$title}}
                    </h5>
                </div>
                <div>
                    <button class="btn icon icon-left btn-success" id="add" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="bi bi-plus-circle"></i> Create</button>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('mahasiswa.get_mahasiswa')}}" data-url="{{route('mahasiswa.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Kelas</th>
                                <th>Prodi</th>
                                <th>Jurusan</th>
                                <th>Status</th>
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
    <div class="modal-dialog modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Form Mahasiswa </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form  id="form" name="form" data-index="{{route('mahasiswa.index')}}" data-get-data="{{route('mahasiswa.get_data')}}">
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" readonly />
                <input type="hidden" id="param" name="param" readonly />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nama">Nama<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="nama" name="nama" type="text" placeholder="Nama" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nim">NIM<span class="text-danger ms-0">*</span> </label>
                                    <div class="form-group">
                                        <input id="nim" name="nim" type="text" placeholder="NIM" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="kelas">Kelas<span class="text-danger ms-0">*</span> </label>
                                    <div class="form-group">
                                        <input id="kelas" name="kelas" type="text" placeholder="Kelas" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <label for="prodi">Prodi<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <select class="form-select choices" name="prodi_id" id="prodi_id" required>
                                    <option value="">Pilih</option>
                                    @foreach ($jurusans as $dt )
                                        <optgroup label="{{$dt->jurusan}}">
                                            @foreach ($prodis->where('jurusan_id',$dt->id) as $dd )
                                                <option value="{{$dd->id}}">{{$dd->prodi}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                    
                                </select>
                            </div>
                            <label for="aktif">Status Aktif<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <select class="form-select choices" name="aktif" id="aktif" required>
                                    <option value="Y">Aktif</option>
                                    <option value="N">Tidak Aktif</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <input id="email" name="email" type="email" placeholder="Email"
                                    class="form-control">
                            </div>
                            <label for="email">Username</label>
                            <div class="form-group">
                                <input id="username" name="username" type="text" placeholder="Username"
                                    class="form-control" value="">
                            </div>
                            <label for="password">Password<small class="text-info ms-0">*Isi Jika ingin mengganti password</small></label>
                            <div class="form-group">
                                <input id="password" name="password" type="text" placeholder="Password"
                                    class="form-control" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/mahasiswa/mahasiswa_list.js')}}"></script>
@endsection
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
                    <table class="table" id="table" data-list="{{route('dosen.get_dosen')}}" data-url="{{route('dosen.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Pangkat</th>
                                <th>Golongan</th>
                                <th>Jabatan</th>
                                <th>Prodi</th>
                                <th>Jurusan</th>
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
                <h4 class="modal-title" id="form-title">Form Dosen </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form  id="form" name="form" data-index="{{route('dosen.index')}}" data-get-data="{{route('dosen.get_data')}}">
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
                            <label for="nip">NIP<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="nip" name="nip" type="text" placeholder="NIP" class="form-control" required>
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
                        </div>
                        <div class="col-md-6">
                            <label for="golongan">Golongan<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="golongan" name="golongan" type="text" placeholder="Golongan" class="form-control" required>
                            </div>
                            <label for="pangkat">Pangkat<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="pangkat" name="pangkat" type="text" placeholder="Pangkat"
                                    class="form-control" required>
                            </div>
                            <label for="jabatan">Jabatan<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="jabatan" name="jabatan" type="text" placeholder="Jabatan"
                                    class="form-control" required>
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

<script src="{{asset('app/assets/pages/dosen/dosen_list.js')}}"></script>
@endsection
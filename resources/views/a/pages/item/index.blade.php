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
                    @can('item-create')
                    <button class="btn icon icon-left btn-success" id="add" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="bi bi-plus-circle"></i> Tambah</button>
                    @endcan

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('item.get_item')}}" data-url="{{route('item.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Besaran</th>
                                <th>Isi</th>
                                <th>Reorder Point</th>
                                <th>Harga Jual</th>
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
            <form  id="form" name="form" data-index="{{route('item.index')}}" data-get-data="{{route('item.get_data')}}">
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" readonly />
                <input type="hidden" id="param" name="param" readonly />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="nama_item">Nama Item<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="nama_item" name="nama_item" type="text" placeholder="Nama Item" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="kategori">Kategori<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="">Pilih</option>
                                    @foreach ($kategories as $dt)
                                        <option value="{{encrypt1($dt->id)}}">{{$dt->kategori_item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="besaran">Besaran<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <select id="besaran" name="besaran" class="form-control" required>
                                    <option value="">Pilih</option>
                                    @foreach ($satuans as $dt)
                                        <option value="{{$dt->satuan}}">{{$dt->satuan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                           <div class="row">
                                <div class="col-md-6">
                                    <label for="isi">Isi Per Besaran<span class="text-danger ms-0">*</span> </label>
                                    <div class="form-group">
                                        <input id="isi" name="isi" type="number" placeholder="Isi Per Besaran" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="satuan">Satuan<span class="text-danger ms-0">*</span> </label>
                                    <div class="form-group">
                                        <select id="satuan" name="satuan" class="form-control" required>
                                            <option value="">Pilih</option>
                                            @foreach ($satuans as $dt)
                                                <option value="{{$dt->satuan}}">{{$dt->satuan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                           </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md">
                                    <label for="hpp">HPP</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp.</span>
                                        <input type="text" class="form-control currency" id="hpp" name="hpp" type="text" placeholder="0" aria-label="HPP" aria-describedby="hpp" data-parsley-errors-container="#hpp_error">
                                    </div>
                                    <span id="hpp_error"></span>
                                </div>
                                <div class="col-md">
                                    <label for="harga_jual">Harga Jual<span class="text-danger ms-0">*</span> </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp.</span>
                                        <input type="text" class="form-control currency" id="harga_jual" name="harga_jual" type="text" placeholder="0" aria-label="Harga Jual" aria-describedby="harga_jual" data-parsley-errors-container="#harga_jual_error" required>
                                    </div>
                                    <span id="harga_jual_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="reorder_point">Reorder Point<span class="text-danger ms-0">*</span> </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="reorder_point" name="reorder_point" type="text" placeholder="0" aria-label="Reorder Point" aria-describedby="reorder_point" required data-parsley-errors-container="#reorder_point_error">
                                <span class="input-group-text" id="reorder_point">Satuan</span>
                            </div>
                            <span id="reorder_point_error"></span>
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

<script src="{{asset('app/assets/pages/item/item_list.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
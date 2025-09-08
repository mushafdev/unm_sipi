@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6">
            <h3>{{ $title }}</h3>
        </div>
        <div class="col-12 col-md-6 text-end mt-0 d-flex justify-content-end align-items-start">
            <a href="{{ route('stok-awal.index') }}" class="btn icon icon-left btn-light me-2">
                <i class="bi bi-arrow-left-square"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5><span class="text-secondary">Lokasi </span> {{ $data->gudang }}</h5>
                        <input type="hidden" name="gudang_id" id="gudang_id" value="{{ encrypt0($data->gudang_id) }}">
                        <input type="hidden" name="item_stok_awal_id" id="item_stok_awal_id" value="{{ encrypt0($data->id) }}">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Waktu</th>
                                        <td>{{ $data->waktu }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td> 
                                            <span class="badge {{\App\Helpers\TransactionHelper::StokAwalIsLocked($data->is_locked,'class')}}">{{\App\Helpers\TransactionHelper::StokAwalIsLocked($data->is_locked,'text')}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir diupdate oleh</th>
                                        <td> 
                                            {{$data->username}}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Penanggung Jawab</th>
                                        <td>{{ $data->penanggung_jawab }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td>{{ $data->catatan }}</td>
                                    </tr>
                                </table>
                            </div>
                            @if ($data->is_locked==='N')
                                <div class="col-md-12 bg-light-primary p-3">
                                    <form class="form" id="form" data-save-item="{{route('stok-awal.save_item')}}">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="item_id">Item</label>
                                                    <select class="form-control item select2-item" name="item_id" style="width: 100%" required data-parsley-errors-container="#item-errors0">
                                                    <option value=""></option>
                                                    </select>
                                                    <span id="item-errors0"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label for="tgl_kadaluarsa">Batch/Tgl.Expired</label>
                                                    <input class="form-control tgl-kadaluarsa" type="date" name="tgl_kadaluarsa" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label for="stok_awal">Stok</label>
                                                    <input class="form-control stok_awal" type="number" name="stok_awal" min="1" placeholder="0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn icon btn-sm btn-success me-1 mb-1 mt-md-4" id="save-item"><i class="bi bi-plus-circle"></i> Tambah Item</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            <div class="col-md-12 mt-4">
                                <h5 class="card-title">Data Item</h5>
                                <div class="table-responsive">
                                    <div class="table-place-full ">
                                    <table class="table table-stripped" id="tableDetail" data-list="{{route('stok-awal.get_item_stok_awal')}}" data-url="{{route('stok-awal.index')}}" data-delete-item="{{route('stok-awal.delete_item')}}" data-posting="{{route('stok-awal.posting')}}">
                                        <thead>
                                            <th width="5%">#</th>
                                            <th width="25%">Item</th>
                                            <th width="15%">Batch/Tgl. Expried</th>
                                            <th width="15%">Expried</th>
                                            <th width="10%">Stok</th>
                                            <th width="10%"></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>

                                @if ($data->is_locked==='N')
                                    <div class="col-md-12 text-end mt-3">
                                        <a href="{{ session('back_url', url('/')) }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                        <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Posting Stok Awal {{$data->gudang}}</button>
                                    </div>
                                @endif
                        </div>

                        

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </section>
</div>

<script src="{{ asset('app/assets/pages/item/stok_awal_detail.js') }}?v={{ identity()['assets_version'] }}"></script>
@endsection

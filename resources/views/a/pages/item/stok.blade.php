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
                    {{-- @can('item-create')
                    <button class="btn icon icon-left btn-success" id="add" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="bi bi-plus-circle"></i> Tambah</button>
                    @endcan --}}

                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label>Gudang</label>
                        <select class="form-select form-filter" id="gudang" data-filter="gudang">
                            <option value="">Semua</option>
                            @foreach ($gudangs as $dt)
                                <option value="{{encrypt1($dt->id)}}">{{$dt->gudang}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('item-stok.get_item_stok')}}" data-url="{{route('item-stok.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Besaran</th>
                                <th>Isi</th>
                                <th>Reorder Point</th>
                                <th>Stok</th>
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

<div class="modal fade text-left" id="modal-kartu-stok" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="w-100">
                            <tr>
                                <td width="15%">Gudang</td>
                                <th id="gudang_show">-</th>
                            </tr>
                            <tr>
                                <td width="15%">Kode</td>
                                <th id="kode_show">-</th>
                            </tr>
                            <tr>
                                <td>Nama Item</td>
                                <th id="nama_item_show">-</th>
                            </tr>
                            <tr>
                                <td>Satuan</td>
                                <th id="satuan_show">-</th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <form class="form" target="_blank" action="{{route('item-stok.print_kartu_stok')}}" data-kartu-stok="{{route('item-stok.get_kartu_stok')}}" name="form_kartu_stok" id="form_kartu_stok">
                            <div class="row">
                                <input type="hidden" class="form-control" name="item_id" id="item_id" value="" placeholder="" required>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 mt-2">Dari <span class="red"></span></label>
                                        <div class="col-md-8 p-0">
                                            <input type="date" class="form-control filter-ks" name="start_date" id="start_date" value="{{date('Y-m-01')}}" placeholder="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-4 mt-2">Sampai <span class="red"></span></label>
                                        <div class="col-md-8 p-0">
                                            <input type="date" class="form-control filter-ks" name="end_date" id="end_date" value="{{date('Y-m-d')}}" placeholder="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group row mr-2 ml-2">
                                    
                                        <button type="submit" class="btn btn btn-md btn-warning col-md-4 mr-2" id="print">
                                            <i class="bi bi-printer"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <div class="table-place-full">
                            <table class="table table-bordered nowrap table-hover" style="width:100%" id="t_kartu_stok" >
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">No. Referensi</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Tgl Kadaluarsa</th>
                                    <th scope="col">Masuk</th>
                                    <th scope="col">Keluar</th>
                                    <th scope="col">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                    <span class="">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/item/item_stok.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
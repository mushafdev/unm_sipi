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
                    @can('item transaction-create')
                        <div class="dropdown">
                            <button class="btn icon icon-left  btn-success dropdown-toggle me-1" type="button" id="dropdownCreate" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownCreate" style="">
                                <a class="dropdown-item text-success fw-semibold" href="{{route('item-transaction.create')}}?jenis=Masuk"><i class="bi bi-cart-plus fs-4 me-2"></i> Item Masuk</a>
                                <a class="dropdown-item text-danger fw-semibold" href="{{route('item-transaction.create')}}?jenis=Keluar"><i class="bi bi-cart-dash fs-4 me-2"></i> Item Keluar</a>
                                <a class="dropdown-item text-primary fw-semibold" href="{{route('item-transaction.create')}}?jenis=Transfer"> <i class="bi bi-cart fs-4 me-2"></i> Tranfer Item</a>
                            </div>
                        </div>
                    @endcan

                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label>Dari Tanggal</label>
                        <input type="date" class="form-control form-filter" id="start_date" data-filter="start_date" value="{{date('Y-m-d', strtotime('-1 month'))}}">
                    </div>
                    <div class="col-md-3 mb-1">
                        <label>Sampai Tanggal</label>
                        <input type="date" class="form-control form-filter" id="end_date" data-filter="end_date" value="{{date('Y-m-d')}}">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('item-transaction.get_item_transaction')}}" data-url="{{route('item-transaction.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Transaksi</th>
                                <th>Waktu</th>
                                <th>No. Referensi</th>
                                <th>Penanggung Jawab</th>
                                <th>Jenis Transaksi</th>
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
<script src="{{asset('app/assets/pages/item/item_transaction_list.js')}}"></script>
@endsection
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
                    @can('penjualan-create')
                        <div class="dropdown">
                            <a class="btn icon icon-left  btn-success me-1" href="{{route('penjualan.pracreate')}}" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </a>
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
                    <table class="table" id="table" data-list="{{route('penjualan.get_penjualan')}}" data-url="{{route('penjualan.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Transaksi</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Pasien</th>
                                <th>No. HP</th>
                                <th>Grand Total</th>
                                <th>Terbayar</th>
                                <th>Void</th>
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
<script src="{{asset('app/assets/pages/penjualan/penjualan_list.js')}}"></script>
@endsection
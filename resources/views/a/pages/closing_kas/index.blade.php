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
                    @can('closing kas-create')
                        <a href="{{route('closing-kas.create')}}" class="btn icon icon-left btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah</a>
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
                    <table class="table" id="table" data-list="{{route('closing-kas.get_closing_kas')}}" data-url="{{route('closing-kas.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Transaksi</th>
                                <th>Waktu</th>
                                <th>Total System</th>
                                <th>Total Aktual</th>
                                <th>Selisih</th>
                                <th>User</th>
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

<script src="{{asset('app/assets/pages/keuangan/closing_kas_list.js')}}"></script>
@endsection
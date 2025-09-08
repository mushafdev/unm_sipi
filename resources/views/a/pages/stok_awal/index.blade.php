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
                    @can('stok awal-create')
                        <div class="dropdown">
                            <button class="btn icon icon-left  btn-success dropdown-toggle me-1" type="button" id="dropdownCreate" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownCreate" style="">
                                @foreach ($gudangs as $dt )
                                <a class="dropdown-item text-primary fw-semibold" href="{{route('stok-awal.create')}}?gudang={{encrypt0($dt->id)}}"><i class="bi bi-box fs-4 me-2"></i> {{$dt->gudang}}</a>
                                    
                                @endforeach
                            </div>
                        </div>
                    @endcan

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('stok-awal.get_stok_awal')}}" data-url="{{route('stok-awal.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>No. Transaksi</th> --}}
                                <th>Waktu</th>
                                <th>Gudang</th>
                                <th>Penanggung Jawab</th>
                                <th>Catatan</th>
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
<script src="{{asset('app/assets/pages/item/stok_awal_list.js')}}"></script>
@endsection
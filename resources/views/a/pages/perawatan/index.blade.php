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
                    {{-- @can('perawatan-create')
                    <a href="{{route('perawatan.create')}}" class="btn icon icon-left btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah</a>
                        
                    @endcan --}}
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label>Tgl Daftar</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar"></i></span>
                            <input type="date" class="form-control form-filter" data-filter="tgl_daftar" placeholder="" id="tgl_daftar" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label>Jenis Layanan</label>
                        <select class="form-select form-filter" id="jenis-layanan" data-filter="jenis-layanan">
                            <option value="">Semua</option>
                            @foreach ($jenis_layanans as $dt)
                                <option value="{{encrypt1($dt->id)}}">{{$dt->jenis_layanan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active status-pendaftaran" data-id="" id="tab-all" data-bs-toggle="tab" href="#" role="tab" aria-controls="all" aria-selected="true"><i class="bi bi-clipboard-fill me-2"></i> Semua</a>
                    </li> 
                    @foreach ($status_pendaftarans as $dt)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link status-pendaftaran" data-id="{{($dt->value)}}" id="tab-{{$dt->value}}" data-bs-toggle="tab" href="#" role="tab" aria-controls="{{$dt->value}}" aria-selected="true"><i class="bi bi-clipboard-fill me-2"></i> {{$dt->label()}}</a>
                    </li> 
                    @endforeach
                    
                </ul>
                <div class="table-responsive mt-3">
                    <table class="table" id="table" data-list="{{route('perawatan.get_perawatan')}}" data-url="{{route('perawatan.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Antrian</th>
                                <th>Tanggal</th>
                                <th>jam</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Req. Dok</th>
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

<script src="{{asset('app/assets/pages/perawatan/perawatan_list.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
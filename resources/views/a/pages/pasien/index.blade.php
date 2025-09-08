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
                    @can('pasien-create')
                    <a href="{{route('pasien.create')}}" class="btn icon icon-left btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah</a>
                        
                    @endcan
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Ketik No. RM/Nama/No.HP/Alamat" id="filter-key" aria-label="Ketik No. RM/Nama/No.HP/Alamat" aria-describedby="button-addon2">
                            <button class="btn btn-pink" type="button" id="btn-filter"><i></i>Cari</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('pasien.get_pasien')}}" data-url="{{route('pasien.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>Tgl. Lahir</th>
                                <th>No. HP</th>
                                <th>NIK</th>
                                <th>Alamat</th>
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

<script src="{{asset('app/assets/pages/pasien/pasien_list.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
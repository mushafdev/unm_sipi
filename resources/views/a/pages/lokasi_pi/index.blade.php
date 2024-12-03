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
                    <a href="{{route('lokasi-pi.create')}}" class="btn icon icon-left btn-success">
                        <i class="bi bi-plus-circle"></i> Create</a>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('lokasi-pi.get_lokasi_pi')}}" data-url="{{route('lokasi-pi.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kota</th>
                                <th>Alamat</th>
                                <th>Telp.</th>
                                <th>Kebutuhan</th>
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

<script src="{{asset('app/assets/pages/lokasi_pi/lokasi_pi_list.js')}}"></script>
@endsection
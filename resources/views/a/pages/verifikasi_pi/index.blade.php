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
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('verifikasi-pi.get_verifikasi_pi')}}" data-url="{{route('verifikasi-pi.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lokasi</th>
                                <th>Pembimbing</th>
                                <th>Mahasiswa</th>
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

<script src="{{asset('app/assets/pages/verifikasi_pi/verifikasi_pi_list.js')}}"></script>
@endsection
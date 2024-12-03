@extends('u.layouts.master')
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
                    <a href="{{route('page.logbook',encrypt0(Session::get('id')))}}" target="_blank" class="btn icon icon-left btn-light">
                        <i class="bi bi-printer"></i> Print</a>
                    <a href="{{route('logbook.create')}}" class="btn icon icon-left btn-success">
                        <i class="bi bi-plus-circle"></i> Create</a>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-top" id="table" data-list="{{route('logbook.get_logbook')}}" data-url="{{route('logbook.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>Kegiatan</th>
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

<script src="{{asset('app/assets/pages/logbook/logbook_list.js')}}"></script>
@endsection
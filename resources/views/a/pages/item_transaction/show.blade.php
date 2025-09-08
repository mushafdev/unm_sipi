@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 text-end mt-0 d-flex justify-content-end align-items-start">
            <a href="{{route('item-transaction.index')}}" class="btn icon icon-left btn-light me-2"><i class="bi bi-arrow-left-square"></i> Kembali</a>
            @if ($data->cancel=='N')
                @can('item transaction-delete')
                <button class="btn icon icon-left btn-danger me-2 delete" data-url="{{route('item-transaction.index')}}" data-id="{{encrypt0($data->id)}}"><i class="bi bi-trash"></i> Batal</button>
                @endcan
            @endif
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
</div>
<div class="page-content"> 
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h5><span class="text-secondary">Jenis Transaksi</span>  {{$data->jenis_transaksi}}</h5>
                            <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless w-100">
                                                <tr>
                                                    <th class="align-top">No. Transaksi </th>
                                                    <td>{{$data->no_transaksi}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="align-top">Waktu </th>
                                                    <td>{{$data->waktu}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="align-top">Gudang Asal </th>
                                                    <td> 
                                                        {{$data->gudang_asal}}
                                                    </td>
                                                </tr>
                                                @if ($data->jenis_transaksi=='Transfer')
                                                <tr>
                                                    <th class="align-top">Gudang Tujuan </th>
                                                    <td> 
                                                        {{$data->gudang_tujuan}}
                                                    </td>
                                                </tr>    
                                                @endif
                                                <tr>
                                                    <th class="align-top">Status </th>
                                                    <td> 
                                                        <span class="badge {{\App\Helpers\TransactionHelper::cancel($data->cancel,'class')}}">{{\App\Helpers\TransactionHelper::cancel($data->cancel,'text')}}</span>
                                                    </td>
                                                </tr>
                                                
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless w-100">
    
                                                <tr>
                                                    <th class="align-top">Penanggung Jawab </th>
                                                    <td>{{$data->penanggung_jawab}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="align-top">No. Referensi </th>
                                                    <td>{{$data->no_referensi}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="align-top">Catatan </th>
                                                    <td>{{$data->catatan}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="table-action mb-2 d-flex justify-content-between">
                                    <h5 class="card-title">Data Item</h5>
                                </div>
                                <div class="table-place-full ">
                                    <table class="table table-stripped">
                                        <thead>
                                            <th>#</th>
                                            <th width="30%">Item</th>
                                            <th width="10%">Qty</th>
                                            <th width="10%">Satuan</th>
                                            <th width="20%">Tgl Kadaluarsa</th>
                                            <th width="20%">No. Batch</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($details as $dt )
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td widtd="30%">{{$dt->item_nama}}</td>
                                                    <td widtd="10%">{{$dt->qty}}</td>
                                                    <td widtd="10%">{{$dt->item_satuan}}</td>
                                                    <td widtd="20%">{{convertYmdToMdy2($dt->tgl_kadaluarsa)}}</td>
                                                    <td width="20%">{{$dt->no_batch}}</td>
                                                </tr>
                                            @endforeach
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{asset('app/assets/pages/item/item_transaction_detail.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 order-md-1 order-last text-end">
            <a href="{{ session('back_url', url('/')) }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <form name="form" id="form" data-store="{{route('item-transaction.store')}}" data-index="{{route('item-transaction.index')}}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="jenis_transaksi" id="jenis_transaksi" value="{{$jenis_transaksi}}">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h5><span class="text-secondary">Jenis Transaksi</span>  {{$jenis_transaksi}}</h5>
                            <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table-form w-100">
                                                <tr>
                                                    <td class="align-top">Waktu<span class="text-danger">*</span> </td>
                                                    <td><input type="datetime-local" class="form-control" name="waktu" id="waktu" value="{{date('Y-m-d H:i')}}"  placeholder="" required> </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top">Gudang Asal<span class="text-danger">*</span> </td>
                                                    <td> 
                                                        <select class="form-control" name="gudang_id" id="gudang_id" required>
                                                            <option value="">Pilih</option>
                                                            @foreach ($gudangs as $dt )
                                                                <option value="{{encrypt1($dt->id)}}">{{$dt->gudang}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                @if ($jenis_transaksi=='Transfer')
                                                <tr>
                                                    <td class="align-top">Gudang Tujuan </td>
                                                    <td> 
                                                        <select class="form-control" name="gudang_tujuan_id" id="gudang_tujuan_id">
                                                            <option value="">Pilih</option>
                                                            @foreach ($gudangs as $dt )
                                                                <option value="{{encrypt1($dt->id)}}">{{$dt->gudang}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>    
                                                @endif
                                                
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table-form w-100">
    
                                                <tr>
                                                    <td class="align-top">Penanggung Jawab </td>
                                                    <td><input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" value=""  placeholder="Penanggung Jawab"> </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top">No. Referensi </td>
                                                    <td><input type="text" class="form-control" name="no_referensi" id="no_referensi" value=""  placeholder="No. Referensi"> </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top">Catatan </td>
                                                    <td><textarea class="form-control" name="catatan" id="catatan" value=""  placeholder="Keterangan"></textarea> </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="table-action mb-2 d-flex justify-content-between">
                                    <h5 class="card-title">Data Item</h5>
                                    <div class="text-right d-flex">
                                        <button type="button"  class="btn btn-success btn-sm" name="" id="tambah-item">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                                <div class="table-place-full ">
                                    <table class="table table-stripped" id="t_item">
                                        <thead>
                                            <th>#</th>
                                            <th width="30%">Item</th>
                                            <th width="10%">Qty</th>
                                            <th width="10%">Satuan</th>
                                            <th width="20%">Tgl Kadaluarsa</th>
                                            <th width="20%">No. Batch</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center no">1</td>
                                                <td class="text-left">
                                                    <select class="form-control item select2-item" name="item_id[]" style="width: 100%" required data-parsley-errors-container="#item-errors0">
                                                    <option value=""></option>
                                                    </select>
                                                    <span id="item-errors0"></span>
                                                </td>
                                                <td class="text-center"><input class="form-control text-center qty" type="number" min="1" name="qty[]" value="1" ></td>
                                                <td class="text-center"><span class="satuan">-</span></td>
                                                <td class="text-center"><input class="form-control tgl-kadaluarsa" type="date" name="tgl_kadaluarsa[]" required></td>
                                                <td class="text-center"><input class="form-control no-batch" type="text" name="no_batch[]" placeholder="No. Batch"></td>
                                                <td class="text-center"><button type="button" class="btn btn-danger delete_item"><i class="bi bi-trash"></i></button></td>
                                            </tr>
    
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 text-end mt-3">
                                <a href="{{ session('back_url', url('/')) }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/item/item_transaction.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
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
        <form name="form" id="form" data-store="{{route('stok-opname.store')}}" data-index="{{route('stok-opname.index')}}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="gudang_id" id="gudang_id" value="{{encrypt0($gudangs->id)}}">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h5><span class="text-secondary">Lokasi</span>  {{$gudangs->gudang}}</h5>
                            <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table-form w-100">
                                                <tr>
                                                    <td class="align-top">Waktu<span class="text-danger">*</span> </td>
                                                    <td><input type="datetime-local" class="form-control" name="waktu" id="waktu" value="{{date('Y-m-d H:i')}}"  placeholder="" required> </td>
                                                </tr>
                                                
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table-form w-100">
    
                                                <tr>
                                                    <td class="align-top">Penanggung Jawab </td>
                                                    <td><input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" value=""  placeholder="Penanggung Jawab" required> </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top">Catatan </td>
                                                    <td><textarea class="form-control" name="catatan" id="catatan" value=""  placeholder="Keterangan"></textarea> </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12 mt-4 ">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active opname-list" data-id="belum" id="tab-belum" data-bs-toggle="tab" href="#" role="tab" aria-controls="belum" aria-selected="true"><i class="bi bi-clipboard-fill me-2"></i> Belum Dihitung</a>
                                    </li> 
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link opname-list" data-id="sudah" id="tab-sudah" data-bs-toggle="tab" href="#" role="tab" aria-controls="sudah" aria-selected="true"><i class="bi bi-clipboard-fill me-2"></i> Sudah dihitung</a>
                                    </li> 
                                    
                                </ul>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="table-place-full ">
                                    <table class="table table-stripped" id="table" data-list="{{route('stok-opname.get_item_stok_opname')}}" data-update-item="{{route('stok-opname.update_item')}}" data-url="{{route('stok-opname.index')}}">
                                        <thead>
                                            <th width="5%">#</th>
                                            <th width="25%">Item</th>
                                            <th width="15%">Batch/Tgl. Expried</th>
                                            <th width="10%">Expired</th>
                                            <th width="10%">Stok System</th>
                                            <th width="10%">Stok Fisik</th>
                                            <th width="10%">Selisih</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 text-end mt-3">
                                <a href="{{ session('back_url', url('/')) }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan Opname</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/item/stok_opname.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
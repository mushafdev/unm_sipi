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
        <form name="form" id="form" data-store="{{route('stok-awal.store')}}" data-index="{{route('stok-awal.index')}}" method="POST">
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

<script src="{{asset('app/assets/pages/item/stok_awal.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
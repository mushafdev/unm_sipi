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
        <form name="form" id="form" data-store="{{route('group.store')}}" data-index="{{route('group.index')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <h5>Info Lokasi PI</h5>
                                    <div class="form-group">
                                        <label for="lokasi_pi" class="form-label">Lokasi PI<span class="text-danger ms-0">*</span></label>
                                        <div class="input-group mb-0">
                                            <input type="text" name="lokasi_pi" id="lokasi_pi" class="form-control" placeholder="Lokasi PI" value=""  disabled required data-parsley-errors-container="#lokasi-pi-errors">
                                            <input type="hidden" name="lokasi_pi_id" id="lokasi_pi_id" class="form-control" placeholder="" value=""  readonly data-parsley-errors-container="#lokasi-pi-errors">
                                        
                                            <button class="btn btn-primary search-lokasi-pi" type="button"><i class="bi bi-search"></i> Pilih </button>
                                        </div>
                                        <span id="lokasi-pi-errors"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat<span class="text-danger ms-0">*</span></label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" value=""  disabled required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="waktu" class="form-label">Waktu<span class="text-danger ms-0">*</span></label>
                                        
                                        <div class="input-group mb-3">
                                            <select class="form-select" name="start_month" id="start_month"  data-parsley-errors-container="#waktu-errors" required>
                                                <option value="">Pilih</option>
                                                @for ($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}">{{getMonthFromNumber($i)}}</option>
                                                @endfor
                                            </select>
                                            <label class="input-group-text" for="inputGroupSelect01"> s/d</label>
                                            <select class="form-select" name="end_month" id="end_month"  data-parsley-errors-container="#waktu-errors" required>
                                                <option value="">Pilih</option>
                                                @for ($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}">{{getMonthFromNumber($i)}}</option>
                                                @endfor
                                            </select>
                                            <select class="form-select" name="year">
                                                @for ($i=date('Y');$i>=2020;$i--)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <span id="waktu-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5>Data Kelompok</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="t_group">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th width="30%">NIM</th>
                                                    <th>Nama</th>
                                                    <th>Kelas</th>
                                                    <th class="text-center">
                                                        <button type="button" id="add-group" class="btn btn-sm icon icon-left btn-success"><i class="bi bi-plus-square"></i> Tambah</button></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-bold-500 ">
                                                        <select class="w-100 mahasiswa_id" style="width: 100%" name="mahasiswa_id[]">
                                                            <option value="{{encrypt0(Session::get('id'))}}">{{Session::get('nim')}}</option>
                                                        </select>
                                                    </td>
                                                    <td class="">{{Session::get('nama')}}</td>
                                                    <td class="text-bold-500 ">{{Session::get('kelas')}}</td>
                                                   
                                                    <td></td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                   
                                </div>
                                <div class="col-md-12 text-end mt-5">
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
@include('u.component.modal_lokasi_pi')
<script src="{{asset('app/assets/pages/group/group.js')}}"></script>
@endsection
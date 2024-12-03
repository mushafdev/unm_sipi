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
        <form name="form" id="form" data-update="{{route('group.update',encrypt0($data->id))}}" data-index="{{route('group.index')}}">
        @method('PUT')
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
                                            <input type="text" name="lokasi_pi" id="lokasi_pi" class="form-control" placeholder="Lokasi PI" value="{{$data->lokasi_pi}}"  disabled required data-parsley-errors-container="#lokasi-pi-errors">
                                            <input type="hidden" name="lokasi_pi_id" id="lokasi_pi_id" class="form-control" placeholder="" value="{{encrypt0($data->lokasi_pi_id)}}"  readonly>
                                        
                                            <button class="btn btn-primary search-lokasi-pi" type="button"><i class="bi bi-search"></i> Pilih </button>
                                        </div>
                                        <span id="lokasi-pi-errors"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat<span class="text-danger ms-0">*</span></label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" value=""  disabled required>{{$data->alamat.', '.$data->kota}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="waktu" class="form-label">Waktu<span class="text-danger ms-0">*</span></label>
                                        
                                        <div class="input-group mb-3">
                                            <select class="form-select" name="start_month" id="start_month"  data-parsley-errors-container="#waktu-errors" required>
                                                <option value="">Pilih</option>
                                                @for ($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}" {{$i==$data->start_month?'selected':''}}>{{getMonthFromNumber($i)}}</option>
                                                @endfor
                                            </select>
                                            <label class="input-group-text" for="inputGroupSelect01"> s/d</label>
                                            <select class="form-select" name="end_month" id="end_month"  data-parsley-errors-container="#waktu-errors" required>
                                                <option value="">Pilih</option>
                                                @for ($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}" {{$i==$data->end_month?'selected':''}}>{{getMonthFromNumber($i)}}</option>
                                                @endfor
                                            </select>
                                            <select class="form-select" name="year">
                                                @for ($i=date('Y');$i>=2020;$i--)
                                                    <option value="{{$i}}" {{$i==$data->year?'selected':''}}>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <span id="waktu-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5>Data Kelompok</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0 " id="t_group" data-delete-item="{{route('group.destroy_item')}}">
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
                                                @foreach ($detail as $dt )
                                                <tr class="table-active group_list">
                                                    <td class="">
                                                        {{$dt->nim}}
                                                    </td>
                                                    <td class="">{{$dt->nama}}</td>
                                                    <td class="text-bold-500">{{$dt->kelas}}</td>
                                                   
                                                    <td class="text-center ">
                                                        @if ($data->send=='N')
                                                            @if ($data->mahasiswa_id!=$dt->mahasiswa_id)
                                                                <button type="button" class="btn icon btn-danger delete_permanent" data-id="{{encrypt0($dt->id)}}"><i class="bi bi-trash"></i></button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                   
                                </div>
                                <div class="col-md-12 text-end mt-5">
                                    @if ($data->send=='N')
                                        <button type="button" id="delete" class="btn icon icon-left btn-danger" data-id="{{encrypt0($data->id)}}"><i class="bi bi-trash"></i> Hapus</button>
                                        <button type="button" id="update" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Update</button>            
                                    @endif
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
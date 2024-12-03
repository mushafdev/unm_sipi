@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('group.index')}}">Daftar Kelompok</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <form name="form" id="form" data-index="{{route('group.index')}}" data-verifikasi="{{route('group.verifikasi')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Info Lokasi PI</h5>
                        <table class="table w-100">
                            <tr>
                                <th class="align-top">Lokasi PI </th>
                                <td class="align-top">{{$data->lokasi_pi}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Alamat</th>
                                <td class="align-top">{{$data->alamat.', '.$data->kota}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">Waktu</th>
                                <td class="align-top">{{getMonthFromNumber($data->start_month).' s/d '.getMonthFromNumber($data->end_month).' '.$data->year}}</td>
                            </tr>
                        </table>

                        <h5>Pembimbing</h5>
                        <table class="table w-100">
                            <tr>
                                <th class="align-top">Nama</th>
                                <td class="align-top">{{$data->pembimbing?$data->pembimbing:'-'}}</td>
                            </tr>
                            <tr>
                                <th class="align-top">NIP</th>
                                <td class="align-top">{{$data->pembimbing_nip?$data->pembimbing_nip:'-'}}</td>
                            </tr>
                        </table>

                        <h5>Status Pengajuan</h5>
                        <label for="" class="form-label">
                            @if ($data->send=='N')
                                <span class="badge bg-danger">Belum dikirim ke admin</span>
                            @else
                                <span class="badge bg-success">Telah dikirim ke admin</span>
                            @endif
                                <span class="badge {!!adminVerifyStatus($data->admin_verify,'badge')!!}">{{adminVerifyStatus($data->admin_verify,'text')}}</span>
                        </label>
                        @if (!empty($data->catatan))
                        <div class="alert alert-light-warning color-warning"><i class="bi bi-exclamation-triangle"></i>
                            <strong>Catatan : </strong>{{$data->catatan}}</div>

                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Data Kelompok</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0" id="t_group">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Kelas</th>
                                                <th>Status Verifikasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detail as $dt)
                                            <tr>
                                                <td class="text-bold-500">
                                                    {{$dt->nim}}
                                                </td>
                                                <td> {{$dt->nama}}</td>
                                                <td> {{$dt->prodi}}</td>
                                                <td> {{$dt->kelas}}</td>
                                                
                                                <td>
                                                    @if ($dt->verify=='N')
                                                        <span class="badge bg-danger">Belum diverifikasi</span>
                                                    @else
                                                        <span class="badge bg-success">Telah diverifikasi</span>
                                                    @endif
                                                </td>
                                            </tr>   
                                            @endforeach
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                           
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <h5>Pembimbing</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group mb-0">
                                                <input type="text" name="pembimbing" id="pembimbing" class="form-control" placeholder="Pembimbing" value="{{$data->pembimbing}}"  disabled required data-parsley-errors-container="#pembimbing-errors">
                                                <input type="hidden" name="pembimbing_id" id="pembimbing_id" class="form-control" placeholder="" value="{{encrypt0($data->pembimbing_id)}}"  readonly>
                                            
                                                <button class="btn btn-primary search-pembimbing" type="button"><i class="bi bi-search"></i> Pilih </button>
                                            </div>
                                            <span id="pembimbing-errors"></span>
                                        </div>
                                    </div>
                                </div>
                                           
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <h5>Info Verifikasi</h5>
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label for="admin-verify" class="form-label">Status Verifikasi<span class="text-danger ms-0">*</span></label>
                                            
                                            <div class="input-group mb-3">
                                                <select class="form-select" name="admin_verify" id="admin_verify"  data-parsley-errors-container="#admin-verify-errors" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($admin_verifys as $k => $v)
                                                        <option value="{{$k}}" {{$k==$data->admin_verify?'selected':''}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <span id="admin-verify-errors"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="catatan" class="form-label">Catatan</label>
                                            <textarea type="text" name="catatan" id="catatan" class="form-control" placeholder="Catatan" value="">{{$data->catatan}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-end">
            
                    <a href="{{route('group.edit',encrypt0($data->id))}}" class="btn icon icon-left btn-info"><i class="bi bi-pencil"></i> Edit</a>
                    <button type="button" id="verifikasi" class="btn icon icon-left btn-primary" data-id="{{encrypt0($data->id)}}"><i class="bi bi-check"></i> Verifikasi</button>
            </div> 
        </div>
        </form>
    </section>
</div>
@include('u.component.modal_pembimbing')
<script src="{{asset('app/assets/pages/group/group.js')}}"></script>
@endsection
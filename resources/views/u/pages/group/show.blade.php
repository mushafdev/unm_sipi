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
        <form name="form" id="form" data-index="{{route('group.index')}}" data-send="{{route('group.send')}}" data-verifikasi-mhs="{{route('group.verifikasi_mhs')}}" method="POST">
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
                        <p>{{$data->catatan}}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
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
            <div class="col-12 text-end">
            
                
                @if ($data->mahasiswa_id!==Session::get('id'))
                    <button type="button" id="verifikasi-mhs" class="btn icon icon-left btn-primary"><i class="bi bi-check"></i> Verifikasi</button>
                @else
                    @if ($data->send=='N')
                        <a href="{{route('group.edit',encrypt0($data->id))}}" class="btn icon icon-left btn-info"><i class="bi bi-pencil"></i> Edit</a>
                        <button type="button" id="send" class="btn icon icon-left btn-primary" data-id="{{encrypt0($data->id)}}"><i class="bi bi-send"></i> Kirim</button>
                    @endif
                @endif
            </div> 
        </div>
        </form>
    </section>
</div>
<script src="{{asset('app/assets/pages/group/group.js')}}"></script>
@endsection
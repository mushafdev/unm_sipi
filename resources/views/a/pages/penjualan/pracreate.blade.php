@extends('a.layouts.master')
@section('content')
<link rel="stylesheet" href="{{asset('app/assets/compiled/css/pracreate.css')}}?v={{identity()['assets_version']}}">

<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            {{-- <h3>{{$title}}</h3> --}}
            <p class="text-subtitle text-muted"></p>
        </div>
        <div class="col-12 col-md-6 order-md-1 order-last text-end">
            <a href="{{ route('penjualan.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-list-check"></i> Daftar Penjualan</a>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
       <div class="row">
            <div class="col-md-12 p-5">
                <div id="sourceSelection">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-pink mb-3">Pilih Sumber Penjualan</h2>
                        <p class="text-muted">Tentukan dari mana penjualan ini berasal</p>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div id="pilihPendaftaran">
                                 <div class="source-card">
                                    <i class="bi bi-clipboard-data source-icon"></i>
                                    <h3 class="source-title">Dari Pendaftaran</h3>
                                    <p class="source-description">
                                        Pelanggan yang sudah terdaftar sebelumnya dengan data lengkap tersedia di sistem
                                    </p>
                                </div>
                            </div>
                           
                        </div>
                        
                        <div class="col-md-6">
                            <a href="{{route('penjualan.create')}}">
                                <div class="source-card">
                                    <i class="bi bi-plus-circle source-icon"></i>
                                    <h3 class="source-title">Buat Langsung</h3>
                                    <p class="source-description">
                                        Input penjualan baru dengan mengisi data pelanggan secara manual
                                    </p>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </section>
</div>

<div class="modal fade" id="modalPendaftaran" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Pendaftaran</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table" id="tablePendaftaran" data-list="{{route('perawatan.get_perawatan')}}" data-create="{{route('penjualan.create')}}">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Antrian</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>No. RM</th>
                    <th>jam</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary"
            data-bs-dismiss="modal">
            <i class="bx bx-x"></i>
            <span class="">Close</span>
        </button>
      </div>
    </div>
  </div>
</div>




<script src="{{asset('app/assets/pages/penjualan/prapenjualan.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted">No. Antrian <b>K001</b>. Layanan sekarang : <b class="text-info">{{$pendaftaran->jenis_layanan}}</b></p>
        </div>
        <div class="col-12 col-md-6 order-md-1 order-last text-end">
            <a href="{{ route('perawatan.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square me-1"></i>Kembali</a>
            @if (!in_array($pendaftaran->status, ['Pembayaran', 'Selesai']))
            <button type="button" id="change-layanan" 
                    data-id="{{ $pendaftaran->id }}" 
                    class="btn btn-warning">
                <i class="bi bi-bag-check me-1"></i> Ubah Layanan
            </button>
                <button type="button" id="next-payment" class="btn icon icon-left btn-primary"><i class="bi bi-bag-check me-1"></i> Lanjutkan Ke Pembayaran</button>
            @endif
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <form name="form" id="form" data-store="{{route('perawatan.store')}}" data-index="{{route('perawatan.index')}}" data-id="{{Request::get('id')}}" data-pembayaran="{{route('perawatan.pembayaran')}}" method="POST">
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" id="pilih-bed" class="w-100 text-start {{$pendaftaran->bed_id?'bg-light-danger':''}} border border-0 border-dashed rounded p-3 hover transition mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-danger">
                                                <path d="M3 7v10"/>
                                                <path d="M21 7v10"/>
                                                <path d="M3 12h18"/>
                                                <path d="M7 7h10a4 4 0 0 1 4 4v6H3v-6a4 4 0 0 1 4-4z"/>
                                            </svg>

                                            @if ($pendaftaran->bed_id)
                                                <div>
                                                    <p class="mb-0 fw-semibold text-dark"><span class="small text-secondary">Ruangan {{$pendaftaran->room_name}} </span> Lantai {{$pendaftaran->lantai}} </p>
                                                    <h5 class="mb-0 text-danger">{{$pendaftaran->bed_number}}</h5>
                                                </div>
                                            @else
                                            <span class="text-secondary">Klik untuk pilih Bed</span>
                                            @endif
                                            
                                        </div>
                                    </button>
                                </div>
                                <div class="col-md-8 text-end">
                                    <p class="mb-1">Total Biaya</p>
                                    <h1 id="grand_total">Rp. 0</h1>
                                    {{-- <button type="button" class="btn btn-primary w-100" id="btnSimpan">Proses Pembayaran</button> --}}
                                </div>
                            </div>
                            
                            <ul class="nav nav-tabs" id="perawatanTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link tab-perawatan active" data-type="pasien" data-bs-toggle="tab" href="#" role="tab" ><i class="bi bi-people me-2 fs-5"></i>Data Pasien</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tab-perawatan" data-type="foto" data-bs-toggle="tab" href="#" role="tab" > <i class="bi bi-camera me-2 fs-5"></i>Foto Before</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tab-perawatan" data-type="service" data-bs-toggle="tab" href="#" role="tab" ><i class="bi bi-star me-2 fs-5"></i>Tindakan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tab-perawatan" data-type="item"  data-bs-toggle="tab" href="#" role="tab" > <i class="bi bi-cart me-2 fs-5"></i>Produk</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tab-perawatan" data-type="resep"  data-bs-toggle="tab" href="#" role="tab" > <i class="bi bi-book me-2 fs-5"></i>Resep</a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content mt-3" id="perawatanTabContent">
                        <div id="tabContent">
                            <div class="text-center w-100 py-5"><div class="spinner-border text-primary" role="status"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<div class="modal fade" id="modalUbahLayanan" tabindex="-1">
  <div class="modal-dialog">
    <form id="form-ubah-layanan" data-ubah-layanan="{{ route('perawatan.ubah_layanan') }}" method="POST">
      @csrf
      <input type="hidden" name="pendaftaran_id" id="pendaftaran_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Layanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <label for="jenis_layanan">Pilih Layanan</label>
          <select name="jenis_layanan_id" id="jenis_layanan" class="form-select">
            @foreach($jenis_layanans as $layanan)
              <option value="{{ $layanan->id }}">{{ $layanan->jenis_layanan }}</option>
            @endforeach
          </select>
        </div>

        <div class="modal-footer">
          <button type="button" id="save-ubah-layanan" class="btn btn-primary">Ubah Layanan</button>
        </div>
      </div>
    </form>
  </div>
</div>

@if (!in_array($pendaftaran->status, ['Pembayaran', 'Selesai']))
    @include('a.component.status_beds')
@endif
<script src="{{asset('app/assets/pages/perawatan/perawatan.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
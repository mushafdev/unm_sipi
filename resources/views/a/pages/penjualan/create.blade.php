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
        <form name="form" id="form" data-store="{{route('penjualan.store')}}" data-index="{{route('penjualan.index')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-8">

            <div class="input-group mb-3 flex-nowrap">
                <span class="input-group-text" 
                style="
                font-size: 19px;
                border-radius: 20px 0px 0px 20px;
                border: 1px #bdc4cf solid;
                padding: 5px 15px 5px 15px;">
                <i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari layanan atau produk..." 
                style="    
                line-height: 30px;
                font-size: 15px;
                border-radius: 0px 20px 20px 0px;
                border: 1px #bdc4cf solid;">
            </div>

            <ul class="nav nav-tabs mb-3" id="tabSelector">
                <li class="nav-item">
                <a class="nav-link type-layanan active" data-type="services" href="#">Tindakan</a>
                </li>
                <li class="nav-item">
                <a class="nav-link type-layanan" data-type="items" href="#">Produk</a>
                </li>
            </ul>

            <div class="row" id="itemsContainer">
                <!-- Items will be rendered here -->
            </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Keranjang</h5>
                        <span class="badge bg-primary" id="cartCount">0</span>
                    </div>
                    @if ($sumber==='langsung')
                       <div class="p-3 pt-0">
                            <div class="input-group mb-3 flex-nowrap">
                                <span class="input-group-text">Sumber Penjualan</span>
                                <select  class="form-control form-select" placeholder="" aria-label="" id="sumber_penjualan_id">
                                    @foreach ($sumber_penjualans as $dt )
                                    <option  value="{{ encrypt1($dt->id) }}">{{ $dt->sumber_penjualan }}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 flex-nowrap">
                                <span class="input-group-text">Pasien</span>
                                <select  class="form-control select2-icon select2-pasien" placeholder="Ketik No. RM/Nama/No.HP/Alamat" id="pasien_id" aria-label="Ketik No. RM/Nama/No.HP/Alamat">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    @endif
                    @if ($sumber==='pendaftaran')
                    <input type="hidden" name="pendaftaran_id" id="pendaftaran_id" value="{{encrypt0($pendaftaran->id)}}">
                    <input type="hidden" name="pasien_id" id="pasien_id" value="{{encrypt0($pendaftaran->pasien_id)}}">
                    <button type="button" class="w-100 text-start bg-light-primary border border-0 border-dashed rounded p-3 hover transition mb-2">
                        <div class="d-flex align-items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="text-primary">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <div>
                            <p class="mb-0 fw-semibold text-dark"><span class="small text-secondary">{{$pendaftaran->no_rm}}</span> {{$pendaftaran->nama}} </p>
                            <p class="mb-0 small text-muted">{{$pendaftaran->no_hp}}</p>
                            </div>
                        </div>
                    </button>
                    @endif
                    <div class="card-body" id="cartContainer">
                        <p class="text-center text-muted mt-3">Keranjang masih kosong</p>
                    </div>
                    <div class="card-footer bg-light-info d-none" id="checkoutFooter">
                    {{-- <div class="d-flex justify-content-between mb-2">
                        <strong>Sub Total</strong>
                        <strong id="subTotal">Rp0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Diskon</strong>
                        <strong id="diskonTotal">Rp0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>PPN</strong>
                        <strong id="ppnTotal">Rp0</strong>
                    </div> --}}
                    <div class="d-flex justify-content-between mb-2 fs-5">
                        <strong>Total</strong>
                        <strong id="grandTotal">Rp0</strong>
                    </div>
                    <button class="btn btn-primary w-100" type="button" id="checkoutBtn">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Total: <strong id="modalTotalPrice">Rp0</strong></p>

        <div class="d-grid gap-2 mb-3" id="metodePembayaranGroup">
          @foreach ($metode_pembayarans as $dt)
            <button 
              class="btn btn-outline-secondary border-secondary metode-pembayaran-btn" 
              data-id="{{ $dt->id }}" 
              data-nama="{{ $dt->metode_pembayaran }}">
              {{ $dt->metode_pembayaran }}
            </button>
          @endforeach
        </div>

        <div class="mb-3">
          <label for="bayarInput" class="form-label">Bayar (Rp)</label>
          <input type="text" class="form-control currency" id="bayarInput" autocomplete="off" placeholder="0" />
        </div>

        <div class="mb-3">
          <label class="form-label">Kembalian</label>
          <div id="kembalianDisplay" class="fs-5 fw-bold text-warning">Rp0</div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary w-100" id="save">Proses & Cetak</button>
      </div>
    </div>
  </div>
</div>




<script src="{{asset('app/assets/pages/penjualan/penjualan.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
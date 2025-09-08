@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{$title}}</h3>
            <p class="text-subtitle text-muted">Detail transaksi penjualan</p>
        </div>
        <div class="col-12 col-md-6 order-md-1 order-last text-end">
            <a href="{{ route('penjualan.index') }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
            <a href="{{ route('penjualan.pracreate') }}" class="btn icon icon-left btn-success"><i class="bi bi-plus-circle"></i> Tambah </a>
        </div>
    </div>
</div>

<div class="page-content"> 
    <section class="section">
        <div class="row">
            <!-- Informasi Transaksi -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Transaksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="140">No. Transaksi</td>
                                                <td width="10">:</td>
                                                <td><strong>{{$data->no_transaksi}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Waktu</td>
                                                <td>:</td>
                                                <td>{{ date('d/m/Y H:i', strtotime($data->waktu)) }}</td>
                                            </tr>
                                            @if($data->pendaftaran_id)
                                            <tr>
                                                <td>Sumber Penjualan</td>
                                                <td>:</td>
                                                <td>Walk In</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td>Sumber Penjualan</td>
                                                <td>:</td>
                                                <td>{{$data->sumber_penjualan}}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="140">Kasir</td>
                                                <td width="10">:</td>
                                                <td>{{$data->kasir ?? '-'}}</td>
                                            </tr>
                                            <tr>
                                                <td>Void</td>
                                                <td>:</td>
                                                <td>
                                                    @if($data->cancel === 'N')
                                                        <span class="badge bg-success">Berhasil</span>
                                                    @else
                                                        <span class="badge bg-danger">Dibatalkan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Pasien</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="text-primary">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <div>
                                            <h6 class="mb-1"><span class="small text-secondary">{{$data->no_rm}}</span> {{$data->pasien}}</h6>
                                            <p class="mb-0 text-muted">{{$data->no_hp}}</p>
                                            <p class="mb-0 small text-muted">{{$data->alamat}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Detail Item</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Item</th>
                                                <th>Type</th>
                                                <th width="80">Qty</th>
                                                <th width="120">Harga</th>
                                                <th width="100">Diskon</th>
                                                <th width="120">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ ucfirst($item->type==='items'?'Produk':'Tindakan') }}</td>
                                                    <td>{{ $item->quantity .' '.$item->satuan }}</td>
                                                    <td class="text-end">Rp.{{ number_format($item->price_ppn, 2, ',', '.') }}</td>
                                                    <td>
                                                        @if($item->discountType === 'percentage')
                                                            {{ $item->discount }}%
                                                        @else
                                                            Rp.{{ number_format($item->discount, 2, ',', '.') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        Rp.{{ number_format($item->sub_total, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sub Total</span>
                            <strong>Rp{{ number_format($data->sub_total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Diskon</span>
                            <strong class="text-danger">Rp{{ number_format($data->diskon_rp, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>PPN</span>
                            <strong>Rp{{ number_format($data->ppn, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Grand Total</strong>
                            <strong class="text-primary fs-5">Rp{{ number_format($data->grand_total, 0, ',', '.') }}</strong>
                        </div>
                        
                        <div class="mb-2">
                            <div class="payment-timeline">
                                @foreach ($payments as $payment )
                                <div class="payment-item success border-bottom border-top py-2">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex gap-2"> 
                                                <h6 class="mb-1 text-secondary">Pembayaran #{{$loop->iteration}}</h6>
                                                @if ($payment->cancel === 'N')
                                                <span class="badge bg-success">Berhasil</span>
                                                @else
                                                <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{toDatetime2($payment->created_at)}}</small>
                                        </div>
                                        <div class="">
                                            {{-- <button class="btn btn-outline-primary btn-action edit-pembayaran" data-id="{{encrypt0($payment->id)}}" type="button"><i class="bi bi-pencil"></i></button> --}}
                                            <button class="btn btn-outline-danger btn-action delete-pembayaran" data-id="{{encrypt0($payment->id)}}" type="button"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>{{$payment->metode_pembayaran}}</span>
                                        <strong class="text-secondary">Rp.{{toCurrency($payment->jumlah)}}</strong>
                                    </div>
                                </div>    
                                @endforeach
                                

                            </div>
                            <button class="btn btn-md btn-light my-3 w-100 " id="addPayment"><i class="bi bi-plus-lg"></i>Tambah Pembayaran</button>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Terbayar</strong>
                            <strong class="text-success fs-5">Rp{{ number_format($data->bayar, 0, ',', '.') }}</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <span>Sisa</span>
                            <strong class="text-danger">Rp{{ number_format($data->sisa, 0, ',', '.') }}</strong>
                            <input type="hidden" id="sisa" name="sisa" value="{{$data->sisa}}">
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <span>Lebih Bayar</span>
                            <strong class="text-warning">Rp{{ number_format($data->kembalian, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button class="btn btn-primary w-100" onclick="printInvoice()"><i class="bi bi-printer"></i> Cetak</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="paymentModal" data-store="{{route('penjualan.pembayaran.store')}}" data-delete="{{route('penjualan.pembayaran.delete')}}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="transaction_id" value="{{encrypt0($data->id)}}">
        <p>Belum Dibayar: <strong id="modalSisa">Rp0</strong></p>

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
        <button class="btn btn-primary w-100" id="save-pembayaran">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script src="{{asset('app/assets/pages/penjualan/penjualan_detail.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
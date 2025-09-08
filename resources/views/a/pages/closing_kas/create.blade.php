@extends('a.layouts.master')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-md-6">
            <h3>{{ $title }}</h3>
            <p class="text-subtitle text-muted">Closing kas harian berdasarkan jenis transaksi</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ session('back_url', url('/')) }}" class="btn btn-light">
                <i class="bi bi-arrow-left-square"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <form id="form" name="form" method="POST" data-store="{{ route('closing-kas.store') }}" data-index="{{ route('closing-kas.index') }}">
            @csrf

            {{-- ================= TABEL PENJUALAN ================= --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-primary">Rekap Penjualan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="penjualan-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <th>Total Sistem</th>
                                    <th>Total Aktual</th>
                                    <th>Selisih</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metode_pembayarans as $i => $dt)
                                <tr>
                                    <td>
                                        {{ $dt->metode_pembayaran }}
                                        <input type="hidden" name="penjualan_metode_id[]" value="{{ encrypt1($dt->id) }}">
                                    </td>
                                    <td>
                                        <input type="text" name="penjualan_system[]" class="form-control text-end bg-light-info autonum total_system" data-index="pj{{ $i }}" placeholder="0" value="{{ $dt->total_system }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="penjualan_aktual[]" required class="form-control text-end autonum total_aktual" data-index="pj{{ $i }}" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="text" name="penjualan_selisih[]" class="form-control text-end bg-light-warning autonum selisih" data-index="pj{{ $i }}" readonly placeholder="0">
                                    </td>
                                    <td>
                                        <textarea name="penjualan_catatan[]" class="form-control" rows="1"></textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= TABEL TRANSAKSI KAS ================= --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title text-success">Rekap Transaksi Kas (Penerimaan & Pengeluaran)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="kas-table">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2">Akun</th>
                                    <th colspan="2" class="text-center">System</th>
                                    <th colspan="2" class="text-center">Aktual</th>
                                    <th colspan="2" class="text-center">Selisih</th>
                                    <th rowspan="2">Catatan</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan</th>
                                    <th>Pengeluaran</th>
                                    <th>Penerimaan</th>
                                    <th>Pengeluaran</th>
                                    <th>Penerimaan</th>
                                    <th>Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($akun_kas as $i => $dt)
                                <tr>
                                    <td>
                                        {{ $dt->nama_akun }} - {{ $dt->nomor_akun }}
                                        <input type="hidden" name="akun_kas_id[]" value="{{ encrypt1($dt->id) }}">
                                    </td>
                                    <td>
                                        <input type="text" name="kas_income[]" class="form-control text-end autonum bg-light-success" value="{{ $dt->penerimaan_system }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="kas_expense[]" class="form-control text-end autonum bg-light-danger" value="{{ $dt->pengeluaran_system }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="kas_income_aktual[]" placeholder="0" class="form-control text-end autonum total_aktual" data-index="ks{{ $i }}">
                                    </td>
                                    <td>
                                        <input type="text" name="kas_expense_aktual[]" placeholder="0" class="form-control text-end autonum total_aktual" data-index="ks{{ $i }}">
                                    </td>
                                    <td>
                                        <input type="text" name="kas_income_selisih[]" placeholder="0" class="form-control text-end autonum bg-light-warning selisih" data-index="ks{{ $i }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="kas_expense_selisih[]" placeholder="0" class="form-control text-end autonum bg-light-warning selisih" data-index="ks{{ $i }}" readonly>
                                    </td>
                                    <td>
                                        <textarea name="kas_catatan[]" class="form-control" rows="1"></textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= TOMBOL SIMPAN ================= --}}
            <div class="text-end mt-4">
                <a href="{{ session('back_url', url('/')) }}" class="btn btn-light me-2">
                    <i class="bi bi-arrow-left-square"></i> Kembali
                </a>
                <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </div>

        </form>
    </section>
</div>

<script src="{{ asset('app/assets/pages/keuangan/closing_kas.js') }}?v={{ identity()['assets_version'] }}"></script>
@endsection

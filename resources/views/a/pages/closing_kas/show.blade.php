@extends('a.layouts.master')
@section('content')

<div class="page-title">
    <div class="row">
        <div class="col-md-6">
            <h3>{{ $title }}</h3>
            <p class="text-subtitle text-muted">Nomor Transaksi: <strong>{{ $data->no_transaksi }}</strong></p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('closing-kas.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">

        {{-- Ringkasan --}}
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th>Waktu</th><td>{{ toDatetime($data->waktu) }}</td></tr>
                    <tr><th>Total Sistem</th><td>{{ number_format($data->total_system, 2, ',', '.') }}</td></tr>
                    <tr><th>Total Aktual</th><td>{{ number_format($data->total_aktual, 2, ',', '.') }}</td></tr>
                    <tr><th>Selisih</th><td>{{ number_format($data->selisih, 2, ',', '.') }}</td></tr>
                    <tr><th>User</th><td>{{ $data->dibuat_oleh }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Rekap Penjualan --}}
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title text-primary">Rekap Penjualan</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
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
                        @foreach ($penjualans as $row)
                        <tr>
                            <td>{{ $row->metode_pembayaran }}</td>
                            <td class="text-end">{{ number_format($row->total_system, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->total_aktual, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->selisih, 0, ',', '.') }}</td>
                            <td>{{ $row->catatan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rekap Transaksi Kas --}}
        <div class="card">
            <div class="card-header"><h5 class="card-title text-success">Rekap Transaksi Kas</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
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
                        @foreach ($trkas as $row)
                        <tr>
                            <td>{{ $row->nama_akun }} - {{ $row->nomor_akun }}</td>
                            <td class="text-end">{{ number_format($row->penerimaan_system, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->pengeluaran_system, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->penerimaan_aktual, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->pengeluaran_aktual, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->penerimaan_selisih, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->pengeluaran_selisih, 0, ',', '.') }}</td>
                            <td>{{ $row->catatan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

@endsection

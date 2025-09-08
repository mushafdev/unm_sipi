@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6">
            <h3>{{ $title }}</h3>
        </div>
        <div class="col-12 col-md-6 text-end mt-0 d-flex justify-content-end align-items-start">
            <a href="{{ route('stok-opname.index') }}" class="btn icon icon-left btn-light me-2">
                <i class="bi bi-arrow-left-square"></i> Kembali
            </a>
            {{-- @if ($data->status !== 'selesai')
                @can('stok opname-delete')
                    <button class="btn icon icon-left btn-danger me-2 delete"
                        data-url="{{ route('stok-opname.index') }}"
                        data-id="{{ encrypt0($data->id) }}">
                        <i class="bi bi-trash"></i> Batal
                    </button>
                @endcan
            @endif --}}
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5><span class="text-secondary">Lokasi </span> {{ $data->gudang }}</h5>
                        <input type="hidden" name="stok_opname_id" id="stok_opname_id" value="{{ encrypt0($data->id) }}">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Waktu</th>
                                        <td>{{ $data->waktu }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td> 
                                            <span class="badge {{\App\Helpers\TransactionHelper::statusOpname($data->status,'class')}}">{{\App\Helpers\TransactionHelper::statusOpname($data->status,'text')}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Penanggung Jawab</th>
                                        <td>{{ $data->penanggung_jawab }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td>{{ $data->catatan }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12 mt-4">
                                <h5 class="card-title">Data Item</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="tableDetail" data-list="{{ route('stok-opname.get_item_stok_opname_detail')}}">
                                        <thead class="">
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Satuan</th>
                                                <th>Tgl. Kadaluarsa</th>
                                                <th>Stok Sistem</th>
                                                <th>Stok Fisik</th>
                                                <th>Selisih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </section>
</div>

<script src="{{ asset('app/assets/pages/item/stok_opname_detail.js') }}?v={{ identity()['assets_version'] }}"></script>
@endsection

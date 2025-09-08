@extends('a.layouts.master')
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3 class="title">{{$title}}</h3>
            <p class="text-subtitle text-muted"></p>
        </div>
    </div>
</div>
<div class="page-content"> 
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">
                        Daftar {{$title}}
                    </h5>
                </div>
                <div>
                    @can('transaksi kas-create')
                        <div class="dropdown">
                            <button class="btn icon icon-left  btn-success dropdown-toggle me-1" type="button" id="dropdownCreate" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownCreate" style="">
                                <a class="dropdown-item text-success fw-semibold create-transaksi" href="#" data-type="expense" data-title="Pengeluaran" data-bs-toggle="modal" data-bs-target="#modal"><i class="bi bi-wallet fs-4 me-2"></i> Pengeluaran</a>
                                <a class="dropdown-item text-danger fw-semibold create-transaksi" href="#" data-type="income" data-title="Pemasukan" data-bs-toggle="modal" data-bs-target="#modal"><i class="bi bi-wallet fs-4 me-2"></i> Pemasukan</a>
                            </div>
                        </div>
                    @endcan

                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label>Dari Tanggal</label>
                        <input type="date" class="form-control form-filter" id="start_date" data-filter="start_date" value="{{date('Y-m-d', strtotime('-1 month'))}}">
                    </div>
                    <div class="col-md-3 mb-1">
                        <label>Sampai Tanggal</label>
                        <input type="date" class="form-control form-filter" id="end_date" data-filter="end_date" value="{{date('Y-m-d')}}">
                    </div>
                    <div class="col-md-3 mb-1">
                        <label>Type</label>
                        <select class="form-control form-filter" id="filter_type" data-filter="type">
                            <option value="">Semua</option>
                            <option value="expense">Pengeluaran</option>
                            <option value="income">Pemasukan</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="table" data-list="{{route('transaksi-kas.get_transaksi_kas')}}" data-url="{{route('transaksi-kas.index')}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Transaksi</th>
                                <th>Waktu</th>
                                <th>Type</th>
                                <th>Kategori</th>
                                <th>Akun</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>


<div class="modal fade text-left" id="modal" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-md"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form  id="form" name="form" data-index="{{route('satuan.index')}}" data-get-data="{{route('satuan.get_data')}}">
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" readonly />
                <input type="hidden" id="param" name="param" readonly />
                <input type="hidden" id="type" name="type" readonly />
                <div class="modal-body">
                    <div class="row">
                        <h5 id="title-show"></h5>
                        {{-- <div class="col-md-12">
                            <label for="waktu">Waktu<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <input id="waktu" name="waktu" type="text" placeholder="Waktu"  class="form-control" required>
                            </div>
                        </div> --}}
                        <div class="col-md-12">
                            <label for="">Akun</label>
                            <div class="form-group">
                               <select id="akun_kas_id" name="akun_kas_id" class="form-select" required>
                                    <option value="">Pilih</option>
                                    @foreach ($akun_kas as $dt)
                                        <option value="{{encrypt1($dt->id)}}">{{ $dt->nama_akun }} {{!empty($dt->nomor_rekening)?' - '.$dt->nomor_rekening:''}}</option>  
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">Kategori</label>
                            <div class="form-group">
                               <select id="kategori_kas_id" name="kategori_kas_id" class="form-select select2-kategori-kas" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="expense">Pengeluaran</option>
                                    <option value="income">Pemasukan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">Jumlah </label>
                            <div class="form-group">
                                <input id="jumlah" name="jumlah" type="text" placeholder="0"  class="form-control currency" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">Keterangan </label>
                            <div class="form-group">
                                <textarea id="keterangan" name="keterangan" type="text" placeholder="Keterangan" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                        <span class="">Close</span>
                    </button>
                    <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/keuangan/transaksi_kas_list.js')}}"></script>
@endsection
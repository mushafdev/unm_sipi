@extends('a.layouts.master')
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
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Room</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nama Room" required>
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">Kode</label>
                                    <input type="text" name="code" class="form-control" placeholder="Kode" required>
                                </div>

                                <div class="mb-3">
                                    <label for="lantai" class="form-label">Lantai</label>
                                    <input type="text" name="lantai" class="form-control" placeholder="Lantai" maxlength="2" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" placeholder="Deskripsi"></textarea>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Bed</h5>
                                    <button type="button" class="btn btn-success mb-3" id="add-bed">+ Tambah Bed</button>

                                </div>
                                <div id="beds">
                                    <div class="row g-2 mb-2 bed-group">
                                        <div class="col-md-4">
                                            <input type="text" name="beds[0][bed_number]" class="form-control" placeholder="Nomor Bed" required>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="beds[0][status]" class="form-select">
                                                <option value="available">Available</option>
                                                <option value="occupied">Occupied</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="beds[0][notes]" class="form-control" placeholder="Catatan">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger remove-bed"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan</button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
<script src="{{asset('app/assets/pages/rooms/rooms.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
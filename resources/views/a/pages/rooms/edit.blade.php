@extends('a.layouts.master')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{ $title }}</h3>
            <p class="text-subtitle text-muted">Edit data room dan bed</p>
        </div>
    </div>
</div>
<div class="page-content">
    <section class="section">
        <form action="{{ route('rooms.update', encrypt0($room->id)) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Room</label>
                                <input type="text" name="name" class="form-control" value="{{ $room->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="code" class="form-label">Kode</label>
                                <input type="text" name="code" class="form-control" value="{{ $room->code }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="lantai" class="form-label">Lantai</label>
                                <input type="text" name="lantai" class="form-control" maxlength="2" value="{{ $room->lantai }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control">{{ $room->description }}</textarea>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Bed</h5>
                                <button type="button" class="btn btn-success mb-3" id="add-bed">+ Tambah Bed</button>
                            </div>
                            <div id="beds">
                                @foreach($room->beds as $i => $bed)
                                <div class="row g-2 mb-2 bed-group">
                                    <input type="hidden" name="beds[{{ $i }}][id]" value="{{ $bed->id }}">
                                    <div class="col-md-4">
                                        <input type="text" name="beds[{{ $i }}][bed_number]" class="form-control" placeholder="Nomor Bed" value="{{ $bed->bed_number }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="beds[{{ $i }}][status]" class="form-select">
                                            <option value="available" {{ $bed->status == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="occupied" {{ $bed->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                            <option value="maintenance" {{ $bed->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="beds[{{ $i }}][notes]" class="form-control" placeholder="Catatan" value="{{ $bed->notes }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger remove-bed"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Update</button>
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

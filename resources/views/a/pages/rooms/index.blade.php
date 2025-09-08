@extends('a.layouts.master')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{ $title }}</h3>
            <p class="text-subtitle text-muted">Daftar ruangan dan tempat tidur</p>
        </div>
    </div>
</div>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Data {{ $title }}</h5>
                </div>
                <div>
                    @can('room-create')
                    <a href="{{ route('rooms.create') }}" class="btn icon icon-left btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table" data-list="{{ route('rooms.get_rooms') }}" data-url="{{ route('rooms.index') }}">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Lantai</th>
                                <th>Jumlah Bed</th>
                                <th>Deskripsi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $index => $room)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $room->name }}</td>
                                <td>{{ $room->code }}</td>
                                <td>{{ $room->lantai }}</td>
                                <td>{{ $room->beds->count() }}</td>
                                <td>{{ $room->description }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><h6 class="dropdown-header">Action</h6></li>
                                            @can('room-edit')
                                            <li>
                                                <a href="{{ route('rooms.edit', encrypt0($room->id)) }}" title="Edit" class="dropdown-item">
                                                    <i class="icon-mid bi bi-pencil-square me-2" aria-hidden="true"></i> Edit
                                                </a>
                                            </li>
                                            @endcan
                                            @can('room-delete')
                                            <li>
                                                <form action="{{ route('rooms.destroy', encrypt0($room->id)) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="icon-mid bi bi-trash me-2" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

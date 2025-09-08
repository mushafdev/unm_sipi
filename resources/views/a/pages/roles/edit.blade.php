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
        <form name="form" id="form" data-update="{{route('roles.update',encrypt0($role->id))}}" data-index="{{route('roles.index')}}" enctype="multipart/form-data" method="POST">
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Info Role</h5>
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama Role<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="{{$role->name}}" required>
                                    </div>

                                    <h5>Hak Akses</h5>
                                    <table class="permissionTable table">
                                        <thead>
                                            <th class="">
                                                {{__('Menu')}}
                                            </th>
                                  
                                            <th class="">
                                                <label>
                                                    <input class="grand_selectall form-check-input" type="checkbox">
                                                    {{__('Pilih Semua') }}
                                                </label>
                                            </th>
                                  
                                            <th class="">
                                                {{__("Hak Akses Tersedia")}}
                                            </th>
                                        </thead>
                              
                              
                              
                                        <tbody>
                                        @foreach($permissions as $key => $group)
                                            <tr class="">
                                                <td class="">
                                                    <b>{{ ucfirst($key) }}</b>
                                                </td>
                                                <td class="" width="30%">
                                                    <label>
                                                        <input class="selectall form-check-input" type="checkbox">
                                                        {{__('Pilih Semua') }}
                                                    </label>
                                                </td>
                                                <td class="">
                              
                                                    @forelse($group as $permission)
                              
                                                    <label class="">
                                                        <input name="permissions[]" {{ $role->permissions->contains('id',$permission->id) ? "checked" : "" }}  class="permissioncheckbox form-check-input" type="checkbox" value="{{ $permission->id }}">
                                                        @php
                                                            $permission_name = explode('-', $permission->name);
                                                        @endphp
                                                        {{$permission_name[1]}} &nbsp;&nbsp;
                                                    </label>
                              
                                                    @empty
                                                        {{ __("No permission in this group !") }}
                                                    @endforelse
                              
                                                </td>
                              
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 text-end">
                                    <a href="{{route('roles.index')}}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                    <a href="{{route('roles.create')}}" class="btn icon icon-left btn-success">
                                        <i class="bi bi-plus-circle"></i> Tambah</a>
                                    <button type="button" id="update" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/roles/roles.js')}}?v={{identity()['assets_version']}}"></script>
@endsection
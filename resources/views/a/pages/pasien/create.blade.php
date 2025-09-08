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
        <form name="form" id="form" data-store="{{route('pasien.store')}}" data-index="{{route('pasien.index')}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img src="{{asset('app/assets/compiled/jpg/3.jpg')}}" id="photo-show" alt="Avatar">
                                <label for="photo" role="button">
                                    <i class="bi bi-pencil"></i>
                                </label>
                                <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="no_rm" class="form-label">No. RM</label>
                                        <input type="text" name="no_rm" id="no_rm" class="form-control bg-light-warning text-danger" placeholder="No. RM" value="">
                                        <small class="text-info">* No. RM otomatis jika dikosongkan</small>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama Lengkap<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="panggilan" class="form-label">Nama Panggilan<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="panggilan" id="panggilan" class="form-control" placeholder="Nama Panggilan" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gelar_depan" class="form-label">Gelar Depan</label>
                                        <input type="text" name="gelar_depan" id="gelar_depan" class="form-control" placeholder="Gelar Depan" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                                        <input type="text" name="gelar_belakang" id="gelar_belakang" class="form-control" placeholder="Gelar Belakang" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Display Panggilan</label>
                                        <div class="d-flex mt-2">
                                            @foreach ($display_namas as $dt )
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="radio" name="display_nama" id="display_{{$dt->value}}" value="{{$dt->value}}" {{$dt->value=='l'?'checked':''}}>
                                                    <label class="form-check-label fw-normal" for="display_{{$dt->value}}">
                                                        {{$dt->label()}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir <span class="text-danger ms-0">*</span></label>
                                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" placeholder="" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" name="nik" id="nik" class="form-control" placeholder="NIK" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis_kelamin" class="form-label" >Jenis Kelamin<span class="text-danger ms-0">*</span></label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                            <option value="">Pilih</option>
                                            @foreach ($jenis_kelamins as $dt )
                                            <option value="{{$dt->value}}">{{$dt->label()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="agama_id" class="form-label" >Agama<span class="text-danger ms-0">*</span></label>
                                        <select name="agama_id" id="agama_id" class="form-control select2" required data-parsley-errors-container="#agama-errors">
                                            <option value="">Pilih</option>
                                            @foreach ($agamas as $dt )
                                                <option value="{{$dt->id}}">{{$dt->agama}}</option>
                                            @endforeach
                                        </select>
                                        <span id="agama-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status_kawin" class="form-label" >Status Kawin</label>
                                        <select name="status_kawin" id="status_kawin" class="form-control">
                                            <option value="">Pilih</option>
                                            @foreach ($status_kawins as $dt )
                                                <option value="{{$dt->value}}">{{$dt->label()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="golongan_darah" class="form-label" >Golongan Darah</label>
                                        <select name="golongan_darah" id="golongan_darah" class="form-control">
                                            <option value="">Pilih</option>
                                            @foreach ($golongan_darahs as $dt )
                                                <option value="{{$dt->value}}">{{$dt->label()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pendidikan_id" class="form-label" >Pendidikan Terakhir</label>
                                        <select name="pendidikan_id" id="pendidikan_id" class="form-control select2" data-parsley-errors-container="#pendidikan-errors" >
                                            <option value="">Pilih</option>
                                            @foreach ($pendidikans as $dt )
                                                <option value="{{$dt->id}}">{{$dt->pendidikan}}</option>
                                            @endforeach
                                        </select>
                                        <span id="pendidikan-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pekerjaan_id" class="form-label" >Pekerjaan</label>
                                        <select name="pekerjaan_id" id="pekerjaan_id" class="form-control select2" data-parsley-errors-container="#pekerjaan-errors">
                                            <option value="">Pilih</option>
                                            @foreach ($pekerjaans as $dt )
                                                <option value="{{$dt->id}}">{{$dt->pekerjaan}}</option>
                                            @endforeach
                                        </select>
                                        <span id="pekerjaan-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_hp" class="form-label">No.HP<span class="text-danger ms-0">*</span></label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="085xxxxxxxxx" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="user@email.com" value="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat<span class="text-danger ms-0">*</span></label>
                                        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prov_id" class="form-label" >Provinsi</label>
                                        <select name="prov_id" id="prov_id" class="form-control select2-prov" data-parsley-errors-container="#prov-errors">
                                            <option value="">Pilih</option>
                                        </select>
                                        <span id="prov-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kab_id" class="form-label" >Kabupaten</label>
                                        <select name="kab_id" id="kab_id" class="form-control select2-kab" data-parsley-errors-container="#kab-errors">
                                            <option value="">Pilih</option>
                                        </select>
                                        <span id="kab-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kec_id" class="form-label" >Kecamatan</label>
                                        <select name="kec_id" id="kec_id" class="form-control select2-kec" data-parsley-errors-container="#kec-errors">
                                            <option value="">Pilih</option>
                                        </select>
                                        <span id="kec-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kel_id" class="form-label" >Kelurahan/Desa</label>
                                        <select name="kel_id" id="kel_id" class="form-control select2-kel" data-parsley-errors-container="#kel-errors">
                                            <option value="">Pilih</option>
                                        </select>
                                        <span id="kel-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_pos" class="form-label" >Kode Pos</label>
                                        <input type="text" name="kode_pos" id="kode_pos" placeholder="Kode Pos" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keterangan" class="form-label" >Keterangan</label>
                                        <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tags" class="form-label" >Tag</label>
                                        <select name="tags[]" id="tags" class="form-control select2-tag" multiple data-parsley-errors-container="#tag-errors">
                                            <option value="">Pilih</option>
                                        </select>
                                        <span id="tag-errors"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <a href="{{ session('back_url', url('/')) }}" class="btn icon icon-left btn-light"><i class="bi bi-arrow-left-square"></i> Kembali</a>
                                    <button type="button" id="save" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

<script src="{{asset('app/assets/pages/pasien/pasien.js')}}?v={{identity()['assets_version']}}"></script>
@endsection

<link rel="stylesheet" href="{{ asset('app/assets/compiled/css/riwayat-pasien.css') }}">
<div class="card mb-3">
    <div class="card-body">
        <ul class="nav nav-tabs" id="pasienTab" role="tablist" data-riwayat="{{route('pasien.riwayat', encrypt0($pasien->id))}}" data-resep="{{route('pasien.resep', encrypt0($pasien->id))}}">
            <li class="nav-item">
                <a class="nav-link active" id="data-diri-tab" data-bs-toggle="tab" href="#data-diri" role="tab">Data Diri</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="riwayat-perawatan-tab" data-bs-toggle="tab" href="#riwayat-perawatan" role="tab">Riwayat Perawatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="riwayat-resep-tab" data-bs-toggle="tab" href="#riwayat-resep" role="tab">Resep</a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="pasienTabContent">
            <div class="tab-pane fade show active" id="data-diri" role="tabpanel">
                <!-- Data Pasien -->
                <div id="dataDiriContent">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th width="20%">No. RM</th>
                                <td class="no_rm">{{$pasien->no_rm}}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td class="nama">{{$pasien->nama}}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td class="tgl_lahir">{{tgl_indo($pasien->tgl_lahir)}}</td>
                            </tr>
                            <tr>
                                <th>Umur</th>
                                <td class="tgl_lahir">{{umur($pasien->tgl_lahir)}}</td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td class="no_hp">{{$pasien->no_hp}}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td class="nik">{{$pasien->nik}}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td class="jenis_kelamin">{{$pasien->jenis_kelamin}}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td class="alamat">{{$pasien->alamat}}</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="riwayat-perawatan" role="tabpanel">
                <div id="riwayatPerawatanContent">
                </div>
            </div>
            <div class="tab-pane fade" id="riwayat-resep" role="tabpanel">
                <div id="riwayatResepContent">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('app/assets/pages/pasien/pasien_info.js')}}?v={{identity()['assets_version']}}"></script>

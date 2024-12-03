<div class="modal fade text-left" id="modal-pembimbing" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Data Dosen </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table mb-0 display" data-list="{{route('dosen.get_dosen')}}" id="table-pembimbing">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Pangkat</th>
                                <th>Golongan</th>
                                <th>Jabatan</th>
                                <th>Prodi</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/component/pembimbing.js')}}"></script>
<div class="modal fade text-left" id="modal-logbook" data-get-data="{{route('data.logbook')}}" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Logbook </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 mb-5">
                        <h5 class="mb-3">Info Mahasiswa</h5>
                        <div class="row">
                            <div class="col-4">
                                <label>Nama</label>
                            </div>
                            <div class="col-8">
                                <h6 class="detail-nama">-</h6>
                            </div>
                            <div class="col-4">
                                <label>NIM</label>
                            </div>
                            <div class="col-8">
                                <h6 class="detail-nim">-</h6>
                            </div>
                            <div class="col-4">
                                <label>Prodi</label>
                            </div>
                            <div class="col-8">
                                <h6 class="detail-prodi">-</h6>
                            </div>
                            <div class="col-4">
                                <label>Kelas</label>
                            </div>
                            <div class="col-8">
                                <h6 class="detail-kelas">-</h6>
                            </div>
                            <div class="col-4">
                                <label>Lokasi PI</label>
                            </div>
                            <div class="col-8">
                                <h6 class="detail-lokasi_pi">-</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 class="mb-3">Kegiatan</h5>
                        <div class="list-group overflow-auto list-kegiatan" style="height:500px;" >
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                    <span class="d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/component/logbook.js')}}"></script>
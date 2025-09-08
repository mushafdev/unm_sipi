<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSearch" aria-labelledby="offcanvasSearchLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasSearchLabel">Advance Search</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_no_rm" class="form-label">No. RM</label>
                    <input type="text" name="f_no_rm" id="f_no_rm" class="form-control filter-form" placeholder="No. RM" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="f_nama" id="f_nama" class="form-control filter-form" placeholder="Nama Lengkap" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_panggilan" class="form-label">Nama Panggilan</label>
                    <input type="text" name="f_panggilan" id="f_panggilan" class="form-control filter-form" placeholder="Nama Panggilan" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_tgl_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="f_tgl_lahir" id="f_tgl_lahir" class="form-control filter-form" placeholder="" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_no_hp" class="form-label">No. HP</label>
                    <input type="text" name="f_no_hp" id="f_no_hp" class="form-control filter-form" placeholder="No. HP" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_no_hp" class="form-label">NIK</label>
                    <input type="text" name="f_no_hp" id="f_no_hp" class="form-control filter-form" placeholder="NIK" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="f_alamat" class="form-label">Alamat</label>
                    <textarea type="text" name="f_alamat" id="f_alamat" class="form-control filter-form" placeholder="Alamat" value=""></textarea>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <button type="button" id="search-pasien" class="btn btn-pink w-100" ><i class="bi bi-search"></i> Cari Pasien</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modal-result-search" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Hasil Pencarian </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tableCariPasien" class="table table-bordered table-hover" data-list="{{route('pasien.get_pasien')}}">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>Tgl. Lahir</th>
                                <th>No. HP</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                    <span class="">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('app/assets/pages/component/search_pasien.js')}}?v={{identity()['assets_version']}}"></script>
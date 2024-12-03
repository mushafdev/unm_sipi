<div class="modal fade text-left" id="modal-lokasi-pi" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Data Lokasi PI </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table mb-0 display" data-list="{{route('lokasi-pi.get_lokasi_pi')}}" id="table-lokasi-pi">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kota</th>
                                <th>Alamat</th>
                                <th>Kebutuhan Pekerjaan</th>
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

<script src="{{asset('app/assets/pages/component/lokasi_pi.js')}}"></script>
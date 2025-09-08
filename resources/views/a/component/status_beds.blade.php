<link rel="stylesheet" href="{{asset('app/assets/compiled/css/beds.css')}}?v={{identity()['assets_version']}}">
<div class="modal fade text-left" id="modalBed" data-update="{{route('perawatan.bed.update')}}" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Pilih Bed </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                        <div class="p-0">
                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Status:</label>
                                        <select class="form-select form-select-sm" id="statusFilter">
                                            <option value="all">Semua</option>
                                            <option value="available">Tersedia</option>
                                            <option value="occupied">Digunakan</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-none">
                                        <label class="form-label small fw-bold">Treatment:</label>
                                        <select class="form-select form-select-sm" id="treatmentFilter">
                                            <option value="all">Semua</option>
                                            <option value="facial">Facial</option>
                                            <option value="laser">Laser</option>
                                            <option value="massage">Massage</option>
                                            <option value="injection">Injection</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Lantai:</label>
                                        <select class="form-select form-select-sm" id="floorFilter">
                                            <option value="all">Semua</option>
                                            <option value="1">Lantai 1</option>
                                            <option value="2">Lantai 2</option>
                                            <option value="3">Lantai 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="legend">
                                <div class="row">
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: linear-gradient(135deg, #28a745, #28a745);"></div>
                                            <span>Tersedia</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: linear-gradient(135deg, #dc3545, #dc3545);"></div>
                                            <span>Digunakan</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: linear-gradient(135deg, #fd7e14, #fd7e14);"></div>
                                            <span>Maintenance</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Floors and Rooms -->
                            <div id="floorsContainer">
                                <!-- Content will be generated by JavaScript -->
                            </div>
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

<script src="{{asset('app/assets/static/js/helper/beds.js')}}?v={{ identity()['assets_version'] }}"></script>
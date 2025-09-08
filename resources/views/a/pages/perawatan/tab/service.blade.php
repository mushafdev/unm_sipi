<link rel="stylesheet" href="{{asset('app/assets/compiled/css/perawatan-items.css')}}?v={{identity()['assets_version']}}">
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-item-center">
                <h5 class="mb-0">Daftar Tindakan</h5>
                
                @if (!in_array($pendaftaran->status, ['Pembayaran', 'Selesai']))
                    <button class="btn btn-success" type="button" id="add-service"><i class="bi bi-plus-circle me-1"></i>Tambah</button>
                @endif
            </div>
            <div class="card-body" id="service-list" data-url="{{route('perawatan.service.data',$id)}}">
            
            </div>
        
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modalService" tabindex="-1" role="dialog"
    aria-labelledby="modalAdd" aria-hidden="true">
    <div class="modal-dialog modal-md"
        role="document">
        <div class="modal-content">
            <div class="modal-load">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="form-title">Form </h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form  id="form-service" name="form-service" data-index="{{route('perawatan.index')}}" data-store="{{route('perawatan.service.store')}}" data-id="{{$id}}">
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" readonly />
                <input type="hidden" id="param" name="param" readonly />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="tindakan">Tindakan<span class="text-danger ms-0">*</span> </label>
                            <div class="form-group">
                                <select class="form-select select2-tindakan" id="tindakan" name="tindakan" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="harga">Harga</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="0" aria-label="harga" name="harga" id="harga" aria-describedby="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label for="diskon">Diskon</label>
                            <div class="input-group">
                                <select class="input-group-text" id="diskonType">
                                    <option value="percentage">Persen (%)</option>
                                    <option value="rupiah">Rupiah (Rp)</option>
                                </select>
                                <input type="text" class="form-control currency" id="diskon" name="diskon" placeholder="0">
                            </div>
                        </div>
                        <div class="col-5 d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <button type="button" class="btn-qty btn-dec"><i class="bi bi-dash"></i></button>
                                </div>
                                <input type="number" class="form-control mx-2 text-center" id="qty" name="qty" placeholder="0" min="1" value="1">
                                <div>
                                    <button type="button" class="btn-qty btn-inc"><i class="bi bi-plus"></i></button>
                                </div>
                        </div>
                        <div class="col-7 text-end mt-3">
                            {{-- <p class="mb-0">Sub Total </p> --}}
                            <span><span class="text-secondary me-2">Sub Total</span><br><span class="h3 sub-total">Rp.100000</span></span>
                        </div>
                        <div class="col-md-12">
                            <label for="dokter">Dokter</label>
                            <div class="form-group">
                                <select class="form-select select2-dokter" id="dokters" name="dokters" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="perawat">Perawat</label>
                            <div class="form-group">
                                <select class="form-select select2-perawat" id="perawats" name="perawats" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="catatan">Catatan</label>
                            <div class="form-group">
                                <textarea class="form-control" id="catatan" name="catatan" cols="30" rows="3"></textarea>
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
                    <button type="button" id="save-service" class="btn icon icon-left btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{asset('app/assets/pages/perawatan/tab/service.js')}}?v={{identity()['assets_version']}}"></script>

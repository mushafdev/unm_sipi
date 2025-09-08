<div id="tab-resep" class="resep-section" 
     data-store="{{ route('perawatan.resep.store') }}" 
     data-delete="{{ route('perawatan.resep.delete', ['id' => '__ID__' ]) }}" 
     data-dokter-id="{{ $dokters->first()->id ?? '' }}" 
     data-dokter-nama="{{ $dokters->first()->nama ?? '-' }}">

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-header">
                    <h5 class="mb-3">Resep Dokter</h5>
                </div>
                <div class="card-body">

                        <form id="form-resep" class="border p-3">
                            <input type="hidden" name="pendaftaran_id" id="pendaftaran_id" value="{{ $pendaftaranId }}">
                            <div class="mb-2">
                                <label class="form-label">Dokter</label>
                                <select name="dokter_id" class="form-select select2-dokter" required>
                                    @if ($reseps->first())
                                            <option  value="{{ encrypt1($reseps->first()->dokter_id )}}" selected>
                                                {{ $reseps->first()->nama_dokter ?? '' }}
                                            </option>
                                        @endif
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Isi Resep</label>
                                <textarea name="resep" rows="4" class="form-control" required>{{ $reseps->first()->resep ?? '' }}</textarea>
                            </div>

                            @if (!in_array($pendaftaran->status, ['Pembayaran', 'Selesai']))
                                <button id="save-resep" class="btn btn-primary icon icon-left" type="button"><i class="bi bi-save me-1"></i>Simpan Resep</button>

                                @if($reseps->isNotEmpty())
                                    <button id="deleteResepBtn" class="btn btn-outline-danger ms-2" type="button" data-id="{{ encrypt0($reseps->first()->id) }}">
                                        <i class="bi bi-trash"></i> Hapus Resep
                                    </button>
                                @endif
                            @endif
                            
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('app/assets/pages/perawatan/tab/resep.js')}}?v={{identity()['assets_version']}}"></script>

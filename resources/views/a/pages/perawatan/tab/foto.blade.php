<link rel="stylesheet" href="{{asset('app/assets/compiled/css/perawatan-foto.css')}}?v={{identity()['assets_version']}}">
<div class="card mb-3" id="tab-foto" data-list='@json($fotoList)' data-upload="{{ route('perawatan.foto.upload') }}" data-delete="{{ route('perawatan.foto.delete') }}">
    <div class="card-body">
        <input type="hidden" name="pendaftaran_id" value="{{ $id }}">
        <input type="hidden" name="x" value="{{ decrypt0($id) }}">
        <div class="photo-slots">
            @foreach (\App\Enums\FotoBeforeEnum::cases() as $posisi)
                <div class="photo-slot" id="photoSlot_{{ $posisi->value }}" data-position="{{ $posisi->value }}">
                    <div class="upload-placeholder">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">{{ $posisi->label() }}</div>
                        <div class="upload-subtext">Klik untuk upload</div>
                    </div>
                    <input type="file" id="photoInput_{{ $posisi->value }}" name="foto_{{ $posisi->value }}"
                           accept="image/*" style="display: none;" data-position="{{ $posisi->value }}">
                </div>
            @endforeach
        </div>

        <div class="photo-actions mt-3">
            <div class="photo-counter">
                <span id="photoCount">0</span>/{{ count(\App\Enums\FotoBeforeEnum::cases()) }} foto terupload
            </div>
        </div>
    </div>
</div>
<script src="{{asset('app/assets/pages/perawatan/tab/foto.js')}}?v={{identity()['assets_version']}}"></script>




<!-- Services Section -->
@if ($services->count())
    <div class="content-section">
        <h6 class="section-title">Layanan Diberikan</h6>
            @foreach ($services as $service)
                <div class="service-grid">
                    <div class="service-item w-100 ps-0">
                        <div class="service-name">
                            {{ $service->service }}
                            <span class="qty-badge">
                                {{ fmod($service->qty, 1) == 0 ? number_format($service->qty, 0) : rtrim(rtrim(number_format($service->qty, 2, '.', ''), '0'), '.') }}x
                            </span>
                        </div>
                        <div class="service-staff">
                            @foreach ($service->dokters as $dokter)
                                <span class="staff-tag">👩‍⚕️ {{ $dokter->dokter_nama }}</span>
                            @endforeach
                            @foreach ($service->karyawans as $karyawan)
                                <span class="staff-tag">💆‍♀️ {{ $karyawan->karyawan_nama }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if (!empty($service->catatan))
                        <div class="notes-box w-100">
                            <p class="mb-0">{{ $service->catatan }}</p>
                        </div>    
                    @endif
                    
                </div>
            @endforeach
    </div>    
@endif



<!-- Prescription Section -->
@if($resep->count())
    <div class="content-section">
        <h6 class="section-title">Resep Dokter</h6>
        <ul class="list-unstyled">
            @foreach ($resep as $r)
                <li><strong>{{ $r->dokter_nama ?? 'Tanpa Nama Dokter' }}:</strong> <br>
                        {!! nl2br(e($r->resep)) !!} 
                </li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Products Section -->
@if ($items->count())
    <div class="content-section">
        <h6 class="section-title">Produk Digunakan</h6>
        <div class="product-list">
            @foreach ($items as $item)
            <div class="product-item">
                <span class="product-name">{{ $item->item }}</span>
                <span class="qty-badge">
                    {{ fmod($item->qty, 1) == 0 ? number_format($item->qty, 0) : rtrim(rtrim(number_format($item->qty, 2, '.', ''), '0'), '.') }} {{$item->satuan}}
                </span>
            </div>
            @endforeach
        </div>
    </div>    
@endif

@php use App\Enums\FotoBeforeEnum; @endphp
<!-- Photos Section -->
@if ($fotos->count())
    <div class="content-section">
        <h6 class="section-title">Dokumentasi Hasil</h6>
        <div class="photo-gallery">
            @foreach ($fotos as $foto)
            <div class="photo-container">
                <img src="{{ asset('images/foto_before/'.$foto->pendaftaran_id.'/'. $foto->foto) }}" alt="{{ $foto->label ?? 'Dokumentasi' }}" class="photo-thumbnail">
                <div class="photo-label">{{ FotoBeforeEnum::tryFrom($foto->position)?->label() ?? 'Tanpa Label' }}</div>
            </div>
            @endforeach
        </div>
    </div>    
@endif
@if ($pendaftaran->old=='Y' && identity()['migration']=='Y')
<div class="row">
    <div class="col-md-12">
        <div class="my-3 text-end">
            <button class="btn btn-outline-primary btn-action edit-riwayat" data-id="{{encrypt0($pendaftaran->id)}}" type="button">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-outline-danger btn-action delete-riwayat" data-id="{{encrypt0($pendaftaran->id)}}" type="button">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>    
@endif



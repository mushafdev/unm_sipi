
@if ($resep->count())
<div class="row" id="listRiwayatResep" >
    <div class="col-md-12">
            <!-- Patient History 1 -->
        @foreach ($resep as $k => $kunj)
            <div class="patient-card fade-in" style="animation-delay: {{ $k * 0.1 }}s">
                <button class="collapse-header" type="button" data-bs-toggle="collapse" data-bs-target="#XFRR{{ $kunj->id }}RRXZ">
                    <div class="visit-info">
                        <div class="visit-date"><i class="bi bi-calendar"></i> {{ tgl_indo($kunj->tgl_pendaftaran) }}</div>
                        
                        <div class="visit-treatment">{{ $kunj->dokter }}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="chevron"><i class="bi bi-chevron-down"></i></span>
                    </div>
                </button>
                <div class="collapse" id="XFRR{{ $kunj->id }}RRXZ">
                    <div class="collapse-content" data-loaded="false" data-id="{{ encrypt0($kunj->id) }}">
                        <div class="text-dark m-3">
                        {!! nl2br(e($kunj->resep)) !!} 
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-md-12">
        <div class="mt-3 pagination-wrapper text-center">
            {{ $resep->links() }}
        </div>

    </div>
</div>

@else
<div class="text-center text-secondary py-5">
    <i class="bi bi-journal-text fs-1"></i>
    <p class="mt-3">Tidak ada riwayat resep ditemukan.</p>
</div>
@endif

<script src="{{ asset('app/assets/pages/component/riwayat_resep.js') }}?v={{ identity()['assets_version'] }}"></script>


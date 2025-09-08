@if ($riwayat->count())
<div class="row" id="listRiwayat" data-detail="{{ route('pasien.riwayat.detail', '__ID__') }}">
    <div class="col-md-12">
            <!-- Patient History 1 -->
        @foreach ($riwayat as $k => $kunj)
            <div class="patient-card fade-in" style="animation-delay: {{ $k * 0.1 }}s">
                <button class="collapse-header" type="button" data-bs-toggle="collapse" data-bs-target="#DFRP{{ $kunj->id }}RPXY">
                    <div class="visit-info">
                        <div class="visit-date"><i class="bi bi-calendar"></i> {{ tgl_indo($kunj->tgl_pendaftaran) }}</div>
                        @if (!empty($kunj->sumber_penjualan))
                        <div class="visit-treatment">{{ $kunj->sumber_penjualan }}</div>
                        @else
                        <div class="visit-treatment">{{ 'Walk in' }}</div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="chevron"><i class="bi bi-chevron-down"></i></span>
                    </div>
                </button>
                <div class="collapse" id="DFRP{{ $kunj->id }}RPXY">
                    <div class="collapse-content" data-loaded="false" data-id="{{ encrypt0($kunj->id) }}">
                        <div class="text-muted small m-3">Sedang memuat data...</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-md-12">
        <div class="mt-3 pagination-wrapper text-center">
            {{ $riwayat->links() }}
        </div>

    </div>
</div>
@else
    <div class="text-center text-secondary py-5">
        <i class="bi bi-journal-text fs-1"></i>
        <p class="mt-3">Tidak ada riwayat Pasien ditemukan.</p>
    </div>
@endif

<script src="{{ asset('app/assets/pages/component/riwayat_pasien.js') }}?v={{ identity()['assets_version'] }}"></script>


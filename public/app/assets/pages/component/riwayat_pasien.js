$(document).ready(function () {
    const urlTemplate = $('#listRiwayat').data('detail'); // contoh: "/pasien/riwayat/__ID__/detail"

    $('#listRiwayat .collapse').on('show.bs.collapse', function () {
        const $contentEl = $(this).find('.collapse-content');
        const isLoaded = $contentEl.attr('data-loaded');
        const encId = $contentEl.data('id');

        if (isLoaded === 'false') {
            $contentEl.html('<div class="text-center w-100 py-5"><div class="spinner-border text-primary" role="status"></div></div>');

            const fetchUrl = urlTemplate.replace('__ID__', encId);

            $.get(fetchUrl)
                .done(function (html) {
                    $contentEl.html(html);
                    $contentEl.attr('data-loaded', 'true');
                })
                .fail(function () {
                    $contentEl.html('<div class="text-danger small m-3">Gagal memuat data.</div>');
                });
        }
    });

    // Tambahan animasi aktif
    $('listRiwayat .collapse-header').on('click', function () {
        const targetId = $(this).data('bs-target');
        $('listRiwayat .collapse-header').removeClass('active');
        if (!$(targetId).hasClass('show')) {
            $(this).addClass('active');
        }
    });

    // Optional: image zoom
    $(document).on('click', '.photo-thumbnail', function () {
        const overlay = $('<div>').css({
            position: 'fixed',
            top: 0, left: 0,
            width: '100%', height: '100%',
            background: 'rgba(0,0,0,0.8)',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            zIndex: 9999,
            cursor: 'pointer'
        });

        const enlargedImg = $('<img>').attr('src', this.src).css({
            maxWidth: '90%',
            maxHeight: '90%',
            borderRadius: '15px',
            boxShadow: '0 20px 60px rgba(0,0,0,0.5)'
        });

        overlay.append(enlargedImg);
        $('body').append(overlay);

        overlay.on('click', function () {
            $(this).remove();
        });
    });
});
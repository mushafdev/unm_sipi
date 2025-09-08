window.initPasienTab = function () {
    window.riwayatPasienUrl = $('#pasienTab').data('riwayat');
    window.riwayatResepUrl = $('#pasienTab').data('resep');

    $('#riwayat-perawatan-tab').on('shown.bs.tab', function () {
        if (!$('#riwayatPerawatanContent').hasClass('loaded')) {
            loadRiwayat(window.riwayatPasienUrl);
        }
    });

    $('#riwayat-resep-tab').on('shown.bs.tab', function () {
        if (!$('#riwayatResepContent').hasClass('loaded')) {
            loadResep(window.riwayatResepUrl);
        }
    });

    
    window.loadRiwayat = function (url) {
        $('#riwayatPerawatanContent').html('<div class="text-center w-100 py-5"><div class="spinner-border text-primary" role="status"></div></div>');
            
        $.get(url, function (html) {
            $('#riwayatPerawatanContent').html(html).addClass('loaded');
        }).fail(function () {
            $('#riwayatPerawatanContent').html('<div class="text-danger">Gagal memuat riwayat.</div>');
        });
    };

    window.loadResep = function (url) {
        $('#riwayatResepContent').html('<div class="text-center w-100 py-5"><div class="spinner-border text-primary" role="status"></div></div>');
            
        $.get(url, function (html) {
            $('#riwayatResepContent').html(html).addClass('loaded');
        }).fail(function () {
            $('#riwayatResepContent').html('<div class="text-danger">Gagal memuat riwayat.</div>');
        });
    };

    // Handler klik pagination di dalam riwayatPerawatanContent
    // $(document).on('click', '#riwayatPerawatanContent .pagination-wrapper a', function (e) {
    //     e.preventDefault();
    //     loadRiwayat(window.riwayatPasienUrl);
    // });

    // $(document).on('click', '#riwayatResepContent .pagination-wrapper a', function (e) {
    //     e.preventDefault();
    //     loadResep(window.riwayatResepUrl);
    // });

};
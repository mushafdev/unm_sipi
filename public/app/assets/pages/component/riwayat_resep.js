
$(document).ready(function () {

    // Tambahan animasi aktif
    $('#listRiwayatResep .collapse-header').on('click', function () {
        const targetId = $(this).data('bs-target');
        $('#listRiwayatResep .collapse-header').removeClass('active');
        if (!$(targetId).hasClass('show')) {
            $(this).addClass('active');
        }
    });

});
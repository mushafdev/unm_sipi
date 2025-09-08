var form = $("#form");
var pendaftaranId = form.data('id');
var urlIndex = form.data('index');

function updateGrandTotal() {
    const url = urlIndex + '/total/' + pendaftaranId;

    $.get(url, function (res) {
        const total = parseInt(res.total || 0);
        $('#grand_total').text('Rp. ' + total.toLocaleString('id-ID'));
    });
}

function initTabScript(tabType) {
    switch (tabType) {
        case 'foto':
            if (typeof initFotoTab === 'function') {
                initFotoTab();
            }
            break;
        case 'item':
            if (typeof initItemTab === 'function') {
                initItemTab();
            }
            break;
        case 'service':
            if (typeof initServiceTab === 'function') {
                initServiceTab();
            }
            break;
        case 'pasien':
            if (typeof initPasienTab === 'function') {
                initPasienTab();
            }
            break;
        case 'resep':
            if (typeof initResepTab === 'function') {
                initResepTab();
            }
            break;
        default:
            break;
    }
}

function loadTabContent(url, tabType) {
    $("#tabContent").html('<div class="text-center w-100 py-5"><div class="spinner-border text-primary" role="status"></div></div>');
    $.ajax({
        url: url,
        method: 'GET',
        success: function (response) {
            $('#tabContent').html(response);

            // Inisialisasi script sesuai tab
            initTabScript(tabType);
        },
        error: function () {
            $('#tabContent').html('<div class="alert alert-danger">Gagal memuat data.</div>');
        }
    });
}

function initTab(tabType) {
    $('.tab-perawatan').removeClass('active');
    $('.tab-perawatan[data-type="' + tabType + '"]').addClass('active');

    const tabMap = {
        'pasien': urlIndex + '/pasien/' + pendaftaranId,
        'foto': urlIndex + '/foto/' + pendaftaranId,
        'service': urlIndex + '/service/' + pendaftaranId,
        'item': urlIndex + '/item/' + pendaftaranId,
        'resep': urlIndex + '/resep/' + pendaftaranId,
    };

    if (tabMap[tabType]) {
        loadTabContent(tabMap[tabType], tabType);
    }
}

$(document).ready(function () {
    updateGrandTotal();

    const tabPerawatanVal = localStorage.getItem("filterTabPerawatan") || "pasien";
    initTab(tabPerawatanVal);

    $(document).on('click', '.tab-perawatan', function (e) {
        e.preventDefault();
        const tabType = $(this).data('type');
        localStorage.setItem("filterTabPerawatan", tabType);
        initTab(tabType);
    });

    $(document).on('click', '#pilih-bed', function (e) {
        e.preventDefault();
        loadBeds();
        $('#modalBed').modal('show');
    });

    $(document).on('click', '.bed-card.bed-available', function () {
        const bedId = $(this).data('id');
        const bedName = $(this).data('bed');
        const roomName = $(this).data('room');
        const floor = $(this).data('floor');
        const pendaftaranId = $('#form').data('id');

        Swal.fire({
            title: 'Yakin memilih bed ini?',
            html: `<div class="text-center h4">
                    Lantai ${floor}<br>${roomName}<br>${bedName}
                   </div>`,
            icon: "warning",
            allowOutsideClick: false,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Pilih Bed Ini',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                return $.ajax({
                    url: $("#modalBed").data('update'),
                    method: 'POST',
                    data: {
                        bed_id: bedId,
                        pendaftaran_id: pendaftaranId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(res => {
                    if (!res.success) {
                        throw new Error(res.message || 'Bed gagal dipilih.');
                    }
                    return res;
                }).catch(err => {
                    Swal.showValidationMessage(err.message);
                });
            }
        }).then(result => {
            if (result.isConfirmed && result.value && result.value.success) {
                location.reload();
            }
        });
    });

    $(document).on('click', '#next-payment', function () {
        const pendaftaranId = $('#form').data('id');

        Swal.fire({
            title: 'Apakah anda yakin ingin melanjutkan ke proses pembayaran?',
            icon: "warning",
            allowOutsideClick: false,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                return $.ajax({
                    url: $("#form").data('pembayaran'),
                    method: 'POST',
                    data: {
                        pendaftaran_id: pendaftaranId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(res => {
                    Swal.fire({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        timer: 2000
                    }).then(() => {
                        location.replace(urlIndex);
                    });
                }).catch(err => {
                    Swal.showValidationMessage(err.message);
                });
            }
        }).then(result => {
            if (result.isConfirmed && result.value && result.value.success) {
                location.reload();
            }
        });
    });

    $('.form-control').on('keyup change', function () {
        $(this).parsley().validate();
    });

    $(document).on("click", "#change-layanan", function () {
        let id = $(this).data("id");
        $("#pendaftaran_id").val(id);
        $("#modalUbahLayanan").modal("show");
    });

    $("#save-ubah-layanan").on("click", function(e) {
        e.preventDefault();

        if($("#form-ubah-layanan").parsley().validate()){
            var param = $(this);
            var form = $('#form-ubah-layanan')[0];
            var form_data = new FormData(form);

            $.ajax({
                type    : 'POST',
                data    : form_data,
                contentType : false,
                processData : false,
                cache: false,
                async: true,
                url     : $("#form-ubah-layanan").data("ubah-layanan"),
                beforeSend: function() {
                    process(param);
                },
                success : function(data) {
                    processDone(param,'bi bi-bag-check',' Ubah Layanan');
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                        timer: 2000
                    }).then((result) => {
                        $("#modalUbahLayanan").modal("hide");
                        location.reload();
                    });
                },
                error: function (reject) {
                    processDone(param,'bi bi-bag-check',' Ubah Layanan');
                    $('#form-ubah-layanan').parsley().reset();

                    if( reject.status === 422 ) {
                        var data = reject.responseText;
                        var jsonResponse = JSON.parse(data);
                        $.each(jsonResponse.errors, function(key, value) {
                            $('#'+key).parsley().addError('uniqueerror', {
                                message: value[0],
                                updateClass: true
                            });
                        });
                    } else {
                        Swal.fire("Error", "There is an error", "error");
                    }
                }
            });
        } else {
            return false;
        }
    });


});



const sisa=$('#sisa').val();
$('#addPayment').click(function () {
    $('#modalSisa').text(formatCurrency(sisa));
    $('#kembalianDisplay').text(formatCurrency(0));
    $('#bayarInput').val('');
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
});

$(document).on('click', '.metode-pembayaran-btn', function () {
    $('.metode-pembayaran-btn').removeClass('active btn-secondary').addClass('btn-outline-secondary');
    $(this).addClass('active btn-secondary').removeClass('btn-outline-secondary');
    selectedPaymentMethod = {
    id: $(this).data('id'),
    nama: $(this).data('nama')
    };
});

$(document).on('input', '#bayarInput', function () {
    const instBayar = AutoNumeric.getAutoNumericElement(
        document.querySelector('#bayarInput')
    );

    const bayarAutoNumeric = instBayar.getNumber();   
    const bayar = bayarAutoNumeric || 0;
    const kembali = bayar - sisa;
    $('#kembalianDisplay').text(formatCurrency(kembali > 0 ? kembali : 0));
});

$('#save-pembayaran').on('click', function () {
    const bayar = AutoNumeric.getAutoNumericElement(document.querySelector('#bayarInput')).getNumber();
    const kembalian = bayar - sisa;
    const metode_pembayaran_id = $('.metode-pembayaran-btn.active').data('id');

    if (!metode_pembayaran_id) {
        Swal.fire("Info", "Silahkan memilih metode pembayaran", "info");
        return;
    }

    $.ajax({
        url: $('#paymentModal').data('store'),
        type: 'POST',
        data: {
            _token: $('input[name="_token"]').val(),
            transaction_id: $('#transaction_id').val(),
            bayar: bayar,
            metode_pembayaran_id: metode_pembayaran_id,

        },
        success: function (response) {
            Swal.fire({
                    title: "Success!",
                    text: response.text,
                    icon: "success",
                    timer: 2000
            }).then((result) => {
                location.reload();

            });
        },
        error: function (xhr) {
             Swal.fire("Error", "Terjadi kesalahan", "error");
        }
    });
});

$(document).on( 'click', '.delete-pembayaran', function () {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Apakah anda yakin?',
    text: "Data ini akan dibatalkan permanen",
    icon: "warning",
    allowOutsideClick: false,
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Batalkan',
    cancelButtonText: 'Batal'
    }).then((result) => {
    if (result.value) {
        $.ajax({
            type    : 'POST',
            url     : $('#paymentModal').data('delete'),
            data    : {
                _token: $('input[name="_token"]').val(),
                id: id
            },
            success : function(data) {
                sweetalert2ToastTE('success','Success!',data.text);
                location.reload();
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
});


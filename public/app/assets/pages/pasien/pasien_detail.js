initPasienTab();
$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})

$(document).on('click', '#add-riwayat', function (e) {
    e.preventDefault();
    resetForm();
    $('#form-title').text('Tambah Riwayat');
    $('#modalAddRiwayat').modal('show');
    $('.modal-load').hide();
});


$('#add-tindakan-row').on('click', function () {
    let original = $('.tindakan-row').first();

    // 1. Hapus Select2 dari elemen asli SEBELUM dikloning
    original.find('.select2-tindakan').select2('destroy');
    original.find('.select2-dokter').select2('destroy');
    original.find('.select2-perawat').select2('destroy');

    // 2. Clone baris pertama
    let newRow = original.clone();

    // 3. Reset isi field
    newRow.find('select').val(null).trigger('change');
    newRow.find('textarea').val('');

    // 4. Tambahkan kembali Select2 ke elemen asli
    initSelect2Tindakan(original);
    initSelect2Dokter(original);
    initSelect2Perawat(original);

    // 5. Tambahkan Select2 ke row baru
    $('#tindakan-wrapper').append(newRow);
    initSelect2Tindakan(newRow);
    initSelect2Dokter(newRow);
    initSelect2Perawat(newRow);

    renumberRows();
});

$('#tindakan-wrapper').on('click', '.remove-row', function () {
    if ($('.tindakan-row').length > 1) {
        $(this).closest('.tindakan-row').remove();
    } else {
        Swal.fire("Error", "Minimal satu baris harus ada.", "error");
    }
     renumberRows();
});

$("#save-riwayat").on('click',function(e) {
    e.preventDefault();
    if($("#form-add-riwayat").parsley().validate()){
    var param = $(this);
    var form =  $('#form-add-riwayat')[0];
    var form_data = new FormData(form);
    ax_save = $.ajax({
        type    : 'POST',
        data    : form_data,
        contentType : false,
        processData : false,
        cache: false,
        async:true,
        url     : $('#form-add-riwayat').data('store'),
        beforeSend: function() {
            process(param);
        },
        success : function(data) {
        processDone(param,'bi bi-save',' Simpan');
        Swal.fire({
                title: "Success!",
                text: data.text,
                icon: "success",
                timer: 2000
        }).then((result) => {

            $('#modalAddRiwayat').modal('hide');
            loadRiwayat(window.riwayatPasienUrl);
            loadResep(window.riwayatResepUrl);

        });
        },
        error: function (reject) {
            processDone(param,'bi bi-save',' Simpan');
            $('#form-add-riwayat').parsley().reset();
            if( reject.status === 422 ) {
                var data=reject.responseText;
                var jsonResponse = JSON.parse(data);
                $.each( jsonResponse.errors, function( key, value) {
                    $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                });
            }else{
                Swal.fire("Error", "There is an error", "error");
            }
        }
    });
    }else {
    return false;
    }

});

$(document).on('click', '.edit-riwayat', function () {
    $('.modal-load').show();
    resetForm();
    $('#form-title').text('Edit Riwayat');

    const id = $(this).data("id");
    $('#modalAddRiwayat').modal('show');

    $.ajax({
        type    : 'GET',
        data    : {id:id},
        url     : $('#riwayatPerawatanContent').data('get-detail'),
        dataType: 'json',
        success : function(data) {
            // Isi data dasar
            $('#id').val(data.id);
            $('#param').val('edit');
            $('#tgl_pendaftaran').val(data.pendaftaran.tgl_pendaftaran);
            $('#jenis_layanan_id').val(data.pendaftaran.jenis_layanan_id).trigger('change');

            // Isi resep jika ada
            if (data.resep.length > 0) {
                const resep = data.resep[0];
                $('#resep_dokter_id').append(`<option value="${resep.dokter_id}" selected>${resep.dokter_nama}</option>`).trigger('change');
                $('#resep').val(resep.resep);
            }

            // Kosongkan dan isi ulang tindakan
            $('#tindakan-wrapper').empty();

            data.services.forEach(function(service, index) {
                let dokterOptions = '';
                service.dokters.forEach(function(dokter) {
                    dokterOptions += `<option value="${dokter.id}" selected>${dokter.dokter_nama}</option>`;
                });

                const tindakanRow = `
                    <div class="tindakan-row border p-3 mb-2">
                        <div class="row">
                            <div class="col-3">
                                <label>Tindakan <span class="text-danger">*</span></label>
                                <select class="form-select select2-tindakan" name="tindakan[]" required>
                                    <option value="${service.service_id}" selected>${service.service}</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label>Dokter</label>
                                <select class="form-select select2-dokter" name="dokters[${index}][]" multiple>
                                    ${dokterOptions}
                                </select>
                            </div>
                            <div class="col-4">
                                <label>Catatan</label>
                                <textarea class="form-control" name="catatan[${index}]" rows="2">${service.catatan ?? ''}</textarea>
                            </div>
                            <div class="col-1 text-end align-middle">
                                <span type="button" class="remove-row text-danger"><i class="bi bi-x-lg"></i></span>
                            </div>
                        </div>
                    </div>
                `;

                $('#tindakan-wrapper').append(tindakanRow);
            });

            // Re-init select2 pada row hasil edit
            $('#tindakan-wrapper .tindakan-row').each(function () {
                initSelect2Tindakan($(this));
                initSelect2Dokter($(this));
                initSelect2Perawat($(this)); // jika kamu pakai perawat juga
            });

            renumberRows(); // pastikan semua field dinamis sesuai index

            $('.modal-load').hide();
        },
        error : function() {
            $('.modal-load').hide();
            Swal.fire("Error", "Terjadi kesalahan saat memuat data", "error");
        }
    });
});

$(document).on('click', '.delete-riwayat', function () {
    const id = $(this).data("id"); // id terenkripsi

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data riwayat ini akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: $('#riwayatPerawatanContent').data('delete'), // pastikan ini rute untuk riwayat
                data: {
                    id:id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.text,
                        icon: 'success',
                        timer: 2000
                    });

                    // Refresh list riwayat dan resep
                    loadRiwayat(window.riwayatPasienUrl);
                    loadResep(window.riwayatResepUrl);
                },
                error: function (xhr) {
                    let msg = "Terjadi kesalahan";
                    if (xhr.status === 403) msg = "Akses ditolak";
                    if (xhr.status === 404) msg = "Data tidak ditemukan";
                    Swal.fire("Error", msg, "error");
                }
            });
        }
    });
});



function resetForm(){
     const $original = $('.tindakan-row').first();

    // 2. Destroy select2 di elemen ASLI sebelum cloning
    $original.find('select').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
    });

    // 3. Clone baris pertama
    const $clone = $original.clone();

    // 4. Bersihkan isi inputan di clone
    $clone.find('select').val(null).trigger('change');
    $clone.find('textarea').val('');

    // 5. Kosongkan wrapper & tambahkan baris baru
    $('#tindakan-wrapper').html('');
    $('#tindakan-wrapper').append($clone);

    initSelect2Tindakan('#modalAddRiwayat');
    initSelect2Dokter('#modalAddRiwayat');
    initSelect2Perawat('#modalAddRiwayat');

    const $form = $('#form-add-riwayat');
    $form.parsley().reset();
    $form[0].reset();

    $('#param').val('add');
    $('#id').val('');
    processDone($('#save'), 'bi bi-save', ' Simpan');

    $('#resep_dokter_id').val(null).trigger('change');
    $('#tindakan').val(null).trigger('change');
    $('#dokters').val(null).trigger('change');
    $('#perawats').val(null).trigger('change');

}

function renumberRows() {
    $('#tindakan-wrapper .tindakan-row').each(function (i) {
        $(this).find('select.select2-dokter').attr('name', 'dokters[' + i + '][]');
        $(this).find('textarea[name^="catatan"]').attr('name', 'catatan[' + i + ']');
    });
}

window.initResepTab = function () {
    
    const $tab = $('#tab-resep');
    if (!$tab.length || $tab.data('initialized')) return;
    $tab.data('initialized', true);

    const storeUrl = $tab.data('store');
    const deleteUrlTemplate = $tab.data('delete');
    const pendaftaranId = $('#pendaftaran_id').val();

    const $form = $('#form-resep');
    const $btnSave = $('#save-resep');

    initSelect2Dokter('#tab-resep');

    $($btnSave).on('click', function (e) {
        e.preventDefault();
        if ($form.parsley().validate()) {

            var form = $form[0];
            var form_data = new FormData(form);
            $.ajax({
               type    : 'POST',
               url     : storeUrl,
                data    : form_data,
                contentType : false,
                processData : false,
                cache: false,
                async:true,
                beforeSend: function () {
                    process($btnSave);
                },
                success: function (data) {
                    processDone($btnSave, 'bi bi-save', ' Simpan Resep');
                    sweetalert2ToastTE('success', 'Success!', data.message);
                    reloadResepTab();
                },
                error: function (reject) {
                    processDone($btnSave, 'bi bi-save', ' Simpan Resep');
                    form.parsley().reset();
                    if (reject.status === 422) {
                        const jsonResponse = JSON.parse(reject.responseText);
                        $.each(jsonResponse.errors, function (key, value) {
                            $('#' + key).parsley().addError('uniqueerror', { message: value[0], updateClass: true });
                        });
                    } else {
                        Swal.fire("Error", "Terjadi kesalahan", "error");
                    }
                }
            });
        }
    });

    $(document).off('click.deleteSingleResep').on('click.deleteSingleResep', '#deleteResepBtn', function () {
        const id = $(this).data('id');
        const url = deleteUrlTemplate.replace('__ID__', id);

        Swal.fire({
            title: 'Hapus Resep?',
            text: 'Data resep ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => $.ajax({ url, method: 'DELETE' })
        }).then(result => {
            if (result.isConfirmed && result.value?.success) {
                sweetalert2ToastTE('success', 'Berhasil!', result.value.message);
                reloadResepTab();
            }
        });
    });

    function reloadResepTab() {
        $.get(`/pelayanan/perawatan/resep/${pendaftaranId}`, function (html) {
            const $html = $('<div>').html(html);
            $('#tab-resep').replaceWith($html.find('#tab-resep'));
            initResepTab(); // re-init setelah replace
        });
    }
};

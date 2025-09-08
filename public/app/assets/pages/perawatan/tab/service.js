window.initServiceTab = function () {
    const form = $('#form-service');
    if (!form.length || form.data('initialized')) return;
    form.data('initialized', true);

    const urlStore = form.data('store');
    const urlIndex = form.data('index');
    const urlGetData = form.data('get-data');
    const pendaftaranId = form.data('id');

    initSelect2Tindakan('#modalService');
    initSelect2Dokter('#modalService');
    initSelect2Perawat('#modalService');

    const autoDiskon = new AutoNumeric('#diskon', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0,
        unformatOnSubmit: true,
        modifyValueOnWheel: false
    });

    function formatQty(qty) {
        const val = parseFloat(qty);
        return Number.isInteger(val) ? val : val.toFixed(2);
    }

    function setSelect2Value($select, id, text) {
        const option = new Option(text, id, true, true);
        $select.append(option).trigger('change');
    }

    function hitungSubtotal() {
        let harga = parseInt($('#harga').data('harga-number')) || 0;
        let diskon = autoDiskon.getNumber() || 0;
        let diskonType = $('#diskonType').val();
        let qty = parseInt($('#qty').val()) || 1;

        let hargaSetelahDiskon = harga;
        if (diskonType === 'percentage') {
            hargaSetelahDiskon -= (harga * diskon / 100);
        } else {
            hargaSetelahDiskon -= diskon;
        }

        if (hargaSetelahDiskon < 0) hargaSetelahDiskon = 0;

        let subtotal = hargaSetelahDiskon * qty;
        $('.sub-total').text(formatCurrency(subtotal));
    }

    function renderService(data) {
        const dokterList = data.dokters.map(nama => `<span class="staff-badge">${nama}</span>`).join('');
        const perawatList = data.perawats.map(nama => `<span class="staff-badge">${nama}</span>`).join('');
        const diskonFinal = data.diskon > 0 ? `<div class="price-final"><span class="discount-badge">-${data.diskon}%</span></div>` : '';
        const priceOriginal = (parseFloat(data.diskon) > 0 || parseFloat(data.diskon_rp) > 0)
            ? `<div class="price-original">Rp.${parseInt(data.harga_jual).toLocaleString()}</div>` : '';

        // Cek apakah status bukan 'pembayaran' atau 'selesai'
        const showActions = !['Pembayaran', 'Selesai'].includes(data.status);

        // Jika boleh, tampilkan tombol edit dan delete
        const actions = showActions ? `
            <div class="actions-container">
                <button class="btn btn-outline-primary btn-action edit-service" data-id="${data.id}" type="button">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-outline-danger btn-action delete-service" data-id="${data.id}" type="button">
                    <i class="bi bi-trash"></i>
                </button>
            </div>` : '';

        return `
        <div class="item-card" data-id="${data.id}">
            <div class="item-row">
                <div class="item-content">
                    <div>
                        <div class="item-title">${data.nama_tindakan}</div>
                        <div class="staff-sections">
                            <div class="staff-section"><span class="staff-label">Dokter:</span>${dokterList || '<span class="text-muted">-</span>'}</div>
                            <div class="staff-section"><span class="staff-label">Perawat:</span>${perawatList || '<span class="text-muted">-</span>'}</div>
                        </div>
                    </div>
                    ${data.catatan ? `<div class="note-section mt-2 p-3"><strong>Catatan:</strong><span>${data.catatan}</span></div>` : ''}
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-secondary">Qty : ${formatQty(data.qty)}</h6>
                        <div class="price-container">
                            ${priceOriginal}
                            ${diskonFinal}
                            <div class="price-normal">Rp.${parseInt(data.sub_total).toLocaleString()}</div>
                        </div>
                    </div>
                </div>
                ${actions}
            </div>
        </div>`;
    }


    function loadServiceList() {
        const url = $('#service-list').data('url');
        $.get(url, function (data) {
            $('#service-list').empty();

            let totalSebelumDiskon = 0, totalDiskon = 0, totalPPN = 0, grandTotal = 0;

            if (data.length > 0) {
                data.forEach(item => {
                    $('#service-list').append(renderService(item));

                    const hargaJual = parseFloat(item.harga_jual);
                    const hargaDiskon = parseFloat(item.total_diskon);
                    const qty = parseFloat(item.qty);
                    const ppnRp = parseFloat(item.ppn_rp || 0);

                    totalSebelumDiskon += hargaJual * qty;
                    totalDiskon += hargaDiskon;
                    totalPPN += ppnRp;
                    grandTotal += parseFloat(item.sub_total);
                });

                $('#service-list').append(`
                    <div class="mt-3 border-top pt-3 text-end">
                        <h5 class="mt-2"><strong>Total:</strong> ${formatCurrency(grandTotal)}</h5>
                    </div>
                `);
                
            } else {
                $('#service-list').html('<p class="text-center text-muted mt-3">Service masih kosong</p>');
            }
        });
        updateGrandTotal();
    }

    // $('#service-list').append(`
    //     <div class="mt-3 border-top pt-3 text-end">
    //         <p class="mb-1"><strong>Sub Total :</strong> ${formatCurrency(totalSebelumDiskon)}</p>
    //         <p class="mb-1"><strong>Diskon:</strong> ${formatCurrency(totalDiskon)}</p>
    //         <p class="mb-1"><strong>PPN:</strong> ${formatCurrency(totalPPN)}</p>
    //         <h5 class="mt-2"><strong>Grand Total:</strong> ${formatCurrency(grandTotal)}</h5>
    //     </div>
    // `);
    $('#harga').data('harga-number', 0);
    loadServiceList();

    $(document).off('click.addService').on('click.addService', '#add-service', function (e) {
        e.preventDefault();
        const $form = $('#form-service');
        $form.parsley().reset();
        $form[0].reset();

        $('#param').val('add');
        $('#id').val('');
        $('#form-title').text('Tambah Tindakan');
        processDone($('#save'), 'bi bi-save', ' Simpan');

        $('#tindakan').val(null).trigger('change');
        $('#dokters').val(null).trigger('change');
        $('#perawats').val(null).trigger('change');

        $('#harga').val('').data('harga-number', 0);
        autoDiskon.clear();
        $('.sub-total').text('Rp.0');

        $('#modalService').modal('show');
        $('.modal-load').hide();
    });

    $(document)
        .off('select2:select.service')
        .on('select2:select.service', '.select2-tindakan', function (e) {
            const data = e.params.data;
            setHargaFormatted(data.harga_jual || 0);
            hitungSubtotal();
        })
        .off('select2:clear.service')
        .on('select2:clear.service', '.select2-tindakan', function () {
            $('#harga').val('').data('harga-number', 0);
            autoDiskon.clear();
            $('#qty').val(1);
            $('.sub-total').text(formatCurrency(0));
        })
        .off('input.service change.service', '#diskon, #diskonType, #qty')
        .on('input.service change.service', '#diskon, #diskonType, #qty', function () {
            hitungSubtotal();
        })
        .off('click.qty.service')
        .on('click.qty.service', '.btn-qty', function () {
            let qty = parseInt($('#qty').val()) || 0;
            if ($(this).hasClass('btn-inc')) qty++;
            else if ($(this).hasClass('btn-dec') && qty > 1) qty--;
            $('#qty').val(qty);
            hitungSubtotal();
        });

    $(document).off('click.editService').on('click.editService', '.edit-service', function () {
        $('.modal-load').show();
        $('#form-title').text('Edit Tindakan');
        form.parsley().reset();
        form[0].reset();
        const id = $(this).data("id");

        $.get(`/pelayanan/perawatan/service/${id}/edit`, function (data) {
            $('#param').val('edit');
            $('#id').val(data.result.id);
            setSelect2Value($('#tindakan'), data.result.service_id, data.result.nama_tindakan);
            $('#harga').val(formatCurrency(data.result.harga_jual)).data('harga-number', data.result.harga_jual);

            if (data.result.diskon > 0) {
                $('#diskonType').val('percentage');
                autoDiskon.set(data.result.diskon || 0);
            } else {
                $('#diskonType').val('rupiah');
                autoDiskon.set(data.result.diskon_rp || 0);
            }

            $('#qty').val(formatQty(data.result.qty));
            $('#catatan').val(data.result.catatan);
            $('.sub-total').text(formatCurrency(data.result.sub_total));

            $('#dokters').empty();
            $('#perawats').empty();

            data.dokters.forEach(item => setSelect2Value($('#dokters'), item.id, item.nama));
            data.perawats.forEach(item => setSelect2Value($('#perawats'), item.id, item.nama));

            $('#modalService').modal('show');
            $('.modal-load').hide();
        });
    });

    $(document).off('click.deleteService').on('click.deleteService', '.delete-service', function () {
        const id = $(this).data("id");
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data ini akan dihapus permanen",
            icon: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'DELETE',
                    url: `/pelayanan/perawatan/service/${id}`,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        sweetalert2ToastTE('success', 'Success!', data.text);
                        loadServiceList();
                    },
                    error: function () {
                        Swal.fire("Error", "Terjadi kesalahan", "error");
                    }
                });
            }
        });
    });

    $(document).off('click.saveService').on('click.saveService', '#save-service', function (e) {
        e.preventDefault();
        const $btn = $(this); 
        if (form.parsley().validate()) {
            const id = $('#id').val();
            const param_form = $('#param').val();
            const tindakan = $('#tindakan').val();
            const harga = parseInt($('#harga').data('harga-number')) || 0;
            const diskonType = $('#diskonType').val();
            const diskon = autoDiskon.getNumber() || 0;
            const qty = parseFloat($('#qty').val()) || 1;
            const catatan = $('#catatan').val();
            const dokters = $('#dokters').val() || [];
            const perawats = $('#perawats').val() || [];

            let diskonRp = (diskonType === 'percentage') ? harga * (diskon / 100) : diskon;
            const hargaSetelahDiskon = Math.max(0, harga - diskonRp);
            const subTotal = hargaSetelahDiskon * qty;

            $.ajax({
                url: urlStore,
                method: 'POST',
                data: {
                    id: id,
                    param: param_form,
                    pendaftaran_id: pendaftaranId,
                    service_id: tindakan,
                    qty: qty,
                    harga_jual: harga,
                    diskon: diskonType === 'percentage' ? diskon : 0,
                    diskon_rp: diskonType === 'rupiah' ? diskonRp : diskonRp,
                    sub_total: subTotal,
                    catatan: catatan,
                    dokters: dokters,
                    perawats: perawats,
                },
                beforeSend: function () {
                    process($btn);
                },
                success: function (data) {
                    processDone($btn, 'bi bi-save', ' Simpan');
                    sweetalert2ToastTE('success', 'Success!', data.text);
                    $('#modalService').modal('hide');
                    loadServiceList();
                },
                error: function (reject) {
                    processDone($btn, 'bi bi-save', ' Simpan');
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

    function setHargaFormatted(harga) {
        $('#harga').val(formatCurrency(harga));
        $('#harga').data('harga-number', harga);
    }
};

window.initItemTab = function () {
    const form = $('#form-item');
    if (!form.length || form.data('initialized')) return;
    form.data('initialized', true);

    const urlStore = form.data('store');
    const urlIndex = form.data('index');
    const urlGetData = form.data('get-data');
    const pendaftaranId = form.data('id');

    initSelect2Item('#modalItem');

    const autoDiskon = new AutoNumeric('#diskon', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0,
        unformatOnSubmit: true,
        modifyValueOnWheel: false
    });

    $(document).off('click.addItem').on('click.addItem', '#add-item', function (e) {
        e.preventDefault();

        form.parsley().reset();
        form[0].reset();

        $('#param').val('add');
        $('#id').val('');
        $('#form-title').text('Tambah Produk');
        processDone($('#save'), 'bi bi-save', ' Simpan');

        $('#item_id').val(null).trigger('change');
        $('#harga').val('').data('harga-number', 0);
        if (typeof autoDiskon !== 'undefined') autoDiskon.clear();
        $('.sub-total').text('Rp.0');

        $('#modalItem').modal('show');
        $('.modal-load').hide();
    });

    function formatQty(qty) {
        const val = parseFloat(qty);
        return Number.isInteger(val) ? val : val.toFixed(2);
    }

    function setHargaFormatted(harga,harga_ppn, ppn = 0) {
        $('#harga').val(formatCurrency(harga_ppn));
        $('#harga').data('harga-number', harga);
        $('#harga').data('ppn', ppn);
    }

    function setSelect2Value($select, id, text) {
        const option = new Option(text, id, true, true);
        $select.append(option).trigger('change');
    }

    function hitungSubtotal() {
        const harga = parseInt($('#harga').data('harga-number')) || 0;
        const diskon = autoDiskon.getNumber() || 0;
        const diskonType = $('#diskonType').val();
        const qty = parseInt($('#qty').val()) || 1;
        const ppn = parseInt($('#harga').data('ppn')) || 0; // dalam persen, misal 11

        let hargaSetelahDiskon = harga;
        if (diskonType === 'percentage') {
            hargaSetelahDiskon -= (harga * diskon / 100);
        } else {
            hargaSetelahDiskon -= diskon;
        }
        if (hargaSetelahDiskon < 0) hargaSetelahDiskon = 0;

        const subtotalSebelumPPN = hargaSetelahDiskon * qty;
        const ppnRp = subtotalSebelumPPN * (ppn / 100);
        const subtotalFinal = roundUpToNearest(subtotalSebelumPPN + ppnRp);

        $('.sub-total').text(formatCurrency(subtotalFinal));
    }

    $(document)
        .off('select2:select.item')
        .on('select2:select.item', '.select2-item', function (e) {
            const data = e.params.data;
            setHargaFormatted(data.harga_jual, data.harga_jual_ppn,data.ppn);
            hitungSubtotal();
        })
        .off('select2:clear.item')
        .on('select2:clear.item', '.select2-item', function () {
            $('#harga').val('').data('harga-number', 0);
            autoDiskon.clear();
            $('#qty').val(1);
            $('.sub-total').text(formatCurrency(0));
        })
        .off('input.item change.item', '#diskon, #diskonType, #qty')
        .on('input.item change.item', '#diskon, #diskonType, #qty', function () {
            hitungSubtotal();
        })
        .off('click.btnQty.item')
        .on('click.btnQty.item', '.btn-qty', function () {
            let qty = parseInt($('#qty').val()) || 0;
            if ($(this).hasClass('btn-inc')) {
                qty++;
            } else if ($(this).hasClass('btn-dec') && qty > 1) {
                qty--;
            }
            $('#qty').val(qty);
            hitungSubtotal();
        });

    function renderItem(data) {
        const qty = parseFloat(data.qty) || 1;
        const diskonPersen = parseFloat(data.diskon) || 0;
        const diskonRp = parseFloat(data.diskon_rp) || 0;
        const ppnRp = parseFloat(data.ppn_rp) || 0;

        const hargaAsli = parseFloat(data.harga_jual) || 0;
        const hargaDiskonPerItem = Math.max(0, hargaAsli - diskonRp);
        const ppnPerItem = ppnRp / qty;
        const hargaFinalPerItem = roundUpToNearest(hargaDiskonPerItem + ppnPerItem);

        const subTotal = roundUpToNearest(parseFloat(data.sub_total) || (hargaFinalPerItem * qty));

        const hasDiskon = diskonPersen > 0 || diskonRp > 0;

        const priceOriginal = hasDiskon
            ? `<div class="price-original">Rp.${hargaAsli.toLocaleString()}</div>`
            : '';

        const diskonFinal = hasDiskon
            ? `<div class="price-final"><span class="discount-badge">${
                diskonRp > 0
                    ? `-Rp.${diskonRp.toLocaleString()}`
                    : `-${diskonPersen}%`
            }</span></div>`
            : '';

        // Cek apakah boleh tampilkan aksi
        const showActions = !['Pembayaran', 'Selesai'].includes(data.status);

        const actions = showActions ? `
            <div class="actions-container">
                <button class="btn btn-outline-primary btn-action edit-item" data-id="${data.id}" type="button">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-outline-danger btn-action delete-item" data-id="${data.id}" type="button">
                    <i class="bi bi-trash"></i>
                </button>
            </div>` : '';

        return `
        <div class="item-card" data-id="${data.id}">
            <div class="item-row">
                <div class="item-content">
                    <div>
                        <div class="item-title">${data.nama_item}</div>
                    </div>
                    ${data.catatan ? `<div class="note-section mt-2 p-3"><strong>Catatan:</strong> <span>${data.catatan}</span></div>` : ''}
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-secondary">Qty : ${formatQty(qty)} ${data.satuan}</h6>
                        <div class="price-container text-end">
                            ${priceOriginal}
                            ${diskonFinal}
                            <div class="price-normal">Rp.${hargaFinalPerItem.toLocaleString()}</div>
                            <div class="sub-total text-muted small">Sub Total: Rp.${subTotal.toLocaleString()}</div>
                        </div>
                    </div>
                </div>
                ${actions}
            </div>
        </div>`;
    }

    function loadItemList() {
        const url = $('#item-list').data('url');
        $.get(url, function (data) {
            $('#item-list').empty();

            let totalSebelumDiskon = 0, totalDiskon = 0, totalPPN = 0, grandTotal = 0;

            if (data.length > 0) {
                data.forEach(item => {
                    $('#item-list').append(renderItem(item));
                    const hargaJual = parseFloat(item.harga_jual);
                    const hargaDiskon = parseFloat(item.total_diskon);
                    const qty = parseFloat(item.qty);
                    const ppnRp = parseFloat(item.ppn_rp || 0);

                    totalSebelumDiskon += hargaJual * qty;
                    totalDiskon += hargaDiskon;
                    totalPPN += ppnRp;
                    grandTotal += parseFloat(item.sub_total);
                });

                $('#item-list').append(`
                    <div class="mt-3 border-top pt-3 text-end">
                        <h5 class="mt-2"><strong>Total:</strong> ${formatCurrency(grandTotal)}</h5>
                    </div>
                `);
            } else {
                $('#item-list').html('<p class="text-center text-muted mt-3">Item masih kosong</p>');
            }
        });
        updateGrandTotal();
    }

    //  $('#item-list').append(`
    //     <div class="mt-3 border-top pt-3 text-end">
    //         <p class="mb-1"><strong>Sub Total :</strong> ${formatCurrency(totalSebelumDiskon)}</p>
    //         <p class="mb-1"><strong>Diskon:</strong> ${formatCurrency(totalDiskon)}</p>
    //         <p class="mb-1"><strong>PPN:</strong> ${formatCurrency(totalPPN)}</p>
    //         <h5 class="mt-2"><strong>Grand Total:</strong> ${formatCurrency(grandTotal)}</h5>
    //     </div>
    // `);

    $(document).off('click.deleteItem').on('click.deleteItem', '.delete-item', function () {
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
                    url: `/pelayanan/perawatan/item/${id}`,
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (data) {
                        sweetalert2ToastTE('success', 'Success!', data.text);
                        loadItemList();
                    },
                    error: function () {
                        Swal.fire("Error", "Terjadi kesalahan", "error");
                    }
                });
            }
        });
    });

    $(document).off('click.editItem').on('click.editItem', '.edit-item', function () {
        $('.modal-load').show();
        $('#form-title').text('Edit Produk');
        form.parsley().reset();
        form[0].reset();

        const id = $(this).data("id");
        $.get(`/pelayanan/perawatan/item/${id}/edit`, function (data) {
            $('#param').val('edit');
            $('#id').val(data.result.id);
            setSelect2Value($('#item_id'), data.result.item_id, data.result.nama_item);
            setHargaFormatted(data.result.harga_jual, data.result.harga_jual_ppn, data.result.ppn);
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
            $('#modalItem').modal('show');
            $('.modal-load').hide();
        });
    });

    $('#harga').data('harga-number', 0);
    $('#harga').data('ppn', 0);
    loadItemList();

    $(document).off('click.saveItem').on('click.saveItem', '#save-item', function (e) {
        e.preventDefault();
        const $btn = $(this); 
        if (form.parsley().validate()) {
            const id = $('#id').val();
            const param_form = $('#param').val();
            const item = $('#item_id').val();
            const harga = parseInt($('#harga').data('harga-number')) || 0;
            const diskonType = $('#diskonType').val();
            const diskon = autoDiskon.getNumber() || 0;
            const qty = parseFloat($('#qty').val()) || 1;
            const catatan = $('#catatan').val();

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
                    item_id: item,
                    qty: qty,
                    harga_jual: harga,
                    diskon: diskonType === 'percentage' ? diskon : 0,
                    diskon_rp: diskonType === 'rupiah' ? diskonRp : diskonRp,
                    sub_total: subTotal,
                    catatan: catatan,
                },
                beforeSend: function () {
                    process($btn);
                },
                success: function (data) {
                    processDone($btn, 'bi bi-save', ' Simpan');
                    sweetalert2ToastTE('success', 'Success!', data.text);
                    $('#modalItem').modal('hide');
                    loadItemList();
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
};

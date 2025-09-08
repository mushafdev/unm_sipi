var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
let selectedPaymentMethod = null;
let cart = [];
let totalHargaSebelumDiskon = 0;
let totalDiskon = 0;
let totalPPN = 0;
let grandTotal = 0;

$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})



let searchTimeout = null;

function renderItems() {
clearTimeout(searchTimeout);

searchTimeout = setTimeout(() => {
    const term = $('#searchInput').val().toLowerCase();
    const type = $('.type-layanan.active').data('type');
    const $container = $('#itemsContainer');

    $container.fadeTo(100, 0.3).html('<div class="text-center w-100 py-3"><div class="spinner-border text-primary" role="status"></div></div>');

    $.ajax({
        url: '/data/search-items',
        method: 'GET',
        data: { q: term, type: type },
        success: function (data) {
            $container.fadeOut(100, function () {
                $container.empty();

                if (data.length === 0) {
                    $container.append('<p class="text-muted">Tidak ditemukan.</p>');
                } else {
                    data.forEach(item => {
                        let stokShow = '';
                        if (item.type === 'items') {
                            stokShow = `<p class="card-text text-muted">Stok : ${parseInt(item.stok)+' '+item.satuan ?? 0}</p>`;
                        }
                        const display = `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card shadow-md">
                                <div class="card-body">
                                    <h5 class="card-title">${item.name}</h5>
                                    ${stokShow}
                                    <h5 class="card-text fw-normal">${formatCurrency(item.price_ppn)}</h5>
                                    <button type="button" class="btn btn-sm btn-light w-100 add-to-cart" data-id="${item.id}" data-name="${item.name}" data-price="${item.price}" data-price-ppn="${item.price_ppn}" data-ppn="${item.ppn}">
                                        <i class="bi bi-plus-lg"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>`;
                        $container.append(display);
                    });
                }

                $container.fadeTo(100, 1).hide().fadeIn(100);
            });
        },
        error: function () {
            $container.html('<p class="text-danger">Gagal memuat data.</p>').fadeTo(200, 1);
        }
    });
}, 300); // debounce 300ms
}


function updateCart() {

    totalHargaSebelumDiskon = 0;
    totalDiskon = 0;
    totalPPN = 0;
    grandTotal = 0;
    const $cart = $('#cartContainer').empty();
    $('#cartCount').text(cart.length);

    if (cart.length === 0) {
        $cart.html('<p class="text-center text-muted">Keranjang masih kosong</p>');
        $('#checkoutFooter').addClass('d-none');
        return;
    }

    cart.forEach((item, index) => {
        const { diskonRp, ppn, subtotalFinal } = calculateItemTotal(item);
        const quantity = item.quantity;
        const price = item.price;
        const hargaTotal = quantity * price;

        totalHargaSebelumDiskon += hargaTotal;
        totalDiskon += diskonRp * quantity;
        totalPPN += ppn;
        grandTotal += subtotalFinal;

        const html = `
        <div class="mb-2 border-bottom pb-2">
            <div class="d-flex justify-content-between">
                <strong>${item.name}</strong>
                <button class="btn no-padding btn-md text-danger p-0 remove-item" data-index="${index}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <div class="d-flex me-3">
                    <button class="btn-qty btn-dec" data-index="${index}"><i class="bi bi-dash"></i></button>
                    <span class="mx-2">${quantity}</span>
                    <button class="btn-qty btn-inc" data-index="${index}"><i class="bi bi-plus"></i></button>
                </div>
                <div class="text-end">
                    <small>Diskon:</small>
                    <div class="input-group input-group-sm mb-1">
                        <select class="form-select bg-light diskon-type" style="max-width: 60px;" data-index="${index}">
                            <option value="percentage" ${item.discountType === 'percentage' ? 'selected' : ''}>%</option>
                            <option value="rupiah" ${item.discountType === 'rupiah' ? 'selected' : ''}>Rp</option>
                        </select>
                        <input type="text" class="form-control diskon-input" data-index="${index}" value="${item.discount || 0}" min="0">
                    </div>
                    <strong class="subtotal-display">${formatCurrency(subtotalFinal)}</strong>
                </div>
            </div>
        </div>`;
        
        $cart.append(html);
    });

    AutoNumeric.multiple('.diskon-input', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0,
        unformatOnSubmit: true,
        modifyValueOnWheel: false
    });

    // console.log(cart);

    updateSummaryTotalsOnly();
    $('#checkoutFooter').removeClass('d-none');
}



function calculateItemTotal(item) {
    const price = parseFloat(item.price || 0);
    const quantity = parseFloat(item.quantity || 1);
    const discount = item.discount || 0;
    const discountType = item.discountType || 'percentage';
    const ppnPersen = item.ppn || 0;

    let diskonRp = 0;
    if (discountType === 'percentage') {
        diskonRp = price * (discount / 100);
    } else {
        diskonRp = discount;
    }

    const hargaSetelahDiskon = Math.max(0, price - diskonRp);
    const subtotalBeforePPN = hargaSetelahDiskon * quantity;
    const ppn = subtotalBeforePPN * (ppnPersen / 100);
    const subtotalFinal = subtotalBeforePPN + ppn;

    const subtotalRounded = roundUpToNearest(subtotalFinal); // dibulatkan ke atas ke kelipatan 100

    item.diskon_rp = roundUpToNearest(diskonRp);
    item.ppn_rp = ppn;
    item.sub_total = subtotalRounded;

    return {
        diskonRp,
        ppn,
        subtotalFinal: subtotalRounded
    };
}



function updateSummaryTotalsOnly() {
    totalHargaSebelumDiskon = 0;
    totalDiskon = 0;
    totalPPN = 0;
    grandTotal = 0;

    cart.forEach(item => {
        const hargaTotal = item.quantity * roundUpToNearest(item.price);
        totalHargaSebelumDiskon += hargaTotal;
        totalDiskon += (item.diskon_rp || 0) * item.quantity;
        totalPPN += item.ppn_rp || 0;
        grandTotal += item.sub_total || 0;
    });

    $('#subTotal').text(formatCurrency(totalHargaSebelumDiskon));
    $('#diskonTotal').text(formatCurrency(totalDiskon));
    $('#ppnTotal').text(formatCurrency(totalPPN));
    $('#grandTotal').text(formatCurrency(grandTotal));
}

  // Events
  $(document).ready(function () {
    renderItems();

    const pendaftaranId = $('#pendaftaran_id').val();
    if (pendaftaranId) {
        $.ajax({
            url: `/penjualan/penjualan/pendaftaran/${pendaftaranId}/keranjang`,
            method: 'GET',
            success: function (data) {
                cart = data.map(item => ({
                    id: item.id,
                    name: item.name,
                    type: item.type, 
                    discountType: item.diskon > 0 ? 'percentage' : (item.diskon_rp > 0 ? 'rupiah' : 'percentage'),
                    discount: item.diskon > 0 ? item.diskon : item.diskon_rp,
                    quantity: parseFloat(item.quantity),
                    price: parseFloat(item.price),
                    price_ppn: parseFloat(item.price_ppn),
                    ppn: parseFloat(item.ppn || 0)
                }));

                cart.forEach(i => calculateItemTotal(i));
                updateCart();
            },
            error: function () {
                console.warn('Gagal memuat keranjang dari pendaftaran.');
            }
        });
    }

    $('#tabSelector .nav-link').click(function () {
      $('#tabSelector .nav-link').removeClass('active');
      $(this).addClass('active');
      renderItems();
    });

    $('#searchInput').on('input', renderItems);


    $(document).on('click', '.add-to-cart', function () {
        const id = parseInt($(this).data('id'));
        const type = $('.type-layanan.active').data('type');

        const name = $(this).data('name');
        const price = parseFloat($(this).data('price'));
        const price_ppn = parseFloat($(this).data('price-ppn'));
        const ppn = parseFloat($(this).data('ppn'));

        const existing = cart.find(i => i.id === id && i.type === type);

        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id,
                name,
                price,
                price_ppn,
                ppn,
                quantity: 1,
                discount: 0,
                discountType: 'percentage', // default
                type
            });
        }

        updateCart();
    });

    $(document).on('click', '.remove-item', function () {
      const index = $(this).data('index');
      cart.splice(index, 1);
      updateCart();
    });

    $(document).on('click', '.btn-inc', function () {
      const index = $(this).data('index');
      cart[index].quantity++;
      updateCart();
    });

    $(document).on('click', '.btn-dec', function () {
      const index = $(this).data('index');
      if (cart[index].quantity > 1) cart[index].quantity--;
      updateCart();
    });

    $(document).on('change', '.diskon-type', function () {
        const index = $(this).data('index');
        const type = $(this).val();
        cart[index].discountType = type;
        cart[index].discount = 0;

        const inputEl = $(this).closest('.input-group').find('.diskon-input')[0];
        const autoNumericInstance = AutoNumeric.getAutoNumericElement(inputEl);

        if (autoNumericInstance) {
            autoNumericInstance.set(0); // Reset tampilan input diskon jadi 0
        }

        const item = cart[index];
        const result = calculateItemTotal(item);

        $(this).closest('.text-end').find('.subtotal-display').text(formatCurrency(result.subtotalFinal));
        updateSummaryTotalsOnly();
    });

    $(document).on('input', '.diskon-input', function () {
        const index = $(this).data('index');
        const anInstance = AutoNumeric.getAutoNumericElement(this);
        if (!anInstance) return;

        let val = anInstance.getNumber() || 0;
        const type = cart[index].discountType;

        // Batasi persen maksimum 100
        if (type === 'percentage' && val > 100) {
            val = 100;
            anInstance.set(100); // update tampilan input
        }

        cart[index].discount = val;

        const item = cart[index];
        const { diskonRp, ppn, subtotalFinal } = calculateItemTotal(item);

        $(this).closest('.text-end').find('.subtotal-display').text(formatCurrency(subtotalFinal));
        updateSummaryTotalsOnly();
    });





    $('#checkoutBtn').click(function () {
        $('#modalTotalPrice').text(formatCurrency(grandTotal));
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
        const kembali = bayar - grandTotal;
        $('#kembalianDisplay').text(formatCurrency(kembali > 0 ? kembali : 0));
    });
  });





$('#save').on('click', function () {
    const bayar = AutoNumeric.getAutoNumericElement(document.querySelector('#bayarInput')).getNumber();
    const kembalian = bayar - grandTotal;
    const metode_pembayaran_id = $('.metode-pembayaran-btn.active').data('id');

    if (!metode_pembayaran_id) {
        Swal.fire("Info", "Silahkan memilih metode pembayaran", "info");
        return;
    }

    if (cart.length === 0) {
        Swal.fire("Info", "Keranjang masih kosong", "info");
        return;
    }

    $.ajax({
        url: $('#form').data('store'),
        type: 'POST',
        data: {
            _token: $('input[name="_token"]').val(),
            pendaftaran_id: $('#pendaftaran_id').val(),
            sumber_penjualan_id: $('#sumber_penjualan_id').val(),
            pasien_id: $('#pasien_id').val(),

            total_harga_sebelum_diskon: totalHargaSebelumDiskon,
            total_diskon: totalDiskon,
            total_ppn: totalPPN,
            grand_total: grandTotal,
            bayar: bayar,
            kembalian: kembalian,
            metode_pembayaran_id: metode_pembayaran_id,
            items: cart.map(item => {
                const diskonRp = item.diskon_rp || 0;
                const hargaDiskon = item.price - diskonRp;
                const totalDiskon = diskonRp * item.quantity;

                return {
                    item_id: item.id,
                    qty: item.quantity,
                    harga_jual: item.price,
                    harga_jual_ppn: item.price_ppn,
                    type: item.type,
                    diskon: item.discountType === 'percentage' ? item.discount || 0 : 0,
                    diskon_rp: item.discountType === 'rupiah' ? item.discount || 0 : diskonRp,
                    total_diskon: totalDiskon,
                    harga_diskon: hargaDiskon,
                    ppn: item.ppn || 0,
                    ppn_rp: item.ppn_rp || 0,
                    sub_total: item.sub_total || 0
                };
            })
        },
        success: function (response) {
            Swal.fire({
                    title: "Success!",
                    text: response.text,
                    icon: "success",
                    timer: 2000
            }).then((result) => {
                location.replace($('#form').data('index')+'/'+response.id);

            });
        },
        error: function (xhr) {
             Swal.fire("Error", "Terjadi kesalahan", "error");
        }
    });
});




var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
var urlList=$('#table').data('list');
var urlUpdateItem=$('#table').data('update-item');


$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})

let opnameListVal = localStorage.getItem("filterOpname") || "";

if (opnameListVal) {
    $('.opname-list').removeClass('active');
    $('.opname-list[data-id="'+opnameListVal+'"]').addClass('active');
}

var table;
table = $('#table').DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
    scrollX: true,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    language: {
        info: "_START_-_END_ dari _TOTAL_ data",
        searchPlaceholder: "Cari data..."
    },
    dom: "<'row mb-2'<'col-md-2'l><'col-md-3'<'custom-filter-kadaluarsa'>>" +
         "<'col-md-7 d-flex justify-content-end align-items-center'f>>" +
         "<'row'<'col-md-12'tr>>" +
         "<'row mt-2'<'col-md-6'i><'col-md-6'p>>",
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: "GET",
        url: urlList, // pastikan variabel ini sudah didefinisikan, misal: `/stok-opname/item`,
        data: function(d) {
            d.gudang_id = $('#gudang_id').val(); // input hidden/select yang berisi encrypted gudang_id
            d.status = $('.opname-list.active').data('id'); 
            d.kadaluarsa_filter = $('#filter-kadaluarsa').val(); 
        },
        error: function(xhr) {
            console.error(xhr);
            Swal.fire("Error", "Gagal memuat data dari server.", "error");
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'item', name: 'item' },
        { data: 'batch_exp', name: 'item_stoks.no_batch' },
        { data: 'expired', name: 'item_stoks.tgl_kadaluarsa' },
        { data: 'stok_sistem', name: 'item_stoks.stok' },
        { data: 'stok_fisik', name: 'stok_fisik', orderable: false, searchable: false },
        { data: 'selisih', name: 'selisih', orderable: false, searchable: false },
        { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
    ],
    createdRow: function(row, data, dataIndex) {
        if (data.expired && data.expired.includes('Kadaluarsa')) {
            $(row).addClass('bg-light-danger');
        }
    },
    initComplete: function () {
        let kadaluarsaVal = localStorage.getItem("filterKadaluarsa") || "";

        let selectKadaluarsa = `
            <select class="form-select form-select-sm" id="filter-kadaluarsa">
                <option value="">Semua Status</option>
                <option value="kadaluarsa">Kadaluarsa</option>
                <option value="akan_kadaluarsa">Akan Kadaluarsa</option>
                <option value="aman">Aman</option>
            </select>
        `;

        $('.custom-filter-kadaluarsa').html(selectKadaluarsa);

        // Set nilai dari localStorage (jika ada)
        $('#filter-kadaluarsa').val(kadaluarsaVal);

        // Trigger reload jika sebelumnya ada filter
        if (kadaluarsaVal) table.ajax.reload();

        // On change filter
        $('#filter-kadaluarsa').on('change', function () {
            let val = $(this).val();
            localStorage.setItem("filterKadaluarsa", val);
            table.ajax.reload();
        });
    }
});


$(document).on('change', '#filter-kadaluarsa', function () {
    table.ajax.reload();
});

$(document).on('input', '.stok-fisik-input', function () {
    let $input = $(this);
    let $row = $input.closest('tr');
    let stokFisik = parseFloat($input.val()) || 0; 
    let stokSistem = parseFloat($row.find('input[readonly]').val()) || 0;

    if (isNaN(stokFisik) || isNaN(stokSistem)) {
        $row.find('input[name="selisih[]"], .selisih-field').val('');
        return;
    }

    let selisih = stokFisik - stokSistem;
    $row.find('input[name="selisih[]"], .selisih-field').val(selisih.toFixed(2));

    $row.find('.update_item').removeClass('d-none');
});

$(document).on('click', '.update_item', function () {
    let $btn = $(this);
    let $row = $btn.closest('tr');
    let id = $btn.data('id');
    let item_stok_id = $btn.data('stok-id');
    let stok_fisik = $row.find('.stok-fisik-input').val();

    $.ajax({
        url: urlUpdateItem,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            draft_id: id,
            item_stok_id: item_stok_id,
            stok_fisik: stok_fisik,
        },
        beforeSend: function() {
            processIcon($btn);
        },
        success: function (data) {
            if($('.opname-list.active').data('id')==='belum'){
                $row.remove();
            }
            sweetalert2ToastTE('success','Success!',data.text);
            $btn.addClass('d-none');
            processIconDone($btn,'bi bi-check');
        },
        error: function () {
            Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan", "error");
            processIconDone($btn,'bi bi-check');
        }
    });
});

$(document).on('click', '.opname-list', function (e) {
    e.preventDefault();
    localStorage.setItem("filterOpname", $(this).data('id'));
    table.ajax.reload(null, true);
});

$("#save").on('click',function(e) {
    e.preventDefault();
    if($("#form").parsley().validate()){
        Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang sudah diinput tidak bisa diubah lagi",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.value) {
            var param = $(this);
            var form = $('form')[0];
            var form_data = new FormData(form);
            ax_save = $.ajax({
                type    : 'POST',
                data    : form_data,
                contentType : false,
                processData : false,
                cache: false,
                async:true,
                url     : urlStore,
                beforeSend: function() {
                    process(param);
                },
                success : function(data) {
                processDone(param,'bi bi-save',' Simpan Opname');
                Swal.fire({
                        title: "Success!",
                        text: data.text,
                        icon: "success",
                        timer: 2000
                }).then((result) => {
                    location.replace(urlIndex+'/'+data.id);

                });
                },
                error: function (reject) {
                    processDone(param,'bi bi-save',' Simpan Opname');
                    $('#form').parsley().reset();
                    if (reject.status === 422) {
                        handleParsleyErrors(reject.responseJSON.errors);
                    } else {
                        Swal.fire("Error", "Terjadi kesalahan sistem", "error");
                    }
                }
            });
        }
        })
    
    }else {
    return false;
    }

});


var form=$('#form');
var urlSaveItem=form.data('save-item');
var urlList=$('#tableDetail').data('list');
var urlDeleteItem=$('#tableDetail').data('delete-item');
var urlPosting=$('#tableDetail').data('posting');
var table;
table = $('#tableDetail').DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
    scrollX: true,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    language: {
        info: "_START_-_END_ dari _TOTAL_ data",
        searchPlaceholder: "Cari data..."
    },
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: "GET",
        url: urlList, // pastikan variabel ini sudah didefinisikan, misal: `/stok-opname/item`,
        data: function(d) {
            d.gudang_id = $('#gudang_id').val(); 
            d.item_stok_awal_id = $('#item_stok_awal_id').val(); 
        },
        error: function(xhr) {
            console.error(xhr);
            Swal.fire("Error", "Gagal memuat data dari server.", "error");
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'item', name: 'item' },
        { data: 'batch_exp', name: 'batch_exp' },
        { data: 'expired', name: 'expired' },
        { data: 'stok_awal', name: 'stok_awal' },
        { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
    ],
});

$("#save-item").on('click',function(e) {
    e.preventDefault();
    if($("#form").parsley().validate()){
    var param = $(this);
    var form = $('form')[0];
    var form_data = new FormData(form);
    form_data.append('gudang_id',$('#gudang_id').val());
    form_data.append('item_stok_awal_id',$('#item_stok_awal_id').val());
    ax_save = $.ajax({
        type    : 'POST',
        data    : form_data,
        contentType : false,
        processData : false,
        cache: false,
        async:true,
        url     : urlSaveItem,
        beforeSend: function() {
            process(param);
        },
        success : function(data) {
        processDone(param,'bi bi-plus-circle',' Tambah Item');
        table.ajax.reload( null, false );
        sweetalert2ToastTE('success','Success!',data.text);
        form.reset();
        // 🔁 Reset Select2
        $('.select2-item').val(null).trigger('change');

        $('#form').parsley().reset();
        
        },
        error: function (reject) {
            processDone(param,'bi bi-plus-circle',' Tambah Item');
            $('#form').parsley().reset();
            if( reject.status === 422 ) {
                var data=reject.responseText;
                var jsonResponse = JSON.parse(data);
                $.each( jsonResponse.errors, function( key, value) {
                    $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                });
            }else{
                    let errorMessage = reject.responseJSON?.message || "Terjadi kesalahan. Silakan coba lagi.";

                    Swal.fire("Error", errorMessage, "error");
            }
        }
    });
    }else {
    return false;
    }

});

$(document).on( 'click', '.delete_item', function () {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Apakah anda yakin?',
    text: "Data ini akan dihapus permanen",
    icon: "warning",
    allowOutsideClick: false,
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal'
    }).then((result) => {
    if (result.value) {
        $.ajax({
            type    : 'DELETE',
            url     : urlDeleteItem+'?id='+id,
            success : function(data) {
                sweetalert2ToastTE('success','Success!',data.text);
                table.ajax.reload( null, false );
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
});

$("#save").on('click',function(e) {
    e.preventDefault();
    Swal.fire({
    title: 'Apakah anda yakin?',
    text: "Periksa baik-baik data stok awal. Data yang sudah diinput tidak bisa diubah lagi dan status stok awal menjadi terkunci",
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
        ax_save = $.ajax({
            type    : 'POST',
            processData : true,
            data:{id:$("#item_stok_awal_id").val()},
            url     : urlPosting,
            beforeSend: function() {
                process(param);
            },
            success : function(data) {
            processDone(param,'bi bi-save',' Posting Stok Awal');
            Swal.fire({
                    title: "Success!",
                    text: data.text,
                    icon: "success",
                    timer: 2000
            }).then((result) => {
                location.reload();

            });
            },
            error: function (reject) {
                processDone(param,'bi bi-save',' Posting Stok Awal');
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
    

});


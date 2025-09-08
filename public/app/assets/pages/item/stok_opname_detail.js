
var urlList=$('#tableDetail').data('list');
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
            d.stok_opname_id = $('#stok_opname_id').val();
        },
        error: function(xhr) {
            console.error(xhr);
            Swal.fire("Error", "Gagal memuat data dari server.", "error");
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'item', name: 'item' },
        { data: 'satuan', name: 'items.satuan' },
        { data: 'expired', name: 'item_stoks.tgl_kadaluarsa' },
        { data: 'stok_sistem', name: 'item_stoks.stok' },
        { data: 'stok_fisik', name: 'stok_fisik', orderable: false, searchable: false },
        { data: 'selisih', name: 'selisih', orderable: false, searchable: false },
    ],
});

$(document).on( 'click', '.delete', function () {
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
            type    : 'DELETE',
            url     : $(this).data('url')+'/'+id,
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


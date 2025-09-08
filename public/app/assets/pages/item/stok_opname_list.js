
var urlIndex=$('#table').data('url');
var urlList=$('#table').data('list');


var table;
table = $('#table').DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: false,
    "scrollX": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "language": {
        "info": "_START_-_END_ of _TOTAL_ entries",
        searchPlaceholder: "Search"
    },
    "destroy": true,
    "processing": true,
    "serverSide": true,
    "stateSave": true,
    ajax: {
        "type": "GET",
        "url": urlList,
        "beforeSend" :function(){
        },
        "complete" : function(){
        },
        error : function(){
            Swal.fire("Error", "There is an error", "error");
        },
        "data": function(d) { 
            d.start_date = $('#start_date').val();                   
            d.end_date = $('#end_date').val();                   
        },
        "dataSrc": function (json) {
            return json.data;
        }
    },
    columns: [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'no_transaksi', name: 'no_transaksi'},
    {data: 'waktu', name: 'waktu'},
    {data: 'gudang', name: 'gudang'},
    {data: 'penanggung_jawab', name: 'penanggung_jawab'},
    {data: 'catatan', name: 'catatan'},
    {data: 'cancel', name: 'cancel'},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "columnDefs": [
    {
        "targets": "datatable-nosort",
        "orderable": false,
    },
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
            url     : urlIndex+'/'+id,
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


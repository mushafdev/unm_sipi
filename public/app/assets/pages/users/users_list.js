var urlList=$('#table').data('list');
var urlIndex=$('#table').data('url');

var table;
table = $('#table').DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
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
    ajax: urlList,
    columns: [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'nama', name: 'nama'},
    {data: 'email', name: 'email'},
    {data: 'telp', name: 'telp'},
    {data: 'role', name: 'role'},
    {data: 'aktif', name: 'aktif'},
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
    title: 'Are you sure?',
    text: "This record and it`s details will be permanantly deleted!",
    icon: "warning",
    allowOutsideClick: false,
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Delete',
    cancelButtonText: 'No'
    }).then((result) => {
    if (result.value) {
        $.ajax({
            type    : 'DELETE',
            url     : urlIndex+'/'+id,
            success : function(data) {
                Swal.fire('Success!',data.text,'success');
                table.ajax.reload( null, false );
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
});


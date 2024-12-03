var urlList=$('#table').data('list');
var urlIndex=$('#table').data('url');

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
    ajax: urlList,
    columns: [
    {data: 'DT_RowIndex', name: 'DT_RowIndex',className: "align-top text-center"},
    {data: 'waktu', name: 'waktu', className: "align-top"},
    {data: 'kegiatan', name: 'kegiatan'},
    {data: 'action', name: 'action', orderable: false, searchable: false, className: "align-top"},
    ],
    "columnDefs": [
    {
        "targets": "datatable-nosort",
        "orderable": false,
    },
    { responsivePriority: 1, targets: 0 },
    { responsivePriority: 2, targets: 1 },
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


var urlList=$('#table').data('list');
var urlIndex=$('#table').data('url');

var table;
table = $('#table').DataTable({
    scrollCollapse: true,
    paging: true,
    searching: false,
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
        "complete" : function(){
            processDone($("#btn-filter"),'',' Cari');
        },
        error : function(){
            processDone($("#btn-filter"),'',' Cari');
            Swal.fire("Error", "There is an error", "error");
        },
        "data": function(d) { 
            d.filter = $('#filter-key').val();               
        },
        "dataSrc": function (json) {
            return json.data;
        }
    },
    columns: [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'no_rm', name: 'no_rm'},
    {data: 'nama', name: 'nama'},
    {data: 'tgl_lahir', name: 'tgl_lahir'},
    {data: 'no_hp', name: 'no_hp'},
    {data: 'nik', name: 'nik'},
    {data: 'alamat', name: 'alamat'},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "columnDefs": [
    {
        "targets": "datatable-nosort",
        "orderable": false,
    },
    ],

});

$(document).on('keypress', '#filter-key', function(e) {
    if (e.which === 13) { 
        $('#btn-filter').click(); 
    }
});

$(document).on('click', '#btn-filter', function() {
    table.ajax.reload(null, true);
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


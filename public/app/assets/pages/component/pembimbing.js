
var urlListPembimbing=$("#table-pembimbing").data('list');
var table;
$('.search-pembimbing').on('click', function() {
    $('#modal-pembimbing').modal('show');
   
    table = $("#table-pembimbing").DataTable({
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
        ajax: urlListPembimbing,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'nip', name: 'nip'},
            {data: 'pangkat', name: 'pangkat'},
            {data: 'golongan', name: 'golongan'},
            {data: 'jabatan', name: 'jabatan'},
            {data: 'prodi', name: 'prodi'},
            {data: 'jurusan', name: 'jurusan'},
        ],
        "columnDefs": [
            {
                "targets": "hide-column",
                "visible": false,
            },
        ],

    });
});

$('#table-pembimbing').on('click','tr',function() {
    var data=table.row(this).data();
    $("#pembimbing_id").val(data['id_enc']);
    $("#pembimbing").val(data['nama']);
    $('#modal-pembimbing').modal('hide');
});






var urlListLokasiPi=$("#table-lokasi-pi").data('list');
var table;
$('.search-lokasi-pi').on('click', function() {
    $('#modal-lokasi-pi').modal('show');
   
    table = $("#table-lokasi-pi").DataTable({
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
        ajax: urlListLokasiPi,
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'lokasi_pi', name: 'lokasi_pi'},
        {data: 'kota', name: 'kota'},
        {data: 'alamat', name: 'alamat'},
        {data: 'kebutuhan_pekerjaan', name: 'kebutuhan_pekerjaan'},
        ],
        "columnDefs": [
            {
                "targets": "hide-column",
                "visible": false,
            },
        ],

    });
});

$('#table-lokasi-pi').on('click','tr',function() {
    var data=table.row(this).data();
    $("#lokasi_pi_id").val(data['id_enc']);
    $("#lokasi_pi").val(data['lokasi_pi']);
    $("#alamat").html(data['alamat']+', '+data['kota']);
    $('#modal-lokasi-pi').modal('hide');
});





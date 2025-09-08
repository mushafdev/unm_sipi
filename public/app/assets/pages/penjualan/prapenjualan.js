var urlList=$('#tablePendaftaran').data('list');
var urlCreate=$('#tablePendaftaran').data('create');
let table = $('#tablePendaftaran').DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
    deferLoading: 0,
    scrollX: true,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    language: {
        info: "_START_-_END_ of _TOTAL_ entries",
        searchPlaceholder: "Search"
    },
    destroy: true,
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: "GET",
        url: urlList,
        beforeSend: function () {},
        complete: function () {},
        error: function () {
            Swal.fire("Error", "There is an error", "error");
        },
        data: function (d) {
            d.status_pendaftaran = 'Pembayaran';
        },
        dataSrc: function (json) {
            return json.data;
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },         // 0
        { data: 'no_antrian', name: 'no_antrian' },           // 1
        { data: 'nama', name: 'nama', orderable: false },     // 2
        { data: 'no_hp', name: 'no_hp', orderable: false },   // 3
        { data: 'no_rm', name: 'no_rm', orderable: false },   // 4
        { data: 'tgl_daftar', name: 'tgl_daftar', orderable: false }, // 5
        { data: 'jam', name: 'jam', orderable: false }        // 6
    ],
    columnDefs: [
        {
            targets: 0, 
        },
        {
            targets: 1, // no_antrian
            responsivePriority: 5
        },
        {
            targets: 2, // nama
            responsivePriority: 1
        },
        {
            targets: 3, // no_hp
            responsivePriority: 2
        },
        {
            targets: 4, // no_rm
            responsivePriority: 8
        },
        {
            targets: 5, // tgl_daftar
            responsivePriority: 6
        },
        {
            targets: 6, // jam
            responsivePriority: 7
        }
    ]
});


$(document).on( 'click', '#pilihPendaftaran', function () {
    $('#modalPendaftaran').modal('show');

    table.ajax.reload(null, true);
});

$('#modalPendaftaran').on('shown.bs.modal', function () {
    table.columns.adjust().responsive.recalc();
});

$(document).on('click', '#tablePendaftaran tbody tr', function () {
    var data = table.row(this).data();
    location.replace(urlCreate+'?pendaftaran='+data.id_enc);
});


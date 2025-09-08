
var urlPasien=$('#tableCariPasien').data('list');

var table;
table = $('#tableCariPasien').DataTable({
    scrollCollapse: true,
    paging: true,
    searching: false,
    autoWidth: true,
    responsive: false,
    deferLoading: 0, 
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
        "url": urlPasien,
        "beforeSend" :function(){
        },
        "complete" : function(){
        },
        error : function(){
            processDone($("#btn-filter"),'',' Cari');
            Swal.fire("Error", "There is an error", "error");
        },
        "data": function(d) { 
            d.no_rm = $('#f_no_rm').val();               
            d.nama = $('#f_nama').val();               
            d.panggilan = $('#f_panggilan').val();               
            d.tgl_lahir = $('#f_tgl_lahir').val();               
            d.no_hp = $('#f_no_hp').val();               
            d.nik = $('#f_nik').val();               
            d.alamat = $('#f_alamat').val();               
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
    ],
    "columnDefs": [
    {
        "targets": "datatable-nosort",
        "orderable": false,
    },
    ],

});

$(document).on('keypress', '.filter-form', function(e) {
    if (e.which === 13) { 
        $('#search-pasien').click(); 
    }
});

$("#search-pasien").on('click',function(e) {
    e.preventDefault();
    $('#modal-result-search').modal('show');
    table.ajax.reload(null, true);

});


$('#modal-result-search').on('shown.bs.modal', function () {
    table.columns.adjust().responsive.recalc();
});

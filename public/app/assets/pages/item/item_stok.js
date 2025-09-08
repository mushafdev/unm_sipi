

var form=$("#form");
var formTitle= $(".title").text();
var urlIndex=form.data('index');
var urlGetData=form.data('get-data');
var urlList=$('#table').data('list');
var urlKartuStok=$('#form_kartu_stok').data('kartu-stok');
let gudangVal = localStorage.getItem("filterGudang") || "";

if (gudangVal) {
    $('#gudang').val(gudangVal);
}

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
            d.gudang = $('#gudang').val();                   
        },
        "dataSrc": function (json) {
            return json.data;
        }
    },
    columns: [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'nama_item', name: 'nama_item'},
    {data: 'kategori_item', name: 'kategori_item'},
    {data: 'besaran', name: 'besaran'},
    {data: 'isi', name: 'isi'},
    {data: 'reorder_point', name: 'reorder_point'},
    {data: 'stok', name: 'stok',className:'text-end'},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "columnDefs": [
    {
        "targets": "datatable-nosort",
        "orderable": false,
    },
    ],

});



$(document).on('change', '.form-filter', function (e) {
    e.preventDefault();
    var filter=$(this).data('filter');
    if(filter == 'gudang'){
        localStorage.setItem("filterGudang", $(this).val());
    }
    
    table.ajax.reload(null, true);
});

$(document).on('change', '.filter-ks', function (e) {
    getKartuStok();
});


$(document).on( 'click', '.kartu-stok', function () {
    $("#item_id").val($(this).data('id'));
    getKartuStok();
});

function getKartuStok(){

    // $('#kode').prop('readonly',true);
    $('#form-title').text('Kartu Stok');
    $.ajax({
    type    : 'GET',
    data    : {id:$("#item_id").val(),gudang_id:$("#gudang").val(),start_date:$('#start_date').val(),end_date:$('#end_date').val()},
    url     : urlKartuStok,
    dataType: 'json',
    beforeSend : function(){
        $('.modal-load').show();
    },
    complete : function(){
        // process_done1($("#filter"), '<i class="fa fa-filter"></i> Filter');
    },
    success : function(data) {
        $('#t_kartu_stok tbody').html(data.table);
        $('#kode_show').html(data.detail_item.kode);
        $('#nama_item_show').html(data.detail_item.nama_item);
        $('#gudang_show').html(data.gudang);
        $('#satuan_show').html(data.detail_item.satuan);

        $('.modal-load').hide();
    }
    });
}


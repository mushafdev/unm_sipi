var urlList=$('#table').data('list');
var urlIndex=$('#table').data('url');

let tglDaftarVal = localStorage.getItem("filterTglDaftar") || "";
let statusVal = localStorage.getItem("filterStatus") || "";
let jenisLayananVal = localStorage.getItem("filterJenisLayanan") || "";

if (tglDaftarVal) {
    $('#tgl_daftar').val(tglDaftarVal);
}
if (statusVal) {
    $('#status').val(statusVal);
}
if (jenisLayananVal) {
    $('.jenis-layanan').removeClass('active');
    $('.jenis-layanan[data-id="'+jenisLayananVal+'"]').addClass('active');
}

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
            d.tgl_daftar = $('#tgl_daftar').val();               
            d.status = $('#status').val();   
            d.jenis_layanan = $('.jenis-layanan.active').data('id');             
        },
        "dataSrc": function (json) {
            return json.data;
        }
    },
    columns: [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'no_antrian', name: 'no_antrian'},
    {data: 'tgl_daftar', name: 'tgl_daftar', orderable: false},
    {data: 'jam', name: 'jam', orderable: false},
    {data: 'no_rm', name: 'no_rm', orderable: false},
    {data: 'nama', name: 'nama', orderable: false},
    {data: 'no_hp', name: 'no_hp', orderable: false},
    {data: 'dokter', name: 'dokter', orderable: false},
    {data: 'status', name: 'status', orderable: false},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "columnDefs": [
        {
            "targets": "datatable-nosort",
            "orderable": false,
        },
    ],
    createdRow: function (row, data, dataIndex) {
        if (data.cancel == 'Y') {
            $(row).addClass('bg-light-danger'); 
        }
    }

});

$(document).on('change', '.form-filter', function (e) {
    e.preventDefault();
    var filter=$(this).data('filter');
    if(filter == 'tgl_daftar'){
        localStorage.setItem("filterTglDaftar", $(this).val());
    }
    if(filter == 'status'){
        localStorage.setItem("filterStatus", $(this).val());
    }
    
    table.ajax.reload(null, true);
});

$(document).on('click', '.jenis-layanan', function (e) {
    e.preventDefault();
    localStorage.setItem("filterJenisLayanan", $(this).data('id'));
    table.ajax.reload(null, true);
});

$(document).on( 'click', '.delete', function () {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Are you sure?',
    text: "This record and it`s details will be permanantly canceled!",
    icon: "warning",
    allowOutsideClick: false,
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Cancel',
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


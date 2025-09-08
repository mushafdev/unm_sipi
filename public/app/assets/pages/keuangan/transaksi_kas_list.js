
var form=$("#form");
var urlIndex=$('#table').data('url');
var urlList=$('#table').data('list');
var urlStore=form.data('store');

$('#modal').on('shown.bs.modal', function () {
    initSelect2TransaksiKas('#modal');
});

var table;
table = $('#table').DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: false,
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
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.type = $('#filter_type').val();
        },
        dataSrc: function (json) {
            return json.data;
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'no_transaksi', name: 'no_transaksi' },
        { data: 'waktu', name: 'waktu' },
        { data: 'type', name: 'type' },
        { data: 'kategori', name: 'kategori' },
        { data: 'akun_kas', name: 'akun_kas' },
        { data: 'keterangan', name: 'keterangan' },
        { data: 'jumlah', name: 'jumlah', className: 'text-end' },
        { data: 'cancel', name: 'cancel' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ],
    columnDefs: [
        {
            targets: "datatable-nosort",
            orderable: false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        if (data.terkunci == 'Y') {
            $(row).addClass('bg-light-secondary'); 
        }
    }
});

$(document).on('change', '.form-filter', function (e) {
    e.preventDefault();
    table.ajax.reload(null, true);
});


$(document).on( 'click', '.create-transaksi', function () {
    var type = $(this).data("type");
    var title = $(this).data("title");
    $('.modal-load').hide();
    $('#form-title').text('Transaksi Kas');
    $('#title-show').html(title);
    $('#form').parsley().reset();
    $("#param").val('add');
    $('#form')[0].reset();
    $('#id').val('');
    $('#type').val(type);
    $(".select2-kategori-kas").val([]).trigger("change.select2");

});

$("#save").on('click',function(e) {
    e.preventDefault();
    if($("#form").parsley().validate()){
    var param = $(this);
    var form = $('form')[0];
    var form_data = new FormData(form);
    ax_save = $.ajax({
        type    : 'POST',
        data    : form_data,
        contentType : false,
        processData : false,
        cache: false,
        async:true,
        url     : urlStore,
        beforeSend: function() {
            process(param);
        },
        success : function(data) {
        processDone(param,'bi bi-save',' Simpan');
        $("#modal").modal('hide');
        table.ajax.reload( null, false );
        sweetalert2ToastTE('success','Success!',data.text);
        
        },
        error: function (reject) {
            processDone(param,'bi bi-save',' Simpan');
            $('#form').parsley().reset();
            if( reject.status === 422 ) {
                var data=reject.responseText;
                var jsonResponse = JSON.parse(data);
                $.each( jsonResponse.errors, function( key, value) {
                    $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                });
            }else{
                Swal.fire("Error", "There is an error", "error");
            }
        }
    });
    }else {
    return false;
    }

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


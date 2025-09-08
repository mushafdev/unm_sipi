

var form=$("#form");
var formTitle= $(".title").text();
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
var urlGetData=form.data('get-data');
var urlList=$('#table').data('list');


$(form).on('keydown', ':input', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        $('#save').click();
    }
});

$('#modal').on('shown.bs.modal', function () {
    $('#nama').trigger('focus');
});

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
    {data: 'id_karyawan', name: 'id_karyawan'},
    {data: 'nama', name: 'nama'},
    {data: 'jabatan', name: 'jabatan'},
    {data: 'no_hp', name: 'no_hp'},
    {data: 'email', name: 'email'},
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


$('#add').on('click',function(e){
    e.preventDefault();
    $('#form').parsley().reset();
    $("#param").val('add');
    $('#form-title').text('Tambah '+formTitle);
    processDone($('#save'),'bi bi-save',' Simpan');
    $('#form')[0].reset();
    $('#id').val('');
    $('#alamat').html('');
    $('.modal-load').hide();
    $("#modal").modal('show');
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

$(document).on( 'click', '.edit', function () {
    $('.modal-load').show();
    $('#form-title').text('Edit '+formTitle);
    $('#form').parsley().reset();
    var id = $(this).data("id");
    $.ajax({
    type    : 'GET',
    data    : {id:id},
    url     : urlGetData,
    dataType: 'json',
    success : function(data) {
        $('#id').val(data.id);
        $('#param').val('edit');
        $("#nama").val(data.result.nama);
        $("#jabatan_id").val(data.jabatan_id);
        $("#alamat").val(data.result.alamat);
        $("#email").val(data.result.email);
        $("#no_hp").val(data.result.no_hp);
        $("#nik").val(data.result.nik);
        $("#id_karyawan").val(data.result.id_karyawan);
        $('.modal-load').hide();
    }
    });

});

$(document).on( 'click', '.delete', function () {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Apakah anda yakin?',
    text: "Data ini akan dihapus permanen",
    icon: "warning",
    allowOutsideClick: false,
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus',
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


var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
var urlSend=form.data('send');
var urlVerifikasi=form.data('verifikasi');
var urlVerifikasiMhs=form.data('verifikasi-mhs');
var urlDeleteItem=$("#t_group").data('delete-item');
var no=0;
$("#add-group").on('click', function() {
    
    no++;
    var html='';
    html+='<tr class="group_list">';

    html+='<td class="text-left">';
    html+='<select class="form-select mahasiswa_id select2-mahasiswa" name="mahasiswa_id[]" style="width: 100%" required data-parsley-errors-container="#mahasiswa-errors'+no+'">';
    html+='<option value=""></option>';
    html+='</select>';
    html+='<span id="mahasiswa-errors'+no+'"></span>';
    html+='</td>';
    html+='<td class="nama">-</td>';
    html+='<td class="kelas">-</td>';
    html+='<td class="text-center">'+'<button type="button" class="btn icon btn-danger delete_mahasiswa"><i class="bi bi-trash"></i></button>'+'</td>';
    html+='</tr>';
    $('#t_group tbody').append(html);

    select2ListMahasiswa();
    


});

$('#t_group').on('select2:select','.select2-mahasiswa', function (e) {
    $(this).closest('tr').find('td:eq(1)').html(e.params.data.nama);
    $(this).closest('tr').find('td:eq(2)').html(e.params.data.kelas);
});

$('#t_group').on('click','.delete_mahasiswa', function() {
    $(this).closest('tr').remove();
});

$('#t_group').on('click','.delete_permanent', function() {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Are you sure?',
    text: "Data ini mungkin telah diverifikasi. Mahasiswa akan melakukan verifikasi ulang jika data dihapus",
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
            type    : 'POST',
            url     : urlDeleteItem,
            data    : {id:id},
            success : function(data) {
                Swal.fire('Success!',data.text,'success');
                location.reload();
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
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
        Swal.fire({
                title: "Success!",
                text: data.text,
                icon: "success",
                timer: 2000
        }).then((result) => {
            // location.replace(urlIndex);
            location.reload();

        });
        },
        error: function (reject) {
            processDone(param,'bi bi-save',' Simpan');
            $('#form').parsley().reset();
            if( reject.status === 422 ) {
                var data=reject.responseText;
                var jsonResponse = JSON.parse(data);
                $.each( jsonResponse.errors, function( key, value) {
                    if (key.indexOf('.') == -1) {
                        if(key!='lokasi_pi_id'){
                            $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        }else{
                            $('#lokasi-pi-errors').parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        }
                    }else{
                        arr=key.split('.');
                        $("."+arr[0]).eq(arr[1]).parsley().addError('uniqueerror', {message: value[0], updateClass: true});    
                    }
                });
            }else{
                Swal.fire("Error", "There is an error", "error");
            }
        }
    });
    }else {
    return false();
    }

});

$("#update").on('click',function(e) {
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
        url     : urlUpdate,
        beforeSend: function() {
            process(param);
        },
        success : function(data) {
        processDone(param,'bi bi-save',' Update');
        Swal.fire({
                title: "Success!",
                text: data.text,
                icon: "success",
                timer: 2000
        }).then((result) => {
            // location.replace(urlIndex);
            location.reload();

        });
        },
        error: function (reject) {
            processDone(param,'bi bi-save',' Update');
            $('#form').parsley().reset();
            if( reject.status === 422 ) {
                var data=reject.responseText;
                var jsonResponse = JSON.parse(data);
                $.each( jsonResponse.errors, function( key, value) {
                    if (key.indexOf('.') == -1) {
                        if(key!='lokasi_pi_id'){
                            $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        }else{
                            $('#lokasi-pi-errors').parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        }  
                    }else{
                        arr=key.split('.');
                        $("."+arr[0]).eq(arr[1]).parsley().addError('uniqueerror', {message: value[0], updateClass: true});    
                    }
                });
            }else{
                Swal.fire("Error", "There is an error", "error");
            }
        }
    });
    }else {
    return false();
    }

});

$("#delete").on('click',function(e) {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Are you sure?',
    text: "Anda akan menghapus semua data terkait kelompok ini",
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
                location.replace('/group/create');
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
});

$("#send").on('click',function(e) {
    var id = $(this).data("id");
    Swal.fire({
    title: 'Are you sure?',
    text: "Data akan dikirim ke admin. Setelah dikirim, anda tidak dapat mengubah data kelompok",
    icon: "warning",
    allowOutsideClick: false,
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Kirim',
    cancelButtonText: 'No'
    }).then((result) => {
    if (result.value) {
        $.ajax({
            type    : 'POST',
            url     : urlSend,
            data    : {id:id},
            success : function(data) {
                Swal.fire('Success!',data.text,'success');
                location.reload();
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
});


$("#verifikasi").on('click',function(e) {
    e.preventDefault();
    if($("#form").parsley().validate()){
        var id = $(this).data("id");
        Swal.fire({
        title: 'Are you sure?',
        text: "Data kelompok akan diverifikasi",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Verifikasi',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
            var param = $(this);
            var form = $('form')[0];
            var form_data = new FormData(form);
            form_data.append('id',id);
            $.ajax({
                type    : 'POST',
                contentType : false,
                processData : false,
                cache: false,
                async:true,
                data    : form_data,
                url     : urlVerifikasi,
                beforeSend: function() {
                    process(param);
                },
                success : function(data) {
                    processDone(param,'bi bi-check',' Verifikasi');
                    Swal.fire('Success!',data.text,'success');
                    location.reload();
                },
                error : function(){
                    processDone(param,'bi bi-check',' Verifikasi');
                    Swal.fire("Error", "There is an error", "error");
                }
            });
        }
        })
    }else {
    return false();
    }
});

$("#verifikasi-mhs").on('click',function(e) {
    e.preventDefault();
    if($("#form").parsley().validate()){
        var id = $(this).data("id");
        Swal.fire({
        title: 'Are you sure?',
        text: "Data kelompok akan diverifikasi",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Verifikasi',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
            var param = $(this);
            var form = $('form')[0];
            var form_data = new FormData(form);
            form_data.append('id',id);
            $.ajax({
                type    : 'POST',
                contentType : false,
                processData : false,
                cache: false,
                async:true,
                data    : form_data,
                url     : urlVerifikasiMhs,
                beforeSend: function() {
                    process(param);
                },
                success : function(data) {
                    processDone(param,'bi bi-check',' Verifikasi');
                    Swal.fire('Success!',data.text,'success');
                    location.reload();
                },
                error : function(){
                    processDone(param,'bi bi-check',' Verifikasi');
                    Swal.fire("Error", "There is an error", "error");
                }
            });
        }
        })
    }else {
    return false();
    }
});


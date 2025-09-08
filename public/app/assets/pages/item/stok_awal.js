var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
var urlList=$('#table').data('list');
var urlUpdateItem=$('#table').data('update-item');


$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})


$("#save").on('click',function(e) {
    e.preventDefault();
    if($("#form").parsley().validate()){
        Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang sudah diinput tidak bisa diubah lagi",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.value) {
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
                    location.replace(urlIndex+'/'+data.id);

                });
                },
                error: function (reject) {
                    processDone(param,'bi bi-save',' Simpan');
                    $('#form').parsley().reset();
                    if (reject.status === 422) {
                        handleParsleyErrors(reject.responseJSON.errors);
                    } else {
                        Swal.fire("Error", "Terjadi kesalahan sistem", "error");
                    }
                }
            });
        }
        })
    
    }else {
    return false;
    }

});

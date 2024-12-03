var form=$("#form");
var urlVerifikasi=form.data('verifikasi');

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


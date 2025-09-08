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
            url     : $(this).data('url')+'/'+id,
            success : function(data) {
                sweetalert2ToastTE('success','Success!',data.text);
                location.reload();
            },
            error : function(){
                Swal.fire("Error", "There is an error", "error");
            }
        });
    }
    })
});


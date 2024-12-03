var form=$("#form");
var urlStore=form.data('store');

$('.summernote').summernote({
    height: 300, 
    minHeight: null, 
    maxHeight: null, 
});
$('#kebutuhan_pekerjaan').inputTags();

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
                        $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
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

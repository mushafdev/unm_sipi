var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');

$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})

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
            location.replace(urlIndex);

        });
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
        processDone(param,'bi bi-save',' Simpan');
        Swal.fire({
                title: "Success!",
                text: data.text,
                icon: "success",
                timer: 2000
        }).then((result) => {
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


$(".permissionTable").on('click', '.selectall', function () {

    if ($(this).is(':checked')) {
        $(this).closest('tr').find('[type=checkbox]').prop('checked', true);

    } else {
        $(this).closest('tr').find('[type=checkbox]').prop('checked', false);

    }

    calcu_allchkbox();

});

$(".permissionTable").on('click', '.grand_selectall', function () {
    if ($(this).is(':checked')) {
        $('.selectall').prop('checked', true);
        $('.permissioncheckbox').prop('checked', true);
    } else {
        $('.selectall').prop('checked', false);
        $('.permissioncheckbox').prop('checked', false);
    }
});

$(function () {

    calcu_allchkbox();
    selectall();

});

function selectall(){

    $('.selectall').each(function (i) {

        var allchecked = new Array();

        $(this).closest('tr').find('.permissioncheckbox').each(function (index) {
            if ($(this).is(":checked")) {
                allchecked.push(1);
            } else {
                allchecked.push(0);
            }
        });

        if ($.inArray(0, allchecked) != -1) {
            $(this).prop('checked', false);
        } else {
            $(this).prop('checked', true);
        }

    });
}

function calcu_allchkbox(){

    var allchecked = new Array();

    $('.selectall').each(function (i) {


        $(this).closest('tr').find('.permissioncheckbox').each(function (index) {
            if ($(this).is(":checked")) {
                allchecked.push(1);
            } else {
                allchecked.push(0);
            }
        });


    });

    if ($.inArray(0, allchecked) != -1) {
        $('.grand_selectall').prop('checked', false);
    } else {
        $('.grand_selectall').prop('checked', true);
    }

}



$('.permissionTable').on('click', '.permissioncheckbox', function () {

    var allchecked = new Array;

    $(this).closest('tr').find('.permissioncheckbox').each(function (index) {
        if ($(this).is(":checked")) {
            allchecked.push(1);
        } else {
            allchecked.push(0);
        }
    });

    if ($.inArray(0, allchecked) != -1) {
        $(this).closest('tr').find('.selectall').prop('checked', false);
    } else {
        $(this).closest('tr').find('.selectall').prop('checked', true);

    }

    calcu_allchkbox();

});

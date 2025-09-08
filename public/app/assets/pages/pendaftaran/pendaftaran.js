var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
var urlDetailPasien=form.data('detail-pasien');
var PasienId=$('#pasien_id').val();
$('.modal-load').hide();
if(PasienId !== '') {
    detailPasien(PasienId);
}
$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})

$('#filter-key').on('select2:select', function (e) {
    let data = e.params.data;
    detailPasien(data.id);

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


$('#tableCariPasien').on('click','tr',function() {
    var data=table.row(this).data();
    detailPasien(data['id_enc']);
    $('#modal-result-search').modal('hide');
    let offcanvas = bootstrap.Offcanvas.getOrCreateInstance($('#offcanvasSearch')[0]);
    offcanvas.hide();
});

function detailPasien(id) {
    $.ajax({
        type    : 'GET',
        data    : {id:id},
        url     : urlDetailPasien,
        dataType: 'json',
        beforeSend: function() {
            $('.modal-load').show();
        },
        success : function(data) {
            $('#pasien_id').val(data.id);
            $('.no_rm').text(data.result.no_rm);
            $('.nama').text(data.result.nama);
            $('.panggilan').text(data.result.panggilan);
            $('.nik').text(data.result.nik);  
            $('.tgl_lahir').text(data.result.tgl_lahir);
            $('.no_hp').text(data.result.no_hp);
            $('.alamat').text(data.result.alamat);
            $('.jenis_kelamin').text(data.result.jenis_kelamin);
            $('.modal-load').hide();
        }
        });
}


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.fileName = input.files[0].name;
        reader.onload = function(e) {
            $('#photo-show').attr('src', e.target.result);
            // $('#file_name').text(e.target.fileName);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#photo").on('change',function() {
    readURL(this);
});

const compressImage = async (file, { quality = 1, type = file.type }) => {
    // Get as image data
    const imageBitmap = await createImageBitmap(file);

    // Draw to canvas
    const canvas = document.createElement('canvas');
    canvas.width = imageBitmap.width;
    canvas.height = imageBitmap.height;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(imageBitmap, 0, 0);

    // Turn into Blob
    const blob = await new Promise((resolve) =>
        canvas.toBlob(resolve, type, quality)
    );

    // Turn Blob into File
    return new File([blob], file.name, {
        type: blob.type,
    });
};

const input = document.querySelector('#photo');
input.addEventListener('change', async (e) => {
    // Get the files
    const { files } = e.target;

    // No files selected
    if (!files.length) return;

    // We'll store the files in this data transfer object
    const dataTransfer = new DataTransfer();

    // For every file in the files list
    for (const file of files) {
        // We don't have to compress files that aren't images
        if (!file.type.startsWith('image')) {
            // Ignore this file, but do add it to our result
            dataTransfer.items.add(file);
            continue;
        }

        // We compress the file by 50%
        const compressedFile = await compressImage(file, {
            quality: 0.5,
            type: 'image/jpeg',
        });

        // Save back the compressed file instead of the original file
        dataTransfer.items.add(compressedFile);
    }
    // console.log(dataTransfer.files);
    // Set value of the file input to our new files list
    e.target.files = dataTransfer.files;
});

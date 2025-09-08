var form=$("#form");
var urlUpdate=form.data('update');

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


var input = document.getElementById('logo');
var $modal = $('#modal');
var logo = document.getElementById('logo-show');
var image = document.getElementById('image');
var cropper;


input.addEventListener('change', function (e) {
  var files = e.target.files;
  var done = function (url) {
    input.value = '';
    image.src = url;
    $modal.modal('show');
  };
  var reader;
  var file;
  var url;

  if (files && files.length > 0) {
    file = files[0];

    if (URL) {
      done(URL.createObjectURL(file));
    } else if (FileReader) {
      reader = new FileReader();
      reader.onload = function (e) {
        done(reader.result);
      };
      reader.readAsDataURL(file);
    }
  }
});

$modal.on('shown.bs.modal', function () {
  cropper = new Cropper(image, {
    aspectRatio: 1,
    viewMode: 3,
  });
}).on('hidden.bs.modal', function () {
  cropper.destroy();
  cropper = null;
});

document.getElementById('crop').addEventListener('click',  async (e) => {
  var initialAvatarURL;
  var canvas;
  
  $modal.modal('hide');

  if (cropper) {
    canvas = cropper.getCroppedCanvas({
      width: 200,
      height: 200,
    });
    initialAvatarURL = logo.src;
    logo.src = canvas.toDataURL();
     // Turn into Blob
     const blob = await  new Promise((resolve) =>
        canvas.toBlob(resolve, 'image/png', 0.5)
    );

    // Turn Blob into File
    const compressedFile= await new File([blob], 'logo.png', {
        type: blob.type,
    });

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(compressedFile);
    console.log(dataTransfer.files);
    input.files = dataTransfer.files;
  }
});
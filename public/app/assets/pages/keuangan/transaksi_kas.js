var form=$("#form");
var urlStore=form.data('store');
var urlDetail=form.data('detail');
var urlUpdate=form.data('update');
var urlIndex=form.data('index');
$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})

let rowNumber = 1;

$('#tambah-item').click(function () {
    rowNumber++;

    let row = `
    <tr>
        <td class="text-center no">${rowNumber}</td>
        <td class="text-left">
            <select class="form-control item select2-item" name="item_id[]" style="width: 100%" required data-parsley-errors-container="#item-errors${rowNumber}">
                <option value=""></option>
                <!-- Tambahkan option item via JS atau Blade jika perlu -->
            </select>
            <span id="item-errors${rowNumber}"></span>
        </td>
        <td class="text-center"><input class="form-control text-center qty" type="number" min="1" name="qty[]" value="1"></td>
        <td class="text-center"><span class="satuan">-</span></td>
        <td class="text-center"><input class="form-control tgl-kadaluarsa" type="date" name="tgl_kadaluarsa[]" required></td>
        <td class="text-center"><input class="form-control no-batch" type="text" name="no_batch[]" placeholder="No. Batch"></td>
        <td class="text-center"><button type="button" class="btn btn-danger delete_item"><i class="bi bi-trash"></i></button></td>
    </tr>`;

    $('#t_item tbody').append(row);

    // Jika menggunakan Select2, inisialisasi ulang
    
    select2ListItem();

    updateRowNumbers();
});

$('#t_item').on('select2:select','.select2-item', function (e) {
    var satuan=e.params.data.satuan;
    $(this).closest('tr').find('td:eq(3) .satuan').html(satuan);
});

// Hapus baris
$(document).on('click', '.delete_item', function () {
    $(this).closest('tr').remove();
    updateRowNumbers();
});

// Update nomor urut otomatis
function updateRowNumbers() {
    rowNumber = 0;
    $('#t_item tbody tr').each(function () {
        rowNumber++;
        $(this).find('td.no').text(rowNumber);
    });
}

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
            location.replace(urlIndex+'/'+data.id);

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

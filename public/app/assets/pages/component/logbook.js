
var urlGetData=$('#modal-logbook').data('get-data');

$('.detail-logbook').on('click', function() {
    var id=$(this).data('id');
    $('.modal-load').show();
    $('#modal-logbook').modal('show');
    $('.list-kegiatan').html('');
    $.ajax({
        type    : 'GET',
        data    : {id:id},
        url     : urlGetData,
        dataType: 'json',
        success : function(data) {
            $.each( data.detail, function( key, value) {
                $('.detail-'+key).html(value);
            });
            var html='';
            $.each( data.logbooks, function( key, value) {
                html+='<a href="#" class="list-group-item list-group-item-action">';
                    html+='<div class="d-flex w-100 mb-2">';
                        html+='<div class="badge badge-pill bg-light-info me-1">'+value['tanggal']+'</div>';
                        html+='<div class="badge badge-pill bg-light-secondary me-1">'+value['jam']+'</div>';
                    html+='</div>';
                    html+='<p class="mb-1">'+value['kegiatan']+'</p>';
                html+='</a>';
                
            });
            if(html==''){
                $('.list-kegiatan').append('<h6 class="text-danger">Belum ada kegiatan</h6>');
            }
            $('.list-kegiatan').append(html);
            $('.modal-load').hide();
        }
        });
   
});






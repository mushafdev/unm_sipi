
var dropdownMenu;

// and when you show it, move it to the body                                     
$(window).on('show.bs.dropdown', function (e) {
    
    // grab the menu        
    dropdownMenu = $(e.target).next();
    console.log(dropdownMenu);

    // detach it and append it to the body
    $('body').append(dropdownMenu.detach());

    // grab the new offset position
    var eOffset = $(e.target).offset();

    // make sure to place it where it would normally go (this could be improved)
    dropdownMenu.css({
        'display': 'block',
            'top': eOffset.top + $(e.target).outerHeight(),
            'left': eOffset.left
    });
});

// and when you hide it, reattach the drop down, and hide it normally                                                   
$(window).on('hide.bs.dropdown', function (e) {
    $(e.target).parent().append(dropdownMenu.detach());
    dropdownMenu.hide();
});

$('.form-control').on('keyup change', function() {
    $(this).parsley().validate();
})

$('.select2').select2();



$('.select2-mahasiswa').select2({
    placeholder: "Ketik NIM/Nama",
    ajax: {
        url: window.location.origin+'/search-mahasiswa',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                searchTerm: params.term,
                isExist: $("select[name='mahasiswa_id[]']")
                .map(function(){return $(this).val();}).get()
            };
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },

    minimumInputLength: 3,
    templateResult: formatSelectMahasiswa,
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function process(p){
    p.attr('disabled',true);
    p.find('i').attr('class', 'spinner-border spinner-border-sm');
    p.contents().filter(function() {
        return this.nodeType == 3; // Node type 3 is a text node
    }).each(function(){
        this.nodeValue = ' Processing...';
    });
}

function processDone(p,i,text){
    p.attr('disabled',false);
    p.find('i').attr('class', i);
    p.contents().filter(function() {
        return this.nodeType == 3; // Node type 3 is a text node
    }).each(function(){
        this.nodeValue = text;
    });
}

function select2ListMahasiswa(){
    $('.select2-mahasiswa').select2({
        placeholder: "Ketik NIM/Nama",
        ajax: {
            url: window.location.origin+'/search-mahasiswa',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term,
                    isExist: $("select[name='mahasiswa_id[]']")
                    .map(function(){return $(this).val();}).get()
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
    
        minimumInputLength: 3,
        templateResult: formatSelectMahasiswa,
    });
}

function formatSelectMahasiswa (repo) {
    if (repo.loading) {
      return repo.text;
    }
  
    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'></div>" +
          "<div class='select2-result-repository__description'></div>" +
          "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
            "<div class='select2-result-repository__categories'><i class='fa fa-folder'></i> </div>" +
          "</div>" +
        "</div>" +
      "</div>"
    );
  
    $container.find(".select2-result-repository__title").text(repo.nama);
    $container.find(".select2-result-repository__description").text(repo.nim);
    $container.find(".select2-result-repository__stargazers").append(repo.kelas);
    // $container.find(".select2-result-repository__categories").append(repo.kategori);
  
    return $container;
}
 

var dropdownMenu;

// and when you show it, move it to the body                                     
$(window).on('show.bs.dropdown', function (e) {
    
    // grab the menu        
    dropdownMenu = $(e.target).next();

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

const elementsCurrencyDecimal = document.querySelectorAll('.currency-decimal');
elementsCurrencyDecimal.forEach(el => {
    new AutoNumeric(el, {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
        minimumValue: '0',
        decimalPlaces: 2,
        modifyValueOnWheel: false,     
        decimalPlacesShownOnBlur: 0,
    });
});

const elementsCurrency = document.querySelectorAll('.currency');
elementsCurrency.forEach(el => {
    new AutoNumeric(el, {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
        minimumValue: '0',
        decimalPlaces: 2,
        modifyValueOnWheel: false,     
        decimalPlacesShownOnBlur: 0,
        decimalPlacesShownOnFocus: 0,
    });
});

$('.select2').select2({
    placeholder: "Pilih",
    allowClear: true,
});

$('.select2-prov').on('change', function() {
    $(".select2-kab,.select2-kec,.select2-kel").val([]).trigger("change.select2");
});

$('.select2-kab').on('change', function() {
    $(".select2-kec,.select2-kel").val([]).trigger("change.select2");
});

$('.select2-kec').on('change', function() {
    $(".select2-kel").val([]).trigger("change.select2");
});

$('.select2-item').select2({
    placeholder: "Ketik Kode/Nama Item",
    ajax: {
        url: window.location.origin+'/data/search-item',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                searchTerm: params.term,
            };
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },
});

function initSelect2TransaksiKas(containerSelector = 'body'){
    $('.select2-kategori-kas').select2({
        placeholder: "Ketik Nama Kategori",
        allowClear: true,
        width: '100%',
        dropdownParent: $(containerSelector), 
        ajax: {
            url: window.location.origin+'/data/search-kategori-kas',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var query = {
                    searchTerm: params.term,
                    x: $("#type").val()
                }
            
                return query;
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
    });
}

function initSelect2Item(containerSelector = 'body'){
    $('.select2-item').select2({
        placeholder: "Ketik Kode/Nama Item",
        minimumInputLength: 3,
        width: '100%',
        allowClear: true,
        dropdownParent: $(containerSelector), 
        ajax: {
            url: window.location.origin+'/data/search-item',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true,
        },

        templateResult: formatSelectItem,
    });
}

function initSelect2Tindakan(containerSelector = 'body'){
    $('.select2-tindakan').select2({
        placeholder: "Ketik Nama Tindakan",
        minimumInputLength: 3,
        width: '100%',
        allowClear: true,
        dropdownParent: $(containerSelector), 
        ajax: {
            url: window.location.origin+'/data/search-service',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
            var query = {
                searchTerm: params.term,
            }
        
            return query;
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
        templateResult: formatSelectTindakan,
        // templateSelection: formatSelectPasienSelection
        
    });
}

function initSelect2Dokter(containerSelector = 'body'){
    $('.select2-dokter').select2({
        placeholder: "Ketik Nama Dokter",
        minimumInputLength: 3,
        width: '100%',
        allowClear: true,
        dropdownParent: $(containerSelector), 
        ajax: {
            url: window.location.origin+'/data/search-dokter',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
            var query = {
                searchTerm: params.term,
            }
        
            return query;
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
    });
}

function initSelect2Perawat(containerSelector = 'body'){
    $('.select2-perawat').select2({
        placeholder: "Ketik Nama Perawat",
        minimumInputLength: 3,
        width: '100%',
        allowClear: true,
        dropdownParent: $(containerSelector), 
        ajax: {
            url: window.location.origin+'/data/search-perawat',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
            var query = {
                searchTerm: params.term,
            }
        
            return query;
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true,
        },
    });
}

$('.select2-pasien').select2({
    placeholder: "Ketik No. RM/Nama/No.HP/Alamat",
    minimumInputLength: 3,
    allowClear: true,
    ajax: {
        url: window.location.origin+'/data/search-pasien',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
        var query = {
            searchTerm: params.term,
        }
    
        return query;
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },
    templateResult: formatSelectPasien,
    // templateSelection: formatSelectPasienSelection
    
});

$('.select2-prov').select2({
    placeholder: "Pilih",
    allowClear: true,
    ajax: {
        url: window.location.origin+'/data/search-provinsi',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
        var query = {
            searchTerm: params.term,
        }
    
        return query;
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },
    
});

$('.select2-kab').select2({
    placeholder: "Pilih",
    allowClear: true,
    ajax: {
        url: window.location.origin+'/data/search-kabupaten',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
        var query = {
            searchTerm: params.term,
            x: $(".select2-prov").find(':selected').val()
        }
    
        return query;
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },
    
});

$('.select2-kec').select2({
    placeholder: "Pilih",
    allowClear: true,
    ajax: {
        url: window.location.origin+'/data/search-kecamatan',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
        var query = {
            searchTerm: params.term,
            x: $(".select2-kab").find(':selected').val()
        }
    
        return query;
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },
    
});

$('.select2-kel').select2({
    placeholder: "Pilih",
    allowClear: true,
    ajax: {
        url: window.location.origin+'/data/search-kelurahan',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
        var query = {
            searchTerm: params.term,
            x: $(".select2-kec").find(':selected').val()
        }
    
        return query;
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },

    
});

$('.select2-tag').select2({
    tags: true,
    tokenSeparators: [','],
    placeholder: 'Tambah atau pilih tag',
    allowClear: true,
    ajax: {
        url: window.location.origin+'/data/search-tag',
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: function(params) {
        var query = {
            searchTerm: params.term,
        }
    
        return query;
        },
        processResults: function(response) {
            return {
                results: response
            };
        },
        cache: true,
    },

    
});



function formatSelectTindakan (repo) {
    if (repo.loading) {
      return repo.text;
    }
  
    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'></div>" +
          "<div class='select2-result-repository__description'>"+
                "<span class='h5 me-1 select2-result-harga'></span>"+
                "<span class='badge bg-light-primary text-primary me-1 select2-result-kategori'></span>"+
          "</div>" +
          "<div class='select2-result-repository__subtitle'></div>" +
          "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
            "<div class='select2-result-repository__categories'><i class='fa fa-folder'></i> </div>" +
          "</div>" +
        "</div>" +
      "</div>"
    );
  
    $container.find(".select2-result-repository__title").text(repo.text);
    $container.find(".select2-result-repository__description .select2-result-harga").text(repo.harga_jual_show);
    $container.find(".select2-result-repository__description .select2-result-kategori").text(repo.kategori);
  
    return $container;
}

function formatSelectItem (repo) {
    if (repo.loading) {
      return repo.text;
    }
  
    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'></div>" +
          "<div class='select2-result-repository__description'>"+
                "<span class='h5 me-1 select2-result-harga'></span>"+
                "<span class='badge bg-light-primary text-primary me-1 select2-result-kategori'></span><br>"+
                "<span class='text-muted me-1 select2-result-stok'></span>"+
          "</div>" +
          "<div class='select2-result-repository__subtitle'></div>" +
          "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
            "<div class='select2-result-repository__categories'><i class='fa fa-folder'></i> </div>" +
          "</div>" +
        "</div>" +
      "</div>"
    );
  
    $container.find(".select2-result-repository__title").text(repo.text);
    $container.find(".select2-result-repository__description .select2-result-harga").text(repo.harga_jual_show);
    $container.find(".select2-result-repository__description .select2-result-kategori").text(repo.kategori);
    $container.find(".select2-result-repository__description .select2-result-stok").text(repo.stok);
  
    return $container;
}

function formatSelectPasien (repo) {
    if (repo.loading) {
      return repo.text;
    }
  
    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'></div>" +
          "<div class='select2-result-repository__description'>"+
                "<span class='badge bg-light-warning text-warning me-1 select2-result-tgl-lahir'></span>"+
                "<span class='badge bg-light-primary text-primary me-1 select2-result-no-hp'></span>"+
                "<span class='badge bg-light-danger text-danger me-1 select2-result-nik'></span><br>"+
                "<span class='badge bg-light-secondary text-secondary me-1 mt-1 select2-result-alamat text-start'></span>"+
          "</div>" +
          "<div class='select2-result-repository__subtitle'></div>" +
          "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
            "<div class='select2-result-repository__categories'><i class='fa fa-folder'></i> </div>" +
          "</div>" +
        "</div>" +
      "</div>"
    );
  
    $container.find(".select2-result-repository__title").text(repo.text);
    $container.find(".select2-result-repository__description .select2-result-tgl-lahir").text(repo.tgl_lahir);
    $container.find(".select2-result-repository__description .select2-result-no-hp").text(repo.no_hp);
    $container.find(".select2-result-repository__description .select2-result-nik").text(repo.nik);
    $container.find(".select2-result-repository__description .select2-result-alamat").text(repo.alamat);
  
    return $container;
}
  

function handleParsleyErrors(jsonErrors) {
    $.each(jsonErrors, function(key, messages) {
        let message = messages[0];

        if (!key.includes('.')) {
            // Untuk field biasa (misal: tanggal)
            let el = $('[name="' + key + '"]');
            if (el.length && typeof el.parsley === 'function') {
                el.parsley().addError('ajax', {
                    message: message,
                    updateClass: true
                });
            }
        } else {
            // Untuk field array (misal: item_id.0 atau qty.2)
            let parts = key.split('.');
            let name = parts[0] + '[]';
            let index = parseInt(parts[1]);

            let el = $('[name="' + name + '"]').eq(index);
            if (el.length && typeof el.parsley === 'function') {
                el.parsley().addError('ajax', {
                    message: message,
                    updateClass: true
                });
            }
        }
    });
}

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

function processIcon(p){
    p.attr('disabled',true);
    p.find('i').attr('class', 'spinner-border spinner-border-small spinner-border-sm ');
    
}

function processIconDone(p,i){
    p.attr('disabled',false);
    p.find('i').attr('class', i);
}

function sweetalert2ToastTE(type,title,text){
    Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: title,
            showConfirmButton: false,
            text: text,
            icon: type,
            timer: 2000
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function roundUpToNearest(value, roundTo = 100) {
    return Math.ceil(value / roundTo) * roundTo;
}


// Global parsley validation styling untuk select2
window.Parsley.on('field:validated', function () {
    const $element = $(this.$element);

    if ($element.is('select') && $element.hasClass('select2-hidden-accessible')) {
        const $select2Container = $element.next('.select2-container');

        if (this.isValid()) {
        $select2Container.removeClass('is-invalid');
        } else {
        $select2Container.addClass('is-invalid');
        }
    }
});


 
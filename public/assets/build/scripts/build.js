var laravelToken = $('meta[name="csrf-token"]').attr('content');
$(document).ajaxError(function(event, jqhxr){
    var response = jqhxr.responseJSON;
    if((jqhxr.status == 401 && response.message == 'Unauthenticated.') || (jqhxr.status == 419 && response.message == 'CSRF token mismatch.')){
        toastr.error('Failed', 'Session Expired');
        setTimeout(() => { window.location.href=(response.url != undefined ? response.url : '/login') }, 200);
    }
    if(jqhxr.status == 403){
        toastr.error('Failed', response.message);
    }
});

axios.defaults.headers.common['X-CSRF-TOKEN'] = laravelToken;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': laravelToken
    }
});
$(function() {
    $('body').buildForm();
    $.extend(true, $.fn.dataTable.defaults, {
        initComplete : function() {
            $('[data-toggle="tooltip"]', this).tooltip();
        }
    });
    
    changeLogo();
    $("#theme-toggle").click(() => { changeLogo() });

    if ($('#sidebar .menu-item a[href="'+window.location.href+'"]')) {
        $('#sidebar .menu-item a[href="'+window.location.href+'"]').length == 0 && $('#sidebar .menu-item a[href="'+window.location.origin+'"]').addClass('active');
        $('#sidebar .menu-item a[href="'+window.location.href+'"]').addClass('active');
        $('#sidebar .menu-item .menu-submenu .menu-item a[href="'+window.location.href+'"]').addClass('active');
        $('#sidebar .menu-item .menu-submenu .menu-item a[href="'+window.location.href+'"]').parent().parent().parent().addClass('active');
        $('#sidebar .menu-item .menu-submenu .menu-item a[href="'+window.location.href+'"]').parent().parent().parent().children('.menu-item-toggle').addClass('active');
        $('#sidebar .menu-item .menu-submenu .menu-item a[href="'+window.location.href+'"]').parent().parent().css('height', 'auto');
        $('#sidebar .menu-item .menu-submenu .menu-item a[href="'+window.location.href+'"]').parent().css('background', 'rgba(224, 224, 224, 0.6)');
    }
});

feather.replace();


$.fn.buildForm = function() {
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    $.each(this, function(key, elem) {
        $('[data-input-type="select2"]', elem).each(function(key, elem) {
            // var containerCssClass = '';
            if ($(elem).hasClass('form-control-sm')) {
                // containerCssClass = 'select2-sm';
            }
            if ($(elem).parents('.dataTables_filter').length == 0) {
                $(elem).css('width', '100%');
            }
            if ($(elem).parents('.bootbox.modal').length == 1) {
                $(elem).select2({
                    dropdownParent: $('.bootbox.modal'),
                    // containerCssClass: containerCssClass,
                    dropdownAutoWidth: false
                });
            } else if ($(elem).parents('.modal').length == 1) {
                $(elem).select2({
                    dropdownParent: $('.modal'),
                    // containerCssClass: containerCssClass,
                    dropdownAutoWidth: false
                });
            } else {
                $(elem).select2({
                    // containerCssClass: containerCssClass,
                    dropdownAutoWidth: false
                });
            }
        });
        $('[data-input-type="number-format"]', elem).each(function(key, elem) {
            var thousand_separator = lang['thousand_separator'];
            if ($(elem).data('thousand-separator') != undefined) {
                if ($(elem).data('thousand-separator') == false) {
                    thousand_separator = '';
                } else {
                    thousand_separator = $(elem).data('thousand-separator');
                    if (thousand_separator === true) {
                        thousand_separator = lang['thousand_separator'];
                    }
                }
            }
            var decimal_separator = lang['decimal_separator'];
            if ($(elem).data('decimal-separator') != undefined) {
                if ($(elem).data('decimal-separator') == false) {
                    decimal_separator = '';
                } else {
                    decimal_separator = $(elem).data('decimal-separator');
                    if (decimal_separator === true) {
                        decimal_separator = lang['decimal_separator'];
                    }
                }
            }
            var precision = 2;
            if ($(elem).data('precision') != undefined ) {
                precision = parseInt($(elem).data('precision'));
            }
            $(elem).number(true, precision, decimal_separator, thousand_separator).on('focus', function(){$(this).select()});
        });
        $('[data-input-type="datepicker"]', elem).each(function(key, elem) {
            if ($(elem).data('end-date')) {
                var endDate = new Date($(elem).data('end-date'));
            } else {
                var endDate = false;
            }

            $(elem).datepicker({
                format : lang['datepicker_format'],
                endDate : endDate,
                enableOnReadonly : false
            });
        });
        $('[data-input-type="timepicker"]', elem).each(function(key, elem) {
            $(elem).timepicker({
                showMeridian: false
            });
        });
        $('[data-input-type="dateinput"]', elem).each(function(key, elem) {
            $(elem).inputmask('datetime', {
                placeholder: 'dd-mm-yyyy',
                mask: "99-99-9999",
                greedy: false,
            });
        });
        $('[data-input-type="datetime"]', elem).each(function(key, elem) {
            $(elem).datetimepicker({
                format: lang['datetime']
            });
        });
        $('[data-input-type="number"]', elem).each(function(key, elem) {
            $(elem).inputmask({
                mask: '9',
                repeat: 16,
                greedy: false,
                numericInput: true
            });
        });
    });
}

function swalConfirm(msg, action) {
    Swal.fire({
        title: msg,
        showCancelButton: true,
        confirmButtonText: 'OK',
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            if ($.isFunction(action)) {
                action();
            } else {
                document.location.href=action;
            }
        }
    })
}



function coalesce(str, to) {
    if (str == null) {
        if (to) {
            return to;
        } else {
            return '';
        }
    } else {
        return str;
    }
}


function toFloat(value) {
    value = parseFloat(value);
    if (!$.isNumeric(value)) {
        return 0;
    } else {
        return value;
    }
}

function confirmDialog(title, message, action) {
    Swal.fire({
        icon : 'warning',
        title: title,
        text: message,
        widthAuto: true,
        showCloseButton: true,
        showCancelButton: true,
        buttonsStyling: false,
        allowOutsideClick: false,
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-outline-success',
            cancelButton: 'btn btn-outline-danger mr-3'
        },
        confirmButtonText: 'Oke',
        cancelButtonText: 'Batalkan'
    }).then((result) => {
        if (result.isConfirmed) {
            if ($.isFunction(action)) {
                action();
            } else {
                document.location.href=action;
            }
        }
    })
}

function alertDialog(title, cancel = false){
    return Swal.fire({
        icon : 'warning',
        title: title,
        showCloseButton: true,
        showCancelButton: cancel ? true : false,
        buttonsStyling: false,
        allowOutsideClick: false,
        customClass: {
            confirmButton: 'btn btn-outline-success',
            cancelButton: cancel ? 'btn btn-outline-danger ml-3' : false
        },
        cancelButtonText:  cancel ? 'Batalkan' : false
    }).then((result) => result);
}

function alertDestroy(){
    return Swal.fire({
        icon : 'warning',
        title: 'Apakah anda yakin menghapus data ini?',
        text : 'Proses ini tidak bisa di batalkan',
        widthAuto: true,
        showCloseButton: true,
        showCancelButton: true,
        buttonsStyling: false,
        allowOutsideClick: false,
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-outline-success',
            cancelButton: 'btn btn-outline-danger mr-3'
        },
        confirmButtonText: 'Oke',
        cancelButtonText: 'Batalkan'
    }).then((result) => result);
}

function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function inArray(value, arr) {
    var length = arr.length;
    for(var i = 0; i < length; i++) {
        if(arr[i] == value) return true;
    }
    return false;
}

function validation(errors){
    $('.error_message').remove();
    $('.is-invalid').removeClass('is-invalid');
    $.each(errors.errors, function(i, error){
        var errors = `<span class="invalid-feedback error_message" role="alert">
                        <strong>${error}</strong>
                    </span>`;
        if($(`[name="${i}"]`).length){
            $(`[name="${i}"]`).addClass('is-invalid').parent().append(errors);
        }else{
            $(`[name="${i}[]"]`).addClass('is-invalid').parent().append(errors);
        }
    });
}

function changeLogo(){
    if($('body').hasClass("theme-dark")){
        $('#logo-light').addClass('d-none');
        $('#logo-dark').removeClass('d-none');
    }else{
        $('#logo-dark').addClass('d-none');
        $('#logo-light').removeClass('d-none');
    }
}

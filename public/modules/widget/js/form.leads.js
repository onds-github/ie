(function () {

    main();
    /******** Our main function ********/
    function main() {
        jQuery(document).ready(function ($) {
            alert(getSyncScriptParams()['width']); //1
        });
    }

    function getSyncScriptParams() {
        var script = document.getElementById('script-form-lead');

        if (!getCookie('COOKIE_KEY')) {

            setCookie('COOKIE_KEY', create_UUID(), 90);

        }
//        Guardar formulário no cookie js

        $.ajax({
            type: 'GET',
            url: 'https://onds.com.br/marketing/form/select',
            data: {id_project: 1},
            success: function (e) {

                $(this).formLead({
                    url: 'https://onds.com.br/marketing/contacts/insert',
                    data: e.results[0].fields,
                    validate: e.results[0].validate
                });

            }
        });


        $.ajax({
            type: 'POST',
            url: 'https://onds.com.br/marketing/analytics/insert',
            data: {server_name: window.location.href, cookie_key: getCookie('COOKIE_KEY')},
            success: function (e) {
                
            }
        });




//         return {
//             width : script.getAttribute('on-id')
//         };
    }

})(); // We call our anonymous function immediately


(function ($) {
    $.fn.formLead = function (options) {

        var defaults = {
            className: null,
            url: null,
            table: null,
            content: null,
            data: null,
            validate: null,
            onDeny: null,
            onSuccess: null
        };

        var settings = $.extend({}, defaults, options);

        $('.on-form-integration').html(type());

        _form(settings.data);

        var form = $('.f-ins').submit(function (event) {
            return false;
        }).form({
            fields: settings.validate,
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: settings.url,
                    data: {data: form.serialize(), cookie_key: getCookie('COOKIE_KEY')},
                    success: function (e) {
                        UIkit.notification(e.message, {status: e.status});
                        if ($.isFunction(settings.onSuccess)) {
                            settings.onSuccess.call(this, e);
                        }
                    },
                    complete: function () {
                        form.form('reset');
                    }
                });

            }
        });

        var PhoneMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
                PhoneOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(PhoneMaskBehavior.apply({}, arguments), options);
                    }
                };

        $('.mask-phone').mask(PhoneMaskBehavior, PhoneOptions);

        function type() {
            return '<h4 class="uk-h4">FORMULÁRIO DE CADASTRO</h4>\n\
                        <hr class="uk-divider-icon" />\n\
                        <form class="ui form f-ins"><div class="ui error message"></div></form>';
        }

        function _form(data) {
            $.each(data, function (a, b) {
                var fields = $('<div />', {
                    class: 'fields'
                }).appendTo('.f-ins');
                $.each(b.fields, function (c, d) {

                    var field = $('<div />', {
                        class: d.field
                    }).appendTo(fields);

                    $('<label />', {
                        html: d.html
                    }).appendTo(field);

                    if (d.type === 'textarea') {
                        $('<textarea />', {
                            name: d.name,
                            rows: d.rows,
                            placeholder: d.html
                        }).appendTo(field);
                    } else if (d.type === 'select') {

                        var select = $('<select />', {
                            class: 'ui selection dropdown',
                            name: d.name
                        }).appendTo(field);

                        $.each(d.itens, function (a, b) {
                            $('<option />', {
                                value: b.value,
                                html: b.html
                            }).appendTo(select);
                        });

                        select.dropdown();

                    } else {
                        $('<input />', {
                            type: d.type,
                            name: d.name,
                            class: d.class,
                            maxlength: d.maxlength,
                            placeholder: d.html,
                            value: d.value
                        }).appendTo(field);
                    }
                });
            });

            $('<button />', {
                class: 'uk-button uk-button-primary uk-border-rounded',
                text: 'Enviar Formulário'
            }).appendTo('.f-ins');
        }

    };
})(jQuery);


function create_UUID() {
    var dt = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (dt + Math.random() * 16) % 16 | 0;
        dt = Math.floor(dt / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}



(function ($) {
    $.fn.onUpdate = function (options) {

        var defaults = {
            url: null,
            table: null,
            modal: null,
            fields: null,
            button: null,
            row: null,
            id: null,
            content: null,
            data: null,
            validate: null,
            onDeny: null,
            onSuccess: null
        };

        var settings = $.extend({}, defaults, options);

        var onFormUpdate = $('#' + settings.modal + ' form').submit(function (event) {
            return false;
        }).form({
            inline: true,
            fields: settings.fields,
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: settings.url,
                    data: {data: onFormUpdate.serialize(), id: settings.row[settings.id]},
                    beforeSend: function (xhr) {
                        if ($.isFunction(settings.onBeforeSend)) {
                            settings.onBeforeSend.call(this, xhr);
                        }
                    },
                    success: function (response) {
                        switch (response.status) {
                            case 'success':
                                settings.table.ajax.reload();
                                onModalUpdate.modal('hide');
                                break;
                        }
                        if ($.isFunction(settings.onSuccess)) {
                            settings.onSuccess.call(this, response);
                        }
                    },
                    complete: function () {

                    }
                });
            }
        });

        var onModalUpdate = $('#' + settings.modal).modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onShow: function () {
                $.each(settings.row, function (attr, value) {
                    if (attr.search("price") > -1) {
                        if (value) {
                            onFormUpdate.form('set value', attr, value.replace(".", ","));
                        }
                    } else {
                        onFormUpdate.form('set value', attr, value);
                    }
                });
            },
            onHidden: function () {
                onFormUpdate.form('reset');
            },
            onApprove: function () {
                onFormUpdate.submit();
                return false;
            }
        }).modal('show');

    };
})(jQuery);
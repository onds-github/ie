
(function ($) {
    $.fn.onUpdate = function (options) {

        var defaults = {
            url: null,
            table: null,
            modal: null,
            fields: null,
            id: null,
            content: null,
            data: null,
            validate: null,
            onDeny: null,
            onSuccess: null
        };

        var _id = null;

        var settings = $.extend({}, defaults, options);

        var onModalUpdate = $('#' + settings.modal).modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onHidden: function () {
                onFormUpdate.form('reset');
            },
            onApprove: function () {
                onFormUpdate.submit();
                return false;
            }
        });

        var onFormUpdate = $('#' + settings.modal + ' form').submit(function (event) {
            return false;
        }).form({
            inline: true,
            fields: settings.fields,
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: settings.url,
                    data: {data: onFormUpdate.serialize(), q: _id},
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

        settings.table.on('click', 'tr td.on-edit', function () {
            var row = settings.table.row($(this).parents('tr')).data();

            _id = row[settings.id];
            $.each(row, function (attr, value) {
                if (attr.search("price") > -1) {
                    onFormUpdate.form('set value', attr, value.replace(".", ","));
                } else {
                    onFormUpdate.form('set value', attr, value);
                }
            });

            onModalUpdate.modal('show');

        });

    };
})(jQuery);
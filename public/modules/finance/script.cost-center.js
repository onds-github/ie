$(document).ready(function () {

    var _table = $('.on-table-cost-center').DataTable({
        ajax: '/finance/cost-center/ajax',
        language: {
            url: '/public/assets/addons/datatables/language.json'
        },
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                className: 'collapsing',
                orderable: false,
                targets: 1,
                render: function (data, type, row) {
                    return '<div class="circular ui icon ' + data.color_cost_center + ' button"><i class="' + data.icon_cost_center + ' icon"></i></div>';
                }
            },
            {
                targets: 2
            },
            {
                targets: -1,
                className: 'collapsing',
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    return '<button class="circular ui icon basic button on-button-update" uk-tooltip="title: Atualizar Registro"><i class="edit icon"></i></button>\n\
                        <button class="circular ui icon basic button on-button-delete" uk-tooltip="title: Excluir Registro"><i class="trash icon"></i></button>';
                }
            }
        ],
        order: [[2, 'asc']]
    });

    var _fields = {
        name_cost_center: {
            identifier: 'name_cost_center',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        icon_cost_center: {
            identifier: 'icon_cost_center',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        color_cost_center: {
            identifier: 'color_cost_center',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        }
    };

    var _modal_insert = $('.on-modal-insert').modal({
        closable: false,
        observeChanges: true,
        autofocus: false,
        onDeny: function () {
            _form_insert.form('reset');
        },
        onApprove: function () {
            _form_insert.submit();
            return false;
        }
    }).modal('attach events', '.on-button-insert');

    var _form_insert = $('.on-form-insert').submit(function (event) {
        return false;
    }).form({
        fields: _fields,
        inline: true,
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/finance/cost-center/insert',
                data: {data: _form_insert.serialize()},
                beforeSend: function (xhr) {
                    _modal_insert.find('.ui.button').addClass('disabled');
                },
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status});
                    switch (e.status) {
                        case 'success':
                            _modal_insert.modal('hide');
                            _form_insert.form('reset');
                            _table.ajax.reload();
                            break;
                    }
                },
                complete: function () {
                    _modal_insert.find('.ui.button').removeClass('disabled');
                }
            });

        }
    });

    $('.on-table-cost-center').on('click', '.on-button-update', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'GET',
            url: '/finance/cost-center/select',
            data: {q: row[ 0 ]},
            success: function (e) {

                $.each(e, function (i, v) {
                    $.each(v, function (x, z) {
                        $('.on-form-update').form('set value', x, z);
                    });
                });

            },
            complete: function () {
                var _modal_update = $('.on-modal-update').modal({
                    closable: false,
                    observeChanges: true,
                    autofocus: false,
                    onDeny: function () {
                        _form_update.form('reset');
                    },
                    onApprove: function () {
                        _form_update.submit();
                        return false;
                    }
                }).modal('show');

                var _form_update = $('.on-form-update').submit(function (event) {
                    return false;
                }).form({
                    fields: _fields,
                    inline: true,
                    onSuccess: function () {
                        $.ajax({
                            type: 'POST',
                            url: '/finance/cost-center/update',
                            data: {data: _form_update.serialize(), q: row[ 0 ]},
                            beforeSend: function (xhr) {
                                _modal_update.find('.ui.button').addClass('disabled');
                            },
                            success: function (e) {
                                UIkit.notification(e.message, {status: e.status});
                                switch (e.status) {
                                    case 'success':
                                        _modal_update.modal('hide');
                                        _form_update.form('reset');
                                        _table.ajax.reload();
                                        break;
                                }
                            },
                            complete: function () {
                                _modal_update.find('.ui.button').removeClass('disabled');
                            }
                        });

                    }
                });
            }
        });

    });


    $('.on-table-cost-center').on('click', '.on-button-delete', function () {
        var row = _table.row($(this).parents('tr')).data();

        var _modal_delete = $('.on-modal-delete').modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onApprove: function () {
                $.ajax({
                    type: 'POST',
                    url: '/finance/cost-center/delete',
                    data: {q: row[ 0 ]},
                    beforeSend: function (xhr) {
                        _modal_delete.find('.ui.button').addClass('disabled');
                    },
                    success: function (e) {
                        UIkit.notification(e.message, {status: e.status});
                        switch (e.status) {
                            case 'success':
                                _table.ajax.reload();
                                break;
                        }
                    },
                    complete: function (jqXHR, textStatus) {
                        _modal_delete.modal('hide');
                        _modal_delete.find('.ui.button').removeClass('disabled');
                    }
                });
            }
        }).modal('show');

    });

    $('.on-icon-cost-dropdown').dropdown();

    $('.on-color-cost-dropdown').dropdown();

});
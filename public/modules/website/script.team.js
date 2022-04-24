$(document).ready(function () {

    var _table = $('.on-table-team').DataTable({
        ajax: '/website/team/ajax',
        language: {
            url: '/public/assets/addons/datatables/language.json'
        },
        columnDefs: [
            {
                targets: 0,
                visible: false
            },
            {
                targets: 1
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
        order: [[1, 'asc']]
    });

    $('.on-contact-dropdown').dropdown({
        apiSettings: {
            url: '/website/team/select-contact-dropdown?q={query}',
            cache: false
        },
        clearable: true,
        filterRemoteData: true,
        message: {
            noResults: 'Nenhum resultado encontrado...'
        }
    }).dropdown('queryRemote', '', function () {});

    var _fields = {
        name_team: {
            identifier: 'name_team',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        id_contact: {
            identifier: 'id_contact',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
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
                url: '/website/team/insert',
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

    $('.on-table-team').on('click', '.on-button-update', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'GET',
            url: '/website/team/select',
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
                            url: '/website/team/update',
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

    $('.on-table-team').on('click', '.on-button-delete', function () {
        var row = _table.row($(this).parents('tr')).data();

        var _modal_delete = $('.on-modal-delete').modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onApprove: function () {
                $.ajax({
                    type: 'POST',
                    url: '/website/team/delete',
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
                return false;
            }
        }).modal('show');

    });

});
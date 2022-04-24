$(document).ready(function () {


    var _table_permission = $('.on-table-permission').DataTable({
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                orderable: false,
                targets: 1
            },
            {
                orderable: false,
                targets: 2,
                visible: false
            },
            {
                orderable: false,
                targets: 3,
                visible: false
            },
            {
                orderable: false,
                targets: 4,
                visible: false
            },
            {
                className: 'right aligned collapsing',
                orderable: false,
                targets: 5,
                render: function (data, type, row) {
                    return (data > 0 ? '<button class="circular ui icon green button on-button-toggle-off" data-id="' + row[0] + '"><i class="toggle on icon"></i></button>' : '<button class="circular ui icon button on-button-toggle-on"><i class="toggle off icon"></i></button>');
                }
            }
        ],
        order: [[0, 'asc']]
    });

    $('.on-table-permission tbody').on('click', 'tr td .on-button-toggle-off', function () {
        var row = _table_permission.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/account/users/delete-permission',
            data: {id_permission: row[5]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table_permission.ajax.reload();
            }
        });
    });

    $('.on-table-permission tbody').on('click', 'tr td .on-button-toggle-on', function () {
        var row = _table_permission.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/account/users/insert-permission',
            data: {id_contract_item: row[0], id_user: getCookie('id_user_permission')},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table_permission.ajax.reload();
            }
        });
    });



    var _table = $('.on-table-users').DataTable({
        ajax: '/account/users/ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        paging: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
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
                className: 'collapsing',
                orderable: false,
                targets: 3
            },
            {
                targets: 4,
                visible: false
            },
            {
                searchable: false,
                orderable: false,
                className: 'center aligned collapsing',
                render: function (data, type, row) {
                    return '<button class="circular ui icon button on-button-menu"><i class="bars icon"></i></button>';
                },
                targets: -1
            }
        ],
        order: [[0, 'asc']]
    });


    $('.on-dropdown-user-type-person').dropdown({
        onChange: function (value) {
            switch (parseInt(value)) {
                case 1:
                    $('.on-input-document-user').addClass('on-mask-cpf');
                    $('.on-input-document-user').removeClass('on-mask-cnpj');

                    $('.on-mask-cpf').mask('000.000.000-00', {reverse: true});

                    $('.on-label-document-user').html('CPF');
                    $('.on-label-nickname-user').html('Apelido');
                    $('.on-label-name-user').html('Nome completo');
                    break;

                case 2:
                    $('.on-input-document-user').addClass('on-mask-cnpj');
                    $('.on-input-document-user').removeClass('on-mask-cpf');

                    $('.on-mask-cnpj').mask('00.000.000/0000-00', {reverse: true});

                    $('.on-label-document-user').html('CNPJ');
                    $('.on-label-nickname-user').html('Nome Fantasia');
                    $('.on-label-name-user').html('Razão Social');
                    break;
            }
        }
    });

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

    var _fields = {
        name_user: {
            identifier: 'name_user',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        email_user: {
            identifier: 'email_user',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        phone_user: {
            identifier: 'phone_user',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        }
    };

    var _form_insert = $('.on-form-insert').submit(function (event) {
        return false;
    }).form({
        fields: _fields,
        inline: true,
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/account/users/insert',
                data: {data: _form_insert.serialize()},
                beforeSend: function (xhr) {
                    $('.on-modal-insert').find('.ui.button').addClass('disabled');
                },
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status});
                },
                complete: function () {
                    _form_insert.form('reset');
                    _modal_insert.modal('hide');
                    _table.ajax.reload();

                    $('.on-modal-insert').find('.ui.button').removeClass('disabled');
                }
            });

        }
    });

    $.contextMenu({
        selector: '.on-table-users tr td .on-button-menu',
        trigger: 'left',
        callback: function (key, options) {

            var row = _table.row($(this).parents('tr')).data();

            var _id_user = row[0];

            switch (key) {
                case 'permission':
                    $('.on-modal-permission').modal({
                        closable: false,
                        observeChanges: true,
                        autofocus: false,
                        onShow: function () {
                            setCookie('id_user_permission', _id_user, 1);
                            
                            
                            _table_permission.ajax.url( '/account/users/ajax-contract-item?id_user=' + _id_user ).load();
                        },
                        onDeny: function () {

                        }
                    }).modal('show');
                    break;

                case 'delete':
                    var _modal_delete = $('.on-modal-delete').modal({
                        closable: false,
                        observeChanges: true,
                        autofocus: false,
                        onApprove: function () {
                            if (row[ 4 ] > 0) {
                                UIkit.notification('Não é possível excluir uma conta com lançamentos vinculados!', {status: 'danger'});
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: '/account/user/delete',
                                    data: {id_user: row[ 0 ]},
                                    beforeSend: function (xhr) {
                                        $('.on-modal-delete').find('.ui.button').addClass('disabled');
                                    },
                                    success: function (e) {
                                        UIkit.notification(e.message, {status: e.status});
                                    },
                                    complete: function (jqXHR, textStatus) {
                                        _modal_delete.modal('hide');
                                        _table.ajax.reload();

                                        $('.on-modal-delete').find('.ui.button').removeClass('disabled');
                                    }
                                });
                            }
                            return false;
                        }
                    }).modal('show');
                    break;
            }


        },
        items: {
            'permission': {name: '<i class="key icon"></i> Permissão', isHtmlName: true},
            'delete': {name: '<i class="trash icon"></i> Excluir', isHtmlName: true}
        }
    });





});
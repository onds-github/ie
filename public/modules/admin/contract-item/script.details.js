$(document).ready(function () {
    
    var _table = $('.on-table-contract-item').DataTable({
        ajax: '/admin/contract-item/ajax?id_company=' + atob(_url()['id_company']),
        language: {
            url: '/public/library/datatables/language.json'
        },
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
                className: 'right aligned collapsing',
                orderable: false,
                targets: 2
            },
            {
                searchable: false,
                orderable: false,
                className: 'right aligned collapsing',
                render: function (data, type, row) {
                    return '<button class="circular ui icon button on-button-menu"><span class="material-icons">menu</span></button>';
                },
                targets: -1
            }
        ]
    });

    var _fields = {
        name_chart_accounts: {
            identifier: 'name_chart_accounts',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        credit_chart_accounts: {
            identifier: 'credit_chart_accounts',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        debit_chart_accounts: {
            identifier: 'debit_chart_accounts',
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
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/admin/contract-item/insert',
                data: {data: _form_insert.serialize() + '&id_company=' + atob(_url()['id_company'])},
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
        selector: '.on-table-contract-item tr td .on-button-menu',
        trigger: 'left',
        callback: function (key, options) {

            var row = _table.row($(this).parents('tr')).data();

            switch (key) {

                case 'update':

                    $.ajax({
                        type: 'GET',
                        url: '/admin/contract-item/select',
                        data: {id_company: atob(_url()['id_company']), q: row[ 0 ]},
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
                                onSuccess: function () {
                                    $.ajax({
                                        type: 'POST',
                                        url: '/admin/contract-item/update',
                                        data: {data: _form_update.serialize(), q: row[ 0 ]},
                                        beforeSend: function (xhr) {
                                            $('.on-modal-update').find('.ui.button').addClass('disabled');
                                        },
                                        success: function (e) {
                                            UIkit.notification(e.message, {status: e.status});
                                        },
                                        complete: function () {
                                            _form_update.form('reset');
                                            _modal_update.modal('hide');
                                            _table.ajax.reload();
                                            
                                            $('.on-modal-update').find('.ui.button').removeClass('disabled');
                                        }
                                    });

                                }
                            });
                        }
                    });


                    break;

                case 'delete':

                    var _modal_delete = $('.on-modal-delete').modal({
                        closable: false,
                        observeChanges: true,
                        autofocus: false,
                        onDeny: function () {

                        },
                        onApprove: function () {
                            if (row[ 4 ] > 0) {
                                UIkit.notification('Não é possível excluir o plano de contas com lançamentos vinculados!', {status: 'danger'});
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: '/admin/contract-item/delete',
                                    data: {q: row[ 0 ]},
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
            'update': {name: '<span class="material-icons">edit</span> Atualizar', isHtmlName: true},
            'delete': {name: '<span class="material-icons">delete</span> Excluir', isHtmlName: true}
        }
    });


    $('.on-module-dropdown').dropdown({
        apiSettings: {
            url: '/admin/contract-item/select-module-dropdown?q={query}',
            cache: false
        },
        clearable: true,
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});


});
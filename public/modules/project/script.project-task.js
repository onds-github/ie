$(document).ready(function () {

    var $url = "/project/project-task/";

    var $id = "project_task_id";

    var $fields = {
        project_task_name: {
            identifier: 'project_task_name',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        }
    };

    var $table = $('#onTableProjectTask').DataTable({
        ajax: $url + 'ajax',
        paging: false,
        dom: 'Brtip',
        buttons: [
            {
                className: 'ui primary button',
                text: 'Novo registro',
                action: function () {

                    $(this).onCreate({
                        url: $url + 'insert',
                        table: $table,
                        fields: $fields,
                        modal: 'onModalCreate'
                    });

                }
            }
        ],
        columns: [
            {
                class: 'on-edit',
                data: 'project_task_name'
            },
            {
                class: 'on-edit',
                data: 'created_at',
                visible: false
            }
        ],
        order: [[1, 'desc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        }
    });

    $.contextMenu({
        selector: 'table tbody tr td',
        callback: function (key, options) {
            var $row = $table.row($(this).parents('tr')).data();

            console.log($row);

            switch (key) {
                case 'update':

                    $(this).onUpdate({
                        url: $url + 'update',
                        id: $id,
                        row: $row,
                        table: $table,
                        fields: $fields,
                        modal: 'onModalUpdate'
                    });

                    break;
            }
        },
        items: {
            "update": {
                name: "<i class='bx bxs-edit'></i> Atualizar",
                isHtmlName: true
            },
            "delete": {
                name: "<i class='bx bxs-trash'></i> Atualizar",
                isHtmlName: true
            }
        }
    });

    $.fn.form.settings.rules.existsDocument = function (value) {
        var isExists;
        $.ajax({
            async: false,
            type: 'POST',
            url: '/account/contact/exists-document',
            data: { q: value },
            success: function (e) {
                isExists = e;
            }
        });
        return isExists;
    };

    $('.ui.dropdown').dropdown();


    //    _table.on('click', 'tr td .on-button-client-toggle-on', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //        $.ajax({
    //            type: 'POST',
    //            url: '/account/contact/update',
    //            data: {data: 'client_contact=1', q: row[_id]},
    //            success: function (e) {
    //            },
    //            complete: function () {
    //                _table.ajax.reload();
    //            }
    //        });
    //    });
    //
    //    _table.on('click', 'tr td .on-button-client-toggle-off', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //        $.ajax({
    //            type: 'POST',
    //            url: '/account/contact/update',
    //            data: {data: 'client_contact=0', q: row[_id]},
    //            success: function (e) {
    //            },
    //            complete: function () {
    //                _table.ajax.reload();
    //            }
    //        });
    //    });
    //
    //    _table.on('click', 'tr td .on-button-provider-toggle-on', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //        $.ajax({
    //            type: 'POST',
    //            url: '/account/contact/update',
    //            data: {data: 'provider_contact=1', q: row[_id]},
    //            success: function (e) {
    //            },
    //            complete: function () {
    //                _table.ajax.reload();
    //            }
    //        });
    //    });
    //
    //    _table.on('click', 'tr td .on-button-provider-toggle-off', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //        $.ajax({
    //            type: 'POST',
    //            url: '/account/contact/update',
    //            data: {data: 'provider_contact=0', q: row[_id]},
    //            success: function (e) {
    //            },
    //            complete: function () {
    //                _table.ajax.reload();
    //            }
    //        });
    //    });
    //
    //    $.fn.form.settings.rules.existsDocument = function (value) {
    //        var isExists;
    //        $.ajax({
    //            async: false,
    //            type: 'POST',
    //            url: '/account/contact/exists-document',
    //            data: {q: value},
    //            success: function (e) {
    //                isExists = e;
    //            }
    //        });
    //        return isExists;
    //    };
    //
    //    var _modal_insert = $('.on-modal-insert').modal({
    //        closable: false,
    //        observeChanges: true,
    //        autofocus: false,
    //        onDeny: function () {
    //            _form_insert.form('reset');
    //        },
    //        onApprove: function () {
    //            _form_insert.submit();
    //            return false;
    //        }
    //    }).modal('attach events', '.on-button-insert');
    //
    //    var _form_insert = $('.on-form-insert').submit(function (event) {
    //        return false;
    //    }).form({
    //        fields: _fields,
    //        inline: true,
    //        onSuccess: function () {
    //            $.ajax({
    //                type: 'POST',
    //                url: '/account/contact/insert',
    //                data: {data: _form_insert.serialize()},
    //                beforeSend: function (xhr) {
    //                    _modal_insert.find('.ui.button').addClass('disabled');
    //                },
    //                success: function (e) {
    //                    UIkit.notification(e.message, {status: e.status});
    //                    switch (e.status) {
    //                        case 'success':
    //                            _modal_insert.modal('hide');
    //                            _form_insert.form('reset');
    //                            _table.ajax.reload();
    //                            break;
    //                    }
    //                },
    //                complete: function () {
    //                    _modal_insert.find('.ui.button').removeClass('disabled');
    //                }
    //            });
    //
    //        }
    //    });
    //
    //    $('.on-table-contact').on('click', '.on-button-update', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //
    //        $.ajax({
    //            type: 'GET',
    //            url: '/account/contact/select',
    //            data: {q: row[ 0 ]},
    //            success: function (e) {
    //
    //                $.each(e, function (i, v) {
    //                    $.each(v, function (x, z) {
    //                        $('.on-form-update').form('set value', x, z);
    //                    });
    //                });
    //
    //            },
    //            complete: function () {
    //                var _modal_update = $('.on-modal-update').modal({
    //                    closable: false,
    //                    observeChanges: true,
    //                    autofocus: false,
    //                    onDeny: function () {
    //                        _form_update.form('reset');
    //                    },
    //                    onApprove: function () {
    //                        _form_update.submit();
    //                        return false;
    //                    }
    //                }).modal('show');
    //
    //                var _form_update = $('.on-form-update').submit(function (event) {
    //                    return false;
    //                }).form({
    //                    fields: _fields,
    //                    inline: true,
    //                    onSuccess: function () {
    //                        $.ajax({
    //                            type: 'POST',
    //                            url: '/account/contact/update',
    //                            data: {data: _form_update.serialize(), q: row[ 0 ]},
    //                            beforeSend: function (xhr) {
    //                                _modal_update.find('.ui.button').addClass('disabled');
    //                            },
    //                            success: function (e) {
    //                                UIkit.notification(e.message, {status: e.status});
    //                                switch (e.status) {
    //                                    case 'success':
    //                                        _modal_update.modal('hide');
    //                                        _form_update.form('reset');
    //                                        _table.ajax.reload();
    //                                        break;
    //                                }
    //                            },
    //                            complete: function () {
    //                                _modal_update.find('.ui.button').removeClass('disabled');
    //                            }
    //                        });
    //
    //                    }
    //                });
    //            }
    //        });
    //
    //    });
    //
    //    $('.on-table-contact').on('click', '.on-button-delete', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //
    //        var _modal_delete = $('.on-modal-delete').modal({
    //            closable: false,
    //            observeChanges: true,
    //            autofocus: false,
    //            onApprove: function () {
    //                $.ajax({
    //                    type: 'POST',
    //                    url: '/account/contact/delete',
    //                    data: {id_contact: row[ 0 ]},
    //                    beforeSend: function (xhr) {
    //                        _modal_delete.find('.ui.button').addClass('disabled');
    //                    },
    //                    success: function (e) {
    //                        UIkit.notification(e.message, {status: e.status});
    //                        switch (e.status) {
    //                            case 'success':
    //                                _table.ajax.reload();
    //                                break;
    //                        }
    //                    },
    //                    complete: function (jqXHR, textStatus) {
    //                        _modal_delete.modal('hide');
    //                        _modal_delete.find('.ui.button').removeClass('disabled');
    //                    }
    //                });
    //                return false;
    //            }
    //        }).modal('show');
    //
    //    });
    //
    //    $('.on-table-contact tbody').on('click', 'tr td .on-button-client-toggle-on', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //        
    //        $.ajax({
    //            type: 'POST',
    //            url: '/account/contact/update',
    //            data: {q: row[0], data: 'client_contact=1'},
    //            success: function (e) {
    //                UIkit.notification({message: e.message, status: e.status});
    //                switch (e.status) {
    //                    case 'success':
    //                        _table.ajax.reload();
    //                        break;
    //                }
    //            }
    //        });
    //    });
    //
    //    $('.on-table-contact tbody').on('click', 'tr td .on-button-client-toggle-off', function () {
    //        var row = _table.row($(this).parents('tr')).data();
    //        
    //        $.ajax({
    //            type: 'POST',
    //            url: '/account/contact/update',
    //            data: {q: row[0], data: 'client_contact=0'},
    //            success: function (e) {
    //                UIkit.notification({message: e.message, status: e.status});
    //                switch (e.status) {
    //                    case 'success':
    //                        _table.ajax.reload();
    //                        break;
    //                }
    //            }
    //        });
    //    });

});
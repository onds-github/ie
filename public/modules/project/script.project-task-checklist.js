$(document).ready(function () {

    $.ajax({
        type: 'GET',
        url: '/project/project-task/select',
        data: {q: _url()['q']},
        success: function (a) {
            $.each(a, function (b, c) {
                $.each(c, function (d, e) {
                    $('.' + d).html(e);
                    switch (d) {
                        case 'date_start_project_task':
                            $('.' + d).html(moment(e).format('DD/MM/YYYY'));
                            break;
                        case 'date_finish_project_task':
                            $('.' + d).html(moment(e).format('DD/MM/YYYY'));
                            break;

                        default:

                            break;
                    }
                });
            });
        }
    });

    var _table = $('.on-table-project-task-checklist').DataTable({
        ajax: '/project/project-task-checklist/ajax?q=' + _url()['q'],
        language: {
            url: '/public/assets/addons/datatables/language.json'
        },
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
            {
                targets: 0,
                visible: false
            },
            {
                targets: 1,
                render: function (data, type, row) {
                    return parseInt(row[2]) == 1 ? '<del>' + data + '</del>' : data;
                }
            },
            {
                targets: 2,
                visible: false
            },
            {
                targets: -1,
                className: 'collapsing',
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    return '<button class="circular ui icon button basic on-button-update" uk-tooltip="title: Atualizar Registro"><i class="edit icon"></i></button>\n\
                        <button class="circular ui icon button basic on-button-delete" uk-tooltip="title: Excluir Registro"><i class="trash icon"></i></button>';
                }
            }
        ]
    });

    var _fields = {
        name_project_task_checklist: {
            identifier: 'name_project_task_checklist',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        check_project_task_checklist: {
            identifier: 'check_project_task_checklist',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        }
    };

    $('.on-check-project-task-checklist-dropdown').dropdown();

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
                url: '/project/project-task-checklist/insert',
                data: {data: _form_insert.serialize(), q: _url()['q']},
                beforeSend: function (xhr) {
                    $('.on-modal-insert').find('.ui.button').addClass('disabled');
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

    $('.on-table-project-task-checklist').on('click', '.on-button-update', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'GET',
            url: '/project/project-task-checklist/select',
            data: {id_project_task: _url()['q'], q: row[ 0 ]},
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
                            url: '/project/project-task-checklist/update',
                            data: {data: _form_update.serialize(), q: row[ 0 ]},
                            beforeSend: function (xhr) {
                                $('.on-modal-update').find('.ui.button').addClass('disabled');
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

    $('.on-table-project-task-checklist').on('click', '.on-button-delete', function () {
        var row = _table.row($(this).parents('tr')).data();

        var _modal_delete = $('.on-modal-delete').modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onApprove: function () {
                $.ajax({
                    type: 'POST',
                    url: '/project/project-task-checklist/delete',
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
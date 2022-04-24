$(document).ready(function () {

    var _table = $('.on-table-company').DataTable({
        ajax: '/admin/company/ajax',
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
                className: 'uk-table-shrink uk-text-nowrap',
                searchable: false,
                orderable: false,
                targets: 1
            },
            {
                orderable: false,
                targets: 2
            },
            {
                orderable: false,
                targets: 3
            },
            {
                className: 'right aligned collapsing',
                orderable: false,
                render: function (data, type, row) {
                    return data ? '<button class="ui green circular icon button on-button-update-off"><i class="toggle on icon"></i></button>' : '<button class="ui circular icon button on-button-update-on"><i class="toggle off icon"></i></button>';
                },
                targets: 4
            },
            {
                searchable: false,
                orderable: false,
                className: 'right aligned collapsing',
                render: function (data, type, row) {
                    return '<button class="circular ui icon button on-button-menu"><i class="bars icon"></i></button>';
                },
                targets: -1
            }
        ],
        order: [[0, 'asc']]
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
        id_company_type_person: {
            identifier: 'id_company_type_person',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        document_company: {
            identifier: 'document_company',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        nickname_company: {
            identifier: 'nickname_company',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        name_company: {
            identifier: 'name_company',
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
                url: '/admin/company/insert',
                data: {data: _form_insert.serialize()},
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status});
                },
                complete: function () {
                    _form_insert.form('reset');
                    _modal_insert.modal('hide');
                    $('.on-table-company').DataTable().ajax.reload();
                }
            });

        }
    });

    $.contextMenu({
        selector: '.on-table-company tr td .on-button-menu',
        trigger: 'left',
        callback: function (key, options) {

            var row = _table.row($(this).parents('tr')).data();

            switch (key) {
                case 'details':
                    window.location.href = '/admin/company/details?id_company=' + btoa(row[0]);
                    break;
            }


        },
        items: {
            'details': {name: 'Atualizar', isHtmlName: true}
        }
    });
    
    $('.on-table-company tbody').on('click', 'tr td .on-button-update-on', function() {
        var row = _table.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/admin/company/update',
            data: {data: 'status_company=1', q: row[0]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table.ajax.reload();
            }
        }); 
    });

    $('.on-table-company tbody').on('click', 'tr td .on-button-update-off', function() {
        var row = _table.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/admin/company/update',
            data: {data: 'status_company=0', q: row[0]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table.ajax.reload();
            }
        }); 
    });

});
$(document).ready(function () {

    var _url = '/account/contact/';
    var _id = 'id_contact';

    var _table = $('#onTableContact').DataTable({
        ajax: _url + 'ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'ui green button',
                text: 'Novo',
                action: function () {

                    $(this).onCreate({
                        url: _url + 'insert',
                        table: _table,
                        fields: _fields,
                        modal: 'onModalCreate'
                    });

                }
            },
            {
                className: 'ui red button',
                text: 'Excluir',
                action: function () {

                    $(this).onDelete({
                        url: _url + 'delete',
                        table: _table,
                        modal: 'onModalDelete',
                        id: _id
                    });

                }
            }
        ],
        stateSave: true,
        paging: false,
        columns: [
            {
                orderable: false,
                data: null,
                defaultContent: '',
                className: 'collapsing select-checkbox noVis'
            },
            {
                className: 'on-edit',
                data: 'nickname_contact',
            },
            {
                className: 'collapsing on-edit',
                data: 'document_contact',
            },
            {
                className: 'on-edit',
                data: 'email_contact',
            },
            {
                className: 'on-edit',
                data: 'phone_primary_contact',
            }
        ],
        order: [[1, 'asc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        }
    });

    $.fn.form.settings.rules.existsDocument = function (value) {
        var isExists;
        $.ajax({
            async: false,
            type: 'POST',
            url: '/account/contact/exists-document',
            data: {q: value},
            success: function (e) {
                isExists = e;
            }
        });
        return isExists;
    };

    var _fields = {
        nickname_contact: {
            identifier: 'nickname_contact',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        }
    };
    
    $(this).onUpdate({
        url: _url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: _id
    });
    
});
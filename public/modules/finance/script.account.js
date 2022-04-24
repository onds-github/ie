$(document).ready(function () {

    var _url = '/finance/account/';
    var _id = 'id_account';

    var _table = $('#onTableAccount').DataTable({
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
                data: 'name_account',
            }
        ],
        order: [[1, 'asc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        }
    });

    var _fields = {
        name_account: {
            identifier: 'name_account',
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

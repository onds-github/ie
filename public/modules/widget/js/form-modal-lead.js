$(document).ready(function () {
    
    var _url = base_url + 'company/invoice/';

    //fields pf
    var _fields_addmailbox = {
        1: {
            fields: [
                {
                    field: 'sixteen wide field',
                    type: 'text',
                    name: 'email',
                    html: 'E-mail'
                }
            ]
        },
        2: {
            fields: [
                {
                    field: 'sixteen wide field',
                    type: 'text',
                    name: 'senha',
                    html: 'Senha'
                }
            ]
        },
        3: {
            fields: [
                {
                    field: 'sixteen wide field',
                    type: 'text',
                    name: 'tamanho',
                    html: 'Tamanho'
                }
            ]
        }
    };

    $.fn.form.settings.rules.existsDocument = function (value) {
        var isExists;
        $.ajax({
            async: false,
            type: 'POST',
            url: _url + '/exists',
            data: {q: value},
            success: function (e) {
                isExists = e;
            }
        });
        return isExists;
    };

    //validate pf
    var _validate_addmailbox = {
        
        nome: {
            identifier: 'nome',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome o nome completo do fornecedor'
                }
            ]
        },
        documento: {
            identifier: 'documento',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome o número de CPF do fornecedor'
                },
                {
                    type: 'existsDocument',
                    prompt: 'Já existe um fornecedor com este número de CPF'
                }
            ]
        }
    };

    var _table = $('.uk-table').DataTable({
        ajax: _url + 'ajax',
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                searchable: false,
                orderable: false,
                targets: 1
            },
            {
                searchable: false,
                orderable: false,
                targets: 2,
                visible: false
            }
        ],
        order: [[0, 'asc']]
    });

    $(this).insert({
        url: _url,
        table: _table,
        data: _fields_addmailbox,
        validate: _validate_addmailbox,
        className: '.b-ins'
    });

    $('.uk-table').on('click', '.b-up-pf', function () {
        var row = _table.row($(this).parents('tr')).data();
        $(this).update({
            url: _url,
            table: _table,
            id: row[0],
            data: _fields_pf,
            validate: _validate_pf
        });
    });

    $('.uk-table').on('click', '.b-up-pj', function () {
        var row = _table.row($(this).parents('tr')).data();
        $(this).update({
            url: _url,
            table: _table,
            id: row[0],
            data: _fields_pj,
            validate: _validate_pj
        });
    });

    $('.uk-table').on('click', '.b-del', function () {
        var row = _table.row($(this).parents('tr')).data();
        $(this).delete({
            url: _url,
            table: _table,
            id: row[0]
        });
    });

});
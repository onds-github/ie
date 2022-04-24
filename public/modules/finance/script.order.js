$(document).ready(function () {

    var $url = '/finance/order/';
    var _id = 'id_order';
    

    var _table = $('#onTableOrder').DataTable({
        ajax: $url + 'ajax',
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
                        url: $url + 'insert',
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
                        url: $url + 'delete',
                        table: _table,
                        modal: 'onModalDelete',
                        id: _id
                    });

                }
            },
            {
                className: 'ui blue button',
                text: 'Duplicar',
                action: function () {

                    var row = _table.rows({selected: true}).data();

                    $.each(row, function (attr, value) {
                        $('#onModalCreate form').form('set value', attr, value);
                    });

                    $(this).onCreate({
                        url: $url + 'insert',
                        table: _table,
                        fields: _fields,
                        modal: 'onModalCreate'
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
                data: 'description_order',
            },
            {
                className: 'on-edit',
                data: 'nickname_contact'
            },
            {
                className: 'on-edit',
                data: 'name_account'
            },
            {
                className: 'on-edit',
                data: 'name_category'
            },
            {
                className: 'on-edit',
                type: 'date-br',
                data: 'date_due_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_order',
                render: function (value, type, row) {
                    return (value);
                }
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: 'situation_order',
                render: function (value, type, row) {
                    return (value == 1 ? '<div class="ui mini fluid center aligned green label">Baixado</div>' : '<div class="ui mini fluid center aligned yellow label onButtonDown">Em aberto</div>');
                }
            }
        ],
        order: [[5, 'asc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        },
        createdRow: function (row, data, dataIndex) {
            switch (parseInt(data['id_type_order'])) {
                case 1:
                    $(row).addClass('positive');
                    break;
                case 2:
                    $(row).addClass('negative');
                    break;
            }
        },
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };
//            var _total = api
//                    .column(6)
//                    .data()
//                    .reduce(function (a, b) {
//                        var cur_index = api.column('id_type_order').data().indexOf(b);
//                        console.log(cur_index);
//                        return intVal(a) + intVal(b);
//                    }, 0);
            // Update footer
//            $(api.column(6).footer()).html(_total);
            
            
                $.ajax({
                    type: 'GET',
                    url: $url + 'balance',
                    success: function (response) {
                        $(api.column(6).footer()).html(
                                'Contas a receber: ' + (response.total_in_notokay) +
                                '<br />Receita: ' + (response.total_in_okay) +
                                '<br />Contas a pagar: ' + (response.total_out_notokay) +
                                '<br />Despesas: ' + (response.total_out_okay)
                        );
                    }
                });
        }
    });

    var _fields = {
        id_contact_type_link: {
            identifier: 'id_contact_type_link',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        date_due_order_in: {
            identifier: 'date_due_order_in',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        price_order_in: {
            identifier: 'price_order_in',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        id_account: {
            identifier: 'id_account',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        }
    };
    
    $(this).onFilter({
        table: _table
    });

    $(this).onUpdate({
        url: $url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: 'id_order'
    });


    $('.on-dropdown-order').dropdown();

    $('.on-dropdown-repeat-period').dropdown();

    $('.on-dropdown-type-order-filter').dropdown();

    $('.on-dropdown-type-date').dropdown();

    $('.on-dropdown-type-period').dropdown({
        onChange: function (value, text) {
            switch (parseInt(value)) {
                case 9:
                    $('#onColFilterPeriod').css('display', 'flex');
                    break;

                default:
                    $('#onColFilterPeriod').css('display', 'none');
                    break;
            }
        }
    });

    $('.on-dropdown-contact').dropdown({
        apiSettings: {
            url: $url + 'dropdown-contact?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-account').dropdown({
        apiSettings: {
            url: $url + 'dropdown-account?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-category').dropdown({
        apiSettings: {
            url: $url + 'dropdown-category?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-table-order tbody').on('click', 'tr td .onButtonOkay', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: $url + 'update',
            data: {data: 'situation_order=1', q: row[_id]},
            success: function (e) {
                switch (e.status) {
                    case 'success':
                        _table.ajax.reload();
                        break;
                }
            }
        });

    });
    
    $('.on-table-order tbody').on('click', 'tr td .onButtonNotOkay', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: $url + 'update',
            data: {data: 'situation_order=0', q: row[_id]},
            success: function (e) {
                switch (e.status) {
                    case 'success':
                        _table.ajax.reload();
                        break;
                }
            }
        });

    });
    
    $.contextMenu({
        trigger: 'right',
        selector: '#onTableOrder tbody tr td.on-edit',
        callback: function (key, options) {
            var row = _table.row($(this).closest('tr')).data();
            switch (key) {
                case 'share-order':
                    window.open('/share/order?q=' + row[0], '_blank');
                    break;
            }
        },
        items: {
            "share-order": {name: '<i class="image icon"></i> Link de pagamento', isHtmlName: true}
        }
    });

});

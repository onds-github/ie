$(document).ready(function () {

    var $url = '/order/';
    
    $.ajax({
        type: 'GET',
        url: $url + 'select',
        data: {q: _url()['q']},
        success: function (response) {
            $.each(response, function (index, row) {
                $.each(row, function (attr, value) {
                    if (attr.search("date") > -1) {
                        if (value) {
                            $('#' + attr).html(moment(value).format('DD/MM/YYYY'));
                        }
                    } else {
                        $('#' + attr).html(value);
                    }
                });
            });

        }
    });

    var _table = $('#onTableOrderSeviceItem').DataTable({
        ajax: $url + 'ajax?q=' + _url()['q'],
        language: {
            url: '/public/library/datatables/language.json'
        },
        ordering: false,
        searching: false,
        paging: false,
        stateSave: true,
        columns: [
            {
                data: 'name_order_service_item',
            },
            {
                className: 'collapsing',
                data: 'quantity_order_service'
            },
            {
                className: 'collapsing',
                data: 'price_order_service_item',
                render: function (value, type, row) {
                    return numeral(row['price_order_service_item']).format('0,0.00');
                }
            },
            {
                className: 'collapsing',
                data: null,
                render: function (value, type, row) {
                    return numeral((parseFloat(row['quantity_order_service']) * parseFloat(row['price_order_service_item']))).format('0,0.00');
                }
            }
        ]
    });
    
    $('.tabular.menu .item').tab();

});
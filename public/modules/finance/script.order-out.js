$(document).ready(function () {

    var _table = $('.on-table-order-out').DataTable({
        ajax: '/finance/order-out/ajax',
        language: {
            url: '/public/assets/addons/datatables/language.json'
        },
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                className: 'collapsing',
                orderable: false,
                targets: 1,
                render: function (data, type, row) {
                    return '<div class="circular ui icon ' + data.color_cost_center + ' button" uk-tooltip="title: ' + data.name_cost_center + '"><i class="' + data.icon_cost_center + ' icon"></i></div>';
                }
            },
            {
                targets: 2
            },
            {
                targets: 3,
                render: function (data, type, row) {
                    return data.document_contact + '<br />' + data.nickname_contact;
                }
            },
            {
                className: 'collapsing',
                targets: 4
            },
            {
                type: 'date-br',
                className: 'single line collapsing',
                targets: 5,
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                type: 'currency',
                className: 'single line collapsing',
                targets: 6
            },
            {
                targets: 7,
                visible: false
            },
            {
                targets: -1,
                className: 'collapsing',
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    return (parseInt(row[7]) == 1 ? '<button class="circular ui icon green button on-button-not-okay" uk-tooltip="title: Desfazer Baixa"><i class="thumbs up icon"></i></button>' : '<button class="circular ui icon button on-button-okay" uk-tooltip="title: Baixar Pagamento"><i class="thumbs down icon"></i></button>') +
                        '<button class="circular ui icon basic button on-button-duplicate" uk-tooltip="title: Duplicar Registro"><i class="copy outline icon"></i></button>\n\
                        <button class="circular ui icon basic button on-button-update" uk-tooltip="title: Atualizar Registro"><i class="edit icon"></i></button>\n\
                        <button class="circular ui icon basic button on-button-delete" uk-tooltip="title: Excluir Registro"><i class="trash icon"></i></button>';
                }
            }
        ],
        order: [[5, 'asc']],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                        i.replace(/[\.]/g, '').replace(/[\,]/g, '.').replace(/[\R$]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
            };

            var _total = api
                    .column(6, {search: 'applied'})
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


            // Update footer
            $(api.column(6).footer()).html('R$ ' + numeral(_total).format('0,0.00'));

        }
    });

    var _fields = {
        description_order_out: {
            identifier: 'description_order_out',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        id_contact: {
            identifier: 'id_contact',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        date_due_order_out: {
            identifier: 'date_due_order_out',
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
        },
        id_cost_center: {
            identifier: 'id_cost_center',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        price_order_out: {
            identifier: 'price_order_out',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
    };

    $('.on-contact-dropdown').dropdown({
        apiSettings: {
            url: '/finance/order-out/select-contact-dropdown?q={query}',
            cache: false
        },
        clearable: true,
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-account-dropdown').dropdown({
        apiSettings: {
            url: '/finance/order-out/select-account-dropdown?q={query}',
            cache: false
        },
        clearable: true,
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-cost-center-dropdown').dropdown({
        apiSettings: {
            url: '/finance/order-out/select-cost-center-dropdown?q={query}',
            cache: false
        },
        clearable: true,
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

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
                url: '/finance/order-out/insert',
                data: {data: _form_insert.serialize()},
                beforeSend: function (xhr) {
                    _modal_insert.find('.ui.button').addClass('disabled');
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

    $('.on-table-order-out').on('click', '.on-button-duplicate', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'GET',
            url: '/finance/order-out/select',
            data: {q: row[ 0 ]},
            success: function (e) {

                $.each(e, function (i, v) {
                    $.each(v, function (x, z) {
                        $('.on-form-insert').form('set value', x, z);
                    });
                });
                
            },
            complete: function () {
                $('.on-modal-insert').modal({
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
                }).modal('show');
                
            }
        });
    });

    $('.on-table-order-out').on('click', '.on-button-update', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'GET',
            url: '/finance/order-out/select',
            data: {q: row[ 0 ]},
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
                            url: '/finance/order-out/update',
                            data: {data: _form_update.serialize(), q: row[ 0 ]},
                            beforeSend: function (xhr) {
                                _modal_update.find('.ui.button').addClass('disabled');
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

    $('.on-table-order-out').on('click', '.on-button-delete', function () {
        var row = _table.row($(this).parents('tr')).data();

        var _modal_delete = $('.on-modal-delete').modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onApprove: function () {
                $.ajax({
                    type: 'POST',
                    url: '/finance/order-out/delete',
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

    $('.on-table-order-out tbody').on('click', 'tr td .on-button-not-okay', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/finance/order-out/update',
            data: {data: 'situation_order_out=0', q: row[ 0 ]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
                switch (e.status) {
                    case 'success':
                        _table.ajax.reload();
                        break;
                }
            }
        });
    });

    $('.on-table-order-out tbody').on('click', 'tr td .on-button-okay', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/finance/order-out/update',
            data: {data: 'situation_order_out=1', q: row[ 0 ]},
            success: function (e) {
                UIkit.notification(e.message, {status: e.status});
                switch (e.status) {
                    case 'success':
                        _table.ajax.reload();
                        break;
                }
            }
        });

    });

    $('.on-contact-dropdown-filter').dropdown({
        apiSettings: {
            url: '/finance/order-out/select-contact-dropdown?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true,
        onChange: function (value, text) {
            if (text) {
                _table.column(3).search(text).draw();
            } else {
                _table.column(3).search('').draw();
            }
        }
    }).dropdown('queryRemote', '', function () {});

    $('.on-account-dropdown-filter').dropdown({
        apiSettings: {
            url: '/finance/order-out/select-account-dropdown?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true,
        onChange: function (value, text) {
            if (text) {
                _table.column(4).search(text).draw();
            } else {
                _table.column(4).search('').draw();
            }
        }
    }).dropdown('queryRemote', '', function () {});

    //filtro por período

    $('.on-button-filter-period').dropdown({
        onChange: function (value) {
            $.ajax({
                type: 'POST',
                url: '/finance/order-out/filter-period',
                data: {id_filter_period: value},
                success: function (a) {
                    switch (parseInt(a.id_filter_period)) {
                        case 1:
                            $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM YYYY"));
                            break;
                        case 2:
                            $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM") + ' à ' + moment(a.filter_period_max).format("DD MMM"));
                            break;
                        case 3:
                            $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("MMM YYYY"));
                            break;
                    }
                },
                complete: function () {
                    _table.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.on-button-filter-period-prev', function () {
        $.ajax({
            type: 'POST',
            url: '/finance/order-out/filter-period-prev',
            success: function (a) {
                switch (parseInt(a.id_filter_period)) {
                    case 1:
                        $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM YYYY"));
                        break;
                    case 2:
                        $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM") + ' à ' + moment(a.filter_period_max).format("DD MMM"));
                        break;
                    case 3:
                        $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("MMM YYYY"));
                        break;
                }
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    $(document).on('click', '.on-button-filter-period-next', function () {
        $.ajax({
            type: 'POST',
            url: '/finance/order-out/filter-period-next',
            success: function (a) {
                switch (parseInt(a.id_filter_period)) {
                    case 1:
                        $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM YYYY"));
                        break;
                    case 2:
                        $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM") + ' à ' + moment(a.filter_period_max).format("DD MMM"));
                        break;
                    case 3:
                        $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("MMM YYYY"));
                        break;
                }
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    var _form_filter_period = $('.on-form-filter-period').submit(function (event) {
        return false;
    }).form({
        fields: {
            filter_period_min: {
                identifier: 'filter_period_min',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Informe'
                    }
                ]
            },
            filter_period_max: {
                identifier: 'filter_period_max',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Informe'
                    }
                ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/finance/order-out/filter-period-custom',
                data: {data: _form_filter_period.serialize()},
                success: function (a) {
                    switch (parseInt(a.id_filter_period)) {
                        case 4:
                            $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM YYYY") + ' à ' + moment(a.filter_period_max).format("DD MMM YYYY"));
                            break;
                    }
                },
                complete: function () {
                    _table.ajax.reload();
                }
            });

        }
    });

    $.ajax({
        type: 'GET',
        url: '/finance/order-out/filter-period-state',
        success: function (a) {
            switch (parseInt(a.id_filter_period)) {
                case 1:
                    $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM YYYY"));
                    break;
                case 2:
                    $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM") + ' à ' + moment(a.filter_period_max).format("DD MMM"));
                    break;
                case 3:
                    $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("MMM YYYY"));
                    break;
                case 4:
                    $('.on-button-filter-period span.text').html(moment(a.filter_period_min).format("DD MMM YYYY") + ' à ' + moment(a.filter_period_max).format("DD MMM YYYY"));
                    break;
            }
            _form_filter_period.form('set value', 'filter_period_min', a.filter_period_min);
            _form_filter_period.form('set value', 'filter_period_max', a.filter_period_max);
        }
    });

});
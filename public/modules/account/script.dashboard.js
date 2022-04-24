$(document).ready(function () {

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select',
        success: function (e) {
            $('.on-total-accounts').html(e.total_accounts);

        }
    });

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select-project-task-status',
        success: function (e) {
            $('.on-slider-project-task-status').html('');
            $.each(e.data, function (i, v) {
                $('.on-slider-project-task-status').append('<div class="column"><div class="ui center aligned segment"><h3 class="ui header">' + v[2] + '<div class="seg header">' + v[1] + '</div></h3></div></div>');
            });
        }
    });

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select-account',
        success: function (e) {
            $('.on-slider-accounts').html('');
            $.each(e.data, function (i, v) {
                $('.on-slider-accounts').append('<div class="column"><div class="ui center aligned segment"><h3 class="ui header">' + v[2] + '<div class="seg header">' + v[1] + '</div></h3></div></div>');
            });
        }
    });


    $('.on-table-cost-center').DataTable({
        ajax: '/account/dashboard/ajax-cost-center',
        language: {
            url: '/public/assets/addons/datatables/language.json'
        },
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                className: 'center aligned collapsing',
                orderable: false,
                targets: 1,
                render: function (data, type, row) {
                    return '<button class="circular ui icon ' + data.color_cost_center + ' button"><i class="' + data.icon_cost_center + ' icon"></i></button>';
                }
            },
            {
                orderable: false,
                targets: 2,
                render: function (data, type, row) {
                    return '<h6 class="uk-h6 uk-margin-remove">' + data + '</h6><p class="uk-text-meta uk-margin-remove">' + row[3] + '</p>';
                }
            },
            {
                type: 'currency',
                targets: 3,
                visible: false
            }
        ],
        order: [[3, 'desc']]
    });

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select-in-out',
        success: function (data) {
            Highcharts.chart('on-container-in-out', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'column'
                },
                title: {
                    text: 'Resumo'
                },
                subtitle: {
                    text: 'Últimos 12 meses'
                },
                yAxis: {
                    title: {
                        text: 'Valor'
                    }
                },
                xAxis: {
                    categories: data.months
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br />',
                    pointFormat: 'R$ {point.y}'
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: data.data,
                responsive: {
                    rules: [
                        {
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }
                    ]
                }
            });
        }
    });

});
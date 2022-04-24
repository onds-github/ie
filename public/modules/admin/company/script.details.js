$(document).ready(function () {

    $.ajax({
        type: 'GET',
        url: '/admin/company/select',
        data: {id_company: atob(_url()['id_company'])},
        success: function (e) {
            $.each(e, function (i, v) {
                $.each(v, function (x, z) {
                    $('form').form('set value', x, z);
                });
            });
        }
    });

    var _form = $('form').submit(function (event) {
        return false;
    }).form({
        inline: true,
        fields: {
            name_company: {
                identifier: 'name_company',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Infome'
                    }
                ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/admin/company/update',
                data: {data: _form.serialize(), q: atob(_url()['id_company'])},
                success: function (e) {
                    UIkit.notification({message: e.message, status: e.status});
                },
                complete: function () {
                    _form.addClass('disabled');
                }
            });
        }
    });

});
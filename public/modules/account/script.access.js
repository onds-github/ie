$(document).ready(function () {
    
    var form = $('.on-form-access').submit(function (e) {
        return false;
    }).form({
        fields: {
            email_user: {
                identifier: 'email_user',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Informe'
                    }
                ]
            },
            password_user: {
                identifier: 'password_user',
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
                url: '/account/access/request',
                data: {data: form.serialize()},
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status, pos: 'bottom-right'});
                    switch (e.status) {
                        case 'success':
                            if (_url()['p']) {
                                window.location.href = _url()['p'];
                            } else {
                                window.location.reload();
                            }
                            break;
                    }
                }
            });
        }
    });
});
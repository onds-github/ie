$(document).ready(function () {

    var _listPost = '';

    $.ajax({
        type: 'GET',
        url: '/post/select-post-related',
        beforeSend: function() {
            $('.on-post-related').empty();
        },
        success: function (data) {

            $.each(data, function (i, v) {
                
                _listPost += '<div>\n\
                    <div class="uk-card uk-card-body uk-card-default uk-border-radius">\n\
                        <div uk-grid>\n\
                            <div class="uk-width-1-3@s">\n\
                                <img src="' + v.image_post + '" />\n\
                            </div>\n\
                            <div class="uk-width-2-3@s">\n\
                                <h3 class="uk-h3">' + v.title_post + '</h3>\n\
                                <p class="uk-text-meta">' + v.description_post + '</p>\n\
                                <a class="uk-link uk-text-primary" href="/post?q=' + v.slug_post + '">Leia Mais <span uk-icon="icon: arrow-right"></span></a>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>';
            });

        },
        complete: function () {
            $('.on-post-related').html(_listPost);
        }
    });

});
$(document).ready(function () {
    
    $.ajax({
        type: 'GET',
        url: 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=PLIluMM1HNoRo_2sf28os_XeigLqB8ZP9B&key=AIzaSyBAMiaNLinGMif1Lz9KeJpxPXRzJi30y9M',
        success: function (e) {
            console.log(e);
          $.each(e.items, function (i, v) {
            $('.playlist-youtube').append('<li><div class="uk-panel"><img src="' + v.snippet.thumbnails.default.url + '" title="' + v.snippet.description + '" width="' + v.snippet.thumbnails.default.width + '" height="' + v.snippet.thumbnails.default.height + '" /></div></li>');
          });
        }
      });

});
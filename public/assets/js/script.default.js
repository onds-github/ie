$(document).ready(function () {
    
    $.ajax({
        type: 'GET',
        url: 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=PLIluMM1HNoRo_2sf28os_XeigLqB8ZP9B&key=AIzaSyBAMiaNLinGMif1Lz9KeJpxPXRzJi30y9M',
        success: function (e) {
            console.log(e);
          $.each(e.items, function (i, v) {
            $('.playlist-PLIluMM1HNoRo_2sf28os_XeigLqB8ZP9B').append('<li><div class="uk-panel"><a href="/blog/post?q=' + v.snippet.resourceId.videoId + '"><img src="' + v.snippet.thumbnails.standard.url + '" title="' + v.snippet.description + '" width="' + v.snippet.thumbnails.standard.width + '" height="' + v.snippet.thumbnails.standard.height + '" /></a></div></li>');
          });
        }
      });

});
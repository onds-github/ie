$(document).ready(function () {
    
    $.ajax({
        type: 'GET',
        url: 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=PLIluMM1HNoRo_2sf28os_XeigLqB8ZP9B&key=AIzaSyBAMiaNLinGMif1Lz9KeJpxPXRzJi30y9M',
        success: function (e) {
            console.log(e);
          $.each(e.items, function (i, v) {
            $('.playlist-youtube').append('<li><div class="uk-panel"><iframe style="min-height: 250px;" width="100%" height="100%" src="https://www.youtube.com/embed/' + v.id.videoId + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div></li>');
          });
        }
      });

});
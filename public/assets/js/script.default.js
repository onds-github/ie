$(document).ready(function () {
    
    $.ajax({
        type: 'GET',
        url: 'https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId=UCBkNpeyvBO2TdPGVC_PsPUA&key=AIzaSyC1DIfvUzREQz20C3gSCaMZXc9aZ1cde3E',
        success: function (e) {
          $.each(e.items, function (i, v) {
            $('.playlist-youtube').append('<li><div class="uk-panel"><iframe style="min-height: 250px;" width="100%" height="100%" src="https://www.youtube.com/embed/' + v.id.videoId + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div></li>');
          });
        }
      });

});
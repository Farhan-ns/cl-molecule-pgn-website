<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('pgn/css/style.css') }}" />
</head>

<body>
  <div id="loading">
    <div class="spinner"></div>
  </div>
  <video id="bgVideo" class="video-bg" autoplay muted loop playsinline preload="auto" oncontextmenu="return false;">
    <source src="{{ asset('pgn/video/Motion-inv.mp4') }}" type="video/mp4" />
    Browser Anda tidak mendukung tag video.
  </video>

  <div class="footer">
    <button class="footer-button font-1" id="openInvitation" style="display: none;">
      Buka Undangan
    </button>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var video = document.getElementById("bgVideo");
      var loadingScreen = document.getElementById("loading");
      var content = document.getElementById("content");
      var openInvitationButton = document.getElementById("openInvitation");

      function hideLoading() {
        loadingScreen.style.display = "none";
        content.style.display = "block";
      }

      if (video.readyState >= 3) {
        hideLoading();
      } else {
        video.addEventListener("canplay", hideLoading);
      }

      // Tambahkan timeout untuk menghindari loading tak terbatas
      setTimeout(hideLoading, 10000); // 10 detik

      // Mencegah video dihentikan
      video.onpause = function() {
        this.play();
      };

      // Mencegah kontrol default browser
      video.controls = false;

      // Mencegah klik kanan pada video
      video.addEventListener('contextmenu', function(e) {
        e.preventDefault();
      });

      // Menampilkan tombol dengan animasi fade in setelah 10 detik
      setTimeout(function() {
        openInvitationButton.style.display = "block";
        openInvitationButton.style.opacity = 0;
        var opacity = 0;
        var intervalID = setInterval(function() {
          if (opacity < 1) {
            opacity += 0.1;
            openInvitationButton.style.opacity = opacity;
          } else {
            clearInterval(intervalID);
          }
        }, 100); // 100ms
      }, 10000);

      // Menambahkan event listener untuk tombol Buka Undangan
      openInvitationButton.addEventListener('click', function() {
        window.location.href = "{{ route('rsvp') }}";
      }); // 10 detik
    });
  </script>
</body>

</html>

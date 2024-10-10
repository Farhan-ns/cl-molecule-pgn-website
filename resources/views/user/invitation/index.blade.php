<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
  <link rel="stylesheet" href="{{ asset('pgn/css/style.css') }}" />
</head>

<body>
  <div id="loading">
    <div class="spinner"></div>
  </div>
   <!-- Slider main container -->
    <div class="swiper">
      <!-- Additional required wrapper -->
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <video
            id="bgVideo-1"
            class="video-bg"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            oncontextmenu="return false;"
          >
            <source src="{{ asset('pgn/video/Motion-1.mp4') }}" type="video/mp4" />
            Browser Anda tidak mendukung tag video.
          </video>
        </div>
        <div class="swiper-slide">
          <video
            id="bgVideo-2"
            class="video-bg"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            oncontextmenu="return false;"
          >
            <source src="{{ asset('pgn/video/Motion-2.mp4') }}" type="video/mp4" />
            Browser Anda tidak mendukung tag video.
          </video>
        </div>
        <div class="swiper-slide">
          <video
            id="bgVideo-3"
            class="video-bg"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            oncontextmenu="return false;"
          >
            <source src="{{ asset('pgn/video/Motion-3.mp4') }}" type="video/mp4" />
            Browser Anda tidak mendukung tag video.
          </video>
        </div>
        ...
      </div>

      <!-- If we need navigation buttons -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

  <div class="footer">
    <button class="footer-button font-1" id="openInvitation" style="display: none;">
      Buka Undangan
    </button>
  </div>

  <script>
      document.addEventListener("DOMContentLoaded", function () {
        var videos = [
          document.getElementById("bgVideo-1"),
          document.getElementById("bgVideo-2"),
          document.getElementById("bgVideo-3")
        ];
        var loadingScreen = document.getElementById("loading");
        //var content = document.getElementById("content");
        var openInvitationButton = document.getElementById("openInvitation");

        function hideLoading() {
          loadingScreen.style.display = "none";
          //content.style.display = "block";
        }
        
        function checkVideosReady() {
          var allReady = videos.every(video => video.readyState >= 3);
          if (allReady) {
            hideLoading();
          }
        }

        videos.forEach(video => {
          if (video.readyState >= 3) {
            checkVideosReady();
          } else {
            video.addEventListener("canplay", checkVideosReady);
          }

          // Mencegah video dihentikan
          //video.onpause = function () {
          //  this.play();
          //};

          // Mencegah kontrol default browser
          video.controls = false;

          // Mencegah klik kanan pada video
          video.addEventListener("contextmenu", function (e) {
            e.preventDefault();
          });
        });

        // Tambahkan timeout untuk menghindari loading tak terbatas
        setTimeout(hideLoading, 10000); // 10 detik

        // Menampilkan tombol dengan animasi fade in setelah 8 detik
        setTimeout(function () {
          openInvitationButton.style.display = "block";
          openInvitationButton.style.opacity = 0;
          var opacity = 0;
          var intervalID = setInterval(function () {
            if (opacity < 1) {
              opacity += 0.1;
              openInvitationButton.style.opacity = opacity;
            } else {
              clearInterval(intervalID);
            }
          }, 100); // 100ms
        }, 8000);

        // Menambahkan event listener untuk tombol Buka Undangan
        openInvitationButton.addEventListener("click", function () {
          window.location.href = "{{route('rsvp')}}";
        }); // 10 detik
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
      const swiper = new Swiper(".swiper", {
        // Optional parameters
        direction: "horizontal",

        // If we need pagination
        pagination: {
          el: ".swiper-pagination",
        },

        // Navigation arrows
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },

        // And if we need scrollbar
        scrollbar: {
          el: ".swiper-scrollbar",
        },
         // Tambahkan event listener untuk slide change
      on: {
        slideChange: function () {
          // Hentikan semua video
          videos.forEach(video => video.pause());
          
          // Putar video pada slide aktif
          const activeVideo = videos[this.activeIndex];
          if (activeVideo) {
            activeVideo.play();
          }
        }
      }
      });
    </script>
</body>

</html>

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
    <source src="{{ asset('pgn/video/bg.mp4') }}" type="video/mp4" />
    Browser Anda tidak mendukung tag video.
  </video>

  <div class="undangan">
    <form action="{{ route('register') }}" method="POST" id="rsvpForm">
      @csrf

      <h2>Form Kehadiran</h2>
      <div class="form-group">
        <label for="company_name">Nama Perusahaan:</label>
        @error('company_name')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <input type="text" id="company_name" name="company_name" required>
      </div>
      {{-- <div class="form-group">
        <label for="intansi">Instansi:</label>
        <select id="intansi" name="intansi" required>
          <option value="">Pilih</option>
          <option value="hadir">Pemerintah</option>
          <option value="tidak_hadir">Media</option>
        </select>
      </div> --}}
      <div class="form-group">
        <label for="name">Nama Peserta Yang Akan Hadir:</label>
        @error('name')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <input type="text" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="office">Jabatan Peserta:</label>
        @error('office')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <input type="text" id="office" name="office" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        @error('email')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="phone">No Handphone Peserta:</label>
        @error('phone')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <input type="text" id="phone" name="phone" min="10" max="14" required>
      </div>

      <div class="form-group">
        <label>Apakah Anda akan menghadiri Acara?</label>
        @error('will_attend')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <div class="radio-group">
          <label class="radio-container">
            <input type="radio" name="will_attend" value="1" required>
            <span class="checkmark"></span>
            Ya
          </label>
          <label class="radio-container">
            <input type="radio" name="will_attend" value="0" required>
            <span class="checkmark"></span>
            Tidak
          </label>
        </div>
      </div>
      <button type="submit" class="submit-button">Kirim</button>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var video = document.getElementById("bgVideo");
      var loadingScreen = document.getElementById("loading");
      var content = document.getElementById("content");

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
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

  @if (Session::has('swal-register-success'))
    <script>
      Swal.fire({
        title: "Registrasi Berhasil",
        text: 'Terima kasih atas registrasi Anda.',
        icon: 'success',
        confirmButtonColor: '#2980b9',
      });
    </script>
  @endif
</body>

</html>

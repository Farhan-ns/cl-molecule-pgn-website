<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('pgn/css/style.css') }}" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <title>PGN 2024</title>
</head>

<body>
  <div id="loading">
    <div class="spinner"></div>
  </div>
  <video id="bgVideo" class="video-bg" autoplay muted loop playsinline preload="auto" oncontextmenu="return false;">
    <source src="{{ asset('pgn/video/bg-new.mp4') }}" type="video/mp4" />
    Browser Anda tidak mendukung tag video.
  </video>


  <div class="undangan">
    <form action="{{ route('register') }}" method="POST" id="rsvpForm" novalidate="novalidate">
      @csrf
      <h2>Form Kehadiran</h2>
      <div class="form-group">
        <label for="company_name">Nama Perusahaan:</label>
        @error('company_name')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <select id="select2-companies" style="width: 100%"></select>
      </div>

      <div class="form-group">
        <label for="company_address">Domisili Perusahaan:</label>
        <input type="text" name="company_address" value="" readonly>
        <input type="hidden" name="company_name" value="">
        {{-- <label for="company_address">Domisili Perusahaan:</label>
        <select id="company_address" name="company_address" required>
          <option value="">Pilih</option>
          <option value="Jakarta">Jakarta</option>
          <option value="Tangerang">Tangerang</option>
          <option value="Bekasi">Bekasi</option>
          <option value="Karawang">Karawang</option>
          <option value="Bogor">Bogor</option>
          <option value="Cirebon">Cirebon</option>
        </select> --}}
      </div>

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
        <label for="phone">No Handphone Peserta (pastikan nomor anda terdaftar di my pertamina):</label>
        @error('phone')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <input type="text" id="phone" name="phone" min="10" max="14" placeholder="08xxxxxxxxxx"
          required>
      </div>

      <div style="display: flex;justify-content: center;align-items:center; margin-bottom: 15px;">
        <img src="{{ asset('pgn/img/qr.jpeg') }}" alt="" style="width: 100px; margin-right: 5px;" />
        <p style="font-size: .85rem;">Download My Pertamina sekarang, dapatkan hadiah Voucher My Pertamina bagi 100
          orang
          yang beruntung di Customer Business Forum 2024</p>
      </div>

      <div style="display: flex;justify-content: center;align-items:center; margin-bottom: 15px;">
        <a href="https://play.google.com/store/apps/details?id=com.dafturn.mypertamina&hl=id">
          <img src="{{ asset('pgn/img/get-on-playstore_compressed.jpg') }}" alt=""
            style="width: 120px; margin-right: 5px;" />
        </a>
        <a href="https://apps.apple.com/id/app/mypertamina/id1295039064">
          <img src="{{ asset('pgn/img/get-on-appstore_compressed.jpg') }}" alt=""
            style="width: 100px; margin-right: 5px;" />
        </a>
      </div>

      <div class="form-group">
        <label>Apakah Anda akan menghadiri Acara?</label>
        @error('will_attend')
          <span style="color: red;">{{ $message }}</span>
        @enderror
        <div class="radio-group" id="will_attend_group">
          <label class="radio-container">
            <input type="radio" name="will_attend" value="1">
            <span class="checkmark"></span>
            Ya
          </label>
          <label class="radio-container">
            <input type="radio" name="will_attend" value="0">
            <span class="checkmark"></span>
            Tidak
          </label>
        </div>
      </div>
      <button type="submit" class="submit-button">Kirim</button>
    </form>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

  <script>
    let cachedData = [];

    $.ajax({
      url: "{{ route('api.getCompanies') }}", // Laravel API endpoint
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        parsedData = JSON.parse(data.data);
        cachedData = parsedData.map(function(item) {
          return {
            id: `${item.Nama}->${item.Area}`,
            text: item.Nama,
          };
        });

        $('#select2-companies').select2({
          placeholder: 'Pilih Perusahaan',
          data: cachedData,
          allowClear: true,
        });

        $('#select2-companies').val(null).trigger('change');

        $('#select2-companies').on('select2:select', function(e) {
          let selectedId = e.params.data.id;
          let splitId = selectedId.split('->');
          let companyName = splitId[0];
          let area = splitId[1];

          // Update the hidden input with the selected Area (address)
          $('input[name="company_name"]').val(companyName);
          $('input[name="company_address"]').val(area);
        });

        // Handle clear event when user deselects
        $('#select2-companies').on('select2:clear', function() {
          // Clear hidden inputs when the selection is cleared
          $('input[name="company_name"]').val('');
          $('input[name="company_address"]').val('');
        });
      },
      error: function(err) {
        console.error("Error fetching companies data", err);
      }
    });
  </script>

  <script>
    const validator = new window.JustValidate('#rsvpForm', {
      submitFormAutomatically: true,
    });

    validator
      .addField('#select2-companies', [{
        rule: 'required',
        errorMessage: 'Silakan memilih perusahaan anda.',
      }])
      .addField('#name', [{
        rule: 'required',
        errorMessage: 'Silakan mengisi nama anda.',
      }])
      .addField('#office', [{
        rule: 'required',
        errorMessage: 'Silakan mengisi jabatan anda.',
      }])
      .addField('#email', [{
        rule: 'required',
        errorMessage: 'Silakan mengisi email anda.',
      }])
      .addField('#phone', [{
        rule: 'required',
        errorMessage: 'Silakan nomor telepon anda.',
      }])
      .addField('#phone', [{
        rule: 'required',
        errorMessage: 'Silakan nomor telepon anda.',
      }])
      .addRequiredGroup(
        '#will_attend_group',
        'Silakan pilih apakah Anda akan menghadiri acara.'
      );
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var video = document.getElementById("bgVideo");
      var loadingScreen = document.getElementById("loading");
      //var content = document.getElementById("content");

      function hideLoading() {
        loadingScreen.style.display = "none";
        //content.style.display = "block";
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
        title: "Pendaftaran Berhasil",
        text: 'Mohon simpan QR ini untuk daftar ulang',
        imageUrl: "{{ session('qr_path') }}",
        imageWidth: 200,
        imageHeight: 200,
        imageAlt: "QR",
        icon: 'success',
        confirmButtonColor: '#2980b9',
      });
    </script>
  @endif
</body>

</html>

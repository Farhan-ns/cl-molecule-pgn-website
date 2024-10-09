<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrasi Musda XVII</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap");

    body {
      background-color: #f1f1f1;
    }

    body {
      font-family: "Montserrat", sans-serif;
    }

    .font-400 {
      font-weight: 400;
      font-size: 0.8em;
    }

    .font-700 {
      font-weight: 700;
      font-size: 0.8em;
    }

    .header-main {
      box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.5);
      background-color: white;
    }

    .file-upload {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .file-upload-button {
      display: inline-block;
      padding: 0.8rem 1.5rem;
      background-color: #0c3748;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .file-upload-button:hover {
      background-color: #a7de11;
    }

    .file-input {
      display: none;
    }

    .file-name {
      margin-top: 10px;
      font-size: 16px;
      color: #333;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.5rem 0.75rem;
    }

    .form-control:focus {
      border-color: #a7de11;
      box-shadow: 0 0 0 .25rem #a7de114a;
    }

    .btn-primary {
      width: 100%;
      padding: 1rem 0.75rem;
      background-color: #a7de11;
      color: #0c3748;
      transition: background-color 0.3s ease, transform 0.3s ease;
      border-radius: 10px;
      border: none;
    }

    .btn-primary:hover {
      background-color: #0c3748;

      color: white;
    }
  </style>
</head>

<body>
  <div class="header-main d-flex justify-content-center py-3">
    <img src="{{ asset('image/Logo.png') }}" alt="" width="250px" />
  </div>
  <div class="container my-5">
    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="font-700 mt-3 mb-2">Nama Lengkap</div>
      @error('name')
        <p class="text-danger">{{ $message }}</p>
      @enderror
      <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" />

      <div class="font-700 mt-3 mb-2">No. WA Aktif</div>
      @error('phone')
        <p class="text-danger">{{ $message }}</p>
      @enderror
      <input type="tel" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" />

      <div class="font-700 mt-3 mb-2">Jabatan di HIPMI</div>
      @error('office')
        <p class="text-danger">{{ $message }}</p>
      @enderror
      <input type="text" class="form-control" name="office" id="office" value="{{ old('office') }}" />

      <div class="font-700 mt-3 mb-2">Asal BPC</div>
      @error('bpc')
        <p class="text-danger">{{ $message }}</p>
      @enderror
      <input type="text" class="form-control" name="bpc" id="bpc" value="{{ old('bpc') }}" />

      <div class="font-700 mt-3 mb-2">Keanggotaan</div>
      @error('membership')
        <p class="text-danger">{{ $message }}</p>
      @enderror
      <select class="form-control" name="membership" id="membership">
        <option selected disabled>Pilih</option>
        <option @if (old('membership') ==
                'VVIP (PJ Gubernur, PJ Walikota, Ketum BPP, Ketum BPD, Ketua Wanbin Wanhor BPD, Ketum Organisasi lain)') selected @endif
          value="VVIP (PJ Gubernur, PJ Walikota, Ketum BPP, Ketum BPD, Ketua Wanbin Wanhor BPD, Ketum Organisasi lain)">
          VVIP (PJ Gubernur, PJ Walikota, Ketum BPP, Ketum BPD, Ketua Wanbin Wanhor BPD, Ketum Organisasi lain)
        </option>
        <option value="VIP (KA Dinas Pemkot, Pengurus BPP, Pengurus BPD, Anggota Wanbin Wanhor BPD & BPC)"
          @if (old('membership') == 'VIP (KA Dinas Pemkot, Pengurus BPP, Pengurus BPD, Anggota Wanbin Wanhor BPD & BPC)') selected @endif>
          VIP (KA Dinas Pemkot, Pengurus BPP, Pengurus BPD, Anggota Wanbin Wanhor BPD & BPC)
        </option>
        <option value="UTUSAN PENUH" @if (old('membership') == 'UTUSAN PENUH') selected @endif>
          UTUSAN PENUH
        </option>
        <option value="PENINJAU" @if (old('membership') == 'PENINJAU') selected @endif>
          PENINJAU
        </option>
      </select>

      <div class="font-700 mt-3 mb-2">Upload Foto Formal</div>
      @error('image')
        <p class="text-danger">{{ $message }}</p>
      @enderror
      <div class="file-upload">
        <label for="file-input" class="file-upload-button font-400">
          <img src="{{ asset('image/Upload.png') }}" alt="" />
          Tambahkan File</label>
        <input type="file" name="image" id="file-input" class="file-input" />
        <div id="file-name" class="file-name font-400"></div>
      </div>

      <button type="submit" class="mt-4 btn btn-primary font-700">KIRIM</button>
    </form>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    document
      .getElementById("file-input")
      .addEventListener("change", function() {
        var fileName = this.files[0] ? this.files[0].name : "";
        document.getElementById("file-name").textContent = fileName;
      });
  </script>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

@if (Session::has('swal-register-success'))
  <script>
    Swal.fire({
      title: "Registrasi Berhasil",
      text: 'Terima kasih atas registrasi Anda, kami akan mengirimkan konfirmasi kehadiran melalui WhatsApp.',
      icon: 'success',
      confirmButtonColor: '#0c3748',
    });
  </script>
@endif

</html>

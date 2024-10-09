@extends('adminlte::page')

@section('title', 'List Registrasi')

@section('content_header')
  <div>
    {{-- <a href="{{ url()->previous() }}">
      <i class="fas fa-arrow-left"></i> Kembali
    </a> --}}
  </div>
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
    <h1>
      <b>Detail Registrasi : {{ $registration->name }}</b>
    </h1>

    <div class="mt-3 mt-md-0">
      @if ($registration->has_attended)
        <div>
          Waktu :
          <span class="font-weight-bolder mr-2">
            {{ \Illuminate\Support\Carbon::parse($registration->attended_at)->setTimezone('Asia/Jakarta')->format('H:i d/m/Y') }}
          </span>
          <x-badges.attended-badge />
        </div>
      @else
        <x-badges.not-attended-badge />
      @endif
    </div>
  </div>
  <div class="w-100 mt-2 d-flex flex-column flex-md-row-reverse">
    @if ($registration->has_attended)
      <button class="btn btn-outline-dark" onclick="handleSetAttendance(false)">
        Set Menjadi Tidak Hadir
      </button>
    @else
      <button class="btn btn-primary" onclick="handleSetAttendance(true)">
        Set Menjadi Hadir
      </button>
    @endif

    <a class="btn btn-primary mr-md-1 mt-2 mt-md-0" href="{{ route('admin.registration.downloadQr', $registration->id) }}">
      Download QR
    </a>
  </div>
@stop

@section('content')

  @if (Session::has('message'))
    <div class="alert alert-primary">
      {{ Session::get('message') }}
    </div>
  @endif

  <div class="mt-2 card">
    <div class="card-body row ">
      <form action="{{ route('admin.registration.update', $registration->id) }}" method="POST"
        enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="col-12 col-md-12">
          <ul>
            @foreach ($errors->all() ?? [] as $err)
              <li class="text-danger">{{ $err }}</li>
            @endforeach
          </ul>
          <div class="d-flex flex-column gap-0 mb-3">
            <h5 class="font-weight text-muted ">
              Nama
            </h5>
            @error('name')
              <p class="text-danger">{{ $message }}</p>
            @enderror
            <div class="">
              <input class="form-control" type="text" name="name" value="{{ old('name') ?? $registration->name }}">
            </div>
          </div>
          <div class="d-flex flex-column gap-0 mb-3">
            <h5 class="font-weight text-muted ">
              Nomor WA Aktif
            </h5>
            @error('phone')
              <p class="text-danger">{{ $message }}</p>
            @enderror
            <div class="">
              <input class="form-control" type="tel" name="phone"
                value="{{ old('phone') ?? $registration->phone }}">
            </div>
          </div>
          <div class="d-flex flex-column gap-0 mb-3">
            <h5 class="font-weight text-muted ">
              Jabatan di Hipmi
            </h5>
            @error('office')
              <p class="text-danger">{{ $message }}</p>
            @enderror
            <div class="">
              <input class="form-control" type="text" name="office"
                value="{{ old('office') ?? $registration->office }}">
            </div>
          </div>
          <div class="d-flex flex-column gap-0 mb-3">
            <h5 class="font-weight text-muted ">
              Asal BPC
            </h5>
            @error('bpc')
              <p class="text-danger">{{ $message }}</p>
            @enderror
            <div class="">
              <input class="form-control" type="text" name="bpc" value="{{ old('bpc') ?? $registration->bpc }}">
            </div>
          </div>
          <div class="d-flex flex-column gap-0 mb-3">
            <h5 class="font-weight text-muted ">
              Keanggotaan
            </h5>
            @error('membership')
              <p class="text-danger">{{ $message }}</p>
            @enderror
            <div class="">
              <input class="form-control" type="text" name="membership"
                value="{{ old('membership') ?? $registration->membership }}">
            </div>
          </div>
          <div class="d-flex flex-column gap-0 mb-3">
            <h5 class="font-weight text-muted ">
              Foto
            </h5>
            <div class="">
              <input class="form-control mb-2" type="file" name="image" id="image">
              <img id="image-preview" style="max-width: 25vw;" src="{{ $registration->image_url }}" alt="">
            </div>
          </div>
        </div>
        <div>
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </div>

  </div>
@stop

@section('css')
  <livewire:styles />

  <script src="//cdn.jsdelivr.net/npm/alpinejs@3.13.2/dist/cdn.min.js" defer></script>

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  {{-- <meta http-equiv="refresh" content="5"> --}}
@stop

@section('js')
  <livewire:scripts />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

  <x-livewire-alert::scripts />

  <script>
    const handleSetAttendance = (isAttended) => {
      const attendStatusText = isAttended ? 'Hadir' : 'Tidak Hadir';
      const userId = "{{ $registration->id }}";
      const link = `{{ url('admin/registration/${userId}/attendance') }}`;

      Swal.fire({
        title: `Ubah status kehadiran menjadi ${attendStatusText}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "Konfirmasi",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.put(link, {
            attended: isAttended,
          }).then(res => {
            if (res.data.success) {
              window.location.reload();
            }
          }).catch(err => {
            console.log('Error setting attendance', err);
          });
        }
      });
    };
  </script>

  <script>
    image.onchange = evt => {
      const preview = document.getElementById('image-preview');

      const [file] = image.files;

      if (file) {
        preview.src = URL.createObjectURL(file);
      }
    };
  </script>

@stop

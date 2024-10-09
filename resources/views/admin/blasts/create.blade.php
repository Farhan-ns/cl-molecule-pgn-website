    @extends('adminlte::page')

    @section('title', 'Tambah List Blast Manual')

    @section('content_header')
      <h1>
        <b>Tambah List Blast Manual</b>
      </h1>
    @stop

    @section('content')

      <h5 class="">Event : GREEN ENERGY INDUSTRI IOT & AUDIT ENERGY</h5>
      <div class="d-flex justify-content-between">
        {{-- <h5>Jumlah Data Registrasi: {{ $registrations_count }}</h5> --}}
        {{-- <a class="btn btn-success ml-1 mt-2" href="{{ route('admin.registration.export') }}"> Export Excel <i class="fas fa-file-excel"></i></a> --}}
      </div>

      @if (Session::has('message'))
        <div class="alert alert-primary">
          {{ Session::get('message') }}
        </div>
      @endif

      <div class="mt-2 w-50">
        @if (\Session::has('error'))
          <div class="alert alert-danger">{{ \Session::get('error') }}</div>
        @endif

        <form action="{{ route('admin.blast.store') }}" method="POST">
          @csrf
          <button class="btn btn-primary mb-3" type="submit">Simpan</button>


          <ul class="text-muted">
            <li>
                Awali nomor telepon dengan <b>+62</b>
            </li>
            <li>
                Pisah nomor dengan <b>enter / baris baru</b>
            </li>
          </ul>
          <p class="text-muted">
            
          </p>
          <p class="text-muted">
            
          </p>
          <textarea class="form-control" name="phone_number" id="" cols="20" rows="15"
            placeholder="+62xxxxxxxxxxx">{{ old('phone_number') }}</textarea>
        </form>
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

      <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"
        integrity="sha512-b94Z6431JyXY14iSXwgzeZurHHRNkLt9d6bAHt7BZT38eqV+GyngIi/tVye4jBKPYQ2lBdRs0glww4fmpuLRwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

      <x-livewire-alert::scripts />

    @stop

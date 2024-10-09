@extends('adminlte::page')

@section('title', 'List Registrasi')

@section('content_header')
  <h1>
    <b>List Registrasi</b>
  </h1>
@stop

@section('content')

  <div class="d-flex justify-content-between">
    <h5>Jumlah Data Registrasi: {{ $registrations_count }}</h5>
    <a class="btn btn-success ml-1 mt-2" href="{{ route('admin.registration.export') }}"> Export Excel <i
        class="fas fa-file-excel"></i></a>
  </div>

  @if (Session::has('message'))
    <div class="alert alert-primary">
      {{ Session::get('message') }}
    </div>
  @endif

  <div class="mt-2">
    <livewire:registration-table theme="bootstrap-4" />
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

  <script>
    const adminId = "{{ request()->user()->id }}";
    let i = 1;
    toastr.options.timeOut = 0;
    toastr.options.extendedTimeOut = 0;

    const fetchNotification = async () => {
      try {
        const notifications = await axios.get(`{{ url('api/${adminId}/notifications') }}`);
        console.log('notifications', notifications);
        notifications.data.data.forEach(data => {
          const {
            registration_name,
            registration_id
          } = data.data;

          console.log('name & registration', `${registration_name} - ${registration_id}`);
          showToast(registration_id, registration_name);
        });
      } catch (error) {
        console.error('Error fetching notifications', error.message);
      }
    };

    const showToast = (id, name) => {
      const regid = id;
      toastr.options.onclick = function() {
        window.open(`{{ url('admin/nametag/${regid}') }}`, '_blank');
      }

      toastr.info(`Berhasil Scan: ${name}`);
    };

    const intervalId = setInterval(fetchNotification, 1000 * 8);
  </script>

  @if (Session::has('swal-register-success'))
    <script>
      Swal.fire({
        title: "Registrasi Berhasil",
        text: {{ session('swal-register-success') }},
        icon: 'success',
        confirmButtonColor: '#0c3748',
      });
    </script>
  @endif
@stop

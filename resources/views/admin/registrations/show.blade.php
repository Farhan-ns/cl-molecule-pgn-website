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
      <div class="col-12 col-md-6">
        <div class="d-flex flex-column gap-0 mb-3">
          <h5 class="font-weight text-muted ">
            Nama
          </h5>
          <h4 class="">
            {{ $registration->name }}
          </h4>
        </div>
        <div class="d-flex flex-column gap-0 mb-3">
          <h5 class="font-weight text-muted ">
            Nomor WA Aktif
          </h5>
          <h4 class="">
            {{ $registration->phone }}
          </h4>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="d-flex flex-column gap-0 mb-3">
          <h5 class="font-weight text-muted ">
            Jabatan di Hipmi
          </h5>
          <h4 class="">
            {{ $registration->office }}
          </h4>
        </div>
        <div class="d-flex flex-column gap-0 mb-3">
          <h5 class="font-weight text-muted ">
            Asal BPC
          </h5>
          <h4 class="">
            {{ $additionalInfo['bpc'] ? \Str::title($additionalInfo['bpc']) : '-' }}
          </h4>
        </div>
        <div class="d-flex flex-column gap-0 mb-3">
          <h5 class="font-weight text-muted ">
            Keanggotaan
          </h5>
          <h4 class="">
            {{ $additionalInfo['membership'] ? \Str::title($additionalInfo['membership']) : '-' }}
          </h4>
        </div>
        <div class="d-flex flex-column gap-0 mb-3">
          <h5 class="font-weight text-muted ">
            Foto
          </h5>
          <h4 class="">
            <img style="max-width: 25vw;" src="{{ $registration->image_url }}" alt="">
          </h4>
        </div>
      </div>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"
    integrity="sha512-b94Z6431JyXY14iSXwgzeZurHHRNkLt9d6bAHt7BZT38eqV+GyngIi/tVye4jBKPYQ2lBdRs0glww4fmpuLRwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
@stop

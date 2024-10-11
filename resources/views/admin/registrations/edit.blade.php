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
  {{-- 
  <div class="mt-2 card">
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          test
        </div>
        <div class="col-6">
          test
        </div>
      </div>
    </div>
  </div> --}}

  <div class="mt-2 card">
    <div class="card-body">
      <form action="{{ route('admin.registration.update', $registration->id) }}" method="POST"
        enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <ul>
          @foreach ($errors->all() ?? [] as $err)
            <li class="text-danger">{{ $err }}</li>
          @endforeach
        </ul>

        <div class="row">
          <div class="col-12 col-lg-12">
            <div class="d-flex flex-column gap-0 mb-3">
              <h5 class="font-weight text-muted ">
                Nama
              </h5>
              @error('name')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="">
                <input class="form-control" type="text" name="name"
                  value="{{ old('name') ?? $registration->name }}">
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="d-flex flex-column gap-0 mb-3">
              <h5 class="font-weight text-muted ">
                Email
              </h5>
              @error('email')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="">
                <input class="form-control" type="email" name="email"
                  value="{{ old('email') ?? $registration->email }}">
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
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
          </div>

          <div class="col-12 col-lg-6">
            <div class="d-flex flex-column gap-0 mb-3">
              <h5 class="font-weight text-muted ">
                Nama Perusahaan
              </h5>
              @error('company_name')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="">
                {{-- <input class="form-control" type="text" name="company_name"
                  value="{{ old('company_name') ?? $registration->company_name }}"> --}}
                <select id="select2-companies" style="width: 100%"></select>
                <input type="hidden" name="company_name"
                  value="{{ old('company_name') ?? $registration->company_name }}">
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="d-flex flex-column gap-0 mb-3">
              <h5 class="font-weight text-muted ">
                Domisili Perusahaan
              </h5>
              @error('company_address')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="">
                <input class="form-control" type="text" name="company_address"
                  value="{{ old('company_address') ?? $registration->company_address }}" readonly>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="d-flex flex-column gap-0 mb-3">
              <h5 class="font-weight text-muted ">
                Jabatan
              </h5>
              @error('office')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="">
                <input class="form-control" type="text" name="office"
                  value="{{ old('office') ?? $registration->office }}">
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="d-flex flex-column gap-0 mb-3">
              <h5 class="font-weight text-muted ">
                Akan Hadir
              </h5>
              @error('will_attend')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="will_attend" id="will_attend_1" value="1"
                    @checked($registration->getWillAttendAttribute() == 1)>
                  <label class="form-check-label" for="will_attend_1">
                    Hadir
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="will_attend" id="will_attend_2" value="0"
                    @checked($registration->getWillAttendAttribute() == 0)>
                  <label class="form-check-label" for="will_attend_2">
                    Tidak Hadir
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-12">
            <button class="btn btn-primary" type="submit">Simpan</button>
          </div>
        </div>
      </form>

    </div>

  </div>
@stop

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <livewire:styles />

  <script src="//cdn.jsdelivr.net/npm/alpinejs@3.13.2/dist/cdn.min.js" defer></script>

  <style>
    [x-cloak] {
      display: none !important;
    }

    .select2-container .select2-selection--single {
      height: auto;
      !important
    }
  </style>

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  {{-- <meta http-equiv="refresh" content="5"> --}}
@stop

@section('js')
  <livewire:scripts />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"
    integrity="sha512-b94Z6431JyXY14iSXwgzeZurHHRNkLt9d6bAHt7BZT38eqV+GyngIi/tVye4jBKPYQ2lBdRs0glww4fmpuLRwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <x-livewire-alert::scripts />

  <script>
    const currentCompanyName = "{{ $registration->company_name }}";
    let cachedData = [];

    $.ajax({
      url: "{{ route('api.getCompanies') }}",
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
          data: cachedData
        });

        let selectedCompany = cachedData.find(function(item) {
          return item.text === currentCompanyName;
        });

        if (selectedCompany) {
          $('#select2-companies').val(selectedCompany.id).trigger('change');
        }

        $('#select2-companies').on('select2:select', function(e) {
          let selectedId = e.params.data.id;
          let splitId = selectedId.split('->');
          let companyName = splitId[0];
          let area = splitId[1];

          $('input[name="company_name"]').val(companyName);
          $('input[name="company_address"]').val(area);
        });
      },
      error: function(err) {
        console.error("Error fetching companies data", err);
      }
    });
  </script>

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

@stop

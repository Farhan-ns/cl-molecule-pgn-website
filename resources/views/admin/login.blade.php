@extends('user.skeleton')

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
@stop

@section('main')

  <div class="bg-main">
  </div>
  <div class="container vh-min-100 align-items-md-center d-flex justify-content-center">
    <div class="my-3 mt-5 my-md-0 mt-md-0 my-lg-3 mt-lg-5">
      <div class="text-center">
        <img class="img-fluid" style="max-width: 25vw;" src="https://dummyimage.com/600x400/000/fff&text=Logo" alt="logo-silatda.png">
        {{-- <img class="img-fluid" style="max-width: 25vw;" src="{{ asset('./logo.png') }}" alt="logo-silatda.png"> --}}
        
        <h1 style="font-size: 2.5em;">Musda Hipmi XVII</h1>
      </div>

      <h2 class="text-center mt-3">Login Admin</h2>

      <div class="card-register mt-3" style="min-width: 35vw;">

        @if ($errors->any())
          @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
              {{ $error }}
            </div>
          @endforeach
        @endif

        <form action="{{ route('admin.auth') }}" method="POST">
          @csrf

          <label for="nama" class="fw-bold">Email</label>
          <input class="form-control mt-2 mb-4 p-3 border-black" type="text" name="email" value="{{ old('email') }}"
            placeholder="Email" required>

          <label for="nama" class="fw-bold">Password</label>
          <input class="form-control mt-2 mb-4 p-3 border-black" type="password" name="password" placeholder="Password"
            required>

          <button type="submit" class="btn mt-4 p-3 w-100 text-white fw-bold" style="background-color: #65ccda;">
            LOGIN
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection

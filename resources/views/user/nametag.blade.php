<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Musda XVII</title>
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

    .font-400-s {
      font-weight: 600;
      font-size: 0.5em;
    }

    .font-400 {
      font-weight: 400;
      font-size: 0.7em;
    }

    .font-700 {
      font-weight: 700;
      font-size: 0.8em;
    }

    .section-main {
      height: 8cm;
      width: 5.3cm;
      background-image: url("{{ asset('image/qr-code-bg.png') }}");
      background-size: cover;
    }

    .bg-main {
      position: absolute;
      top: 0;
      bottom: 0;
      right: 0;
      left: 0;
    }

    .jabatan {
      margin-bottom: 1.5cm;
    }

    .qr-main {
      margin-bottom: 0.75cm;
      width: 3cm;
      height: 3cm;
    }

}
  </style>
</head>

<body >
  <div id="section-main" class="section-main d-flex flex-column justify-content-end py-3">
    <img id="img" class="qr-main align-self-center" src="{{ $registration->image_url }}">
    {{-- <img id="img" class="qr-main align-self-center" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(750)->generate($encryptedId)) !!} "> --}}

    <div class="align-self-center font-400">
      {{ $registration->name }}
    </div>

    <div class="jabatan align-self-center font-400-s">
      @if (str_contains($additionalInfo['membership'], 'VVIP'))
        VVIP
      @elseif (str_contains($additionalInfo['membership'], 'VIP'))
        VIP
      @elseif (str_contains($additionalInfo['membership'], 'PENINJAU'))
        PENINJAU
      @elseif (str_contains($additionalInfo['membership'], 'UTUSAN'))
        UTUSAN PENUH
      @else
        -
      @endif
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>

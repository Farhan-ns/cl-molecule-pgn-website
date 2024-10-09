<div>
  <label for="phone_number">Nomor Telepon</label>
  @foreach ($inputArray as $key => $value)
    {{-- <p>{{ $key }}</p> --}}
    {{-- <p>{{ $value }}</p> --}}
    <div class="form-group" wire:key='{{ $value }}'>
      <div class="d-flex align-items-center" wire:key='{{ $value }}'>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">+62</span>
          </div>
          <input type="number" class="form-control" name="name[]" value="{{ $value }}">
        </div>
        <button class="btn btn-danger mx-2" wire:click.prevent="removeInput({{ $value }})">X</button>
      </div>
    </div>
  @endforeach
  <button class="btn btn-primary" wire:click.prevent="addInput">Tambah Input</button>
</div>

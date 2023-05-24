<div class="form-group {{$class}}">
    <label for="name">{{ __($title) }}</label>
    <input
        {{ $attributes }}
        id="{{$name}}"
        name="{{$name}}"
        type="{{$type}}"
        class="form-control @error($name) is-invalid @enderror"
        value="{{ old($name) }}"
        placeholder="{{ __($placeholder) }}"
        autocomplete="{{$name}}"
        aria-describedby="{{$name}}"
        {{$required ? 'required' : ''}}
        autofocus
    >

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

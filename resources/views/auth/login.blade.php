@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ localizedRoute('login') }}">
                        @csrf
                        <x-form-field
                            title='Email Address'
                            name='email'
                            type='email'
                            class='mb-3'
                        />

                        <x-form-field
                            title='Password'
                            name='password'
                            type='password'
                            class='mb-3'
                        />

                        <div class="form-group mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                            @if (Route::has('register'))
                                <a class="nav-link mt-2" href="{{ localizedRoute('register') }}">{{__('You don\'t have any account? Register!')}}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

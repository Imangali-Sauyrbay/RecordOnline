@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ localizedRoute('register') }}">
                        @csrf

                        <x-form-field
                            title='Full Name'
                            placeholder='Lastname Firstname Patronomyc'
                            name='name'
                            class='mb-3'
                        />

                        <x-form-field
                            title='Group'
                            placeholder='eg. IP-19-3t'
                            name='group'
                            class='mb-3'
                        />

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

                        <x-form-field
                            title='Confirm Password'
                            name='password_confirmation'
                            type='password'
                            class='mb-3'
                        />

                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                            @if (Route::has('register'))
                                <a class="nav-link mt-2" href="{{ localizedRoute('login') }}">{{__('You already have an account? Login!')}}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

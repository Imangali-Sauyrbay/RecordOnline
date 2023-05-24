@extends('layouts.app')

@section('scripts')
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        setTimeout(() => {
            var collection = document
            .getElementsByClassName('alert')

            for (var el of collection) {
                if(el) {
                    el.parentElement.removeChild(el);
                }
            }
        }, 5000);
    });
</script>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mb-3">
                <div class="card-body">
                    <form
                    method="POST"
                    action="{{ localizedRoute('profile.update.data') }}"
                    enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-6 offset-3 offset-md-0 col-md-4 d-flex justify-content-center align-items-center">
                                <img
                                src="{{asset('storage/' . Auth::user()->avatar)}}"
                                class="image rounded-circle"
                                alt="{{__('Avatar')}}"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-form-field
                                title='Full Name'
                                placeholder="Lastname Firstname Patronomyc"
                                name='name'
                                type='text'
                                class='mb-3'
                                value='{{auth()->user()->name}}'
                                :required="false"
                                />

                                @if(!auth()->user()->isCoworker())
                                <x-form-field
                                title='Group'
                                name='group'
                                type='text'
                                class='mb-3'
                                value='{{auth()->user()->group}}'
                                :required="false"
                                />
                                @endif

                                <x-form-field
                                title='Avatar'
                                name='avatar'
                                type='file'
                                class='mb-3'
                                :required="false"
                                />
                            </div>
                        </div>

                        <div class="mb-0 d-flex align-items-center justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ localizedRoute('profile.update.password') }}">
                        @csrf

                        <x-form-field
                        title='Password'
                        name='password'
                        type='password'
                        class='mb-3'
                        />

                        <x-form-field
                        title='New Password'
                        name='new_password'
                        type='password'
                        class='mb-3'
                        />

                        <x-form-field
                        title='Confirm Password'
                        name='new_password_confirmation'
                        type='password'
                        class='mb-3'
                        />

                        <div class="mb-0 d-flex align-items-center justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

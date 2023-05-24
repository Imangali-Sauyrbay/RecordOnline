@extends('layouts.app')

@section('scripts')
<script>
    window.lang = {
        dates: "{{ __('dates') }}",
        time: "{{ __('Time') }}",
        duration: "{{__('Select date and time')}}"
    }

    window.searchUrl = "{{ localizedRoute('record.lits') }}"

    window.addEventListener('DOMContentLoaded', (event) => {
        // Removing any existing in dom alerts after 5s
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

@vite(['resources/js/addNewRecordForm.js'])
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

            <div class="card">
                <div class="card-header">{{ __('Online record') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ localizedRoute('record.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Required literature') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="subscription" class="col-md-4 col-form-label text-md-end">{{ __('Select a subscription') }}</label>

                            <div class="col-md-6">
                                <select id="subscription" name="subscription" class="form-select" aria-label="select" required autofocus>
                                    @isset($subscription)
                                        @foreach ($subscription as $sub)
                                            <option value="{{$sub->id}}">{{$sub->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>

                                @error('subscription')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="duration" class="col-md-4 col-form-label text-md-end">{{__('Select a duration')}}</label>

                            <div class="col-md-6">
                                <select id="duration" name="duration" class="form-select" aria-label="select" required autofocus>
                                    @for ($i = 10; $i < 50; $i+=10)
                                        <option value="{{$i}}">{{$i}} {{__('minutes')}}</option>
                                    @endfor
                                </select>

                                @error('duration')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="selec-lits">
                            <label class="col-md-4 col-form-label text-md-end">{{__('Search literatures')}}</label>

                            <div class="col-md-6">
                                <selec-lits>Loading...</selec-lits>

                                @error('duration')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3" id="selec-date-time">
                            <selec-date-time>Loading...</selec-date-time>
                            @error('datetime')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0 d-flex align-items-center justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Record') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

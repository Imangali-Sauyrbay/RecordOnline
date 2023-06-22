@extends('layouts.app')

@section('scripts')
    <script>
        var urlParams = new URLSearchParams(window.location.search)
        var tz = Intl.DateTimeFormat().resolvedOptions().timeZone

        if (!urlParams.has('tz')) {
            urlParams.append('tz', tz)
            var baseUrl = window.location.href.split('?')[0]
            var newUrl = `${baseUrl}?${urlParams.toString()}`

            window.location.href = newUrl;
        }

        window.addEventListener('DOMContentLoaded', function() {
            var links = document.querySelectorAll('.page-link')
            links.forEach(function(link) {
                if(!link.hasAttribute('href')) return
                var urlParams = new URLSearchParams(link.href.split('?')[1] || '')
                if (urlParams.has('tz')) return
                urlParams.append('tz', tz)
                var baseUrl = link.href.split('?')[0]
                link.href = `${baseUrl}?${urlParams.toString()}`
            })

            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    sessionStorage.setItem('scrollPosition', window.scrollY);
                })
            })

            var scrollPosition = sessionStorage.getItem('scrollPosition');

            if (scrollPosition) {
                window.scrollTo({
                    top: parseInt(scrollPosition),
                    behavior: 'instant'
                })

                sessionStorage.removeItem('scrollPosition');
            }

        })
    </script>

    @if(auth()->user()->isCoworker())
        @php
            $sub = auth()->user()->subscription->id;
        @endphp

        <script>
            window.subscriptionId = {{ $sub }}
            window.records = {{ $recordsCount }}
            window.route = "{{ localizedRoute('records.count', ['sub' => $sub]) }}"
            window.lang = {
                addedMsg: "{{__('Records was added: #. Please refresh the website to see them!')}}"
            }
        </script>

        @if(config('app.ws'))
            @vite(['resources/js/recordsWs.js'])
        @else
            @vite(['resources/js/records.js'])
        @endif
    @endif
@endsection

@section('navbar-left')
    @if(auth()->user()->isCoworker())
        <h4>{{ auth()->user()->subscription->name }}</h4>
    @endif
@endsection

@section('content')
<div class="container">
    @if(auth()->user()->isCoworker())
        <div class="alert alert-info info d-none" id="info"></div>
    @endif

    @if ($records->hasPages())
        <div class="pagination-wrapper">
            {{ $records->links() }}
        </div>
    @endif

    @forelse ($records as $record)
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            <div class="card @if($record->recorded_to < now()) text-muted @endif">
                <div class="card-header"><h4>{{ $record->title }}</h4></div>

                <div class="card-body">
                    <h5>{{__('User')}}: {{$record->user->name}} <img src="{{asset('storage/' . $record->user->avatar)}}" class="image rounded-circle" style="width: 40px;"></h5>
                    <p>{{__('Email Address')}}: <a class="nav-link d-inline m-0 p-0" href="mailto:{{ $record->user->email }}">{{$record->user->email}}</a></p>
                    <p>{{__('Group')}}: {{$record->user->group ?? __('null')}}</p>
                    <p>{{__('To Subscription')}}: {{$record->subscription->name}}</p>
                    <p>{{__('Duration')}}: {{$record->duration}} {{__('minutes')}}</p>
                    <p>{{__('Date')}}: {{$record->recorded_to->setTimezone($tz)}}</p>
                    @if ($record->lits)
                        @php
                            $lits = explode(';;;', $record->lits);
                        @endphp
                        <p>{{__('Literatures')}}:</p>
                        <ul>
                            @foreach ($lits as $lit)
                                <li>{{$lit}}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="@if(!auth()->user()->isCoworker())text-end mb-0 @else mb-3 @endif">
                        <h5><span class="badge rounded-pill text-bg-primary">{{$record->recorded_to->setTimezone($tz)->diffForHumans()}}</span></h5>
                    </div>

                    @if(auth()->user()->isCoworker())
                    <form action="{{localizedRoute('record.status.update', ['record' => $record->id])}}" method="POST">
                        @csrf
                        <select class="form-select mb-2" name="status">
                            @foreach ($statuses as $status)
                                <option value="{{$status->id}}" @if($status->id === $record->recordStatus->id) disabled selected @endif>{{$status->getTranslatedAttribute('title', app()->getLocale(), config('app.locale'))}}</option>
                            @endforeach
                        </select>

                        <div class="form-group d-flex align-items-center justify-content-end">
                            <a target="_blank" href="{{getEmailUrl(
                                $record->recordStatus->subject . '. ' . __('Sincerely') . ': ' . auth()->user()->name,
                                $record->recordStatus->html ?:
                                $record->recordStatus->content,
                                $record->user->email
                            )}}" class="btn btn-outline-secondary mx-1">{{__('Send Email')}}</a>
                            <button type="submit" class="btn btn-primary">{{__('Change')}}</button>
                        </div>
                    </form>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-end align-items-center">
                    <h5 class="text-muted">
                        <span class="badge {{$record->recordStatus->badge ?? 'text-bg-secondary'}}">
                            {{__('Status')}}: {{ $record->recordStatus->getTranslatedAttribute('title', app()->getLocale(), config('app.locale')) }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4>{{__('You dont have any records!')}}</h4>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    @if ($records->hasPages())
        <div class="pagination-wrapper">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection

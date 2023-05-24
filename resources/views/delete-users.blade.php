@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="page-content container-fluid" style="margin: 3rem">
        <form class="form-edit-add" role="form"
            action="{{ localizedRoute('delete.users.delete') }}"
            method="POST">
            @csrf

            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="group">{{ __('Select group or year') }}</label>
                        <select class="form-control select2" id="group" name="group" required>
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach

                            @foreach ($groups as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-danger pull-right save">
                <i class="voyager-trash"></i>
                <span>{{ __('Delete') }}</span>
            </button>
        </form>
    </div>

    <div class="page-content container-fluid" style="margin: 3rem">
        <form class="form-edit-add" role="form"
            action="{{ localizedRoute('delete.users.restore') }}"
            method="POST">
            @csrf

            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="group">{{ __('Select group or year') }}</label>
                        <select class="form-control select2" id="group" name="group" required>
                            @foreach ($yearsTrashed as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach

                            @foreach ($groupsTrashed as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success pull-right save">
                <i class="voyager-trash"></i>
                <span>{{ __('Restore') }}</span>
            </button>
        </form>
    </div>
@stop

@extends('adminlte::page')

@section('title', __('onekeyinstallers.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('onekeyinstallers.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('onekeyinstallers.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('onekeyinstallers.table')

@endsection

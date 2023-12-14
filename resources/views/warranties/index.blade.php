@extends('adminlte::page')

@section('title', __('warranties.title') )

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('warranties.header') }}</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('warranties.table')

@endsection

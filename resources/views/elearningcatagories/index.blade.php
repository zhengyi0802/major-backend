@extends('adminlte::page')

@section('title', __('elearningcatagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('elearningcatagories.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('elearningcatagories.title') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('elearningcatagories.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('elearningcatagories.table')

@endsection

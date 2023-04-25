@extends('adminlte::page')

@section('title', __('mediacatagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mediacatagories.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('mediacatagories.title') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('mediacatagories.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('mediacatagories.table')

@endsection

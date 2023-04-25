@extends('adminlte::page')

@section('title', __('videocatagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('videocatagories.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('videocatagories.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('videocatagories.table')

@endsection


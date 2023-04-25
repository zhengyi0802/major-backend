@extends('adminlte::page')

@section('title', __('product_statuses.title') )

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('product_statuses.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>產品狀態</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('product_statuses.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('product_statuses.table')

@endsection

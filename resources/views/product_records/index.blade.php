@extends('adminlte::page')

@section('title', __('product_records.title') )

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('product_records.header') }}</h1>
@stop

@section('content')
    @include('product_records.table')
@endsection


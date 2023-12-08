@extends('adminlte::page')

@section('title',  __('products.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('products.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('products.details') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-info" href="{{ route('products.index') }}">{{ __('tables.back') }}</a>
                <a class="btn btn-primary" href="{{ route('products.edit', $product->id); }}">{{ __('tables.edit') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.type') }} : </strong>
                {{ $product->type->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.serialno') }} : </strong>
                {{ $product->serialno }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.android_id') }} : </strong>
                {{ $product->android_id }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.project') }} : </strong>
                {{ ($product->project != null) ? ($product->project->name) : "--------" }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.ether_mac') }} : </strong>
                {{ $product->ether_mac }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.wifi_mac') }} : </strong>
                {{ $product->wifi_mac }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.expire_date') }} : </strong>
                {{ $product->expire_date }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('products.status') }}:</strong>
                {{ $product->status->name }}
            </div>
        </div>
     </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('products.records') }}</h1>
            </div>
        </div>
    </div>

    @include('products.table2')

@endsection

@extends('adminlte::page')

@section('title', __('product_queries.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('product_queries.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
            @include('layouts.back')
        </div>
    </div>

    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.serialno') }} :</strong>
                {{ $product_query->serialno }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.ether_mac') }} :</strong>
                {{ $product_query->ether_mac }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.wifi_mac') }} :</strong>
                {{ $product_query->wifi_mac }}
            </div>
        </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.keywords') }} :</strong>
                {{ $product_query->keywords }}
            </div>
        </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.query') }} :</strong>
                {{ $product_query->query }}
            </div>
        </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.response') }} :</strong>
                {{ $product_query->response }}
            </div>
        </div>

         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('product_queries.created_at') }} :</strong>
                {{ $product_query->created_at }}
            </div>
        </div>
     </div>
@stop

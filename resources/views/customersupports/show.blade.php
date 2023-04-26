@extends('adminlte::page')

@section('title', __('customersupports.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('customersupports.header') }}</h1>
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
                <strong>{{ __('customersupports.project') }} :</strong>
                {{ $customersupport->project->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.qrcode_type') }} :</strong>
                @if ($customersupport->qrcode_type == "text")
                    {{ __('customersupports.type_text') }}
                @elseif ($customersupport->qrcode_type == "image")
                    {{ __('customersupports.type_image') }}
                @else
                    {{ __('customersupports.type_null') }}
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.qrcode_content') }} :</strong>
                {{ $customersupport->qrcode_content }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.message') }} :</strong>
                {{ $customersupport->message }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.rcapp_label') }} :</strong>
                {{ $customersupport->rcapp_label }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.rcapp') }} :</strong>
                {{ $customersupport->rcapp }}
            </div>
         </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.rcapp_url') }} :</strong>
                {{ $customersupport->rcapp_url }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('tables.creator') }} :</strong>
                {{ $customersupport->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('customersupports.status') }} :</strong>
                {{ ($customersupport->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection

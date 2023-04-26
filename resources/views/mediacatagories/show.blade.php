@extends('adminlte::page')

@section('title', __('mediacatagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mediacatagories.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card-group col-md-12">
            @include('layouts.back')
            @if (auth()->user()->rol != 'operator')
            <div class="card col-md-2">
               @include('mediacatagories.popwindow')
            </div>
            @endif
        </div>
    </div>

    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.project') }} :</strong>
                {{ $mediacatagory->project->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.parent') }} :</strong>
                {{ $mediacatagory->parent ? $mediacatagory->parent->name : null }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.type') }} :</strong>
                {{ $mediacatagory->type }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.name') }} :</strong>
                {{ $mediacatagory->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.keywords') }} :</strong>
                {{ $mediacatagory->keywords }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.descriptions') }} :</strong>
                {{ $mediacatagory->description }}
            </div>
         </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.thumbnail') }} :</strong>
                <img src="{{ $mediacatagory->thumbnail }}">
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('tables.creator') }} :</strong>
                {{ $mediacatagory->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.status') }} :</strong>
                {{ ($mediacatagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection
@section('plugins.BootstrapSelect', true)

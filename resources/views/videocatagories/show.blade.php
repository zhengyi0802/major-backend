@extends('adminlte::page')

@section('title', __('videocatagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('videocatagories.header') }}</h1>
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
                <strong>{{ __('videocatagories.user') }} :</strong>
                {{ $videocatagory->user ? $videocatagory->user->name : null }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videocatagories.parent') }} :</strong>
                {{ $videocatagory->parent ? $videocatagory->parent->name : __('videocatagories.root')  }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videocatagories.name') }} :</strong>
                {{ $videocatagory->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videocatagories.description') }} : </strong></br>
                {{ $videocatagory->description }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videocatagories.status') }} :</strong>
                {{ ($videocatagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection

@extends('adminlte::page')

@section('title', __('elearnings.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('elearnings.header') }}</h1>
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
                <strong>{{ __('elearnings.catagory') }} :</strong>
                {{ $elearning->catagory->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.name') }} :</strong>
                {{ $elearning->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.descriptions') }} :</strong>
                {{ $elearning->description }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.preview') }} :</strong>
                <img src="{{ $elearning->preview }}">
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.mime_type') }} :</strong>
                @if ($elearning->mime_type == 'i_video')
                    {{ __('elearnings.i_video') }}
                @elseif ($elearning->mime_type == 'e_video')
                    {{ __('elearnings.e_video') }}
                @elseif ($elearning->mime_type == 'youtube')
                    {{ __('elearnings.youtube') }}
                @elseif ($elearning->mime_type == 'youtube_id')
                    {{ __('elearnings.youtube_id') }}
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.url') }} : {{ ($elearning->url_http !=null) ? $elearning->url_http : $elearning->url }}</strong>
            </div>
            <div class="form-group">
                @if ($elearning->mime_type == 'youtube_id')
                     {{ $elearning->url }}
                @else
                     <video width="640" height="480" controls >
                         <source src="{{ $elearning->url }}" type="video/mp4" >
                     </video>
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('tables.creator') }} :</strong>
                {{ $elearning->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.status') }} :</strong>
                {{ ($elearning->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection

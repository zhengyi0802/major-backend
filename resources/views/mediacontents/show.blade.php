@extends('adminlte::page')

@section('title', __('mediacontents.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mediacontents.header') }}</h1>
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
                <strong>{{ __('mediacontents.catagory') }} :</strong>
                {{ $mediacontent->catagory->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacontents.name') }} :</strong>
                {{ $mediacontent->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacontents.descriptions') }} :</strong>
                {{ $mediacontent->description }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacontents.preview') }} :</strong>
                <img src="{{ $mediacontent->preview }}">
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacontents.mime_type') }} :</strong>
                @if ($mediacontent->mime_type == 'i_video')
                    {{ __('mediacontents.i_video') }}
                @elseif ($mediacontent->mime_type == 'e_video')
                    {{ __('mediacontents.e_video') }}
                @elseif ($mediacontent->mime_type == 'youtube')
                    {{ __('mediacontents.youtube') }}
                @elseif ($mediacontent->mime_type == 'youtube_id')
                    {{ __('mediacontents.youtube_id') }}
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacontents.url') }} : {{ ($mediacontent->url_http != null) ? $mediacontent->url_http : $mediacontent->url }}</strong>
            </div>
            <div class="form-group">
                @if ($mediacontent->mime_type == 'youtube_id')
                     {{ $mediacontent->url }}
                @else
                     <video width="640" height="360" controls >
                         <source src="{{ $mediacontent->url }}" type="video/mp4" >
                     </video>
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('tables.creator') }} :</strong>
                {{ $mediacontent->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacontents.status') }} :</strong>
                {{ ($mediacontent->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection

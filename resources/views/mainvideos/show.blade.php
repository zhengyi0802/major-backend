@extends('adminlte::page')

@section('title', __('mainvideos.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mainvideos.header') }}</h1>
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
                <strong>{{ __('mainvideos.project') }} :</strong>
                {{ $mainvideo->project ? $mainvideo->project->name : null  }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mainvideos.type') }} :</strong>
                @if ($mainvideo->type == 'playlist')
                    {{ __('mainvideos.type_playlist') }}
                @elseif ($mainvideo->type == 'youtube_playlist')
                    {{ __('mainvideos.type_youtube_playlist') }}
                @elseif ($mainvideo->type == 'youtube_playlist_id')
                    {{ __('mainvideos.type_youtube_playlist_id') }}
                @elseif ($mainvideo->type == 'stream')
                    {{ __('mainvideos.type_stream') }}
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mainvideos.description') }} :</strong><br>
                {{ $mainvideo->description }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mainvideos.playlist') }} :</strong><br>
                @foreach ($playlist as $video)
                  {{ $video }}<br>
                @endforeach
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mainvideos.play_random') }} :</strong>
                {{ ($mainvideo->play_random==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('tables.creator') }} :</strong><br>
                {{ $mainvideo->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mainvideos.status') }} :</strong>
                {{ ($mainvideo->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection

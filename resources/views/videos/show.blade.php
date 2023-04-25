@extends('adminlte::page')

@section('title', __('videos.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('videos.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('videos.index') }}">{{ __('tables.back') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videos.user') }} :</strong>
                {{ $video->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videos.catagory') }} :</strong>
                {{ $video->catagory ? $video->catagory->name :  __('videocatagories.root')  }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videos.title') }} :</strong>
                {{ $video->title }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videos.video_url') }} : {{ $video->url_http }}</strong>
            </div>
            <div class="form-group">
                <video width="640" height="360" controls >
                    <source src="{{ $video->video_url }}" type="video/mp4" >
                </video>
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videos.description') }} : </strong></br>
                {{ $video->description }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('videos.status') }} :</strong>
                {{ ($video->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
         </div>
     </div>
@endsection

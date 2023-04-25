@extends('adminlte::page')

@section('title', __('mainvideos.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mainvideos.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.edit') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('frontend_views.edit', $project->id) }}">{{ __('tables.back') }}</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mainvideos.store2', ['mainvideo' => $mainvideo, 'project' => $project]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group" id="proj_id" name="proj_id" value="$project->id" >
                    <strong>{{ __('mainvideos.project') }} : {{ $project->name }} </strong>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mainvideos.type') }} :</strong>
                    <select id="type" name="type" >
                         <option value="playlist" {{ ($mainvideo->type == "playlist") ? "selected" : null }} >{{ __('mainvideos.type_playlist') }}</option>
                         <option value="youtube_playlist" {{ ($mainvideo->type == "youtube_playlist") ? "selected" : null }} >{{ __('mainvideos.type_youtube_playlist') }}</option>
                         <option value="youtube_playlist_id" {{ ($mainvideo->type == "youtube_playlist_id") ? "selected" : null }}>{{ __('mainvideos.type_youtube_playlist_id') }}</option>
                         <option value="stream" {{ ($mainvideo->type == "stream") ? "selected" : null }} >{{ __('mainvideos.type_stream') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mainvideos.playlist') }} :</strong>
                    <textarea class="form-control" style="height:150px" name="playlist">{{ $mainvideo->playlist }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mainvideos.description') }} :</strong>
                    <textarea class="form-control" style="height:150px" name="description">{{ $mainvideo->description }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mainvideos.status') }} :</strong>
                    <input type="radio" name="status" value="1" {{ ($mainvideo->status==1) ? "checked":null }} >{{ __('tables.status_on') }}
                    <input type="radio" name="status" value="0" {{ ($mainvideo->status!=1) ? "checked":null }} >{{ __('tables.status_off') }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
            </div>
        </div>
    </form>
@endsection

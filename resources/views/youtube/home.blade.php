@extends('adminlte::page')

@section('title', __('youtube.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('youtube.header') }}</h1>
@stop

@section('content')
    <style>
      img { width: 60%; height: 60%; }
      .progress { position:relative; width:100%; border: 1px solid #7F98B2; padding: 1px; border-radius: 3px; }
      .bar { background-color: #B4F5B4; width:0%; height:25px; border-radius: 3px; }
      .percent { position:absolute; display:inline-block; top:3px; left:48%; color: #7F98B2;}
    </style>

    <form action="{{ route('youtube.commit') }}" method="POST" >
         @csrf
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('youtube.keyword') }} : </strong>
                    <input type="text" name="keyword" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('youtube.maxresults') }} : </strong>
                    <input type="number" name="maxResults" value="20">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
             {{ $results ? $results:null }}
        </div>
    </div>
@endsection

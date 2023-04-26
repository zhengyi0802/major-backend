@extends('adminlte::page')

@section('title', __('appmanagers.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('appmanagers.header') }}</h1>
@stop

@section('content')
    <style>
      img { width: 60%; height: 60%; }
      .progress { position:relative; width:100%; border: 1px solid #7F98B2; padding: 1px; border-radius: 3px; }
      .bar { background-color: #B4F5B4; width:0%; height:25px; border-radius: 3px; }
      .percent { position:absolute; display:inline-block; top:3px; left:48%; color: #7F98B2;}
    </style>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.new') }}</h1>
            </div>
            @include('layouts.back')
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

    <form name="vform" action="{{ route('appmanagers.store') }}" method="POST" enctype="multipart/form-data" >
         @csrf
         <div class="row">\
            <div class="col-xs-12 col-sm-12 col-me-12">
                <div class="form-group">
                   <strong>{{ __('appmanagers.project') }} : </strong>
                   <select id="proj_id" name="proj_id" >
                         <option value="0" selected>{{ __('appmanagers.project_all') }}</option>
                      @foreach ($projects as $project)
                         <option value="{{ $project->id }}" >{{ $project->name }}</option> 
                      @endforeach
                   </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-me-12">
                <div class="form-group">
                   <strong>{{ __('appmanagers.package') }} : </strong>
                   <select id="apk_id" name="apk_id" >
                         <option value="0" selected>--------</option>
                      @foreach ($apkmanagers as $apkmanager)
                         <option value="{{ $apkmanager->id }}" >{{ $apkmanager->label.'('. $apkmanager->package_version_name.')' }}</option> 
                      @endforeach
                   </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('appmanagers.market_id') }} : </strong>
                    <input type="radio" name="market_id" value="1" >{{ __('appmanagers.market_yes') }}
                    <input type="radio" name="market_id" value="0" checked>{{ __('appmanagers.market_no') }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('appmanagers.installer_flag') }} : </strong>
                    <input type="radio" name="installer_flag" value="1" >{{ __('appmanagers.flag_on') }}
                    <input type="radio" name="installer_flag" value="0" checked>{{ __('appmanagers.flag_off') }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('appmanagers.delaytime') }} : </strong>
                    <input type="number" name="delaytime" value="5" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('appmanagers.status') }} : </strong>
                    <input type="radio" name="status" value="1" checked>{{ __('tables.status_on') }}
                    <input type="radio" name="status" value="0" >{{ __('tables.status_off') }}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
            </div>
        </div>
    </form>
@endsection

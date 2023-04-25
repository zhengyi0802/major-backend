@extends('adminlte::page')

@section('title', __('apkmanagers.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('apkmanagers.header') }}</h1>
@stop

@section('content')

    <style>
      .progress { position:relative; width:100%; border: 1px solid #7F98B2; padding: 1px; border-radius: 3px; }
      .bar { background-color: #B4F5B4; width:0%; height:25px; border-radius: 3px; }
      .percent { position:absolute; display:inline-block; top:3px; left:48%; color: #7F98B2;}
    </style>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>{{ __('tables.new') }}</h1>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('apkmanagers.index') }}">{{ __('tables.back') }}</a>
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

<form name="vform" action="{{ route('apkmanagers.store') }}" method="POST" enctype="multipart/form-data" >
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.path') }} : </strong>
                <input type="file" name="app_file">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.description') }} :</strong>
                <textarea class="form-control" style="height:150px" name="description" ></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.launcher_id') }} : </strong>
                <select name="launcher_id" >
                   <option value="-1" selected >{{ __('apkmanagers.launcher_false') }}</option>
                   <option value="1" >{{ __('apkmanagers.launcher_magicviewer') }}</option>
                   <option value="2" >{{ __('apkmanagers.launcher_mundi') }}</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.types') }} : </strong>
                   <input type="checkbox" onClick="toggle(this)">{{ __('apkmanagers.type_all') }}
                @foreach($types as $type)
                   <input type="checkbox" id="type" name="type[]" value="{{ $type->name }}">
                   <lable for="{{ 'type-'.$type->id }}">{{ $type->name }}</label>
                @endforeach
                <script language="JavaScript">
                   function toggle(source) {
                     checkboxes = document.getElementsByName('type[]');
                     for(var i=0, n=checkboxes.length; i < n; i++) {
                         checkboxes[i].checked = source.checked;
                     }
                   }
                </script>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.projects') }} : </strong>
                @foreach($projects as $project)
                   <input type="checkbox" name="project[]" class="form-control" value="{{ $project->name }}">
                   <lable for="{{ 'proj-'.$project->id }}">{{ $project->name }}</label>
                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.mac_addresses') }} : </strong>
                <textarea class="form-control" style="height:150px" name="mac_addresses" ></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apkmanagers.status') }} : </strong>
                <input type="radio" name="status" value="1" checked>{{ __('tables.status_on') }}
                <input type="radio" name="status" value="0">{{ __('tables.status_off') }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="progress">
                <div class="bar"></div>
                <div class="percent">0%</div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
        </div>
    </div>
</form>
@endsection

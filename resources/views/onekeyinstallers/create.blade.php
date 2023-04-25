@extends('adminlte::page')

@section('title', __('onekeyinstallers.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('onekeyinstallers.header') }}</h1>
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
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('onekeyinstallers.index') }}">{{ __('tables.back') }}</a>
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

    <form name="vform" action="{{ route('onekeyinstallers.store') }}" method="POST" enctype="multipart/form-data" >
         @csrf
         <div class="row">\
            <div class="col-xs-12 col-sm-12 col-me-12">
                <div class="form-group">
                   <strong>{{ __('onekeyinstallers.project') }} : </strong>
                   <select id="proj_id" name="proj_id" >
                         <option value="0" selected>{{ __('onekeyinstallers.project_all') }}</option>
                      @foreach ($projects as $project)
                         <option value="{{ $project->id }}" >{{ $project->name }}</option>
                      @endforeach
                   </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('onekeyinstallers.external_url') }} : </strong>
                    <input type="radio" name="external_flag" value="1" onclick="handleClick(this)" checked >{{ __('onekeyinstallers.external_link') }}
                    <input type="radio" name="external_flag" value="0" onclick="handleClick(this)" >{{ __('onekeyinstallers.internal_link') }}
                </div>
            </div>
            <div id="external" class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('onekeyinstallers.label') }} : </strong>
                    <input type="text" name="label" class="form-control" >
                </div>

                <div class="form-group">
                    <strong>{{ __('onekeyinstallers.thumbnail') }} : </strong>
                    <input type="text" name="thumbnail" class="form-control" >
                </div>
                <div class="form-group">
                    <strong>{{ __('onekeyinstallers.package_name') }} : </strong>
                    <input type="text" name="package_name" class="form-control" >
                </div>
                <div class="form-group">
                    <strong>{{ __('onekeyinstallers.url') }} : </strong>
                    <input type="text" name="url" class="form-control" >
                </div>
            </div>
            <div id="internal" class="col-xs-12 col-sm-12 col-me-12" style="display:none">
                <div class="form-group">
                   <strong>{{ __('onekeyinstallers.package') }} : </strong>
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
                    <strong>{{ __('onekeyinstallers.status') }} : </strong>
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
@section('adminlte_js')
           <script type="text/javascript">
                    var flag = '1';
                    function handleClick(radio) {
                        flag = radio.value;
                        if (flag == '1') {
                           document.getElementById('external').style.display="";
                           document.getElementById('internal').style.display="none";
                        } else {
                           document.getElementById('external').style.display="none";
                           document.getElementById('internal').style.display="";
                        }
                    };

           </script>
@endsection

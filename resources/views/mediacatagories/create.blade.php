@extends('adminlte::page')

@section('title', __('mediacatagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mediacatagories.header') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>{{ __('tables.new') }}</h1>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('mediacatagories.index') }}">{{ __('tables.back') }}</a>
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

<form action="{{ route('mediacatagories.store') }}" method="POST" enctype="multipart/form-data" >
     @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.project') }} : </strong>
                <select id="proj_id" name="proj_id" onchange="changeProject(this)">
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ ($project->id == $proj_id) ? "selected" : null }}>
                      {{ $project->id . '-' . $project->name }}
                    </option>
                @endforeach
                </select>
                <script>
                  var changeProject = function(select) {
                      window.location.href='/mediacatagories/create?proj_id=' + select.value;
                  };
                </script>

            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.parent') }} : </strong>
                <select id="parent_id" name="parent_id" >
                    <option value="0">------</option>
                    @foreach($mediacatagories as $catagory)
                       <option value="{{ $catagory->id }}">
                         {{ $catagory->name }}
                       </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.type') }} : </strong>
                <select id="type" name="type" >
                    <option value="catalog" selected>{{ __('mediacatagories.type_catagory') }}</option>
                    <option value="contents">{{ __('mediacatagories.type_contents') }}</option>
                    <option value="keywords">{{ __('mediacatagories.type_keywords') }}</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.name') }} :</strong>
                <input type="text" id="name" name="name" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.keywords') }} :</strong>
                <input type="text" id="keywords" name="keywords" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.descriptions') }} :</strong>
                <textarea class="form-control" style="height:150px" name="description"></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <div id="div-url-name"><strong>{{ __('mediacatagories.thumbnail') }} : </strong></div>
                <div id="div-image">
                    <input type="file" id="image" name="image" accept="image/*" onchange="loadImage(event)" >
                </div>
                <div id="div-preview">
                    <img name="preview" id="preview" >
                </div>
            </div>
            <script>
                    var loadImage = function(event) {
                        var output = document.getElementById('preview');
                        output.src = URL.createObjectURL(event.target.files[0]);
                        output.onload = function() {
                           URL.revokeObjectURL(output.src) // free memory
                        }
                    };
            </script>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('mediacatagories.status') }} :</strong>
                <input type="radio" name="status" value="1" checked>{{ __('tables.status_on') }}
                <input type="radio" name="status" value="0">{{ __('tables.status_off') }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
         </div>
     </div>
</form>
@endsection

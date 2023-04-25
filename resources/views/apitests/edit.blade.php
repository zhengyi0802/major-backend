@extends('adminlte::page')

@section('title', __('apitests.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('apitests.header') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>{{ __('tables.new') }}</h1>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('apitests.index') }}">{{ __('tables.back') }}</a>
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

<form action="{{ route('apitests.update', $apitest->id) }}" method="POST">
    @METHOD('PUT')
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-me-12">
            <div class="form-group">
               <strong>{{ __('apitests.project') }} : </strong>
               <select id="proj_id" name="proj_id" >
                     <option value="0">{{ __('apitests.project_all') }}</option>
                  @foreach ($projects as $project)
                     <option value="{{ $project->id }}" >{{ $project->name }}</option>
                  @endforeach
               </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-me-12">
            <div class="form-group">
               <strong>{{ __('apitests.type') }} : </strong>
               <select id="type" name="type" >
                     <option value="string" {{ ($apitest->type == "string") ? "selected" : null  }}>{{ __('apitests.type_string') }}</option>
                     <option value="jason"  {{ ($apitest->type == "jason") ? "selected" : null }} >{{ __('apitests.type_jason') }}</option>
                     <option value="text" {{ ($apitest->type == "text") ? "selected" : null }} >{{ __('apitests.type_text') }}</option>
               </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.key') }} :</strong>
                <input type="text" name="key" class="form-control" value="{{ $apitest->key }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.value') }} :</strong>
                <textarea class="form-control" style="height:150px" name="value">{{ $apitest->value }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.memo') }} :</strong>
                <textarea class="form-control" style="height:150px" name="memo">{{ $apitest->memo }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.status') }} :</strong>
                <input type="radio" name="status" value="1" {{ ($apitest->status) ? "checked" : null }}>{{ __('tables.status_on') }}
                <input type="radio" name="status" value="0" {{ (!$apitest->status) ? "checked" : null }}>{{ __('tables.status_off') }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
        </div>
    </div>
</form>
@endsection

@extends('adminlte::page')

@section('title', __('managers.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('managers.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.edit') }}</h1>
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

    <form action="{{ route('managers.update',$manager->id) }}" method="POST">
        @method('PUT')
        @csrf
         <div class="row">
           <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.project') }} :</strong>
                    <select id="proj_id" name="proj_id" >
                         <option value="0" style="background-color: blue">{{ __('managers.project_all') }}</option>
                         @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ ($manager->proj_id == $project->id) ? "selected" : null }} >{{ $project->name }}</option> 
                         @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.name') }} :</strong>
                    <input type="text" name="name" value="{{ $manager->name }}" class="form-control">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.account') }} :</strong>
                    <input type="text" name="account" value="{{ $account }}" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.password') }} :</strong>
                    <input type="text" name="password" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.job_title') }} :</strong>
                    <input type="text" name="job_title" value="{{ $manager->job_title }}" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.description') }}:</strong>
                    <textarea class="form-control" style="height:150px" name="descriptions" >{{ $manager->descriptions }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('managers.status') }} : </strong>
                    <input type="radio" name="status" value="1" {{ ($manager->status==1) ? "checked":null }} >{{ __('tables.status_on') }}
                    <input type="radio" name="status" value="0" {{ ($manager->status!=1) ? "checked":null }} >{{ __('tables.status_off') }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
            </div>
        </div>
    </form>
@endsection

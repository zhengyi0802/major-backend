@extends('adminlte::page')

@section('title', __('apitests.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('apitests.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('apitests.index') }}">{{ __('tables.back') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.project') }} :</strong>
                {{ ($apitest->project!=null) ? $apitest->project->name : __('apitests.project_all') }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.user') }} :</strong>
                {{ $apitest->user->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.type') }} :</strong>
                @if ($apitest->type == "string")
                    {{ __('apitests.type_string') }}
                @elseif ($apitest->type == "jason")
                    {{ __('apitests.type_jason') }}
                @elseif ($apitest->type == "text")
                    {{ __('apitests.type_text') }}
                @endif
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.key') }} :</strong>
                {{ $apitest->key }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.value') }} :</strong>
                {{ $apitest->value }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.memo') }} :</strong>
                {{ $apitest->memo }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('apitests.status') }} :</strong>
                {{ ($apitest->status==1) ? __('tables.status_on'):__('tables.status_off') }}
            </div>
        </div>
     </div>
@endsection

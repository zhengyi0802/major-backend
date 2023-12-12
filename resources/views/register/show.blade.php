@extends('adminlte::page')

@section('title', __('register.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('register.header') }}</h1>
@stop
<style>
    div.upgrade {
        margin-bottom : 20px;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
            @include('layouts.back')
        </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
      <table>
        <tr>
            <td><x-adminlte-card title="{{ __('register.name') }}" theme="info" icon="fas fa-lg">
                {{ $register->name }}
            </x-adminlte></td>
            <td><x-adminlte-card title="{{ __('register.phone') }}" theme="warning" icon="fas fa-lg">
                {{ $register->phone }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.register_date') }}" theme="warning" icon="fas fa-lg">
                {{ $register->register_date }}
            </x-adminlte-card></td>
        </tr>
        <tr>
            <td><x-adminlte-card title="{{ __('register.address') }}" theme="warning" icon="fas fa-lg">
                {{ $register->address }}
            </x-adminlte-card></td>
        </tr>
      </table>
      </div>
   </div>
@endsection

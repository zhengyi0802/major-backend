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
      <table>
         <tr>
            <td><x-adminlte-card title="{{ __('register.name') }}" theme="info" icon="fas fa-lg">
              {{ $register['name'] ?? '' }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.phone') }}" theme="info" icon="fas fa-lg">
              {{ $register['phone'] ?? '' }}
             </x-adminlte-card></td>
         </tr>
         <tr>
            <td colspan="2"><x-adminlte-card title="{{ __('register.address') }}" theme="info" icon="fas fa-lg">
              {{ $register['address'] ?? '' }}
             </x-adminlte-card></td>
         </tr>
         <tr>
            <td><x-adminlte-card title="{{ __('register.model_id') }}" theme="info" icon="fas fa-lg">
              {{ $register['model_id'] ?? '' }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.android_id') }}" theme="info" icon="fas fa-lg">
              {{ $register['android_id'] ?? '' }}
             </x-adminlte-card></td>
         </tr>
         <tr>
            <td><x-adminlte-card title="{{ __('register.register_time') }}" theme="info" icon="fas fa-lg">
              {{ $register['register_time'] }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.warranty_date') }}" theme="info" icon="fas fa-lg">
              {{ $register['warranty_date'] }}
            </x-adminlte-card></td>
         </tr>
         <tr>
            <td colspan="2"><h2>{{ __('register.company') }}</h3></td>
         </td>
         <tr>
            <td colspan="2"><h3>{{ __('register.caddress') }}</h3></td>
         </td>
         <tr>
            <td colspan="2"><h3>{{ __('register.cphone') }}</h3></td>
         </td>
      </table>
    </div>
@if ($register['type_id'] == 1 ||
     $register['type_id'] == 2 ||
     $register['type_id'] == 3 ||
     $register['type_id'] == 13 )
   <div class="row">
      <x-adminlte-card title="{{ __('register.expire_date') }}" theme="info" icon="fas fa-lg">
          {{ $register['expire_date'] }}
      </x-adminlte-card><
   </div>
@endif
@endsection

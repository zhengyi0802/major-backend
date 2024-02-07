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
              {{ $warranty->order()->name ?? '' }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.phone') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->order()->phone ?? '' }}
             </x-adminlte-card></td>
         </tr>
         <tr>
            <td colspan="2"><x-adminlte-card title="{{ __('register.address') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->order()->address ?? '' }}
             </x-adminlte-card></td>
         </tr>
         <tr>
            <td><x-adminlte-card title="{{ __('register.model_id') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->productModel()->model ?? '' }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.android_id') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->product->android_id ?? '' }}
             </x-adminlte-card></td>
         </tr>
         <tr>
            <td><x-adminlte-card title="{{ __('register.register_time') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->register_time }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('register.warranty_date') }}" theme="info" icon="fas fa-lg">
              {{ date('Y-m-d', strtotime('+3 years', strtotime($warranty->register_time))) }}
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
         </tr>
      </table>
    </div>
@endsection

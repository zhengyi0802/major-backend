@extends('adminlte::page')

@section('title', __('warranties.title'))

@section('content_header')
@stop

<style>
    div.upgrade {
        margin-bottom : 20px;
                }
    table {
        width : 100%;
        margin-left : 13px;
        margin-right: 10px;
        background-color: lightgray;
          }
    td {
        padding-left: 8px;
        }
</style>
@section('content')
    <div class="row">
      <img src="/head.jpg" width="100%">
    </div>
    @if (false)
    <div class="row">
      <table>
         <tr>
            <td><x-adminlte-card title="{{ __('warranties.name') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->order() ? $warranty->order()->name : null }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('warranties.phone') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->order() ? $warranty->order()->phone : null }}
            </x-adminlte-card></td>
         </tr>
         <tr>
            <td colspan="2"><x-adminlte-card title="{{ __('warranties.address') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->order() ? $warranty->order()->address : null }}
            </x-adminlte-card></td>
         </tr>
         <tr>
            <td><x-adminlte-card title="{{ __('warranties.model_id') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->productModel() ? $warranty->productModel()->model : null }}
            </x-adminlte-card></td>
            <td><x-adminlte-card title="{{ __('warranties.register_time') }}" theme="info" icon="fas fa-lg">
              {{ $warranty->register_time }}
            </x-adminlte-card></td>
         </tr>
      </table>
    </div>
    @endif
    <div class="row">
      <table border="1">
      <tr>
         <td>{{ __('warranties.name') }}</td>
         <td>{{ $warranty->order() ? $warranty->order()->name : null }}</td>
         <td>{{ __('warranties.phone') }}</td>
         <td>{{ $warranty->order() ? $warranty->order()->phone : null }}</td>
      </tr>
      <tr>
         <td>{{ __('warranties.address') }}</td>
         <td colspan="3">{{ $warranty->order() ? $warranty->order()->address : null }}</td>
      </tr>
      <tr>
         <td>{{ __('warranties.model_id') }}</td>
         <td colspan="3">{{ $warranty->productModel() ? $warranty->productModel()->model : null }}</td>
      </tr>
      <tr>
         <td>{{ __('warranties.serialno') }}</td>
         <td colspan="3">{{ null }}</td>
      </tr>
      <tr>
         <td>{{ __('warranties.register_time') }}</td>
         <td colspan="3">{{ $warranty->register_time }}</td>
      </tr>
      <tr>
         <td colspan="4"></td>
      </tr>
      </table>
    </div>
@endsection

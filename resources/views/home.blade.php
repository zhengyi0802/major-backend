@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">更新說明</h1>
@stop

@section('content')
    <div class="row col-12">
      <x-adminlte-card title="改版公告" theme="dark" icon="fas fa-lg fa-fan">
        小改版 : 2024年3月10日 (名稱異動)
      </x-adminlte-card>
      <x-adminlte-card title="改版公告" theme="dark" icon="fas fa-lg fa-fan">
        小改版 : 2023年4月24日 (表格支持排序功能以及搜尋)
      </x-adminlte-card>
    </div>
@stop

@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">更新說明</h1>
@stop

@section('content')
    <div class="row col-12">
      <x-adminlte-card title="改版公告" theme="danger" icon="fas fa-lg fa-fan">
        <pre>小改版 : 2024年3月10日 (名稱異動)
             影音管理 => 影音搜尋管理
             教學管理 => 企業媒體管理
             公告管理 => 訊息公告管理
             常見問題 => 客服教學
             應用頁面廣告 => 應用程式廣告
             客服相關設置 => 線上客服設置</pre>
      </x-adminlte-card>
    </div>
    <div class="row col-12">
      <x-adminlte-card title="改版公告" theme="dark" icon="fas fa-lg fa-fan">
        小改版 : 2023年4月24日 (表格支持排序功能以及搜尋)
      </x-adminlte-card>
    </div>
@stop

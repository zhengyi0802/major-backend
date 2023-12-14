@extends('adminlte::page')

@section('title', __('register.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('register.header') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>{{ __('tables.new') }}</h1>
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
<style>
   .error {
      color       : red;
      margin-left : 5px;
      font-size   : 12px;
   }
   label.error {
      display     : inline;
   }
   span.must {
      color     : red;
      font-size : 12px;
   }
</style>
<form id="catagory-form" action="{{ route('registers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group col-md-4">
                <strong>{{ __('register.phone') }} :<span class="must">{{ __('tables.must') }}</span></strong>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <strong>{{ __('register.android_id') }} : {{ $register['aid'] }}</strong>
                <input type="text" name="aid" class="form-control" value="{{ $register['aid'] }}"  hidden>
            </div>
            <div class="form-group col-md-4">
                <strong>{{ __('register.ether_mac') }} : {{ $register['ether_mac'] }}</strong>
                <input type="text" name="ether_mac" class="form-control" value="{{ $register['ether_mac'] }}" hidden>
            </div>
            <div class="form-group col-md-4">
                <strong>{{ __('register.wifi_mac') }} : {{ $register['wifi_mac'] }}</strong>
                <input type="text" name="wifi_mac" class="form-control" value="{{ $register['wifi_mac'] }}" hidden>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('#catagory-form').validate({
           onkeyup: function(element, event) {
               var value = this.elementValue(element).replace(/^\s+/g, "");
               $(element).val(value);
           },
           rules: {
               phone: {
                  required: true
               },
           },
           messages: {
               phone: {
                  required: '電話必填'
               },
           },
           submitHandler: function(form) {
                form.submit();
           }
        });
    });
</script>
@section('plugins.jqueryValidation', true)

@endsection

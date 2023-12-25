@extends('adminlte::page')

@section('title', __('orders.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('orders.header') }}</h1>
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
       option:checked {
          background-color: yellow;
       }
    </style>
    <form id="order-form" action="{{ route('orders.update',$order->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
         <div class="row">
           <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group col-md-6">
                    <strong>{{ __('orders.name') }} :</strong>
                    <input type="text" name="name" value="{{ $order->name }}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <strong>{{ __('orders.phone') }} :</strong>
                    <input type="text" name="phone" value="{{ $order->phone }}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <strong>{{ __('orders.address') }} :</strong>
                    <input type="text" name="address" value="{{ $order->address }}" class="form-control">
                </div>
                @if (auth()->user()->role == App\Enums\UserRole::Administrator)
                <div class="form-group col-md-6">
                    <strong>{{ __('orders.order_date') }} :</strong>
                    <input type="date" name="order_date" value="{{ $order->order_date }}" class="form-control">
                </div>
                @endif
                <div class="form-group col-md-6">
                    <strong>{{ __('orders.sales') }} :</strong>
                    <select id="sales_id" name="sales_id" >
                        @foreach ($sales as $s)
                           <option value="{{ $s->id }}" {{ ($s->id == $order->sales_id) ? "selected" : null }} >{{ $s->name }}</option>
                        @endforeach
                  </select>
                </div>
                @include('orders.eproduct')
           </div>
           <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
           </div>
        </div>
    </form>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('#member-form').validate({
           onkeyup: function(element, event) {
               var value = this.elementValue(element).replace(/^\s+/g, "");
               $(element).val(value);
           },
           rules: {
               name: {
                  required: true
               },
               phone: {
                  required: true
               },
               address: {
                  required: true
               },
           },
           messages: {
               name: {
                  required: '姓名必填'
               },
               phone: {
                  required: '電話必填'
               },
               address: {
                  required: '地址必填'
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


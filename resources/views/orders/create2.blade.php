@extends('adminlte::page')

@section('title', __('orders.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('orders.header') }}</h1>
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

<form id="order-form" action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group col-md-4">
                <strong>{{ __('orders.name') }} : {{ $customer->name }}</strong>
                <input type="text" name="customer_id" class="form-control" value="{{ $customer->id }}" hidden  />
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" hidden  />
            </div>
            <div class="form-group col-md-4">
                <strong>{{ __('orders.phone') }} :<span class="must">{{ __('tables.must') }}</span></strong>
                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
            </div>
            <div class="form-group col-md-4">
                <strong>{{ __('orders.address') }} :<span class="must">{{ __('tables.must') }}</span></strong>
                <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
            </div>
            <table>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.project') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><select id="project_id" name="project_id" >
                      @foreach ($projects as $project)
                         <option value="{{ $project->id }}" >{{ $project->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.product') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><select id="product_id" name="product_id" >
                      @foreach ($productModels as $product)
                         <option value="{{ $product->id }}" >{{ $product->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.price') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><input type="number" name="price" value="0">
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.installation_fee') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><input type="number" name="installation_fee" value="0">
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.extras') }} :</strong></td>
                <td><select id="extra_id" name="extra_id[]" multiple="multiple" size="10">
                      <option value="0">----------</option>
                      @foreach ($extras as $extra)
                         <option value="{{ $extra->id }}" >{{ $extra->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.sales') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><select id="sales_id" name="sales_id" >
                      @foreach ($sales as $s)
                         <option value="{{ $s->id }}" >{{ $s->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            </table>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('#order-form').validate({
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
               project_id: {
                  required: true
               },
               product_id: {
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
               product_id: {
                  required: '產品型號必選'
               },
               project_id: {
                  required: '行銷方案必選'
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

         <table>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.project') }} :</strong></td>
                <td><select id="project_id" name="project_id" onchange="proj()">
                      @foreach ($projects as $project)
                         <option value="{{ $project->id }}" {{ ($order->project_id == $project->id) ? "selected" : null }}>{{ $project->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.product') }} :</strong></td>
                <td><select id="product_id" name="product_id" >
                      @foreach ($productModels as $model)
                         <option value="{{ $model->id }}" {{ ($order->product_id == $model->id) ? "selected" : null }}>{{ $model->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.price') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><input type="number" name="price" value="{{ $order->price }}">
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.installation_fee') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><input type="number" name="installation_fee" value="{{ $order->installation_fee }}">
            </tr>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.extras') }} :</strong></td>
                <td><select id="extra_id" name="extra_id[]" multiple="multiple" size="10" >
                     <option value="0">----------</option>
                  @foreach ($extras as $extra)
                     <option value="{{ $extra->id }}" {{ in_array($extra->id, $gifts) ? "selected" : null }} >{{ $extra->name }}</option>
                  @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.flow') }} :</strong></td>
                <td><select id="flow" name="flow" onchange="checkflow(this)">
                  <option value="1" {{ ($order->flow == 1) ? "selected" : null }}>{{ trans_choice('orders.flows', 1) }}</option>
                  <option value="2" {{ ($order->flow == 2) ? "selected" : null }}>{{ trans_choice('orders.flows', 2) }}</option>
                  <option value="3" {{ ($order->flow == 3) ? "selected" : null }}>{{ trans_choice('orders.flows', 3) }}</option>
                  <option value="4" {{ ($order->flow == 4) ? "selected" : null }}>{{ trans_choice('orders.flows', 4) }}</option>
                  <option value="5" {{ ($order->flow == 5) ? "selected" : null }}>{{ trans_choice('orders.flows', 5) }}</option>
                  <option value="6" {{ ($order->flow == 6) ? "selected" : null }}>{{ trans_choice('orders.flows', 6) }}</option>
                  <option value="7" {{ ($order->flow == 7) ? "selected" : null }}>{{ trans_choice('orders.flows', 7) }}</option>
                </select></td>
            </tr>
            <tr class="form-group col-md-6" id="shipdate" >

            </tr>
            <tr class="form-group col-md-6" id="shipdate" >
                <td><strong>{{ __('orders.shipping_date') }} :</strong></td>
                <td><input type="date"  name="shipping_date" value="{{ $order->shipping ? $order->shipping->shipping_date : null }}" /></td>
            </tr>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.memo') }} :</strong></td>
                <td><textarea name="memo" rows="10"></textarea></td>
            </tr>
         </table>
<script>
    function checkflow(event) {
       var val = event.value;
       if (val > 2 && val < 5) {
           document.getElementById('shipdate').style.display="";
       } else {
           document.getElementById('shipdate').style.display="none";
       }
    }
</script>

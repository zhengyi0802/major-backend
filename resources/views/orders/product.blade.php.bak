         <table>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.project') }} :</strong></td>
                <td><select id="project_id" name="project_id" onchange="proj()">
                      <option value="" selected>--------</option>
                      @foreach ($projects as $project)
                         <option value="{{ $project->id }}" >{{ $project->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.product') }} :</strong></td>
                <td><select id="product_id" name="product_id" >
                      @foreach ($productModels as $model)
                         <option value="{{ $model->id }}" >{{ $model->name }}</option>
                      @endforeach
                </select></td>
            </tr>
            <tr class="form-group col-md-4">
                <td><strong>{{ __('orders.price') }} :<span class="must">{{ __('tables.must') }}</span></strong></td>
                <td><input type="number" name="price" value="{{ $order->price }}">
            </tr>
            <tr class="form-group col-md-6">
                <td><strong>{{ __('orders.extras') }} :</strong></td>
                <td><select id="extra_id" name="extra_id[]" multiple="multiple" size="10" >
                     <option value="0">----------</option>
                  @foreach ($extras as $extra)
                     <option value="{{ $extra->id }}" >{{ $extra->name }}</option>
                  @endforeach
                </select></td>
            </tr>
         </table>
<script>
    function proj() {
      d = document.getElementById("project_id").value;
    }
</script>

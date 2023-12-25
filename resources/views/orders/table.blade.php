@php
$heads = [
    ['label' =>__('orders.id'), 'width' => 10],
    __('orders.flow'),
    __('orders.name'),
    __('orders.phone'),
    __('orders.order_date'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 10],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp

<x-adminlte-datatable id="order-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
  @foreach($orders as $order)
    <tr class="{{ ($order->status || $order->flow == 6) ? null : "bg-gray"}}">
      <td>{{ $order->id }}</td>
      <td>{{ trans_choice('orders.flows', $order->flow) }}</td>
      <td>{{ $order->name }}</td>
      <td>{{ $order->phone }}</td>
      <td>{{ ($order->order_date) ? $order->order_date : date('Y-m-d', strtotime($order->created_at)) }}</td>
      <td><nobr>
          <x-adminlte-button theme="info" title="{{ __('tables.detail') }}" icon="fa fa-lg fa-fw fa-eye"
            onClick="window.location='{{ route('orders.show', $order->id); }}'" >
          </x-adminlte-button>
      </nobr></td>
    </tr>
  @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


@php
$heads = [
    ['label' => __('product_records.id'), 'width' => 10 ],
    ['label' => __('product_records.user'), 'width' => 20],
    __('product_records.ether_mac'),
    __('product_records.wifi_mac'),
    __('product_records.result'),
    __('product_records.created_at'),
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="product-record-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
       @foreach ($records as $record)
        <tr class="form-group {{ ($record->user_id == 0) ? 'bg-gray':'bg-red' }}" >
            <td>{{ $record->id }}</td>
            <td>{{ $record->user ? $record->user->name : null }}</td>
            <td>{{ $record->product->ether_mac }}</td>
            <td>{{ $record->product->wifi_mac }}</td>
            <td>{{ $record->result }}</td>
            <td>{{ $record->created_at }}</td>
        </tr>
        @endforeach
</x-adminlte-datatable>
{!! $records->links() !!}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


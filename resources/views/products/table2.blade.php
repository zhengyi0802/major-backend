@php
$heads = [
    ['label' => __('product_records.id'), 'width' => 10 ],
    ['label' => __('product_records.user'), 'width' => 20],
    __('product_records.result'),
    __('product_records.created_at'),
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="product-record-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
       @foreach ($product->records as $record)
        <tr class="form-group {{ ($record->user_id == 0) ? 'bg-gray':'bg-red' }}" >
            <td>{{ $record->id }}</td>
            <td>{{ $record->user ? $record->user->name : null }}</td>
            <td>{{ $record->result }}</td>
            <td>{{ $record->created_at }}</td>
        </tr>
        @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


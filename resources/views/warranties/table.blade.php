@php
$heads = [
    ['label' =>__('warranties.id'), 'width' => 10],
    __('warranties.android_id'),
    __('warranties.name'),
    __('warranties.phone'),
    __('warranties.register_date'),
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null,],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="warranty-table" :heads="$heads"  :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($warranties as $warranty)
        <tr>
            <td>{{ $warranty->id }}</td>
            <td>{{ $warranty->product->android_id }}</td>
            <td>{{ $warranty->name }}</td>
            <td>{{ $warranty->phone }}</td>
            <td>{{ $warranty->register_time }}</td>
        </tr>
        @endforeach
</x-adminlte-datatable>
{!! $warranties->links() !!}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


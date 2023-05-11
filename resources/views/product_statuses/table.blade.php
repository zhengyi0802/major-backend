@php
$heads = [
    ['label' => __('product_statuses.id'), 'width' => 10 ],
    ['label' => __('product_statuses.name'), 'width' => 12 ],
    __('product_statuses.detail'),
    __('tables.creator'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'columns' => [null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="product-catagory-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($productStatuses as $productStatus)
        <tr>
            <td>{{ $productStatus->id }}</td>
            <td>{{ $productStatus->name }}</td>
            <td>{{ $productStatus->detail }}</td>
            <td>{{ $productStatus->user->name }}</td>
            <td>
                <form action="{{ route('product_statuses.destroy',$productStatus->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('product_statuses.show',$productStatus->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('product_statuses.edit',$productStatus->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


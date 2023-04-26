@php
$heads = [
    ['label' =>__('product_types.id'), 'width' => 10],
    __('product_types.catagory'),
    __('product_types.name'),
    __('product_types.description'),
    __('tables.creator'),
    __('product_types.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="product-type-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($productTypes as $productType)
        <tr>
            <td>{{ $productType->id }}</td>
            <td>{{ $productType->catagory->name }}</td>
            <td>{{ $productType->name }}</td>
            <td>{{ $productType->descriptions }}</td>
            <td>{{ $productType->user->name }}</td>
            <td>{{ ($productType->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('product_types.destroy',$productType->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('product_types.show',$productType->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('product_types.edit',$productType->id) }}">{{ __('tables.edit') }}</a>
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


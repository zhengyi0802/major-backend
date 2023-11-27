@php
$heads = [
    ['label' => __('product_catagories.id'), 'width' => 10 ],
    ['label' => __('product_catagories.name'), 'width' => 10 ],
    __('product_catagories.description'),
    __('tables.creator'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'columns' => [null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="product-catagory-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($productCatagories as $productCatagory)
        <tr>
            <td>{{ $productCatagory->id }}</td>
            <td>{{ $productCatagory->name }}</td>
            <td>{{ $productCatagory->descriptions }}</td>
            <td>{{ $productCatagory->user->name }}</td>
            <td>
                <form action="{{ route('product_catagories.destroy',$productCatagory->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('product_catagories.show',$productCatagory->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('product_catagories.edit',$productCatagory->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('tables.confirm_delete') }}');">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


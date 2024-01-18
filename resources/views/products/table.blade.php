@php
$heads = [
    ['label' =>__('products.id'), 'width' => 10],
    __('products.project'),
    __('products.android_id'),
    __('products.ether_mac'),
    __('products.wifi_mac'),
    __('products.expire_date'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => __('tables.language_url') ],
];
@endphp
<x-adminlte-datatable id="product-table" :heads="$heads"  :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->project? $product->project->name : null }}</td>
            <td>{{ $product->android_id }}</td>
            <td>{{ $product->ether_mac }}</td>
            <td>{{ $product->wifi_mac }}</td>
            <td>{{ $product->expire_date }}</td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">{{ __('tables.edit') }}</a>
                    @if (auth()->user()->role == "admin")
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('tables.confirm_delete') }}');">{{ __('tables.delete') }}</button>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
</x-adminlte-datatable>
{!! $products->links() !!}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


@php
$heads = [
    ['label' =>__('warranties.id'), 'width' => 10],
    __('warranties.android_id'),
    __('warranties.name'),
    __('warranties.phone'),
    __('warranties.register_time'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null,  ['orderable' => false]],
    'language' => [ 'url' => __('tables.language_url') ],
];
@endphp
<x-adminlte-datatable id="warranty-table" :heads="$heads"  :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($warranties as $warranty)
        <tr>
            <td>{{ $warranty->id }}</td>
            <td>{{ $warranty->product->android_id }}</td>
            <td>{{ ($warranty->order()) ? $warranty->order()->name : $warranty->name }}</td>
            <td>{{ $warranty->phone }}</td>
            <td>{{ $warranty->register_time }}</td>
            <td>
                <form action="{{ route('warranties.destroy',$warranty->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('warranties.show', $warranty->id) }}">{{ __('tables.details') }}</a>
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


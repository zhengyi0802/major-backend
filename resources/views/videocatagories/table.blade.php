@php
$heads = [
    ['label' =>__('videocatagories.id'), 'width' => 10],
    __('videocatagories.user'),
    __('videocatagories.parent'),
    __('videocatagories.name'),
    __('videocatagories.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="videocatagory-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($catagories as $videocatagory)
        <tr>
            <td>{{ $videocatagory->id }}</td>
            <td>{{ $videocatagory->user ? $videocatagory->user->name : null  }}</td>
            <td>{{ $videocatagory->parent ? $videocatagory->parent : __('videocatagories.root') }}</td>
            <td>{{ $videocatagory->name }}</td>
            <td>{{ ($videocatagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('videocatagories.destroy', $videocatagory->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('videocatagories.show', $videocatagory->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('videocatagories.edit', $videocatagory->id) }}">{{ __('tables.edit') }}</a>
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


@php
$heads = [
    ['label' =>__('qacatagories.id'), 'width' => 10],
    __('qacatagories.position'),
    __('qacatagories.name'),
    __('qacatagories.descriptions'),
    __('tables.creator'),
    __('qacatagories.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="qacatagory-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($qacatagories as $qacatagory)
        <tr>
            <td>{{ $qacatagory->id }}</td>
            <td>{{ $qacatagory->position }}</td>
            <td>{{ $qacatagory->name }}</td>
            <td>{{ $qacatagory->descriptions }}</td>
            <td>{{ $qacatagory->user->name }}</td>
            <td>{{ ($qacatagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('qacatagories.destroy',$qacatagory->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('qacatagories.show',$qacatagory->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('qacatagories.edit',$qacatagory->id) }}">{{ __('tables.edit') }}</a>
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


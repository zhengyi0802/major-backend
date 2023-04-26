@php
$heads = [
    ['label' =>__('advertisings.id'), 'width' => 10],
    __('advertisings.project'),
    __('advertisings.index'),
    __('advertisings.thumbnail'),
    __('tables.creator'),
    __('advertisings.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="advertisings-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($advertisings as $advertising)
        <tr>
            <td>{{ $advertising->id }}</td>
            <td>{{ $advertising->project->name }}</td>
            <td>{{ $advertising->index }}</td>
            <td><img src="{{ $advertising->thumbnail }}" width="320px" height="180px"></td>
            <td>{{ $advertising->user->name }}</td>
            <td>{{ ($advertising->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('advertisings.destroy', $advertising->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('advertisings.show', $advertising->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('advertisings.edit', $advertising->id) }}">{{ __('tables.edit') }}</a>
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


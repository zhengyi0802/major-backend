@php
$heads = [
    ['label' =>__('logos.id'), 'width' => 10],
    __('logos.project'),
    __('logos.name'),
    __('logos.image'),
    __('tables.creator'),
    __('logos.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="logo-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($logos as $logo)
        <tr>
            <td>{{ $logo->id }}</td>
            <td>{{ ($logo->project != null) ? $logo->project->name : __('logos.project_all') }}</td>
            <td>{{ $logo->name }}</td>
            <td><img src="{{ $logo->image }}" width="320px" height="180px"></td>
            <td>{{ $logo->user->name }}</td>
            <td>{{ ($logo->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('logos.destroy', $logo->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('logos.show', $logo->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('logos.edit', $logo->id) }}">{{ __('tables.edit') }}</a>
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


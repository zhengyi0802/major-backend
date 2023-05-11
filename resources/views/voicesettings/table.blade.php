@php
$heads = [
    ['label' =>__('voicesettings.id'), 'width' => 10],
    __('voicesettings.project'),
    __('voicesettings.keywords'),
    __('voicesettings.label'),
    __('tables.creator'),
    __('voicesettings.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="voicesettings-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($voicesettings as $voicesetting)
        <tr>
            <td>{{ $voicesetting->id }}</td>
            <td>{{ $voicesetting->project->name }}</td>
            <td>{{ $voicesetting->keywords }}</td>
            <td>{{ $voicesetting->label }}</td>
            <td>{{ $voicesetting->user->name }}</td>
            <td>{{ ($voicesetting->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('voicesettings.destroy',$voicesetting->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('voicesettings.show',$voicesetting->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('voicesettings.edit',$voicesetting->id) }}">{{ __('tables.edit') }}</a>
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


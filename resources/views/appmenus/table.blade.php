@php
$heads = [
    ['label' =>__('appmenus.id'), 'width' => 10],
    __('appmenus.project'),
    __('appmenus.position'),
    __('appmenus.label'),
    __('appmenus.thumbnail'),
    __('tables.creator'),
    __('appmenus.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="appmenu-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($appmenus as $appmenu)
        <tr>
            <td>{{ $appmenu->id }}</td>
            <td>{{ $appmenu->project->name }}</td>
            <td>{{ $appmenu->position }}</td>
            <td>{{ $appmenu->label }}</td>
            <td><img src="{{ $appmenu->thumbnail }}" width="160px" height="160px"></td>
            <td>{{ $appmenu->user->name }}</td>
            <td>{{ ($appmenu->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('appmenus.destroy',$appmenu->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('appmenus.show',$appmenu->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('appmenus.edit',$appmenu->id) }}">{{ __('tables.edit') }}</a>
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


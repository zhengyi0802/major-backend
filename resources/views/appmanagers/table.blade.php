@php
$heads = [
    ['label' =>__('appmanagers.id'), 'width' => 10],
    __('appmanagers.project'),
    __('appmanagers.package'),
    __('appmanagers.package_icon'),
    __('appmanagers.market_id'),
    __('appmanagers.installer_flag'),
    __('tables.creator'),
    __('appmanagers.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 10],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="appmanager-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($appmanagers as $appmanager)
        <tr>
            <td>{{ $appmanager->id }}</td>
            <td>{{ ($appmanager->project) ? $appmanager->project->name : __('appmanagers.project_all')  }}</td>
            <td>{{ $appmanager->apk->label }}</td>
            <td><img src="{{ $appmanager->apk->icon }}" width="64px" heigt="64px" ></td>
            <td>{{ ($appmanager->market_id==1) ? __('appmanagers.market_yes'):__('appmanagers.market_no') }}</td>
            <td>{{ $appmanager->installer_flag ? __('appmanagers.flag_on'):__('appmanagers.flag_off') }}</td>
            <td>{{ $appmanager->user->name }}</td>
            <td>{{ ($appmanager->status) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('appmanagers.destroy',$appmanager->id) }}" method="POST">
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


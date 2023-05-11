@php
$heads = [
    ['label' =>__('apkmanagers.id'), 'width' => 10],
    __('apkmanagers.label'),
    __('apkmanagers.icon'),
    __('apkmanagers.package_version_name'),
    __('apkmanagers.sdk_version'),
    __('tables.creator'),
    __('apkmanagers.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 15],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="apkmanager-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($apkmanagers as $apkmanager)
        <tr>
            <td>{{ $apkmanager->id }}</td>
            <td>{{ $apkmanager->label }}</td>
            <td><img src="{{ $apkmanager->icon }}" width="80px" height="80px"></td>
            <td>{{ $apkmanager->package_version_name }}</td>
            <td>{{ $apkmanager->sdk_version }}</td>
            <td>{{ $apkmanager->user->name }}</td>
            <td>{{ ($apkmanager->status==1) ?  __('tables.status_on') : __('tables.status_off') }}</td>
            <td>
                <form action="{{ route('apkmanagers.destroy',$apkmanager->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('apkmanagers.show',$apkmanager->id) }}">{{ __('tables.details') }}</a>
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


@php
$heads = [
    ['label' =>__('logmessages.id'), 'width' => 10, ],
    __('logmessages.action'),
    __('logmessages.version_name'),
    __('logmessages.mac_eth'),
    __('logmessages.mac_wifi'),
    __('logmessages.created_at'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 10],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="logmessage-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($logmessages as $logmessage)
        <tr class="form-group {{ ($logmessage->action == 'Error') ? 'bg-red' : null }}" >
            <td>{{ $logmessage->id }}</td>
            <td>{{ $logmessage->action }}</td>
            <td>{{ $logmessage->version_name }}</td>
            <td>{{ $logmessage->mac_eth }}</td>
            <td>{{ $logmessage->mac_wifi }}</td>
            <td>{{ $logmessage->created_at }}</td>
            <td>
            <a class="btn btn-info" href="{{ route('logmessages.show',$logmessage->id) }}">{{ __('tables.details') }}</a>
            </td>
        </tr>
        @endforeach
</x-adminlte-datatable>
{!! $logmessages->links() !!}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


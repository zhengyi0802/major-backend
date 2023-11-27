@php
$heads = [
    ['label' =>__('onekeyinstallers.id'), 'width' => 10],
    __('onekeyinstallers.project'),
    __('onekeyinstallers.package'),
    __('onekeyinstallers.package_icon'),
    __('tables.creator'),
    __('onekeyinstallers.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="onekeyinstaller-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($onekeyinstallers as $onekeyinstaller)
        <tr>
            <td>{{ $onekeyinstaller->id }}</td>
            <td>{{ ($onekeyinstaller->project) ? $onekeyinstaller->project->name : __('onekeyinstallers.project_all')  }}</td>
            <td>{{ ($onekeyinstaller->external_flag) ? $onekeyinstaller->label : $onekeyinstaller->apk->label }}</td>
            <td><img src="{{ ($onekeyinstaller->external_flag) ? $onekeyinstaller->thumbnail : $onekeyinstaller->apk->icon }}" width="64px" heigt="64px" ></td>
            <td>{{ $onekeyinstaller->user->name }}</td>
            <td>{{ ($onekeyinstaller->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('onekeyinstallers.destroy',$onekeyinstaller->id) }}" method="POST">
                    @csrf
                    @if( $onekeyinstaller->external_flag )
                    <a class="btn btn-info" href="{{ route('onekeyinstallers.show', $onekeyinstaller->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-info" href="{{ route('onekeyinstallers.edit', $onekeyinstaller->id) }}">{{ __('tables.edit') }}</a>
                    @endif
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('tables.confirm_delete') }}');">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


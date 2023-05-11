@php
$heads = [
    ['label' =>__('menus.id'), 'width' => 10],
    __('menus.project'),
    __('menus.name'),
    __('menus.icon'),
    __('menus.tag'),
    __('menus.type'),
    __('tables.creator'),
    __('menus.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="menu-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($menus as $menu)
        <tr>
            <td>{{ $menu->id }}</td>
            <td>{{ $menu->project->name }}</td>
            <td>{{ $menu->name }}</td>
            <td><img src="{{ $menu->icon }}" width="120px" height="120px"></td>
            <td>{{ $menu->tag }}</td>
            <td>{{ $menu->type }}</td>
            <td>{{ $menu->user->name }}</td>
            <td>{{ ($menu->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('menus.destroy',$menu->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('menus.show',$menu->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('menus.edit',$menu->id) }}">{{ __('tables.edit') }}</a>
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


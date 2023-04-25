@php
$heads = [
    ['label' =>__('hotapps.id'), 'width' => 10],
    __('hotapps.project'),
    __('hotapps.package'),
    __('hotapps.package_icon'),
    __('tables.creator'),
    __('hotapps.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 10],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="hotapp-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($hotapps as $hotapp)
        <tr>
            <td>{{ $hotapp->id }}</td>
            <td>{{ ($hotapp->project) ? $hotapp->project->name : __('hotapps.project_all')  }}</td>
            <td>{{ $hotapp->apk->label }}</td>
            <td><img src="{{ $hotapp->apk->icon }}" width="64px" heigt="64px" ></td>
            <td>{{ $hotapp->user->name }}</td>
            <td>{{ ($hotapp->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('hotapps.destroy',$hotapp->id) }}" method="POST">
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


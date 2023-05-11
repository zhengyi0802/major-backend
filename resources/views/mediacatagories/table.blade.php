@php
$heads = [
    ['label' =>__('mediacatagories.id'), 'width' => 10],
    __('mediacatagories.project'),
    __('mediacatagories.parent'),
    __('mediacatagories.name'),
    __('mediacatagories.thumbnail'),
    __('tables.creator'),
    __('mediacatagories.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="mediacatagory-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($mediacatagories as $mediacatagory)
        <tr>
            <td>{{ $mediacatagory->id }}</td>
            <td>{{ $mediacatagory->project->name }}</td>
            <td>{{ $mediacatagory->parent ? $mediacatagory->parent->name : __('mediacatagories.root') }}</td>
            <td>{{ $mediacatagory->name }}</td>
            <td><img src="{{ $mediacatagory->thumbnail }}" width="320px" height="180px"></td>
            <td>{{ $mediacatagory->user->name }}</td>
            <td>{{ ($mediacatagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('mediacatagories.destroy',$mediacatagory->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('mediacatagories.show',$mediacatagory->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('mediacatagories.edit',$mediacatagory->id) }}">{{ __('tables.edit') }}</a>
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


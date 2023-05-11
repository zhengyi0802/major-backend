@php
$heads = [
    ['label' =>__('elearningcatagories.id'), 'width' => 10],
    __('elearningcatagories.project'),
    __('elearningcatagories.parent'),
    __('elearningcatagories.name'),
    __('elearningcatagories.thumbnail'),
    __('tables.creator'),
    __('elearningcatagories.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="elearningcatagory-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($elearningcatagories as $elearningcatagory)
        <tr>
            <td>{{ $elearningcatagory->id }}</td>
            <td>{{ $elearningcatagory->project->name }}</td>
            <td>{{ $elearningcatagory->parent ? $elearningcatagory->parent->name : __('elearningcatagories.root') }}</td>
            <td>{{ $elearningcatagory->name }}</td>
            <td><img src="{{ $elearningcatagory->thumbnail }}" width="320px" height="180px"></td>
            <td>{{ $elearningcatagory->user->name }}</td>
            <td>{{ ($elearningcatagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('elearningcatagories.destroy',$elearningcatagory->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('elearningcatagories.show',$elearningcatagory->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('elearningcatagories.edit',$elearningcatagory->id) }}">{{ __('tables.edit') }}</a>
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


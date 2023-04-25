@php
$heads = [
    ['label' =>__('elearnings.id'), 'width' => 10],
    __('elearnings.catagory'),
    __('elearnings.name'),
    __('elearnings.preview'),
    __('tables.creator'),
    __('elearnings.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="elearning-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($elearnings as $elearning)
        <tr>
            <td>{{ $elearning->id }}</td>
            <td>{{ $elearning->catagory ? $elearning->catagory->name : null }}</td>
            <td>{{ $elearning->name }}</td>
            <td><img src="{{ $elearning->preview }}" width="320px" height="180px"></td>
            <td>{{ $elearning->user->name }}</td>
            <td>{{ ($elearning->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('elearnings.destroy',$elearning->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('elearnings.show',$elearning->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('elearnings.edit',$elearning->id) }}">{{ __('tables.edit') }}</a>
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


@php
$heads = [
    ['label' =>__('mediacontents.id'), 'width' => 10],
    __('mediacontents.catagory'),
    __('mediacontents.name'),
    __('mediacontents.preview'),
    __('tables.creator'),
    __('mediacontents.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="mediacontent-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($mediacontents as $mediacontent)
        <tr>
            <td>{{ $mediacontent->id }}</td>
            <td>{{ $mediacontent->catagory ? $mediacontent->catagory->name : null }}</td>
            <td>{{ $mediacontent->name }}</td>
            <td><img src="{{ $mediacontent->preview }}" width="320px" height="180px"></td>
            <td>{{ $mediacontent->user->name }}</td>
            <td>{{ ($mediacontent->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('mediacontents.destroy',$mediacontent->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('mediacontents.show',$mediacontent->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('mediacontents.edit',$mediacontent->id) }}">{{ __('tables.edit') }}</a>
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


@php
$heads = [
    ['label' =>__('qalists.id'), 'width' => 10],
    __('qalists.catagory'),
    __('qalists.question'),
    __('qalists.type'),
    __('tables.creator'),
    __('qalists.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="qalist-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($qalists as $qalist)
        <tr>
            <td>{{ $qalist->id }}</td>
            <td>{{ $qalist->catagory->name }}</td>
            <td>{{ $qalist->question }}</td>
            <td>{{ ($qalist->type == 'youtube')  ? 'YouTube' : 'Video' }}</td>
            <td>{{ $qalist->user->name }}</td>
            <td>{{ ($qalist->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('qalists.destroy',$qalist->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('qalists.show',$qalist->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('qalists.edit',$qalist->id) }}">{{ __('tables.edit') }}</a>
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


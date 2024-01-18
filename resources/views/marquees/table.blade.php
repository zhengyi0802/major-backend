@php
$heads = [
    ['label' =>__('marquees.id'), 'width' => 10],
    __('marquees.type'),
    __('marquees.project'),
    __('marquees.serialno'),
    __('marquees.content'),
    __('tables.creator'),
    __('marquees.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => __('tables.language_url') ],
];
@endphp
<x-adminlte-datatable id="marquees-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($marquees as $marquee)
        <tr>
            <td>{{ $marquee->id }}</td>
            <td>
                @if ($marquee->type == 1)
                    {{ __('marquees.type_single') }}
                @elseif ($marquee->type == 2)
                    {{ __('marquees.type_project') }}
                @else
                    {{ __('marquees.type_all') }}
                @endif
            </td>
            <td>{{ $marquee->project->name ?? '' }}</td>
            <td>{{ $marquee->serialno ?? '' }}</td>
            <td>{{ $marquee->content }}</td>
            <td>{{ $marquee->user->name }}</td>
            <td>{{ ($marquee->status==1) ?  __('tables.status_on') : __('tables.status_off') }}</td>
            <td>
                <form action="{{ route('marquees.destroy',$marquee->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('marquees.show',$marquee->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('marquees.edit',$marquee->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('tables.confirm_delete') }}');">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


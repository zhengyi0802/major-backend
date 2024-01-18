@php
$heads = [
    ['label' =>__('bulletins.id'), 'width' => 10],
    __('bulletins.project'),
    __('bulletins.popup'),
    __('bulletins.ftitle'),
    __('bulletins.status'),
    __('tables.creator'),
    __('bulletins.date'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 24],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => __('tables.language_url') ],
];
@endphp
<x-adminlte-datatable id="bulletin-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($bulletins as $bulletin)
        <tr>
            <td>{{ $bulletin->id }}</td>
            <td>{{ $bulletin->project ? $bulletin->project->name : null}}</td>
            <TD>{{ ($bulletin->popup==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>{{ $bulletin->title }}</td>
            <td>{{ $bulletin->user->name }}</td>
            <td>{{ ($bulletin->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>{{ $bulletin->date }}</td>
            <td>
                <form action="{{ route('bulletins.destroy',$bulletin->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('bulletinitems.index2',$bulletin->id) }}">{{ __('bulletins.items') }}</a>
                    <a class="btn btn-primary" href="{{ route('bulletins.edit',$bulletin->id) }}">{{ __('tables.edit') }}</a>
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


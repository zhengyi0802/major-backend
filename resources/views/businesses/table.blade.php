@php
$heads = [
    ['label' =>__('businesses.id'), 'width' => 10],
    __('businesses.project'),
    __('businesses.serial'),
    __('businesses.logo'),
    __('businesses.intervals'),
    __('tables.creator'),
    __('businesses.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="business-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($businesses as $business)
        <tr>
            <td>{{ $business->id }}</td>
            <td>{{ $business->project->name }}</td>
            <td>{{ $business->serial+1 }}</td>
            <td><img src="{{ $business->logo_url }}" width="320px" height="180px"></td>
            <td>{{ $business->intervals }}</td>
            <td>{{ $business->user->name }}</td>
            <td>{{ ($business->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('businesses.destroy', $business->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('businesses.show', $business->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('businesses.edit', $business->id) }}">{{ __('tables.edit') }}</a>
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


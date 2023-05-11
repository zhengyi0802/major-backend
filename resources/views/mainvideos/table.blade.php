@php
$heads = [
    ['label' =>__('mainvideos.id'), 'width' => 10],
    __('mainvideos.project'),
    __('mainvideos.type'),
    __('tables.creator'),
    __('mainvideos.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="mainvideo-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($mainvideos as $mainvideo)
        <tr>
            <td>{{ $mainvideo->id }}</td>
            <td>{{ $mainvideo->project ? $mainvideo->project->name : null }}</td>
            <td>
                @if ($mainvideo->type == 'playlist')
                    {{ __('mainvideos.type_playlist') }}
                @elseif ($mainvideo->type == 'youtube_playlist')
                    {{ __('mainvideos.type_youtube_playlist') }}
                @elseif ($mainvideo->type == 'youtube_playlist_id')
                    {{ __('mainvideos.type_youtube_playlist_id') }}
                @elseif ($mainvideo->type == 'stream')
                    {{ __('mainvideos.type_stream') }}
                @endif
            </td>
            <td>{{ $mainvideo->user->name }}</td>
            <td>{{ ($mainvideo->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('mainvideos.destroy',$mainvideo->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('mainvideos.show',$mainvideo->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('mainvideos.edit',$mainvideo->id) }}">{{ __('tables.edit') }}</a>
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


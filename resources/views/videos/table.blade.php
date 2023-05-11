@php
$heads = [
    ['label' =>__('videos.id'), 'width' => 10],
    __('videos.user'),
    __('videos.catagory'),
    __('videos.title'),
    __('videos.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="video-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($videos as $video)
        <tr>
            <td>{{ $video->id }}</td>
            <td>{{ $video->user ? $video->user->name : null }}</td>
            <td>{{ $video->catagory ? $video->catagory->name : __('videocatagories.root') }}</td>
            <td><video width="320" height="180" controls >
                   <source src="{{ $video->video_url }}" type="video/mp4" >
                </video>
            </td>
            <td>{{ ($video->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('videos.destroy', $video->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('videos.show', $video->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('videos.edit', $video->id) }}">{{ __('tables.edit') }}</a>
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


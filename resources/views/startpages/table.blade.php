@php
$heads = [
    ['label' =>__('startpages.id'), 'width' => 10],
    __('startpages.name'),
    __('startpages.project_name'),
    __('startpages.url'),
    __('tables.creator'),
    __('startpages.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/zh-HANT.json' ],
];
@endphp
<x-adminlte-datatable id="startpage-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
        @foreach ($startpages as $startpage)
        <tr>
            <td>{{ $startpage->id }}</td>
            <td>{{ $startpage->name }}</td>
            <td>{{ ($startpage->project) ? $startpage->project->name : '--------'  }}</td>
            <td>
                @if ($startpage->mime_type == 'image')
                     <img src="{{ $startpage->url }}" width="320px" height="240px" >
                @elseif (($startpage->mime_type == 'i_video') || ($startpage->mime_type == 'e_video'))
                     <video width="320" height="180" controls >
                         <source src="{{ $startpage->url }}" type="video/mp4">
                     </video>
                @else
                     {{ $startpage->url }}
                @endif
            </td>
            <td>{{ $startpage->user->name }}</td>
            <td>{{ ($startpage->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('startpages.destroy',$startpage->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('startpages.show',$startpage->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('startpages.edit',$startpage->id) }}">{{ __('tables.edit') }}</a>
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


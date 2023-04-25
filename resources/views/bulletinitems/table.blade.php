@php
$heads = [
    ['label' =>__('bulletinitems.id'), 'width' => 10],
    __('bulletinitems.bulletin'),
    __('bulletinitems.url'),
    __('tables.creator'),
    __('bulletins.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 20],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="bulletinitem-table" :heads="$heads" :config="$config" theme="info" striped hoverable >
        @foreach ($bulletinitems as $bulletinitem)
        <tr>
            <td>{{ $bulletinitem->id }}</td>
            <td>{{ $bulletinitem->parent->title . '-' . $bulletinitem->parent->message }}</td>
            <td>
              @if ($bulletinitem->mime_type == "image")
                  <img src="{{ $bulletinitem->url }}" width="320" height="180">
              @elseif ($bulletinitem->mime_type == "i_video" || $bulletinitem->mime_type == "e_video")
                  <video width="320" height="180" controls >
                      <source src="{{ $bulletinitem->url }}" type="video/mp4">
                  </video>
              @elseif ($bulletinitem->mime_type == "youtube")
                  <video width="320" height="180" controls >
                      <source src="{{ $bulletinitem->url }}" type="video/mp4">
                  </video>
               @endif
            </td>
            <td>{{ $bulletinitem->user->name }}</td>
            <td>{{ ($bulletinitem->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('bulletinitems.destroy',$bulletinitem->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('bulletinitems.show',$bulletinitem->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('bulletinitems.edit',$bulletinitem->id) }}">{{ __('tables.edit') }}</a>
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


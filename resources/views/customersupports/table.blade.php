@php
$heads = [
    ['label' =>__('customersupports.id'), 'width' => 10],
    __('customersupports.project'),
    __('customersupports.qrcode_type'),
    __('customersupports.qrcode_content'),
    __('customersupports.rcapp_label'),
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
<x-adminlte-datatable id="project-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
       @foreach ($customersupports as $customersupport)
        <tr>
            <td>{{ $customersupport->id }}</td>
            <td>{{ $customersupport->project->name }}</td>
            <td>
             @if ($customersupport->qrcode_type == "image")
                {{ __('customersupports.type_image') }}
             @elseif ($customersupport->qrcode_type == "text")
                {{ __('customersupports.type_text') }}
             @elseif ($customersupport->qrcode_type == "null")
                {{ __('custimersupports.type_null') }}
             @endif
            </td>
            <td>{{ $customersupport->qrcode_content }}</td>
            <td>{{ $customersupport->rcapp_label }}</td>
            <td>{{ $customersupport->user->name }}</td>
            <td>{{ ($customersupport->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('customersupports.destroy',$customersupport->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('customersupports.show',$customersupport->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('customersupports.edit',$customersupport->id) }}">{{ __('tables.edit') }}</a>
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


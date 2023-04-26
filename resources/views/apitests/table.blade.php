@php
$heads = [
    ['label' =>__('apitests.id'), 'width' => 10],
    __('apitests.project'),
    __('apitests.key'),
    __('apitests.type'),
    __('apitests.user'),
    __('apitests.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 10],
];
$config = [
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Chinese.json' ],
];
@endphp
<x-adminlte-datatable id="apitest-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable border>
  @foreach($apitests as $apitest)
  <tr>
    <td>{!! $apitest->id !!}</td>
    <td>{{ ($apitest->project!=null) ? $apitest->project: __('apitests.project_all')  }}</td>
    <td>{!! $apitest->key !!}</td>
    <td>@if ($apitest->type == 'string')
          {{ __('apitests.type_string') }}
        @elseif ($apitest->type == 'jason')
          {{ __('apitests.type_jason') }}
        @elseif ($apitest->type == 'plaintext')
          {{ __('apitests.type_plaintext') }}
        @endif
    </td>
    <td>{!! $apitest->user->name !!}</td>
    <td>{!! $apitest->status ? __('tables.status_on') : __('tables.status_off') !!}</td>
    <td><nobr>
      <form name="apitest-delete-form" action="{{ route('apitests.destroy', $apitest->id); }}" method="POST">
        @csrf
        @method('DELETE')
        @if (auth()->user()->role != 'operator')
          <x-adminlte-button theme="primary" title="{{ __('tables.edit') }}" icon="fa fa-lg fa-fw fa-pen"
            onClick="window.location='{{ route('apitests.edit', $apitest->id); }}'" >
          </x-adminlte-button>
          <x-adminlte-button theme="danger" title="{{ __('tables.delete') }}" icon="fa fa-lg fa-fw fa-trash"
            type="submit" >
          </x-adminlte-button>
        @endif
          <x-adminlte-button theme="info" title="{{ __('tables.detail') }}" icon="fa fa-lg fa-fw fa-eye"
            onClick="window.location='{{ route('apitests.show', $apitest->id); }}'" >
          </x-adminlte-button>
      </form>
    </nobr></td>
  </tr>
  @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


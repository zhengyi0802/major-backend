@php
$heads = [
    ['label' =>__('projects.id'), 'width' => 10],
    __('projects.name'),
    __('projects.start_time'),
    __('projects.stop_time'),
    __('projects.status'),
    ['label' => __('tables.action'), 'no-export' => true, 'width' => 10],
];
$config = [
    'order' => [[0, 'desc']],
    'columns' => [null, null, null, null, null, ['orderable' => false]],
    'language' => [ 'url' => __('tables.language_url') ],
];
@endphp
<x-adminlte-datatable id="project-table" :heads="$heads" :config="$config" theme="info" head-theme="dark" striped hoverable bordered>
  @foreach($projects as $project)
    <tr>
      <td>{!! $project->id !!}</td>
      <td>{!! $project->name !!}</td>
      <td>{!! $project->start_time !!}</td>
      <td>{!! $project->stop_time !!}</td>
      <td>{!! ($project->status) ? __('tables.status_on') : __('tables.status_off') !!}</td>
      <td><nobr>
          <form name="project-delete-form" action="{{ route('projects.destroy', $project->id); }}" method="POST">
            @csrf
            @method('DELETE')
              <x-adminlte-button theme="primary" title="{{ __('tables.edit') }}" icon="fa fa-lg fa-fw fa-pen"
                onClick="window.location='{{ route('projects.edit', $project->id); }}'" >
              </x-adminlte-button>
            @if (auth()->user()->id == 1)
              <x-adminlte-button theme="danger" title="{{ __('tables.delete') }}" icon="fa fa-lg fa-fw fa-trash"
                type="submit" >
              </x-adminlte-button>
             @endif
              <x-adminlte-button theme="info" title="{{ __('tables.detail') }}" icon="fa fa-lg fa-fw fa-eye"
                onClick="window.location='{{ route('projects.show', $project->id); }}'" >
              </x-adminlte-button>
            </form>
      </nobr></td>
    </tr>
  @endforeach
</x-adminlte-datatable>
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)


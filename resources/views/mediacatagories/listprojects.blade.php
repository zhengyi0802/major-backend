@php
    $pconfig = [
        "title" => "可多選",
        "liveSearch" => true,
        "liveSearchPlaceholder" => "搜尋....",
        "showTick" => true,
        "actionsBox" => true,
    ];
@endphp

<x-adminlte-select-bs id="projects" name="proj_id[]" label="{{ __('apkmanagers.projects') }}"
    label-class="text-danger" igroup-size="sm" :config="$pconfig" multiple fgroup-class="col-md-12">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-tag"></i>
        </div>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-dark" label="{{ __('tables.clear') }}" icon="fas fa-lg fa-ban text-danger"/>
    </x-slot>
    @foreach($projects as $project)
       <option value="{{ $project->id }}">{{ $project->id . '-' . $project->name }}</option>
    @endforeach
</x-adminlte-select-bs>

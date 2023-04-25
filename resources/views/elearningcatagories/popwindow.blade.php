    <form name="copy-form" action="{{ route('elearningcatagories.copy') }}" method="POST" enctype="multipart/form-data" >
    @csrf
    <input name='id' value="{{ $elearningcatagory->id }}" type="hidden" >
    <x-adminlte-modal id="newCatagoryCopy" title="{{ __('tables.new').__('elearningcatagories.table_name') }}" theme="teal" size="lg"
        icon="fas fa-bell" h-centered static-backdrop >
        <div class="card-group col-md-12">
           @include('elearningcatagories.listprojects')
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="success" label="{{ __('tables.accept') }}" type="submit"/>
            <x-adminlte-button theme="danger" label="{{ __('tables.dismiss') }}" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-model>
    </form>
    <x-adminlte-button label="{{ __('tables.copy') }}" data-toggle="modal" data-target="#newCatagoryCopy" class="bg-teal" />

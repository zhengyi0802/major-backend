@extends('adminlte::page')

@section('title', __('elearnings.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('elearnings.header') }}</h1>
@stop

@section('content')
    <style>
      img { width: 60%; height: 60%; }
      .progress { position:relative; width:100%; border: 1px solid #7F98B2; padding: 1px; border-radius: 3px; }
      .bar { background-color: #B4F5B4; width:0%; height:25px; border-radius: 3px; }
      .percent { position:absolute; display:inline-block; top:3px; left:48%; color: #7F98B2;}
    </style>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>{{ __('tables.new') }}</h1>
        </div>
        @include('layouts.back')
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form name="vform" action="{{ route('elearnings.store') }}" method="POST" enctype="multipart/form-data" >
     @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div name="catagory_group" id="catagory_group" class="form-group">
                <strong>{{ __('elearnings.catagory') }} : </strong>
                <select id="catagory_id" name="catagory_id" >
                    @foreach($elearningcatagories as $elearningcatagory)
                       <option value="{{ $elearningcatagory->id }}">{{ $elearningcatagory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.name') }} :</strong>
                <input type="text" name="name" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.password') }} :</strong>
                <input type="text" name="password" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.descriptions') }} :</strong>
                <textarea class="form-control" style="height:150px" name="description"></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <div id="div-url-name"><strong>{{ __('elearnings.preview') }} : </strong></div>
                <div id="div-image">
                    <input type="file" id="image" name="image" accept="image/*" onchange="loadImage(event)" >
                </div>
                <div id="div-preview">
                    <img name="preview" id="preview" >
                </div>
            </div>
            <script>
                    var loadImage = function(event) {
                        var output = document.getElementById('preview');
                        output.src = URL.createObjectURL(event.target.files[0]);
                        output.onload = function() {
                           URL.revokeObjectURL(output.src) // free memory
                        }
                    };
            </script>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.mime_type') }} :</strong>
                <select id="mime_type" name="mime_type" >
                    <option value="i_video" selected>{{ __('elearnings.i_video') }}</option>
                    <option value="e_video">{{ __('elearnings.e_video') }}</option>
                    <option value="youtube">{{ __('elearnings.youtube') }}</option>
                    <option value="youtube_id">{{ __('elearnings.youtube_id') }}</option>
                    <option value="ppt">{{ __('elearnings.ppt') }}</option>
                    <option value="pdf">{{ __('elearnings.pdf') }}</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.i_video') }} :</strong>
                <input type="file" name="file" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="progress">
                <div class="bar"></div>
                <div class="percent">0%</div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.url') }} :</strong>
                <input type="text" name="url" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('elearnings.status') }} :</strong>
                <input type="radio" name="status" value="1" checked>{{ __('tables.status_on') }}
                <input type="radio" name="status" value="0">{{ __('tables.status_off') }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
         </div>
     </div>
</form>
@endsection

@section('adminlte_js')
           <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
           <script type="text/javascript">
                     $(document).ready(function() {
                          var bar = $('.bar');
                          var percent = $('.percent');
                          $("form[name='vform']").ajaxForm({
                                beforeSend: function() {
                                    var percentVal = '0%';
                                    bar.width(percentVal)
                                    percent.html(percentVal);
                                },
                                uploadProgress: function(event, position, total, percentComplete) {
                                    var percentVal = percentComplete + '%';
                                    bar.width(percentVal)
                                    percent.html(percentVal);
                                },
                                complete: function(xhr) {
                                    //alert('File Has Been Uploaded Successfully');
                                    console.log("uploaded");
                                    window.location.href="/elearnings";
                                }
                          });
                     });
            </script>
@endsection

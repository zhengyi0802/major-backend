@extends('adminlte::page')

@section('title', __('mediacontents.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('mediacontents.header') }}</h1>
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
                <h1>{{ __('tables.edit') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('mediacontents.index') }}">{{ __('tables.back') }}</a>
            </div>
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

    <form name="vform" action="{{ route('mediacontents.update', $mediacontent->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div name="catagory_group" id="catagory_group" class="form-group">
                    <strong>{{ __('mediacontents.catagory') }} : </strong>
                    <select id="catagory_id" name="catagory_id" >
                        @foreach($mediacatagories as $mediacatagory)
                           <option value="{{ $mediacatagory->id }}" {{ ($mediacontent->catagory_id == $mediacatagory->id) ? "selected" : null }}>{{ $mediacatagory->name }}>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mediacontents.name') }} :</strong>
                    <input type="text" name="name" value="{{ $mediacontent->name }}" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mediacontents.descriptions') }} :</strong>
                    <textarea class="form-control" style="height:150px" name="description">{{ $mediacontent->description }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <div id="div-url-name"><strong>{{ __('mediacontents.preview') }} : </strong></div>
                    <div id="div-image">
                        <input type="file" id="image" name="image" accept="image/*" onchange="loadImage(event)" >
                    </div>
                    <div id="div-preview">
                        <img name="preview" id="preview" src="{{ $mediacontent->preview }}">
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
                    <strong>{{ __('mediacontents.mime_type') }} :</strong>
                    <select id="mime_type" name="mime_type" >
                        <option value="i_video" {{ ($mediacontent->mime_type == 'i_video') ? "selected" : null }} >{{ __('mediacontents.i_video') }}</option>
                        <option value="e_video" {{ ($mediacontent->mime_type == 'e_video') ? "selected" : null }} >{{ __('mediacontents.e_video') }}</option>
                        <option value="youtube" {{ ($mediacontent->mime_type == 'youtube') ? "selected" : null }} >{{ __('mediacontents.youtube') }}</option>
                        <option value="youtube_id" {{ ($mediacontent->mime_type == 'youtube_id') ? "selected" : null }} >{{ __('mediacontents.youtube_id') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mediacontents.i_video') }} :</strong>
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
                    <strong>{{ __('mediacontents.url') }} :</strong>
                    <input type="text" name="url" class="form-control" value="{{ $mediacontent->url }}" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('mediacontents.status') }} :</strong>
                    <input type="radio" name="status" value="1" {{ ($mediacontent->status==1) ? "checked":null }} >{{ __('tables.status_on') }}
                    <input type="radio" name="status" value="0" {{ ($mediacontent->status!=1) ? "checked":null }} >{{ __('tables.status_off') }}
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
                                    window.location.href="/mediacontents";
                                }
                          });
                     });
            </script>
@endsection

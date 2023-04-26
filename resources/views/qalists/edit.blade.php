@extends('adminlte::page')

@section('title', __('qalists.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('qalists.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.edit') }}</h1>
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

    <form name="vform" action="{{ route('qalists.update',$qalist->id) }}" method="POST">
        @csrf
        @method('PUT')
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('qalists.catagory') }} :</strong>
                    <select id="catagory_id" name="catagory_id">
                        @foreach($qacatagories as $qacatagory)
                           <option value="{{ $qacatagory->id }}" {{ ($qacatagory->id == $qalist->catagory_id) ? "selected" : null }} >{{ $qacatagory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('qalists.question') }} :</strong>
                    <input type="text" name="question" value="{{ $qalist->question }}" class="form-control" placeholder="Question">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('qalists.type') }} :</strong>
                    <select id="type" name="type" onchange="changeInput(this)">
                        <option value="i_video" {{ ($qalist->type == 'i_video') ? "selected" : null }} >{{ __('qalists.i_video') }}</option>
                        <option value="e_video" {{ ($qalist->type == "e_video") ? "selected" : null }} >{{ __('qalists.e_video') }}</option>
                        <option value="youtube" {{ ($qalist->type == "youtube") ? "selected" : null }} >{{ __('qalists.youtube_id') }}</option>
                    </select>
                    <script>
                      var changeInput = function(select) {
                          if (select.value == 'i_video') {
                              document.getElementById('div-url').style.display='none';
                              document.getElementById('div-upload').style.display='';
                          } else {
                              document.getElementById('div-url').style.display='';
                              document.getElementById('div-upload').style.display='none';
                          }
                      };
                    </script>
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
                    <strong>{{ __('qalists.answer') }} : {{ $qalist->answer }}</strong>
                </div>
                <div id="div-upload" style="display:none" >
                    <input type="file" id="file" name="file" >
                </div>
                <div id="div-url">
                    <input type="text" name="answer" value="{{ $qalist->answer }}" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('qalists.status') }} :</strong>
                    <input type="radio" name="status" value="1" {{ ($qalist->status==1) ? "checked":null }} >{{ __('tables.status_on') }}
                    <input type="radio" name="status" value="0" {{ ($qalist->status!=1) ? "checked":null }} >{{ __('tables.status_off') }}
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
                                    window.location.href="/qalists";
                                }
                          });
                     });
            </script>
@endsection

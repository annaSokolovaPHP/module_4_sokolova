@extends('admin.templates.default')
@section('content')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <p class="h2">
                    @if (!empty ($dataType->icon))
                        <i class="{{ $dataType->icon }}"></i>
                    @endif
                    {{$dataType->display_name_plural}}
                </p>
            </div>

            @if (!empty($dataTypeContent->id))
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <a href="#" class="btn btn-danger pull-right" data-toggle="modal" data-target="#Delete">
                        <i class="glyphicon glyphicon-trash"></i> Удалить
                    </a>
                </div>

                <div id="Delete" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" type="button" data-dismiss="modal">×</button>
                                <h4 class="modal-title">Удаление записи</h4>
                            </div>
                            <div class="modal-body">Вы действительно хотите удалить запись?</div>
                            <div class="modal-footer">

                                <form action="{{url('admin/'.$dataType->slug.'/delete/'.$dataTypeContent->id)}} " method="POST">
                                    {{ method_field("DELETE") }}
                                    {{ csrf_field() }}
                                    <input type="submit" class="btn btn-danger delete-confirm"
                                           value="Удалить {{ $dataType->display_name_singular }}">
                                    <button class="btn btn-default pull-right" type="button" data-dismiss="modal">Закрыть</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form role="form"
                      action="
                      @if(!empty($dataTypeContent->id))
                      {{url('admin/'.$dataType->slug.'/update/'.$dataTypeContent->id)}}
                      @else{{url('admin/'.$dataType->slug.'/create')}}
                      @endif"
                      method="POST" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    @foreach( $dataType->editRows as $row)
                        <div class="form-group{{ $errors->has($row->field) ? ' has-error' : '' }}">

                            <label for="{{$row->field}}" class="col-md-4 control-label">{{ $row->display_name }}</label>
                            @if($row->type == "image")
                                @if(isset($dataTypeContent->{$row->field}) &&
                                    Storage::disk('public')->exists($dataTypeContent->{$row->field}))
                                    <div>
                                        <img class="img-thumbnail" id = "{{$row->field}}"
                                            src="{{ Storage::disk('public')->url($dataTypeContent->{$row->field})}}">
                                <br>
                                    <button type="button" class="btn btn-sm btn-danger" href="#" onclick="deleteImage({{$row->field}});" id = "delete_{{$row->field}}">
                                        <i class="glyphicon glyphicon-trash"> Удалить {{$row->display_name}}</i>
                                        </button>
                                    <button type="button" class="btn btn-sm btn-success hidden" href="#" onclick="restoreImage({{$row->field}});" id = "restore_{{$row->field}}">
                                            <i class="glyphicon glyphicon-refresh"> Восстановить {{$row->display_name}}</i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="delete_{{$row->field}}" value="">
                                    <br>
                                @endif
                                <input type="file" id = "file_{{$row->field}}" name="{{$row->field}}">
                                @if ($errors->has('image'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @elseif(isset($errorsUpload))
                                    <span class="help-block">
                                                <strong>{{ $errorsUplod }}</strong>
                                    </span>
                                @endif
                            @elseif($row->type == "textarea")
                                <br>
                                <textarea name = "{{$row->field}}" class="form-control " rows="10">
                                    @if(old($row->field))
                                        {{old($row->field)}}
                                    @elseif(isset($dataTypeContent->{$row->field}) && !empty($dataTypeContent->{$row->field}))
                                        {!!$dataTypeContent->{$row->field}!!}
                                    @endif
                                </textarea>
                            @elseif($row->type == "checkbox")
                                <input name="{{$row->field}}" type='hidden' value='0'>
                                <input type="checkbox" value="1" name = "{{$row->field}}"
                                       <?php
                                       if(old($row->field) == 1){
                                           echo "checked";
                                       }elseif(isset($dataTypeContent->{$row->field}) && $dataTypeContent->{$row->field} == 1){
                                           echo "checked";
                                       }
                                        ?>
                                >
                            @else
                                <input type="{{$row->type}}" class="form-control" name="{{$row->field}}"
                                       @if(old($row->field)){
                                           value = "{!! old($row->field)  !!}"
                                       @elseif(isset($dataTypeContent->{$row->field}) && !empty($dataTypeContent->{$row->field})){
                                            value ="{!!$dataTypeContent->{$row->field} !!}"
                                        @endif
                                       autofocus>
                            @endif
                            @if ($errors->has($row->field))
                                <span class="help-block">
                                    <strong>{{ $errors->first($row->field) }}</strong>
                                </span>
                            @endif
                        </div>
                    @endforeach

                    @yield('relationFields')

                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
                    </div>
                    <br>
                    <br>
                </form>
            </div>
        </div>
    </div>

    <script>
        function deleteImage(el){
            var name = $(el).attr('name');
            $('#' + name).addClass('hidden');
            $('#restore_' + name).removeClass('hidden');
            $('#delete_' + name).addClass('hidden');
            $('input[name=delete_' + name + ']').val('1');
        }

        function restoreImage(el){
            var name = $(el).attr('name');
            $('#' + name).removeClass('hidden');
            $('#restore_' + name).addClass('hidden');
            $('#delete_' + name).removeClass('hidden');
            $('input[name=delete_' + name + ']').val('');
        }
    </script>
@stop





@extends('admin.templates.default')
@section('content')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <p class="h2 text-center">
                    @if (!empty ($dataType->icon))
                        <i class="{{ $dataType->icon }}"></i>
                    @endif
                    Просмотр профиля {{ $dataType->display_name_singular }}
                </p>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <a href="{{url('admin/'.$dataType->slug.'/edit/'.$dataTypeContent->id)}}" class="btn btn-primary edit  pull-right">
                    <i class="glyphicon glyphicon-edit"></i> Правка
                </a>
                <a href="#" class="btn btn-danger  pull-right" data-toggle="modal" data-target="#Delete">
                    <i class="glyphicon glyphicon-trash"></i>Удалить
                </a>

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
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-bordered">
                    <div class="panel-body text-center">
                        @foreach( $dataType->readRows as $row)
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <p class="h4">{{ $row->display_name }}</p>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                @if($row->type == "image"&&
                                    isset($dataTypeContent->{$row->field}) &&
                                    Storage::disk('public')->exists($dataTypeContent->{$row->field}))
                                    <img
                                            src="{{Storage::disk('public')->url($dataTypeContent->{$row->field})}}">
                                @elseif($row->type == "checkbox")
                                        <input type="checkbox" disabled="disabled"
                                                @if($dataTypeContent->{$row->field} == 1)
                                                checked
                                                @endif
                                        >
                                @else
                                    <p class="h4"> {!! $dataTypeContent->{$row->field} !!} </p>
                                @endif

                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                            </div>
                        @endforeach

                            @yield('addInfoRead')


                </div>
            </div>
        </div>
    </div>
@stop
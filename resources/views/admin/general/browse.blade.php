@extends('admin.templates.default')
@section('content')
    <div class="container-fluid">

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                <p class="h2">
                <p class="h2">
                    @if (!empty ($dataType->icon))
                        <i class="{{ $dataType->icon }}"></i>
                    @endif
                    {{ $dataType->display_name_plural }}
                    <a href="{{URL::to('admin/'.$dataType->slug.'/add')}}" class="btn btn-success" id ='addRecord'>
                       <i class="glyphicon glyphicon-plus"></i> Добавить
                    </a>

                        @yield('button')
                </p>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if (Session::has('errors'))
                    <div class="alert alert-danger">
                        <strong>Ошибка:</strong>
                        {{session('errors')}}
                    </div>
                @elseif(Session::has('message'))
                    <div class="alert alert-success">
                        <strong>Успешно!</strong>
                        {{session('message')}}
                    </div>
                @endif
                @if(isset($errorsUplod))
                    <div class="alert alert-danger">
                        <strong>Ошибка:</strong>
                        {{$errorsUplod}}
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <table id="tablesorter" class="responsive table table-hover" width="100%">
                            <thead>
                                <tr>
                                    <?php
                                    $counter = 0;
                                    $class = "hidden-xs"
                                    ?>
                                    @foreach( $dataType->browseRows as $rowName)
                                    <?php $counter++; ?>
                                        <div>
                                            <th class = "{{($counter != 1) ? $class : ''}}">{{ $rowName->display_name }}</th>
                                        </div>
                                    @endforeach

                                        @yield('rowName')

                                    <th class ="text-center "> Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $dataTypeContent as $data)
                                    <tr>
                                     <?php $counter=0; ?>
                                    @foreach( $dataType->browseRows as $row)
                                        <?php $counter++; ?>
                                        @if($row->type == "image" &&
                                         isset($data->{$row->field}) &&
                                         Storage::disk('public')->exists($data->{$row->field}))
                                            <td class = "{{($counter != 1) ? $class : ''}}">
                                                <img class=" img-circle user_avatar"
                                                     src="{{Storage::disk('public')->url($data->{$row->field})}}"></td>
                                        @elseif($row->type == "checkbox")
                                            <td class = "{{($counter != 1) ? $class : ''}} ">
                                                <input type="checkbox" disabled="disabled"
                                                @if($data->{$row->field} == 1)
                                                    checked
                                                @endif
                                                >
                                            </td>
                                        @else
                                            <td class = "{{($counter != 1) ? $class : ''}}"> {!! $data->{$row->field} !!}</td>
                                        @endif

                                    @endforeach

                                         @if (View::exists("admin.$dataType->slug.browse-include"))
                                             @include("admin.$dataType->slug.browse-include")
                                         @endif

                                        <td>
                                            <a href="{{URL::to('admin/'.$dataType->slug.'/edit/'.$data->id)}}" class="btn-sm btn-primary pull-right edit">
                                                <i class="glyphicon glyphicon-edit"></i>  Правка
                                            </a>
                                            <a href="{{URL::to('admin/'.$dataType->slug.'/show/'.$data->id)}}" class="btn-sm btn-warning pull-right">
                                                <i class="glyphicon glyphicon-search"></i> Обзор
                                            </a>
                                            <a href="#" class="btn-sm btn-danger pull-right" data-toggle="modal" data-target="#{{$data->id}}">
                                                <i class="glyphicon glyphicon-trash"></i> Удалить
                                            </a>

                                            <div id="{{$data->id}}" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button class="close" type="button" data-dismiss="modal">×</button>
                                                            <h4 class="modal-title">Удаление записи</h4>
                                                        </div>
                                                        <div class="modal-body">Вы действительно хотите удалить запись?</div>
                                                        <div class="modal-footer">

                                                            <form action="{{url('admin/'.$dataType->slug.'/delete/'.$data->id)}} " method="POST">
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

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $( document ).ready(function() {
            $("#tablesorter").dataTable({
                responsive: true,
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "Предыдущая",
                        "next": "Следующая",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                }
            });
        })
    </script>

@stop



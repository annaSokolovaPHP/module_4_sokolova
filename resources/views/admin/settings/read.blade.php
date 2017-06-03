@extends('admin.templates.default')
@section('content')
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <p class="h2 text-center">
                @if (!empty ($dataType->icon))
                    <i class="{{ $dataType->icon }}"></i>
                @endif
                {{ $dataType->display_name_singular }}
            </p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pull-right">
            <a href="{{url('admin/'.$dataType->slug.'/edit/'.$dataTypeContent->id)}}" class="btn btn-primary edit  pull-right">
                Правка
            </a>
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
                <div class="panel-body text-center">

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Роль пользователя по умолчанию</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->roles->display_name}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>



                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Роль администратора</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->adminRoles->display_name}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Меню сайта</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->userTopMenu->name}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Меню админ панели</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->adminTopMenu->name}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Меню админ панели левое</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->adminLeftMenu->name}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>


                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Цвет меню</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->menu_color}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Рамка меню</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->menu_border_color}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>


                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <p class="h4">Фон сайта</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                            <p class="h4">{{$dataTypeContent->background_color}}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>
@stop
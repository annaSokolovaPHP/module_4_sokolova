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
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form role="form"
                  action="{{url('admin/'.$dataType->slug.'/update/'.$dataTypeContent->id)}}"
                  method="POST" enctype="multipart/form-data">

                {{ csrf_field() }}

                @include('admin.templates.select_option',
                    [
                    'label'         => "Роль пользователя по умолчанию",
                    'name'          => "default_user_role",
                    'options'       => App\Roles::all(),
                    'key'           => "id",
                    'display_name'  => "display_name",
                    ])


                @include('admin.templates.select_option',
                    [
                    'label'         => "Роль администратора",
                    'name'          => "admin_role",
                    'options'       => App\Roles::all(),
                    'key'           => "id",
                    'display_name'  => "display_name",
                    ])
                <?php $menus = App\Menus::all(); ?>
                @include('admin.templates.select_option',
                     [
                     'label'         => "Меню сайта",
                     'name'          => "user_top_menu",
                     'options'       => $menus,
                     'key'           => "id",
                     'display_name'  => "name",
                     ])

                @include('admin.templates.select_option',
                      [
                      'label'         => "Меню админ панели",
                      'name'          => "admin_top_menu",
                      'options'       => $menus,
                      'key'           => "id",
                      'display_name'  => "name",
                      ])
                @include('admin.templates.select_option',
                      [
                      'label'         => "Меню админ панели левое",
                      'name'          => "admin_left_menu",
                      'options'       => $menus,
                      'key'           => "id",
                      'display_name'  => "name",
                      ])
                <label for="menu_color" class="col-md-4 control-label">Цвет меню</label>
                <input type="text" class="form-control" name="menu_color"
                       @if(old('menu_color')){
                       value = "{!! old('menu_color')  !!}"
                       @elseif(!empty($dataTypeContent->menu_color)){
                       value ="{!!$dataTypeContent->menu_color !!}"
                        @endif>

                <label for="menu_border_color" class="col-md-4 control-label">Рамка меню</label>
                <input type="text" class="form-control" name="menu_border_color"
                       @if(old('menu_border_color')){
                       value = "{!! old('menu_border_color')  !!}"
                       @elseif(!empty($dataTypeContent->menu_border_color)){
                       value ="{!!$dataTypeContent->menu_border_color !!}"
                        @endif>

                <label for="background_color" class="col-md-4 control-label">Фон сайта</label>
                <input type="text" class="form-control" name="background_color"
                       @if(old('background_color')){
                       value = "{!! old('background_color')  !!}"
                       @elseif(!empty($dataTypeContent->background_color)){
                       value ="{!!$dataTypeContent->background_color !!}"
                        @endif>

                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
                </div>
                <br>
                <br>
            </form>
        </div>
    </div>
</div>
@stop





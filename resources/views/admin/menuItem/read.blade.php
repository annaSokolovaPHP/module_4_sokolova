@extends('admin.general.read')
@section('addInfoRead')
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="h4">Основное меню</p>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
        <p class="h4">{{$dataTypeContent->menus->name}}</p>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <hr>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="h4">Связанный пункт меню</p>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
        <?php
                if($dataTypeContent->parent_id){
                   $name = App\MenuItem::find($dataTypeContent->parent_id)->title;
                }
        ?>
        <p class="h4">{{$name}}</p>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <hr>
    </div>
@stop
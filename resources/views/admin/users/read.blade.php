@extends('admin.general.read')
@section('addInfoRead')
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="h4">Роль</p>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
        <p class="h4">{{$dataTypeContent->roles->display_name}}</p>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <hr>
    </div>
@stop
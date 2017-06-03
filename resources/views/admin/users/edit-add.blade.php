@extends('admin.general.edit-add')
@section('relationFields')
    <?php
        $name         = "role_id";
        $options      = App\Roles::all();
        $label        = "Роль пользователя";
        $key          = "id";
        $display_name = "display_name";
    ?>
   @include('admin.templates.select_option')
@stop





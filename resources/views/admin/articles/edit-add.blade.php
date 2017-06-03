@extends('admin.general.edit-add')
@section('relationFields')
   @include('admin.templates.select_option',
    [
    'name'         => "category_id",
    'options'      => App\CategoriesArticles::all(),
    'label'        => "Категория статьи",
    'key'          => "id",
    'display_name' => "name",
    ])

   <?php
   $model        =  new App\Article();
   ?>
@stop





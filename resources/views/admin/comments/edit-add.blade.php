@extends('admin.general.edit-add')
@section('relationFields')
    <?php
    $model        =  new App\CommentsArticle;
    ?>
    @include('admin.templates.radio',
     [
     'name'         => 'public',
     'values'       => $model->getEnum(),
     ])
@stop





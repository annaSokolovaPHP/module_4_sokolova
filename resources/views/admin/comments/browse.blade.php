@extends('admin.general.browse')
@section('button')
    <a href="{{URL::to('admin/comments/browse/policy')}}" class="btn btn-success">
         Комментарии политика
    </a>
    <script>
        $('#addRecord').hide();
    </script>

@stop

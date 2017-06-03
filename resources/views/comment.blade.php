@extends('templates.default')
@section('page_script')
    <script type="text/javascript" src="{{ asset('css/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            language:"ru"
        });
    </script>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row article_row">

            <form method="post" action="{{url('articles/update_comment')}}" class="form-horizontal well">
                {{csrf_field()}}
                РЕДАКТИРОВАНИЕ КОММЕНТАРИЯ
                <input type="hidden" name="id" value="{{$comment->id}}">

                <div class="control-group">

                    <div class="controls">
                       <textarea name = "content" class="form-control " rows="10" required>
                               {{$comment->content}}
                        </textarea>
                    </div>
                </div>

                <button class="btn btn-success" type="submit"> Сохранить</button>
            </form>
        </div>


        </div>
    </div>
@stop
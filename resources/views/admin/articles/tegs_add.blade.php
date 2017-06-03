@extends('admin.templates.default')
@section('content')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <p class="text-center h3">{{$article->title}}</p>
                <p class="h2">
                    Теги статьи
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form role="form"
                      action="{{url('admin/articles/'.$article->id.'/save_tegs')}}"
                      method="POST" enctype="multipart/form-data">

                    {{ csrf_field() }}

                        <div class="form-group" id ="inputs">

                                <input type="text" class="form-control" name="name"
                                       required>
                        </div>

                    <div class="col-md-6">
                        <a class="btn btn-primary" id = 'add_input' role="button">Добавить еще тег</a>
                    </div>

                    <br><br>
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
                    </div>
                    <br>
                    <br>
                </form>
            </div>
        </div>
    </div>

    <input type="text" id="control" class="hidden col-lg-12 col-md-12 col-sm-12 col-xs-12"
           required>

    <script>

        $( document ).ready(function() {
            var count = 1;
            $( "#add_input" ).click(function() {
                var $el = $( "#control" ).clone();
                $el.removeClass('hidden');
                $el.attr('name', 'name' + count);
                $el.appendTo( "#inputs" ).append("<br>");
                count++;
            });
        });

    </script>
@stop





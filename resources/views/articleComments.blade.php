
<div>
    <h1 class="page-header text-success">Комментарии</h1>
</div>
<!-- Blog Comments -->
@if(Auth::guest())
    <div class="text-center ">
        Чтобы написать комментарий, войдите на сайт под своим именем
        <a class="btn btn-success" href="{{ url('/login') }}">Войти</a>
    </div>
@else

    <button type="submit" id = "add_main_form"  class="btn btn-success " onclick="addForm(this);">
        Добавить комментарий
    </button>

    <div class="form">
        <!-- форма добавления комментария -->
    </div>
    <br>
    <div  class ='alert alert-info hide' id = "info_add_main_form">
    </div>
@endif

<div class="row">
        <ol class="comment-list col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $prevLevel = ""; ?>
        @foreach ($comments as $comment)
            @if(($prevLevel === $comment->level && $comment->level != 0) || $comment->level > 4)

        </ul>
            @elseif($prevLevel === $comment->level && $comment->level == 0)
        </li>
        @elseif($prevLevel > $comment->level)
            <?php
            for($i = 0; $i <= ($prevLevel - $comment->level); $i++){
                echo "</ul>";
            }
            ?>
        @endif

<?php $prevLevel = ($comment->level >4) ? 4 : $comment->level; ?>
            @if($comment->level == 0)
                <li class="comment">
            @else
                <ul class="children">
            @endif
    <div class="white_color comment-body">
        <div>
            <span class ="h3 user_name_comment text-success"> {{$comment->user->name}}</span>
            <span class="user_name_comment text-success">{{$comment->created_at->format('d.m.y H:i:s')}}</span>
        </div>

            @if(!empty($comment->user->avatar) && Storage::disk('public')->exists($comment->user->avatar))
            <div class = "content">
            <img  class="img-thumbnail comment_img" src="{{Storage::disk('public')->url($comment->user->avatar)}}" alt="{{$comment->user->name}}">
            </div>
                @endif

                {!! $comment->content !!}

        <button class="pull-right"  onclick="down_like({{$comment->id}})">
            <icon class = "glyphicon glyphicon-thumbs-down" id="down_like_{{$comment->id}}">{{$comment->down_like}} </icon></button>
        <button class="pull-right"  onclick="up_like({{$comment->id}})">
            <icon class = "glyphicon glyphicon-heart" id="up_like_{{$comment->id}}">{{$comment->up_like}}</icon></button>
        <br>
        <br>
        <div>
            @if(Auth::user())
            <button type="submit"
                    id="{{$comment->id}}"
                    class="btn btn-success btn-sm"
                    onclick="addForm(this);">
                Ответить
            </button>
            @endif
        </div>
    </div>
    <div class="form">
    <!-- форма добавления комментария -->
    </div>
        @endforeach
    </ol>
</div>

@if(!Auth::guest())
<li class='comment template_comment_li hide'>
</li>


<ul class='children hide template_comment'>
<div class="white_color comment-body" >
    <div >
         <span class ="h3 user_name_comment">
        {{Auth::user()->name}}
         </span>
        <span class = 'created_at user_name_comment'> </span>
    </div>
    <div class = 'content'>
        @if(!empty(Auth::user()->avatar) && Storage::disk('public')->exists(Auth::user()->avatar))
            <img  class="img-thumbnail comment_img" src="{{Storage::disk('public')->url(Auth::user()->avatar)}}" alt="{{Auth::user()->name}}">
        @endif

    </div>

    <div>

        <a class="btn btn-success btn-sm">
            Редактировать
        </a>
            <button type="submit"
                    id=""
                    class="btn btn-success btn-sm"
                    onclick="addForm(this);">
                Ответить
            </button>

    </div>
</div>
<div class="form">
    <!-- форма добавления комментария -->
</div>
</ul>
@endif

<script>
    function removeForm(el) {
        var id = $(el).parent().find('input[name~="parent_id"]').val();
        if(id == 'add_main_form'){
            $('#add_main_form').removeClass('disabled');
        }else {
            $('#' + id).removeClass('disabled');
        }
        tinymce.get('content_' + id).remove();

        $(el).parent().remove();


    };
    function addForm(el) {
        var id = $(el).attr('id');
        if(id != 'add_main_form'){
            var divForm = $(el).closest('div[class^="white_color comment-body"]').next('.form');
        }else{
            var divForm = $(el).next('.form');
        }
        if( divForm.children().length == 0 ){
            var id = $(el).attr('id');
            var host = 'http://localhost/laravel/site/public';
            var form = $("<form/>",
                {   method:'post',
                    action: "{{asset('articles/'.$article->slug.'/create_comment')}}",
                    //onsubmit: "saveComment(this);",
                    id : "add_form_"+ id}
            );
            form.append("<br>");
            form.append(
                $("<div>",
                    { class:'alert alert-info hide',
                    id: 'info_' + id}
                )
            );
            form.append("</div>");
            form.append('{{csrf_field()}}');

            form.append(
                $("<input>",
                    { type:'hidden',
                        name:'parent_id',
                        value:id}
                )
            );
            form.append(
                $("<textarea>",
                    { class:"textarea_comment",
                        rows:"3",
                        name:"content",
                    id : "content_" + id}
                )
            );
            form.append(
                $("<button>",
                    { class : "btn btn-default col-md-offset-4 col-lg-offset-4 col-sm-offset-4",
                        href:'#',
                        onclick:"removeForm(this);",
                        type: "reset",
                        id: "remove_form_" + id}
                ).append('Скрыть')
            );
            form.append(
                $("<input>",
                    { type:'submit',
                        value:'Отправить',
                        class:"btn btn-success send input_margin",
                        onclick: "saveComment(this);"
                    }
                )
            );
            divForm.append(form);
            tinymce.init({
                selector: "textarea",
                language:"ru"
            });
            $(el).addClass('disabled');
        }

    };
    function  saveComment(el) {
        var form = $(el).parent();
        event.preventDefault();
            id = $(form).find('input[name=parent_id]').val();

        var content =  tinyMCE.get('content_' + id).getContent();
        $.ajax({
            url: "{{asset('articles/'.$article->slug.'/create_comment')}}",
            method: 'POST',
            data: {'_token': $(form).find('input[name=_token]').val(),
                'content' : content,
                'parent_id': id
            },
            type:"JSON",
            dataType: 'JSON',
            success: function (responce) {
                if(responce.level == 0){
                    var parentEl = $(".comment-list ");
                }else {
                    var parentEl = $('#add_form_'+ id).parent().parent();
                }


                removeForm($('#remove_form_'+ id));
                if(responce.public == 'no'){
                    if(responce.level == 0){
                        $("#add_main_form").next().append("<br><div  class ='alert alert-info'>"+
                            responce.message + "<div>"
                        );
                    }else {
                        parentEl.append("<br><div  class ='alert alert-info'>"+
                            responce.message + "<div>"
                        );
                    }

                }else{
                    var infolog = "Спасибо. Комментарий опубликован";
                    if(responce.comment.level == 0){
                        var html = $('.template_comment').html();
                        var template = $('.template_comment_li').clone();
                        template.html(html);
                        template.removeClass('hide template_comment_li');
                        $('#info_add_main_form').html(infolog);
                        $('#info_add_main_form').removeClass('hide');
                    }else {
                        var template = $('.template_comment').clone();
                        template.removeClass('hide template_comment');
                        parentEl.append("<br><div  class ='alert alert-info'>"+
                            infolog + "<div>"
                        );
                    }
                    template.find('span[class=created_at]').html(responce.created_at);
                    template.find('div[class=content]').append(responce.comment.content);
                    template.find('button[type=submit]').attr('id',responce.comment.id);
                    var pathname = window.location.pathname;
                    var el_a = template.find('a').attr('href', pathname +'/edit_comment/' + responce.comment.id);

                    function runHide(el_a) {
                        setTimeout(function(){
                            console.log(el_a);
                            el_a.hide();
                        }, 60000);
                    }

                    runHide(el_a);

                    if(responce.comment.level == 0){
                        $('.comment-list').prepend(template);
                    }else {
                        parentEl.append(template);
                    }

                }
            },
            error: function (json){
                    console.log(json);

                $('#info_' + id).empty();
                $('#info_' + id).append(
                   " Ошибка сохранения комменатария."
                );
                $('#info_' + id).removeClass('hide');
            }
            });
    };

    function down_like(id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{asset('articles/comment_down_like')}}",
            method: 'POST',
            data: {
                'id': id
            },
            type:"JSON",
            dataType: 'JSON',
            success: function (responce) {
                $('#down_like_' + id).html(responce.count);
            },
            error: function (json){
                console.log(json);
            }
        });
    };

    function up_like(id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{asset('articles/comment_up_like')}}",
            method: 'POST',
            data: {
                'id': id
            },
            type:"JSON",
            dataType: 'JSON',
            success: function (responce) {
                $('#up_like_' + id).html(responce.count);
            },
            error: function (json){
                console.log(json);
            }
        });
    };


    setTimeout(function(){
        $('#open_subscribe').modal(modalOptions);
    }, 15000)

</script>


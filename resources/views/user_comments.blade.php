@extends('templates.default')

@section('content')
    <div class="container-fluid">
        <div class="row article_row">
            <div class=" col-lg-7 col-lg-offset-1 col-md-7  col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
                <p class="h3 text-center"> Имя пользователя: {{$user->name}}</p>
                <br>
                <br>
                <br>
                <p class="h3 center"> Комментарии пользователя</p>

                <li class="row">
                    <ol class="comment-list col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        @foreach ($comments as $comment)
                            <li class="comment">

                            <div class="white_color comment-body">
                                <div>
                                    <span class ="h3 user_name_comment text-success"> {{$comment->user->name}}</span>
                                    <span class="user_name_comment text-success">{{$comment->created_at->format('d.m.y H:i:s')}}</span>
                                </div>
                                {!! $comment->content !!}
                                </div>
                            </li>
                            @endforeach
                    </ol>
                </li>
            </div>
        </div>
    </div>

    <div class=" col-lg-7 col-md-7 col-lg-offset-1 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 text-center">

        {{$comments->links('templates.pagination')}}

    </div>
@stop
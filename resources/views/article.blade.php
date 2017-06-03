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
<div class="container-fluid" >
    <div class="row">
        <div class=" col-lg-2  col-md-2   col-sm-1 col-xs-2">
            <?php $count = 0; ?>
            @foreach($advertising as $block)
                <div class="panel panel-success price" id="{{$block->id}}">
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="text-center text-success">{{$block->product_name}}</p>
                            <hr class = "hr_margin_15">
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span id="price_{{$block->id}}" class="price_val" id="test">
                                  {{$block->price}}
                              </span>
                                <button type="button"
                                        id="popover_{{$block->id}}"
                                        class="hide"
                                        data-toggle="popover"
                                        <?php  $all = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                                        $pass = '';
                                        $len = 10;
                                        $cnt = strlen($all) - 1;
                                        srand((double)microtime()*1000000);
                                        for($i=0; $i<$len; $i++) $pass .= $all[rand(0, $cnt)];
                                        ?>
                                        title="Купон на скидку -{{$pass}}"
                                        data-content="Примените и получите 10% скидку">Кнопка с popover
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        </div>
                        <p class="small">{!! $block->seller !!}}</p>
                    </div>
                </div>
                <?php $count++;
                if($count == 4)
                    break;
                ?>
            @endforeach
        </div>

        <div class=" col-lg-5  col-md-5  col-sm-8  col-xs-8">
            <div class=" white_color col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="text-center">
                    <h1 class="text-success">{{$article->title}}</h1>
                </div>
                <!-- Author -->
                <p class="lead">
                <i class="glyphicon glyphicon-time"></i> Опубликовано: {{$article->created_at->format('d.m.y H:i:s')}}
                </p>


                @if(!empty($article->image) && Storage::disk('public')->exists($article->image))
                <img  class="img-thumbnail article_img" src="{{Storage::disk('public')->url($article->image)}}" alt="{{$article->title}}">
                @endif
                @if (Auth::guest() && $article->analytics == 1)
                    <?php
                    $count = 0;
                            $body = explode(".", $article->body);
                            foreach ($body as $value){
                                echo $value.'. ';
                                $count++;
                                if($count == 3)
                                    break;
                    }
                    ?>

                @else
                    {!! $article->body !!}
                @endif

            </div>
            Читают сейчас: <span id = "read"></span>
            <br>
            Общее количество прочтений: <span id = "total_read">{{$article->count_read}}</span>

            <div class="col-lg-12 col-md-12  col-sm-12  col-xs-12">
                @include('articleComments')
            </div>

        </div>


            @include('templates/groupArticles')
        <div class=" col-lg-2  col-md-2   col-sm-1 col-xs-2">
            <?php $count = 0; ?>
            @foreach($advertising as $block)
                <?php $count++;
                if($count <= 4)
                    continue;
                ?>
                <div class="panel panel-success price" id="{{$block->id}}">
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="text-center text-success" id="{{$block->id}}">{{$block->product_name}}</p>
                            <hr class = "hr_margin_15">
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span id="price_{{$block->id}}" class="price_val" id="test">
                                  {{$block->price}}
                              </span>
                                <button type="button"
                                        id="popover_{{$block->id}}"
                                        class="hide"
                                        data-toggle="popover_left"
                                        <?php  $all = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                                        $pass = '';
                                        $len = 10;
                                        $cnt = strlen($all) - 1;
                                        srand((double)microtime()*1000000);
                                        for($i=0; $i<$len; $i++) $pass .= $all[rand(0, $cnt)];
                                        ?>
                                        title="Купон на скидку -{{$pass}}"
                                        data-content="Примените и получите 10% скидку">Кнопка с popover
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        </div>
                        <p class="small">{!! $block->seller !!}}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>


    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center small">
                <hr> COPYRIGHT &copy; 2017 УКРПСИХОЛОГ
            </div>
        </div>
        <!-- /.row -->
    </footer>

</div>
<script>

    $( document ).ready(function() {
        setInterval(function() {
            var count = 1 + Math.floor(Math.random() * 5);
            $('#read').html(count);
            update_total(count);
        }, 3000);

        function update_total(count) {
            var baseUrl = window.location.pathname;
            $.ajax({
                url: baseUrl + "/count_total",
                method: 'GET',
                data: { read_count: count},
                type:"JSON",
                dataType: 'JSON',
                success: function (responce) {
                    var count_total = $('#total_read').html();
                    $('#total_read').html(parseInt(count_total,10) + count);
                },
                error: function (responce){
                }
            });
        };
    });

</script>
@stop
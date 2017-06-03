@extends('templates.default')
@section('page_script')
    <link rel="stylesheet" type="text/css" href="{{asset ('css/css/elastislide.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset ('css/css/custom.css')}}" />
    <script src="{{asset ('css/js/modernizr.custom.17475.js')}}"></script>
    <script type="text/javascript" src="{{asset ('css/js/jquery.elastislide.js')}}"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            $( '#carousel' ).elastislide( {
                minItems : 2
            });

            $( function() {
                var availableTags = <?php echo json_encode($tegs); ?>;
                $( "#tags" ).autocomplete({
                    source: availableTags
                });

                $( "#tags1" ).autocomplete({
                    source: availableTags
                });
            } );
        });
    </script>
@stop

@section('content')
<div class="container-fluid">
  <div class="row article_row">

      <div class=" col-lg-2  col-md-2   col-sm-1 col-xs-2">
          <?php $count = 0; ?>
          @foreach($advertising as $block)
              <div class="panel panel-success price" id="{{$block->id}}">
                  <div class="panel-body">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <p class="text-center text-success ttt" id="ttt">{{$block->product_name}}</p>
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

      <div class=" col-lg-5 col-md-5   col-sm-8  col-xs-8 ">
          <div class="row">
              <div class="row ">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <!-- Elastislide Carousel -->
                      <ul id="carousel" class="elastislide-list">
                          @foreach ($carousel as $new)
                              <img class = "img-thumbnail" src="{{Storage::disk('public')->url($new->image)}}" alt="{{$new->title}}">
                              @if(Storage::disk('public')->exists($new->image))
                                  <li>
                                      <a data-toggle="modal" data-target=".pop-up-{{$new->id}}" href="#">
                                          <img class = "img-thumbnail" src="{{Storage::disk('public')->url($new->image)}}" alt="{{$new->title}}">
                                      </a>
                                  </li>
                              @endif
                          @endforeach
                      </ul>
                      <!-- End Elastislide Carousel -->
                  </div>
              </div>
              @foreach ($carousel as $new)
                  @if(Storage::disk('public')->exists($new->image))
                      <div class="modal fade pop-up-{{$new->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-1" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                  </div>
                                  <div class="modal-body">
                                      <img src="{{Storage::disk('public')->url($new->image)}}" class="img-responsive center-block" alt="{{$new->alt}}">
                                  </div>
                              </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                      </div><!-- /.modal mixer image -->
                  @endif
              @endforeach


          </div>
    @if($articles->count() == 0)
    <p class="h2 text-center"> К сожалению по Вашему запросу ничего не найдно</p>
    @endif
      <?php $count = 0;?>
          @foreach($articles as $key => $article)
              @if($count < 1 )
                  <div class="row article_row ">
                      <div class="hidden-lg hidden-md hidden-sm col-xs-10 col-xs-offset-1">
                          <form method="get" action="{{ url('/articles1')}}">
                              <div class="input-group">
                                  <input type="text" class="form-control" placeholder="Поиск" name="find" id = 'tags'>
                                  <div class="input-group-btn">
                                      <button class="btn btn-default search" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                  </div>
                              </div>
                          </form>
                      </div>

                      <div class="hidden-lg hidden-md hidden-sm col-xs-10 col-xs-offset-1">
                          <div class="list-group">
                              <a class ="h4 list-group-item btn active_green" id = "category">КАТЕГОРИИ
                                  <i class="glyphicon  glyphicon-plus pull-right"> </i></a>
                          @foreach($categoriesArticles as $categories)
                              <a href="{{url('/articles/category_'.$categories->slug)}}"
                                 @if(isset($category) && !empty($category) && $categories->slug == $category)
                                 class= "list-group-item active_green hidden category_item"
                                 @else
                                 class= "list-group-item hidden category_item"
                                      @endif
                              >
                                  {{$categories->name}}<span class="badge">{{App\Article::findByStatus('PUBLISHED')->where('category_id', $categories->id)->count()}}</span>
                              </a>
                          @endforeach
                          </div>
                      </div>
                  </div>
              @endif
          <div class="row article_row ">
                     <article class=" white_color col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="row article_row">
                              <div>
                                  <h2 class = "text-center text-success">{{$article->title}}</h2>
                              </div>
                          </div>
                          <hr>
                          <div class="row article_row">
                              <div>
                                  <i class="icon_green glyphicon glyphicon-calendar"></i> {{$article->created_at->format('d.m.y H:i:s')}}
                                  | <i class="icon_green glyphicon glyphicon-comment"></i>
                                  @if($article->comments->count() > 0)
                                      {{$article->comments->count()}} Комментариев
                                  @else
                                      Комментарии отсутствуют
                                  @endif
                              </div>
                          </div>
                          <div class="row article_row">
                              <div>
                                  @if(!empty($article->image) && Storage::disk('public')->exists($article->image))
                                      <img  class="img-thumbnail article_img" src="{{Storage::disk('public')->url($article->image)}}" alt="{{$article->title}}">
                                  @endif

                                  @foreach($article->tegs as $teg)
                                      |<a href ="{{url('/articles/teg/'.$teg->id)}}" >{{$teg->name}} </a>|
                                  @endforeach
                              </div>
                          </div>
                          <div class="row article_row">
                              <div>
                                  <a href ="{{url('/articles/'.$article->slug)}}" class="btn btn-default read-more" >
                                      Читать далее... <i class="glyphicon glyphicon-chevron-right icon_green"></i>
                                  </a>
                              </div>
                          </div>
                          <div class="row article_row">
                              <div>
                                  <hr>
                              </div>
                          </div>
                      </article>
          </div>
                  <?php $count++; ?>


              @endforeach
          <div class=" col-lg-12 col-md-12  col-sm-120 col-xs-12 text-center">

              {{$articles->links('templates.pagination')}}

          </div>
      </div>
              @include('templates.groupArticles')

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


</div>
    <script>
        $( document ).ready(function() {
            $( "#category" ).click(function() {
                $( ".category_item" ).each(function() {
                   if($( this ).hasClass('hidden')) {
                       $(this).removeClass('hidden');
                   }else{
                       $(this).addClass('hidden');
                   }
                });
            });
        });
    </script>
@stop
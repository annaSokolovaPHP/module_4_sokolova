
    <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
        <div>
            <form method="post" action="{{url('articles/teg')}}">
                {{csrf_field()}}
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Поиск" name="find" id = 'tags1'>
                    <div class="input-group-btn">
                        <button class="btn btn-default search" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
<br>
        <div>
            <form method="post" action="{{url('find_param')}}" class="form-horizontal well">
                {{csrf_field()}}
                ПАРАМЕТРЫ ПОИСКА
                <div class="control-group">
                    <label class="control-label" for="date_from">Дата с</label>
                    <div class="controls">
                        <input type="date" name="date_from">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="date_to" >Дата по</label>
                    <div class="controls">
                        <input type="date" name ="date_to">
                    </div>
                </div>
                <div class="control-group">
                <label class="control-label" for="teg">ТЕГИ</label>
                @foreach($tegs as $teg)
                    <label class="checkbox">
                        <input type="checkbox" name = "teg[]" value="{{$teg->id}}">{{$teg->name}}
                    </label>
                @endforeach
                </div>
                <div class="control-group">
                <label class="control-label" for="category">КАТЕГОРИИ</label>
                @foreach($categoriesArticles as $category)
                    <label class="checkbox">
                        <input type="checkbox" name = "category[]" value="{{$category->id}}">{{$category->name}}
                    </label>
                @endforeach
                </div>
                <button class="btn btn-success" type="submit"> ПОИСК</button>
            </form>
        </div>


        <div class="list-group">
            <p class ="h4 text-center list-group-item text-success">АНАЛИТИКА</p>
            <a href="{{url('/articles/analytics')}}" class= "list-group-item">Аналитика</a>
        </div>
        <div class="list-group">
            <p class ="h4 text-center list-group-item text-success">КАТЕГОРИИ</p>
            @foreach($categoriesArticles as $categories)
                <a href="{{url('/articles/category_'.$categories->slug)}}"
                   @if(isset($category) && !empty($category) && $categories->slug == $category)
                   class= "list-group-item active_green"
                   @else
                   class= "list-group-item"
                        @endif
                >
                    {{$categories->name}}<span class="badge">{{App\Article::where('category_id', $categories->id)->count()}}</span>
                    <hr>
                    @foreach($categories->getTop5Articles as $articles)

                       <p> {{$articles->title}}</p>

                    @endforeach

                </a>


            @endforeach
        </div>
        <div class="list-group">
            <p class ="h4 text-center list-group-item text-success">ПОПУЛЯРНОЕ</p>
            @if(isset($popularArticle))
                @foreach($popularArticle as $popular)
                    <a href="{{URL::to('articles/'.$popular['slug'])}}" class ="list-group-item">
                        @if(!empty($popular['image']) && Storage::disk('public')->exists($popular['image']))
                            <img  class="img-thumbnail img_popular" src="{{Storage::disk('public')->url($popular['image'])}}" alt="{{$popular['title']}}">
                        @endif
                        {{$popular['title']}}
                    </a>
                @endforeach
            @endif
        </div>

        <div class="list-group">
            <p class ="h4 text-center list-group-item text-success">АКТИВНЫЕ ПОЛЬЗОВАТЕЛИ</p>
            @if(isset($user_top_comments))
                @foreach($user_top_comments as $user)
                    <a href="{{URL::to('articles/comments/'.$user['id'])}}" class ="list-group-item">
                        {{$user['name']}}
                    </a>
                @endforeach
            @endif
        </div>

    </div>








<!-- Меню navbar -->
<nav class="navbar navbar-default navbar-fixed-top marginBottom-0" role="navigation">

    <!-- Бренд и переключатель, который вызывает меню на мобильных устройствах -->
    <div class="navbar-header">
        <!-- Кнопка с полосочками, которая открывает меню на мобильных устройствах -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu" aria-expanded="false">
            <span class="sr-only">МЕНЮ</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Бренд или логотип фирмы (обычно содержит ссылку на главную страницу) -->
        <a href="{{URL::to($menuItems->first()->url)}}" target="{{$menuItems->first()->target}}" class="navbar-brand">{{ config('app.name') }}</a>
    </div>
    <!-- Содержимое меню (коллекция навигационных ссылок, формы и др.) -->
    <div class="collapse navbar-collapse" >


        <!-- Список ссылок, расположенных слева -->
        <ul class="nav navbar-nav">
            @foreach($menuItems as $menuItem)
                @if(!$menuItem->hasChild())
                    <li class = "{{Route::currentRouteName() == trim($menuItem->url, '/') ? 'active' : ''}}">
                        <a href="{{URL::to($menuItem->url)}}" target="{{$menuItem->target}}">{{$menuItem->title}}</a>
                    </li>
                @else
                    <li class="dropdown">
                        <a href="{{URL::to($menuItem->url)}}" class="dropdown-toggle" data-toggle="dropdown" target="{{$menuItem->target}}">{{$menuItem->title}}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach($menuItem->childMenu() as $menu_level_2)
                                @if(!$menu_level_2->hasChild())
                                    <li class = "{{Route::currentRouteName() == trim($menu_level_2->url, '/') ? 'active' : ''}}">
                                        <a href="{{URL::to($menu_level_2->url)}}" target="{{$menu_level_2->target}}">{{$menu_level_2->title}}</a>
                                    </li>
                                @else
                                    <li class="dropdown dropdown-submenu">
                                        <a href="{{URL::to($menu_level_2->url)}}" class="dropdown-toggle" data-toggle="dropdown" target="{{$menu_level_2->target}}">{{$menu_level_2->title}}</a>
                                        <ul class="dropdown-menu">
                                            @foreach($menu_level_2->childMenu() as $menu_level_3)
                                                <li class = "{{Route::currentRouteName() == trim($menu_level_3->url, '/') ? 'active' : ''}}">
                                                    <a href="{{URL::to($menu_level_3->url)}}" target="{{$menu_level_3->target}}">{{$menu_level_3->title}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>


        <!-- Список ссылок, расположенный справа -->
        <ul class="nav navbar-nav navbar-right exit">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Войти</a></li>
                <li><a href="{{ url('/register') }}">Регистрация</a></li>
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Выйти
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" class= "hidden">
                                {{ csrf_field() }}
                            </form>
                        </li>

                        @if(Auth::user()->isAdmin(App\Settings::first()->admin_role) == 1)
                            <li>
                                <a href="{{ url('/admin') }}">
                                    Админка
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</nav>

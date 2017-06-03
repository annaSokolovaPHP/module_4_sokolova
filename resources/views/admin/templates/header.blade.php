<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>

    <!-- Bootstrap core CSS -->
    <link href="{{asset ('css/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset ('css/css/bootstrap-theme.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset ('css/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset ('css/css/responsive.dataTables.min.css')}}">
    <link href="{{asset ('css/css/style.css')}}" rel="stylesheet">
    @if(App\Settings::all()->first()->background_color)
        <style>
            body {
                background-color: {{App\Settings::all()->first()->background_color}};
            }

        </style>
    @endif

    <?php
    $menu_color = App\Settings::all()->first()->menu_color ? App\Settings::all()->first()->menu_color : '#34b767';
    $menu_border_color = App\Settings::all()->first()->menu_border_color ? App\Settings::all()->first()->menu_border_color : '#547b54';
    ?>
    <style>
        .navbar-default {
            background-color: {{$menu_color}};
            border-color: {{$menu_border_color}};
            background-image:none;
        }
        /* цвет текста, содержащий название сайта или бренда */
        .navbar-default .navbar-brand {
            color: #fff;
        }
        /* цвет текста (название сайта или бренда), при поднесении к нему курсора мышки или при его нахождении в фокусе */
        .navbar-default .navbar-brand:hover,
        .navbar-default .navbar-brand:focus {
            color: #d1ffed;
        }
        /* Цвет пунктов навигационного меню */
        .navbar-default .navbar-nav > li > a {
            color: #fff;
        }
        /* Цвет пункта меню, при поднесении к нему курсора мышки или при его нахождении в фокусе */
        .navbar-default .navbar-nav > li > a:hover,
        .navbar-default .navbar-nav > li > a:focus {
            color: #d1ffed;
        }
        /* Цвет и фон активного пункта меню, а также поднесении к нему курсора мышки или при его нахождении в фокусе */
        .navbar-default .navbar-nav > .active > a,
        .navbar-default .navbar-nav > .active > a:hover,
        .navbar-default .navbar-nav > .active > a:focus {
            color: #d1ffed;
            background-color: {{$menu_border_color}};
            background-image:none;
        }
        /* Цвет и фон открытого пункта меню, а также поднесении к нему курсора мышки или при его нахождении в фокусе */
        .navbar-default .navbar-nav > .open > a,
        .navbar-default .navbar-nav > .open > a:hover,
        .navbar-default .navbar-nav > .open > a:focus {
            color: #d1ffed;
            background-color: {{$menu_border_color}};
        }
        /* Цвет стрелочки (треугольничка) у раскрывающихся пунктов меню */
        .navbar-default .navbar-nav > .dropdown > a .caret {
            border-top-color: #fff;
            border-bottom-color: #fff;
        }
        /* Цвет стрелочки (треугольничка) при поднесении к нему курсора мышки или при его нахождении в фокусе */
        .navbar-default .navbar-nav > .dropdown > a:hover .caret,
        .navbar-default .navbar-nav > .dropdown > a:focus .caret {
            border-top-color: #d1ffed;
            border-bottom-color: #d1ffed;
        }
        /* Цвет стрелочки (треугольничка), открывшегося пункта меню */
        .navbar-default .navbar-nav > .open > a .caret,
        .navbar-default .navbar-nav > .open > a:hover .caret,
        .navbar-default .navbar-nav > .open > a:focus .caret {
            border-top-color: #d1ffed;
            border-bottom-color: #d1ffed;
        }
        /* CSS стили для мобильных устройств */
        /* Цвет рамки у кнопки, которая открывает меню */
        .navbar-default .navbar-toggle {
            border-color: {{$menu_border_color}};
        }
        /* Цвет фона кнопки (которая открывает меню) при поднесении к ней курсора мышки или при нахождении её в фокусе */
        .navbar-default .navbar-toggle:hover,
        .navbar-default .navbar-toggle:focus {
            background-color: {{$menu_border_color}};
        }
        /* Цвет полосочек в кнопочке, которая открывает меню */
        .navbar-default .navbar-toggle .icon-bar {
            background-color: #fff;
        }

        /* Dropdown menu background color*/

        .navbar-nav > li > .dropdown-menu {     background-color: {{$menu_color}};  }

        /* Dropdown menu font color*/

        .navbar-nav > li > .dropdown-menu a{   color: #fff;  }
        .navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.open>a {
            background-image: none;
        }
        .dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover {
            background-image: none;
        }
        .dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover{
            color: #d1ffed;
            background-color: {{$menu_border_color}};

        }
        /*NAVBAR*/
    </style>
    <script type="text/javascript" src="{{asset ('css/jquery-3.1.1.min.js')}}"></script>

    <script type="text/javascript" src="{{asset ('css/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset ('css/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset ('css/js/dataTables.responsive.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('css/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            language:"ru"
        });
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class ="admin">
@include('admin.templates.menu')























<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>myStudentDrive</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 75px;
        }

        .subtitle {
            font-size: 35px;
            font-weight: bold;
        }

        .links > a {
            /*color: #636b6f;*/
            color: black;
            padding: 0 25px;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
            color: #3490dc;
            font-weight: bold;
            margin-top: 75px;
        }

        .links.custom-subtitles{
            display: block;
        }
        .links.custom-subtitles a{
            display: block;
            margin-top: 1.5em;
            color: #052d53;
            font-weight: bold;
            font-size: 20px;
        }

    </style>
</head>
<body class="body-welcome">
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/dashboard') }}">dashboard</a>
            @else
                <a href="{{ route('login') }}">Iniciar Sesión</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Regístrate</a>
                @endif
            @endauth
        </div>
    @endif

    {{--                <h1 class="main-title">My Student drive</h1>--}}


    <div class="content">

        <div class="title m-b-md">
            My Student Drive
        </div>
        <img src="{{asset('images/content/clip-education_v2.png')}}" style="width: 75%" alt="cool">
                        <div class="links custom-subtitles">
                            <a>Gestiona tus estudios y asignaturas</a>
                            <a>Organiza tu agenda</a>
                            <a>Apunta tus tareas</a>
                        </div>

    </div>
</div>
<script src="{{asset('js/app.js')}}"></script> <!--Añadimos el js generado con webpack, donde se encuentra nuestro componente vuejs-->
</body>
</html>

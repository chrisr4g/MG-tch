<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MoviesShowcase - @yield('title')</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <style type="text/css">
            .ajax-load{
                background: #e1e1e1;
                padding: 10px 0px;
                width: 100%;
            }
            .showcaseItem{
                border:2px black solid;
                margin-bottom: 10px;
                padding-right: 10px;
                padding-left:  10px;
            }
        </style>
    </head>
    <body>
        @include('layouts.components.header')

        <div class="container-fluid">
            @yield('content')
        </div>

        @include('layouts.components.footer')
    </body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'eSmartBuy') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #29b6f6;
        }

        a.nav-link {
            color: #ecf0f5;
        }       

        .imgCen {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 40px;
            width: 40%;
        }
        .brand-logo img{
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 40px;
            margin-top: 20px;
        }

        .landingDisc p{
            text-align: center;
            color: #eeeeee;
            font-size: 35px;
            font-family: 'Roboto', sans-serif;
        }
        
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .imgCen {
                margin-top: 60px;
                width: 45%;
            }

            .brand-logo img {
                width: 40px;
            }

            .landingDisc p {
                font-size: 30px;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .imgCen {
                margin-top: 80px;
                width: 55%;
            }

            .brand-logo img {
                width: 40px;
            }
        }
        
        @media (min-width: 576px) and (max-width: 767.98px) {
            .imgCen {
                margin-top: 90px;
                width: 50%;
            }   

            .brand-logo img {
                width: 40px;
            }
        }

        @media (max-width: 575.98px) {
            .imgCen {
                margin-top: 100px;
                width: 60%;
            }

            .landingDisc p {
                font-size: 25px;
            }
        }
    </style>
</head>
<body>

<section class="section-home">
    <ul class="nav justify-content-end">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">|</a>
            </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Register</a>
        </li>
    </ul>
    
    <div class="center-img">
        <img src="images/centerImg.png" class="imgCen">
    </div>

    <div class="brand-logo">
        <img class="img-fluid" src="images/MSLP.png">
    </div>

    <div class="container landingDisc">
        <p>We Care We Compare!</p>
    </div>
</section>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</div>
</body>
</html>

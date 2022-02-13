<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ env('APP_NAME') }}@isset($title) - {{ $title }}@endisset
    </title>
    <link rel="shortcut icon" href="{{ asset('storage/fav.png') }}">

    <style>
        :root {
            --primary-bg-dark: #164f6b; 
            --primary-bg-mid: #0e75a7; 
            --primary-bg-light: #039be5;  
            
            --primary-trans-bg-dark: #164f6b;
            --primary-trans-bg-light: #039be5;
            
            --green-light: #27a747;
            --green-dark: #2a8841;
            
            --secondary-bg-dark: #8d1003; 
            --secondary-bg-light: #c91e0b; 
            
            --switch-dark: #164f6b; 
            --switch-light: #039be5; 

            --button-dark: #164f6b; 
            --button-light: #039be5;
            --button-secondary: #8d1003;
        }
    </style>

    <script src="{{asset('js/datatable/jquery-3.5.1.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
    <script type="text/javascript" src="{{asset('countdowntimer/src/js/jQuery.countdownTimer.js')}}"></script>
    <script src="{{asset('js/lightbox.js')}}"></script>
    <script src="{{asset('materialize-css/js/materialize.min.js')}}"></script>
    <script src="{{ asset('js/vue.min.js') }}"></script>
    

    <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet"> <!-- font-awesome -->
    <link rel="stylesheet" type="text/css" href="{{asset('countdowntimer/src/css/jQuery.countdownTimer.css')}}" />
    <link rel="stylesheet" href="{{asset('css/material-icons.css')}}">
    <link rel="stylesheet" charset="utf-8" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" charset="utf-8" href="{{asset('materialize-css/css/materialize.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
    <link rel="stylesheet"  href="{{asset('css/lightbox.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/wnoty.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/small.css')}}">
</head>
<body>
    <div class="app" id="app">

        {{-- Navbar goes here --}}
        @include('layouts.nav')
        {{-- CONTENT AREA    --}}
        @yield('content')
        
    </div>
    @include('sweetalert::alert')
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <script>
        function checkScreenSize() {
            let window_size = parseInt($(window).width());
            let mark = parseInt(992)
            if (window_size > mark) {
                $('#hide-side-nav').fadeIn();

                $('.my-content-wrapper').animate({
                    'padding-left': '300px'
                });
                $('#users-table').animate({
                    'width': '100%'
                });
                $('.breadcrumbWrap').animate({
                    'margin-left': '310px'
                });
                $('#show-side-nav').animate({
                    'width': '0px',
                    'margin-right': '0px'
                });
            } else if (window_size <= mark) {

                $('.my-content-wrapper').animate({
                    'padding-left': '0px'
                })
                $('#users-table').animate({
                    'width': '100%'
                })
                $('#users-table').animate({
                    'width': '100%'
                })
                $('.breadcrumbWrap').animate({
                    'margin-left': '15px'
                })
                $('#show-side-nav').animate({
                    'width': '60px',
                    'margin-right': '20px'
                })
            }
        }
        $(document).ready(function() {
            
            $('.sidenav').sidenav();
            $('.collapsible').collapsible()
            $('.dropdown-trigger').dropdown()

            checkScreenSize()

            $('#hide-side-nav').click(function(e) {
                $(this).fadeOut();
                $('#slide-out').animate({
                    'width': '0px'
                });
                $('.my-content-wrapper').animate({
                    'padding-left': '0px'
                });
                $('#users-table').animate({
                    'width': '100%'
                });
                $('#users-table').animate({
                    'width': '100%'
                });
                $('.breadcrumbWrap').animate({
                    'margin-left': '0px'
                });
                $('#show-side-nav').animate({
                    'width': '60px',
                    'margin-right': '20px'
                });
            })
            $('#show-side-nav').click(function(e) {
                $('#hide-side-nav').fadeIn();
                $('#slide-out').animate({
                    'width': '300px'
                });
                $('.my-content-wrapper').animate({
                    'padding-left': '300px'
                });
                $('#users-table').animate({
                    'width': '100%'
                });
                $('.breadcrumbWrap').animate({
                    'margin-left': '310px'
                });
                $('#show-side-nav').animate({
                    'width': '0px',
                    'margin-right': '0px'
                });
            })

        })
    </script>
    @stack('scripts')
</body>
</html>

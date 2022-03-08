<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ env('APP_NAME') }}@isset($title) - {{ $title }}@endisset
    </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.png') }}">
    <!-- PWA  -->
    <meta name="theme-color" content="#164f6b"/>
	<link rel="apple-touch-icon" href="{{ asset('icon512.png') }}">
	<link rel="manifest" href="{{ asset('/manifest.json') }}">
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
    <link rel="stylesheet" href="{{asset('css/medium.css')}}">
    <link rel="stylesheet" href="{{asset('css/small.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/minimal.css')}}">
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
    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if (!navigator.serviceWorker.controller) {
            navigator.serviceWorker.register("/sw.js").then(function (reg) {
                console.log("Service worker has been registered for scope: " + reg.scope);
            });
        }
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

        function search(event){

            let value = event.currentTarget.value.length > 0 ? event.currentTarget.value : false
            if(value.length > 0 || event.keyCode == 8 || event.keyCode == 46){
                axios.get(`/dashboard/personnel/search/${value}`).then((res) => {
                    // console.log(res.data);
                    let result = res.data

                    $('.results').css('display', 'flex')
                    // $('.results').append(`<p>${result.length} record(s) found.</p>`)
                    let rows = `<p disabled>${result.length} records found!</p>`
                    result.forEach((value, index, array) => {
                        rows +=`<a href="/dashboard/personnel/${value.id}/show">${value.name}</a>`
                    })
                    $('.results').html(rows)
                })
            }
        }

        $(document).ready(function() {

            $('.search_wrapper > div > i').click(function(){
                $('.search_wrapper > div > input').focus()
            })

            $('.search_wrapper > div > input').keyup(function(event){
                search(event)
            })
            $('.search_wrapper > div > input').focus(function(event){
                search(event)
            })

            $('body').click(function(evt){    
                if(evt.target.id == "results")
                    return;
                //For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
                if($(evt.target).closest('#results').length)
                    return;             

                //Do processing of click event here for every element except with id menu_content
                $('.results').css('display', 'none')
            });

            
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

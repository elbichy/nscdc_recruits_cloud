{{-- NAVBAR --}}
<div id="space-for-sidenav" class="navbar-fixed">
    <nav>
        <div class="nav-wrapper">
            {{-- Show View --}}
            <a href="#" id="show-side-nav" class="hide-on-med-and-down">
                <i class="small material-icons white-text">menu</i>
            </a>

            {{-- BREADCRUMB --}}
            <div class="left breadcrumbWrap hide-on-med-and-up">
                <a href="/{{request()->segment(1)}}/{{request()->segment(2)}}" class="breadcrumb">{{(request()->segment(2) == '') ? 'Dashbord' : ucfirst(request()->segment(2))}}</a>
                @if(request()->segment(3) != '')
                    <a href="/{{request()->segment(1)}}/{{ request()->segment(2) }}/{{request()->segment(3)}}" class="breadcrumb">{{ strtoupper(request()->segment(3)) }}</a>
                @endif
            </div>
            
            {{-- BREADCRUMB --}}
            <div class="left breadcrumbWrap hide-on-small-and-down">
                
                <a href="/dashboard" class="breadcrumb">DASHBOARD</a>

                {{-- <a href="/{{request()->segment(1)}}/{{request()->segment(2)}}/{{request()->segment(3)}}" class="breadcrumb">{{(request()->segment(3) == '') ? 'Dashbord' : ucfirst(request()->segment(3))}}</a> --}}

                @if(request()->segment(2) != '' && request()->segment(3) == '')
                    <a href="/{{request()->segment(1)}}/{{ request()->segment(2) }}" class="breadcrumb">{{ strtoupper(request()->segment(2)) }}</a>
                @endif

                @if(request()->segment(3) != '')
                    <a href="/{{request()->segment(1)}}/{{ request()->segment(2) }}" class="breadcrumb">{{ strtoupper(request()->segment(2)) }}</a>
                    <a href="/{{request()->segment(1)}}/{{ request()->segment(2) }}/{{request()->segment(3)}}" class="breadcrumb">{{ strtoupper(request()->segment(3)) }}</a>
                @endif
                
                @if(request()->segment(4) != '')
                    <a href="/{{request()->segment(1)}}/{{ request()->segment(2) }}/{{request()->segment(3)}}/{{request()->segment(4)}}" class="breadcrumb">{{ strtoupper(request()->segment(4)) }}</a>
                @endif
            
                @if(request()->segment(5) != '')
                    <a href="/{{request()->segment(1)}}/{{ request()->segment(2) }}/{{request()->segment(3)}}/{{request()->segment(4)}}/{{request()->segment(5)}}" class="breadcrumb">{{ strtoupper(request()->segment(5)) }}</a>
                @endif
            </div>
            <div class="search_wrapper">
                <div>
                    <input type="text" name="search" id="search" placeholder="Search">
                    <i class="material-icons">search</i>
                </div>
                <span class="results" id="results">
                    {{-- <a>results</a> --}}
                </span>
            </div>
            {{-- OTHER MENU RIGHT --}}
            <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only right"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @auth
            <!-- Dropdown Structure -->
            <ul id="dropdown1" class="dropdown-content">
                <li><a href="{{ route('personnel_show', auth()->user()->id) }}"><i class="material-icons left">person</i> Profile</a></li>
                <li class="divider"></li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-icons left">power_settings_new</i> Logout
                    </a>
                </li>
            </ul>
            
            <ul class="right hide-on-small-only">
                 <p style="padding-right:12px;"><a class="dropdown-trigger"  data-target="dropdown1" href="#!"  style="display: inline-block;">{{ auth()->user()->name }} <i class="material-icons right">arrow_drop_down</i></a></p>
            </ul>
            @endauth
        </div>
    </nav>
</div>

{{-- SIDE NAV --}}
<ul id="slide-out" class="sidenav sidenav-fixed" style="min-height: 100%; display: flex; flex-direction: column;">
    <div class="sideNavContainer">
        {{-- THE RED LOGO AREA --}}
        <li>
            <div class="user-view">
                {{-- Hide View --}}
                <a href="#" id="hide-side-nav" class="hide-on-med-and-down">
                    <i class="small material-icons white-text">close</i>
                </a>

                {{-- BUSINESS LOGO --}}
                <a href="#user"><img class="circle" src="{{asset('storage/nscdclargelogo.png')}}"></a>
            
                {{-- BUSINESS NAME --}}
                <a href="#name"><span class="white-text name">
                    Nigeria Security & Civil Defence Corps
                </span></a>

                {{-- BUSINESS BRANCH AND ADDRESS --}}
                <a href="#email"><span class="white-text email">Personnel Management Platform</span></a>
            </div>
        </li>
        <li class="{{(request()->segment(1) == 'dashboard' && request()->segment(2) == NULL) ? 'active' : ''}}">
            <a href="/dashboard"><i class="fal fa-tachometer-alt fa-2x"></i>DASHBOARD</a>
        </li>
        {{-- PERSONNEL --}}
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="{{ request()->segment(2) == 'personnel' ? 'active' :  ''}}">
                    <a style="padding:0 32px;" class="collapsible-header">
                        <i class="fal fa-users fa-2x"></i>PERSONNEL<i class="material-icons right">arrow_drop_down</i>
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="{{(request()->segment(3) == 'new') ? 'active' : ''}}">
                                <a href="{{ route('personnel_create') }}">Create new</a>
                            </li>
                            <li class="{{(request()->segment(3) == 'all') ? 'active' : ''}}">
                                <a href="{{ route('personnel_all') }}">Personnel</a>
                            </li>
                            <li class="{{(request()->segment(3) == 'privilages') ? 'active' : ''}}">
                                <a href="{{ route('personnel_privilages') }}">Privilages</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        {{-- DEPLOYMENTS --}}
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
            <li class="{{ request()->segment(3) == 'redeployment' ? 'active' : '' }}">
                <a style="padding:0 32px;" class="collapsible-header">
                    <i class="fal fa-people-arrows fa-2x"></i>DEPLOYMENTS<i class="material-icons right">arrow_drop_down</i>
                </a>
                <div class="collapsible-body">
                <ul>
                    <li class="{{(request()->segment(4) == 'create') ? 'active' : ''}}">
                        <a href="#">Add new</a>
                    </li>
                    <li class="{{(request()->segment(4) == 'today') ? 'active' : ''}}">
                        <a href="#">Today's Posting</a>
                    </li>
                    <li class="{{(request()->segment(4) == 'all') ? 'active' : ''}}">
                        <a href="#">All Postings</a>
                    </li>
                    <li class="{{(request()->segment(4) == 'reversed') ? 'active' : ''}}">
                        <a href="#">Trashed Postings</a>
                    </li>
                </ul>
                </div>
            </li>
            </ul>
        </li>
        {{-- PROGRESSION --}}
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
            <li class="{{ request()->segment(2) == 'progression' ? 'active' :  ''}}">
                <a style="padding:0 32px;" class="collapsible-header">
                    <i class="fal fa-sort-circle-up fa-2x"></i></i>PROGRESSION<i class="material-icons right">arrow_drop_down</i>
                </a>
                <div class="collapsible-body">
                    <ul>
                        <li class="{{(request()->segment(3) == 'appointment') ? 'active' : ''}}">
                            <a href="{{ route('manage_appointment') }}">Entry</a>
                        </li>
                        <li class="{{(request()->segment(3) == 'advancement') ? 'active' : ''}}">
                            <a href="#">Advancement</a>
                        </li>
                        <li class="{{(request()->segment(3) == 'promotion') ? 'active' : ''}}">
                            <a href="#">Promotion</a>
                        </li>
                        <li class="{{(request()->segment(3) == 'appointment') ? 'active' : ''}}">
                            <a href="#">Upgrading/Conversion</a>
                        </li>
                    </ul>
                </div>
            </li>
            </ul>
        </li>
    
        {{-- OTHER MENU RIGHT FOR MOBILE DEVICES --}}
        <li class="hide-on-med-and-up col s12" style="justify-self: flex-end; margin-top: auto;">
            <ul class="mobileLogout">
                <li class="logOutBtn">
                    <a href="{{ route('personnel_show', auth()->user()->id) }}">
                        PROFILE
                        <i style="margin:0; margin-left:6px;" class="fal fa-user fa-lg"></i>
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        LOGOUT
                        <i style="margin:0; margin-left:6px;" class="material-icons left">power_settings_new</i>
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            <ul class="col s4 right white-text" style="display:flex; justify-content:center; align-items:center; width:80%;">
                @if(auth()->check())
                    <a class="white-text" href="#">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</a>
                @endif
            </ul>
        </li>
        
    </div>
</ul>
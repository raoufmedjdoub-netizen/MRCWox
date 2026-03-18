<!DOCTYPE html>
<html lang="{{ Language::iso() }}">
<head>
    @include('Frontend.Layouts.partials.head')
    @yield('styles')
    <style>
        /* Default layout header overrides */
        #header {
            background: #1e2235 !important;
            border-bottom: none !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18) !important;
        }
        #header .navbar { background: transparent !important; border: none !important; }
        #header .navbar-brand img { max-height: 32px; }
        #header .nav > li > a {
            color: rgba(255,255,255,0.75) !important;
            font-family: 'Inter', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: color 0.2s, background 0.2s;
        }
        #header .nav > li > a:hover,
        #header .nav > li.open > a {
            color: #fff !important;
            background: rgba(255,255,255,0.08) !important;
        }
        #header .dropdown-menu {
            background: #1e2235 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 10px !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3) !important;
            padding: 6px !important;
        }
        #header .dropdown-menu > li > a {
            color: rgba(255,255,255,0.8) !important;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            border-radius: 7px !important;
            padding: 8px 12px !important;
        }
        #header .dropdown-menu > li > a:hover {
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }
        .content { background: #F4F6F8 !important; min-height: calc(100vh - 52px); }
    </style>
</head>
<body>

<div id="header">
    <nav class="navbar navbar-main">
        <div class="container-fluid">
            <div class="navbar-header">
                @if ( Appearance::assetFileExists('logo') )
                    <a class="navbar-brand" href="/" title="{{ Appearance::getSetting('server_name') }}"><img src="{{ Appearance::getAssetFileUrl('logo') }}"></a>
                @endif
            </div>

            <ul class="nav navbar-nav navbar-right">

                @yield('header-menu-items')

                <li class="dropdown">
                    <a href="javascript:" class="dropdown-toggle" role="button" id="dropMyAccount" data-toggle="dropdown" rel="tooltip" data-placement="bottom" title="{!!trans('front.my_account')!!}">
                        <span class="icon account"></span>
                        <span class="text">{!!trans('front.my_account')!!}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropMyAccount">
                        <li>
                            <a href="javascript:" data-url="{{ route('subscriptions.index') }}" data-modal="subscriptions_edit">
                                <span class="icon membership"></span>
                                <span class="text">{!!trans('front.subscriptions')!!}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{!!route('logout')!!}">
                                <span class="icon logout"></span>
                                <span class="text">{!!trans('global.log_out')!!}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="language-selection">
                    <a href="javascript:" data-url="{{ route('languages.index') }}" data-modal="language-selection">
                        <span class="icon">
                            <img src="{{ Language::flag() }}" alt="Language" class="img-thumbnail">
                        </span>
                        <span class="text">{!!trans('global.language')!!}</span>
                    </a>
                </li>
            </ul>

        </div>
    </nav>
</div>

<div class="content">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>

@include('Frontend.Layouts.partials.trans')

@yield('self-scripts')

<script src="{{ asset_resource('assets/js/core.js') }}" type="text/javascript"></script>
<script src="{{ asset_resource('assets/js/app.js') }}" type="text/javascript"></script>

@yield('scripts')

</body>
</html>
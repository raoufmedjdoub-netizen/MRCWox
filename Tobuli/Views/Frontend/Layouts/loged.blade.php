<!DOCTYPE html>
<html lang="{{ Language::iso() }}">
<head>
    @include('Frontend.Layouts.partials.head')
    @yield('styles')
    <style>
        /* ===== Layout: Hide old header, shift content right for new nav sidebar ===== */
        #header { height: 0 !important; min-height: 0 !important; overflow: hidden !important; padding: 0 !important; border: none !important; box-shadow: none !important; }
        #header .navbar { display: none !important; }

        /* Shift sidebar, map and bottombar by nav sidebar width (224px) using transform */
        #sidebar   { transform: translateX(224px) !important; top: 0 !important; }
        #mapWrap   { transform: translateX(224px) !important; top: 0 !important; height: 100vh !important; }
        #bottombar { transform: translateX(224px) !important; }

        /* ===== New Left Navigation Sidebar ===== */
        #left-nav {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 224px;
            background: #fff;
            z-index: 1002;
            border-right: 1px solid #DDE3E8;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        .lnav__logo {
            padding: 1.125rem 1rem;
            border-bottom: 1px solid #DDE3E8;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            min-height: 60px;
        }
        .lnav__logo img { max-height: 34px; max-width: 160px; object-fit: contain; }
        .lnav__logo-text { font-size: 0.9375rem; font-weight: 700; color: #30313D; text-decoration: none; }
        .lnav__scroll {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0;
            scrollbar-width: thin;
            scrollbar-color: #DDE3E8 transparent;
        }
        .lnav__scroll::-webkit-scrollbar { width: 3px; }
        .lnav__scroll::-webkit-scrollbar-thumb { background: #DDE3E8; border-radius: 2px; }
        .lnav__item {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 9px 12px !important;
            margin: 1px 8px !important;
            border-radius: 10px !important;
            color: #30313D !important;
            text-decoration: none !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            transition: background 0.15s !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            border: none !important;
            background: transparent !important;
        }
        .lnav__item:hover, .lnav__item:focus {
            background: #F3F5F7 !important;
            color: #30313D !important;
            text-decoration: none !important;
        }
        .lnav__item.lnav--active {
            background: linear-gradient(90deg, #6B7485 0%, #AAB3C0 100%) !important;
            color: #fff !important;
        }
        .lnav__icon {
            width: 20px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .lnav__icon .icon { display: block !important; margin: 0 !important; }
        .lnav__badge {
            margin-left: auto; background: #1CB4D9; color: #fff;
            font-size: 0.65rem; border-radius: 10px; padding: 1px 6px;
            min-width: 18px; text-align: center; display: none;
        }
        .lnav__badge:not(:empty) { display: block; }
        .lnav__sep {
            height: 1px; background: #DDE3E8;
            margin: 0.375rem 1rem;
        }
        .lnav__footer {
            border-top: 1px solid #DDE3E8;
            padding: 0.375rem 0;
            flex-shrink: 0;
        }

        /* ===== Content Sidebar — floating card ===== */
        #sidebar {
            background: #fff !important;
            border: 1px solid #DDE3E8 !important;
            border-right: none !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 24px rgba(0,0,0,0.13) !important;
            top: 12px !important;
            height: calc(100vh - 24px) !important;
            margin-left: 12px !important;
            overflow: hidden !important;
        }
        #sidebar .sidebar-content { background: #fff !important; }
        #sidebar .nav-tabs { background: #fff !important; border-bottom: 1px solid #DDE3E8 !important; }
        #sidebar .nav-tabs > li > a {
            color: #6b7280 !important;
            font-family: 'Inter', sans-serif; font-size: 0.8125rem; font-weight: 500;
            border: none !important; border-radius: 0 !important;
            padding: 12px 16px !important; background: transparent !important;
            transition: color 0.2s;
        }
        #sidebar .nav-tabs > li > a:hover { color: #30313D !important; background: #F3F5F7 !important; }
        #sidebar .nav-tabs > li.active > a,
        #sidebar .nav-tabs > li.active > a:focus {
            color: #30313D !important; background: transparent !important;
            border-bottom: 2px solid #1CB4D9 !important; font-weight: 600;
        }
        #sidebar .btn-collapse { background: #F3F5F7 !important; border-radius: 0 8px 8px 0 !important; border: 1px solid #DDE3E8 !important; }
        #sidebar .btn-collapse i { border-color: #6b7280 !important; }
        #sidebar .btn-collapse:hover { background: #DDE3E8 !important; }

        /* Sidebar search inputs */
        #sidebar .form-control, #sidebar input[type="text"], #sidebar input[type="search"] {
            background: #F9FAFB !important; border: 1.5px solid #DDE3E8 !important;
            color: #30313D !important; border-radius: 8px !important; font-family: 'Inter', sans-serif;
        }
        #sidebar .form-control:focus {
            border-color: #71a8e6 !important; background: #fff !important;
            box-shadow: 0 0 0 3px rgba(113,168,230,0.18) !important;
        }

        /* Map controls */
        .map-controls .btn {
            background: #fff !important; border: 1px solid #DDE3E8 !important;
            color: #30313D !important; border-radius: 8px !important;
            box-shadow: 0 2px 6px rgba(60,66,87,0.1) !important;
        }
        .map-controls .btn:hover { background: #F3F5F7 !important; }

        /* Bottom bar */
        #bottombar { background: #fff !important; border-top: 1px solid #DDE3E8 !important; box-shadow: 0 -4px 12px rgba(60,66,87,0.08) !important; }

        /* ===== Collapse toggle button ===== */
        #lnav-toggle {
            position: absolute;
            top: 50%; right: -12px;
            transform: translateY(-50%);
            width: 24px; height: 24px;
            background: #fff;
            border: 1px solid #DDE3E8;
            border-radius: 50%;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            z-index: 1003;
            box-shadow: 0 1px 4px rgba(0,0,0,0.12);
            transition: background 0.15s;
            padding: 0;
            line-height: 1;
            font-size: 10px;
            color: #6B7485;
        }
        #lnav-toggle:hover { background: #F3F5F7; }

        /* ===== Collapsed state ===== */
        #left-nav { transition: width 0.22s cubic-bezier(.4,0,.2,1); }
        #left-nav.lnav--collapsed { width: 60px; }
        #left-nav.lnav--collapsed .lnav__logo img { max-width: 32px; max-height: 32px; }
        #left-nav.lnav--collapsed .lnav__logo-text { display: none; }
        #left-nav.lnav--collapsed .lnav__item > span:not(.lnav__icon) { display: none !important; }
        #left-nav.lnav--collapsed .lnav__badge { display: none !important; }
        #left-nav.lnav--collapsed .lnav__item {
            justify-content: center !important;
            padding: 10px 0 !important;
            margin: 1px 6px !important;
        }
        #left-nav.lnav--collapsed .lnav__logo {
            justify-content: center;
            padding: 1.125rem 0.5rem;
        }
        #left-nav.lnav--collapsed .lnav__sep { margin: 0.375rem 10px; }
        #left-nav.lnav--collapsed .lnav__footer .lnav__item > span:not(.lnav__icon) { display: none !important; }

        /* Tooltip on hover when collapsed */
        #left-nav.lnav--collapsed .lnav__item { position: relative; }
        #left-nav.lnav--collapsed .lnav__item[data-label]:hover::after {
            content: attr(data-label);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%; transform: translateY(-50%);
            background: #30313D; color: #fff;
            font-size: 0.75rem; font-weight: 500;
            padding: 4px 10px; border-radius: 6px;
            white-space: nowrap; pointer-events: none;
            z-index: 9999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
    </style>
</head>

<body style="overflow: hidden;">

{{-- ===== New Vertical Left Navigation ===== --}}
<div id="left-nav">
    {{-- Logo --}}
    <div class="lnav__logo" style="position:relative;">
        @if(Appearance::assetFileExists('logo'))
            <a href="/" title="{{ Appearance::getSetting('server_name') }}">
                <img src="{{ Appearance::getAssetFileUrl('logo') }}" alt="{{ Appearance::getSetting('server_name') }}">
            </a>
        @else
            <a href="/" class="lnav__logo-text">{{ Appearance::getSetting('server_name') ?: config('app.name') }}</a>
        @endif
        <button id="lnav-toggle" title="Réduire / Agrandir">&#8249;</button>
    </div>

    {{-- Scrollable nav items --}}
    <div class="lnav__scroll">

        {{-- Objects / Trackers --}}
        <a href="#objects_tab" class="lnav__item lnav--active" data-toggle="tab" onclick="app.openTab('objects_tab');">
            <span class="lnav__icon"><span class="icon devices"></span></span>
            <span>{!!trans('front.objects')!!}</span>
        </a>

        {{-- Events / History --}}
        @if(Auth::user()->perm('events', 'view'))
        <a href="#events_tab" class="lnav__item" data-toggle="tab" onclick="app.openTab('events_tab');">
            <span class="lnav__icon"><span class="icon events"></span></span>
            <span>{!!trans('front.events')!!}</span>
        </a>
        @endif

        {{-- History --}}
        @if(Auth::user()->perm('history', 'view'))
        <a href="#history_tab" class="lnav__item" data-toggle="tab" onclick="app.openTab('history_tab');">
            <span class="lnav__icon"><span class="icon history"></span></span>
            <span>{!!trans('front.history')!!}</span>
        </a>
        @endif

        <div class="lnav__sep"></div>

        {{-- Alerts --}}
        @if(Auth::User()->perm('alerts', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{!! route('alerts.index_modal') !!}" data-modal="alerts">
            <span class="lnav__icon"><span class="icon alerts"></span></span>
            <span>{!!trans('front.alerts')!!}</span>
        </a>
        @endif

        {{-- Geofences --}}
        @if(Auth::User()->perm('geofences', 'view'))
        <a href="javascript:" class="lnav__item" onclick="app.geofences.list();app.openTab('geofencing_tab');">
            <span class="lnav__icon"><span class="icon geofences"></span></span>
            <span>{!!trans('front.geofencing')!!}</span>
        </a>
        @endif

        {{-- Routes --}}
        @if(Auth::User()->perm('routes', 'view'))
        <a href="javascript:" class="lnav__item" onclick="app.routes.list();app.openTab('routes_tab');">
            <span class="lnav__icon"><span class="icon routes"></span></span>
            <span>{!!trans('front.routes')!!}</span>
        </a>
        @endif

        {{-- POI --}}
        @if(Auth::User()->perm('poi', 'view'))
        <a href="javascript:" class="lnav__item" onclick="app.pois.list();app.openTab('pois_tab');">
            <span class="lnav__icon"><span class="icon poi"></span></span>
            <span>{!!trans('front.poi')!!}</span>
        </a>
        @endif

        {{-- Reports --}}
        @if(Auth::User()->perm('reports', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{!!route('reports.create')!!}" data-modal="reports_create">
            <span class="lnav__icon"><span class="icon reports"></span></span>
            <span>{!!trans('front.reports')!!}</span>
        </a>
        @endif

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="lnav__item" onclick="event.preventDefault(); app.dashboard.init();">
            <span class="lnav__icon"><span class="icon dashboard"></span></span>
            <span>{!!trans('front.dashboard')!!}</span>
        </a>

        <div class="lnav__sep"></div>

        {{-- Chat --}}
        @if(Auth::User()->perm('chat', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{!!route('chat.index')!!}" data-modal="chat">
            <span class="lnav__icon"><span class="icon chat"></span></span>
            <span>{!!trans('front.chat')!!}</span>
            <span id="unread-msg-count" class="lnav__badge"></span>
        </a>
        @endif

        {{-- Tasks --}}
        @if(Auth::User()->perm('tasks', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('tasks.index') }}" data-modal="tasks">
            <span class="lnav__icon"><span class="icon task"></span></span>
            <span>{!!trans('front.tasks')!!}</span>
        </a>
        @endif

        {{-- Maintenance --}}
        @if(Auth::User()->perm('maintenance', 'view'))
        <a href="{!!route('maintenance.index')!!}" class="lnav__item" target="_blank">
            <span class="lnav__icon"><span class="icon services"></span></span>
            <span>{!!trans('front.maintenance')!!}</span>
        </a>
        @endif

        {{-- Expenses --}}
        @if(Auth::User()->perm('device_expenses', 'view') && expensesTypesExist())
        <a href="javascript:" class="lnav__item" data-url="{{ route('device_expenses.modal') }}" data-modal="devices_expenses">
            <span class="lnav__icon"><span class="icon money"></span></span>
            <span>{!!trans('front.expenses')!!}</span>
        </a>
        @endif

        {{-- Sharing --}}
        @if(Auth::User()->perm('sharing', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('sharing.index') }}" data-modal="sharing">
            <span class="lnav__icon"><span class="icon sharing"></span></span>
            <span>{!!trans('front.sharing')!!}</span>
        </a>
        @endif

        {{-- Send Command --}}
        @if(Auth::User()->perm('send_command', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('send_command.create') }}" data-modal="send_command">
            <span class="lnav__icon"><span class="icon send-command"></span></span>
            <span>{!!trans('front.send_command')!!}</span>
        </a>
        @endif

        {{-- Camera --}}
        @if(Auth::User()->perm('camera', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('device_media.create') }}" data-modal="camera_photos">
            <span class="lnav__icon"><span class="icon camera"></span></span>
            <span>{!!trans('front.camera')!!}</span>
        </a>
        @endif

        {{-- Forwards --}}
        @if(Auth::User()->perm('forwards', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('forwards.index') }}" data-modal="forwards">
            <span class="lnav__icon"><span class="icon forwards"></span></span>
            <span>{!! trans('front.forwards') !!}</span>
        </a>
        @endif

        {{-- Call Actions --}}
        @if(Auth::user()->perm('call_actions', 'view') && Auth::user()->perm('events', 'view'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('call_actions.index') }}" data-modal="call_actions">
            <span class="lnav__icon"><span class="icon call_action"></span></span>
            <span>{!! trans('front.call_actions') !!}</span>
        </a>
        @endif

        {{-- Device Config --}}
        @if(Auth::user()->able('configure_device'))
        <a href="javascript:" class="lnav__item" data-url="{{ route('device_config.index') }}" data-modal="device_config">
            <span class="lnav__icon"><span class="icon devices"></span></span>
            <span>{!!trans('front.device_configuration')!!}</span>
        </a>
        @endif

        {{-- External URL --}}
        @if(config('addon.external_url') && settings('external_url.enabled') && Auth::user()->perm('external_url', 'view'))
        <a href="{!! (new \Tobuli\Helpers\TextBuilder\UserExternalUrlBuilder())->build(settings('external_url.external_url'), Auth::user()) !!}" class="lnav__item" target="_blank">
            <span class="lnav__icon"><span class="icon external-link"></span></span>
            <span>{!!trans('front.external_url')!!}</span>
        </a>
        @endif

        {{-- Admin panel link --}}
        @if(isAdmin())
        <div class="lnav__sep"></div>
        <a href="{!!route('admin')!!}" class="lnav__item">
            <span class="lnav__icon"><span class="icon admin"></span></span>
            <span>{!!trans('global.admin')!!}</span>
        </a>
        @endif

    </div>{{-- /.lnav__scroll --}}

    {{-- Footer --}}
    <div class="lnav__footer">
        {{-- Setup --}}
        <a href="javascript:" class="lnav__item" data-url="{!!route('my_account_settings.edit')!!}" data-modal="my_account_settings_edit">
            <span class="lnav__icon"><span class="icon setup"></span></span>
            <span>{!!trans('front.setup')!!}</span>
        </a>
        {{-- My Account --}}
        <a href="javascript:" class="lnav__item" data-url="{{ route('my_account.edit') }}" data-modal="subscriptions_edit">
            <span class="lnav__icon"><span class="icon account"></span></span>
            <span>{!!trans('front.my_account')!!}</span>
        </a>
        {{-- Language --}}
        <a href="javascript:" class="lnav__item" data-url="{{ route('languages.index') }}" data-modal="language-selection">
            <span class="lnav__icon">
                <img src="{{ Language::flag() }}" alt="Language" style="width:18px;height:12px;object-fit:cover;border-radius:2px;">
            </span>
            <span>{!!trans('global.language')!!}</span>
        </a>
        {{-- Ruler --}}
        <a href="#objects_tab" class="lnav__item" data-toggle="tab" onclick="app.ruler();">
            <span class="lnav__icon"><span class="icon ruler"></span></span>
            <span>{!!trans('front.ruler')!!}</span>
        </a>
        {{-- Logout --}}
        <a href="{!!route('logout')!!}" class="lnav__item" style="color: #e53e3e !important;">
            <span class="lnav__icon"><span class="icon logout"></span></span>
            <span>{!!trans('global.log_out')!!}</span>
        </a>
    </div>
</div>
{{-- ===== End Left Navigation ===== --}}

@include('Frontend.Popups.index_banners_top')
@include('Frontend.Layouts.partials.loading')
@include('Frontend.Layouts.partials.header')

<div id="sidebar" class="sidebar left">

    <a class="btn-collapse" onclick="app.changeSetting('toggleSidebar');"><i></i></a>

    <div class="sidebar-content">
        <ul class="nav nav-tabs nav-default">
            <li role="presentation" class="active">
                <a href="#objects_tab" type="button" data-toggle="tab">{!!trans('front.objects')!!}</a>
            </li>
            @if(Auth::user()->perm('events', 'view'))
                <li role="presentation">
                    <a href="#events_tab" type="button" data-toggle="tab">{!!trans('front.events')!!}</a>
                </li>
            @endif
            <li role="presentation">
                <a href="#history_tab" type="button" data-toggle="tab">{!!trans('front.history')!!}</a>
            </li>
            {{-- hidden, import for correct tab work (shown, hidden evenets) --}}
            <li role="presentation" class="hidden"><a href="#alerts_tab" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#geofencing_tab" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#geofencing_create" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#geofencing_edit" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#routes_tab" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#routes_create" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#routes_edit" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#pois_tab" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#pois_create" data-toggle="tab"></a></li>
            <li role="presentation" class="hidden"><a href="#pois_edit" data-toggle="tab"></a></li>
        </ul>

        @yield('items')
    </div>
</div>

<div id="mapWrap">
    <div id="map"></div>
    <div id="map-controls" class="map-controls top-right">
        <div>
            <div class="btn-group-vertical" role="group">
                <button type="button" class="btn" onclick="app.mapFull();">
                    <span class="icon map-expand"></span>
                </button>
            </div>
        </div>

        <div id="map-controls-tile">
            <div class="btn-group-vertical" data-position="fixed" role="group">
                <button type="button" class="btn" onClick="$('.leaflet-control-layers').toggleClass('leaflet-control-layers-expanded');">
                    <span class="icon map-change"></span>
                </button>
            </div>
        </div>

        <div>
            <div class="btn-group-vertical" role="group">
                <button type="button" class="btn" onclick="app.zoomIn();"><span class="icon zoomIn"></span></button>
                <button type="button" class="btn" onclick="app.zoomOut();"><span class="icon zoomOut"></span></button>
            </div>
        </div>

        <div id="map-controls-layers">
            <div class="btn-group-vertical" role="group" data-toggle="buttons">
                <label class="btn">
                    <input id="clusterDevice" type="checkbox" autocomplete="off" onchange="app.changeSetting('clusterDevice', this.checked);">
                    <span class="icon group-devices"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.fit_objects')!!}">
                    <input id="fitBounds" type="checkbox" autocomplete="off" onchange="app.devices.toggleFitBounds(this.checked);">
                    <span class="icon fitBounds"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.objects')!!}">
                    <input id="showDevice" type="checkbox" autocomplete="off" onchange="app.changeSetting('showDevice', this.checked);">
                    <span class="icon devices"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.geofences')!!}">
                    <input id="showGeofences" type="checkbox" autocomplete="off" onchange="app.changeSetting('showGeofences', this.checked);">
                    <span class="icon geofences"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.routes')!!}">
                    <input id="showRoutes" type="checkbox" autocomplete="off" onchange="app.changeSetting('showRoutes', this.checked);">
                    <span class="icon routes"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.poi')!!}">
                    <input id="showPoi" type="checkbox" autocomplete="off" onchange="app.changeSetting('showPoi', this.checked);">
                    <span class="icon poi"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.show_names')!!}">
                    <input id="showNames" type="checkbox" autocomplete="off" onchange="app.changeSetting('showNames', this.checked);">
                    <span class="icon show-name"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.show_tails')!!}">
                    <input id="showTail" type="checkbox" autocomplete="off" onchange="app.changeSetting('showTail', this.checked);">
                    <span class="icon show-tail"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.live_traffic')!!}">
                    <input id="showTraffic" type="checkbox" autocomplete="off" onchange="app.changeSetting('showTraffic', this.checked);">
                    <span class="icon traffic"></span>
                </label>

                @if (auth()->user()->able('flights_info'))
                    <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.live_aircraft')!!}">
                        <input id="showAircraft" type="checkbox" autocomplete="off" onchange="app.changeSetting('showAircraft', this.checked);">
                        <span class="fa fa-plane"></span>
                    </label>
                @endif

                @if (config('addon.html_geolocation'))
                    <button type="button" class="btn" onclick="app.getMyLocation();"><span class="icon icon-fa fa-dot-circle-o"></span></button>
                @endif
            </div>
        </div>

        <div id="history-control-layers" style="display: none;">
            <div class="btn-group-vertical" role="group" data-toggle="buttons">
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.route')!!}">
                    <input id="showHistoryRoute" type="checkbox" autocomplete="off" onchange="app.changeSetting('showHistoryRoute', this.checked);">
                    <span class="icon routes"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.arrows')!!}">
                    <input id="showHistoryArrow" type="checkbox" autocomplete="off" onchange="app.changeSetting('showHistoryArrow', this.checked);">
                    <span class="icon device"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.stops')!!}">
                    <input id="showHistoryStop" type="checkbox" autocomplete="off" onchange="app.changeSetting('showHistoryStop', this.checked);">
                    <span class="icon parking"></span>
                </label>
                <label class="btn" data-toggle="tooltip" data-placement="left" title="{!!trans('front.events')!!}">
                    <input id="showHistoryEvent" type="checkbox" autocomplete="off" onchange="app.changeSetting('showHistoryEvent', this.checked);">
                    <span class="icon event"></span>
                </label>
            </div>
        </div>
    </div>
</div>

<a class="ajax-popup-link hidden"></a>
<input id="upload_file" type="file" style="display: none;" onchange=""/>

@include('Frontend.Layouts.partials.trans')

@yield('self-scripts')

<script src="{{ asset_resource('assets/js/core.js') }}" type="text/javascript"></script>
<script src="{{ asset_resource('assets/js/app.js') }}" type="text/javascript"></script>

<div id="bottombar">
    @include('Frontend.History.bottom')
    @include('Frontend.Widgets.index')
</div>

<div id="conversations"></div>


<script type="text/javascript">
    var handlers = L.drawLocal.draw.handlers;
    handlers.polygon.tooltip.start = '{{ trans('front.click_to_start_drawing_shape') }}';
    handlers.polygon.tooltip.cont = '{{ trans('front.click_to_continue_drawing_shape') }}';
    handlers.polygon.tooltip.end = '{{ trans('front.click_first_point_to_close_this_shape') }}';
    handlers.polyline.error = '{{ trans('front.shape_edges_cannot_cross') }}';
    handlers.polyline.tooltip.start = '{{ trans('front.click_to_start_drawing_line') }}';
    handlers.polyline.tooltip.cont = '{{ trans('front.click_to_continue_drawing_line') }}';
    handlers.polyline.tooltip.end = '{{ trans('front.click_last_point_to_finish_line') }}';

    handlers.circle.radius = '{{ trans('front.radius') }}';
    handlers.circle.tooltip.start = '{{ trans('front.click_and_drag_to_draw_shape') }}';
    handlers.simpleshape.tooltip.end = '{{ trans('front.release_mouse_to_finish_drawing') }}';
</script>

@yield('scripts')
@include('Frontend.Layouts.partials.app')

<script type="text/javascript">
    $(window).on("load", function() {
        app.init();
        @if($dashboard)
            app.dashboard.init();
        @endif
    });
</script>

<div class="modal" id="js-confirm-link" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                loading
            </div>
            <div class="modal-footer" style="margin-top: 0">
                <button type="button" value="confirm" class="btn btn-primary btn-main submit js-confirm-link-yes">{{ trans('admin.confirm') }}</button>
                <button type="button" value="cancel" class="btn btn-default" data-dismiss="modal">{{ trans('admin.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@include('Frontend.Popups.index')

<script type="text/javascript">
(function() {
    var NAV_FULL = 224;
    var NAV_MINI = 60;
    var nav  = document.getElementById('left-nav');
    var btn  = document.getElementById('lnav-toggle');
    var styleEl = document.createElement('style');
    styleEl.id = 'lnav-shift';
    document.head.appendChild(styleEl);

    function applyShift(collapsed) {
        var w = collapsed ? NAV_MINI : NAV_FULL;
        styleEl.textContent =
            '#sidebar   { transform: translateX(' + w + 'px) !important; }\n' +
            '#mapWrap   { transform: translateX(' + w + 'px) !important; }\n' +
            '#bottombar { transform: translateX(' + w + 'px) !important; }';
        btn.innerHTML = collapsed ? '&#8250;' : '&#8249;';
    }

    function toggle() {
        var collapsed = nav.classList.toggle('lnav--collapsed');
        localStorage.setItem('lnav_collapsed', collapsed ? '1' : '0');
        applyShift(collapsed);
    }

    /* Restore saved state */
    if (localStorage.getItem('lnav_collapsed') === '1') {
        nav.classList.add('lnav--collapsed');
        applyShift(true);
    }

    if (btn) btn.addEventListener('click', toggle);
})();
</script>
</body>
</html>
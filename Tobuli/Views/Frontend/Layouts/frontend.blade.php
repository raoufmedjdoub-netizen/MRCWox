<!doctype html>
<html lang="{{ Language::iso() }}" class="no-js" itemscope itemtype="http://schema.org/WebSite">
<head>
    @include('Frontend.Layouts.partials.head')

    <script src="{{ asset_resource('assets/js/core.js') }}"></script>

    @yield('styles')

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body.sign-in-layout {
            font-family: 'Inter', 'SF Pro Text', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F4F6F8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(60,66,87,0.12), 0 5px 15px rgba(0,0,0,0.06);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
        }
        .login-card .logo-wrap {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .login-card .logo-wrap img {
            max-height: 48px;
            max-width: 200px;
        }
        .login-card .login-title {
            font-size: 1.375rem;
            font-weight: 600;
            color: #30313D;
            text-align: center;
            margin-bottom: 0.25rem;
        }
        .login-card .login-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .login-card .form-group { margin-bottom: 1rem; }
        .login-card .form-control {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            font-size: 0.9375rem;
            font-family: inherit;
            color: #30313D;
            background: #F9FAFB;
            border: 1.5px solid #DDE3E8;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .login-card .form-control:focus {
            border-color: #71a8e6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(113,168,230,0.18);
        }
        .login-card .btn-primary {
            display: block;
            width: 100%;
            height: 44px;
            background: linear-gradient(135deg, #1CB4D9 0%, #71a8e6 100%);
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: inherit;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            margin-top: 0.25rem;
        }
        .login-card .btn-primary:hover { opacity: 0.9; }
        .login-card .btn-primary:active { transform: scale(0.99); }
        .login-card .btn-default {
            display: block;
            width: 100%;
            height: 40px;
            background: transparent;
            color: #6b7280;
            font-size: 0.875rem;
            font-family: inherit;
            border: 1.5px solid #DDE3E8;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            line-height: 38px;
            transition: background 0.2s, color 0.2s;
            margin-top: 0.5rem;
        }
        .login-card .btn-default:hover { background: #F4F6F8; color: #30313D; }
        .login-card .divider {
            border: none;
            border-top: 1px solid #DDE3E8;
            margin: 1.25rem 0;
        }
        .login-card .checkbox label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
            cursor: pointer;
        }
        .login-card .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .login-card .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .login-card .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .login-card .sign-in-text {
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
            margin-top: 1.25rem;
        }
        .app-links-row {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            margin-top: 1.25rem;
        }
        .app-links-row img { height: 36px; }
        @if ( Appearance::getSetting('login_page_background_color') )
        body.sign-in-layout { background-color: {{ Appearance::getSetting('login_page_background_color') }} !important; }
        @endif
        @if ( Appearance::assetFileExists('background') )
        body.sign-in-layout { background-image: url( {!! Appearance::getAssetFileUrl('background') !!} ); background-size: cover; background-position: center; }
        @endif
    </style>
</head>

<body class="sign-in-layout">

<div class="login-card">
    @yield('content')
</div>

</body>
</html>
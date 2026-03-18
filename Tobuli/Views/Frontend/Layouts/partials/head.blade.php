<title>{{ Appearance::getSetting('server_name') }}</title>

<base href="{{ url('/') }}">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="app-version" content="{{ config('tobuli.version') }}">
<meta name="app-build" content="{{ config('app.build') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ Appearance::getSetting('server_description') }}">
<link rel="shortcut icon" href="{{ Appearance::getAssetFileUrl('favicon') }}" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    corePlugins: { preflight: false },
    prefix: 'tw-',
    theme: {
        extend: {
            colors: {
                primary: '#71a8e6',
                'primary-dark': '#5a94d8',
                sky: '#1CB4D9',
                'theme-text': '#30313D',
                'theme-border': '#DDE3E8',
                'theme-bg': '#F4F6F8',
                'theme-sidebar': '#1e2235',
                'theme-sidebar-hover': '#2a2f4a',
            },
            fontFamily: {
                sans: ['Inter', 'SF Pro Text', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
            },
            borderRadius: {
                DEFAULT: '10px',
                lg: '12px',
            },
            boxShadow: {
                panel: '0 15px 35px rgba(60,66,87,0.12)',
                card: '0 2px 8px rgba(60,66,87,0.08)',
            },
        }
    }
}
</script>
<style>
    :root {
        --theme-primary: #71a8e6;
        --theme-sky: #1CB4D9;
        --theme-text: #30313D;
        --theme-border: #DDE3E8;
        --theme-bg: #F4F6F8;
        --theme-sidebar: #1e2235;
        --font-main: 'Inter', 'SF Pro Text', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    body { font-family: var(--font-main) !important; color: var(--theme-text) !important; }
</style>
<link rel="stylesheet" href="{{ asset_resource('assets/css/'.Appearance::getSetting('template_color').'.css') }}">
@if (Language::dir() == 'rtl')
    <link rel="stylesheet" href="{{ asset_resource('assets/css/rtl.css') }}">
@endif
@if (Appearance::assetFileExists('css'))
    <link rel="stylesheet" href="{{ Appearance::getAssetFileUrl('css') }}">
@endif
@if (Appearance::assetFileExists('js'))
    <script src="{{ Appearance::getAssetFileUrl('js') }}" type="text/javascript" defer></script>
@endif

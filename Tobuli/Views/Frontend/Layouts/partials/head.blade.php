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
<link rel="stylesheet" href="{{ asset_resource('assets/css/'.Appearance::getSetting('template_color').'.css') }}">
@if (str_starts_with(Appearance::getSetting('template_color'), 'light-'))
    <link rel="stylesheet" href="{{ asset_resource('assets/css/overrides.css') }}">
@endif
@if (Language::dir() == 'rtl')
    <link rel="stylesheet" href="{{ asset_resource('assets/css/rtl.css') }}">
@endif
@if (Appearance::assetFileExists('css'))
    <link rel="stylesheet" href="{{ Appearance::getAssetFileUrl('css') }}">
@endif
@if (Appearance::assetFileExists('js'))
    <script src="{{ Appearance::getAssetFileUrl('js') }}" type="text/javascript" defer></script>
@endif
@if (str_starts_with(Appearance::getSetting('template_color'), 'light-'))
<script>
  /* Dark mode — apply saved preference before paint to avoid flash */
  (function(){
    if (localStorage.getItem('gpswox-theme') === 'dark') {
      document.documentElement.setAttribute('data-theme', 'dark');
    }
  })();
  function toggleDarkMode() {
    var html = document.documentElement;
    var isDark = html.getAttribute('data-theme') === 'dark';
    if (isDark) {
      html.removeAttribute('data-theme');
      localStorage.setItem('gpswox-theme', 'light');
      document.getElementById('dark-mode-icon').innerHTML = '☾';
    } else {
      html.setAttribute('data-theme', 'dark');
      localStorage.setItem('gpswox-theme', 'dark');
      document.getElementById('dark-mode-icon').innerHTML = '☀';
    }
  }
  /* Update icon on load */
  document.addEventListener('DOMContentLoaded', function() {
    var icon = document.getElementById('dark-mode-icon');
    if (icon) {
      icon.innerHTML = document.documentElement.getAttribute('data-theme') === 'dark' ? '☀' : '☾';
    }
  });
</script>
@endif

@extends('Frontend.Layouts.frontend')

@section('content')
    @if ( Appearance::assetFileExists('logo-main') )
    <div class="logo-wrap">
        <a href="{{ route('home') }}">
            <img src="{{ Appearance::getAssetFileUrl('logo-main') }}" alt="Logo">
        </a>
    </div>
    @endif

    <div class="login-title">
        @if ( Appearance::getSetting('welcome_text') )
            {!! Appearance::getSetting('welcome_text') !!}
        @else
            {{ trans('front.sign_in') }}
        @endif
    </div>
    <div class="login-subtitle">{{ Appearance::getSetting('server_name') }}</div>

    @if (Session::has('success'))
        <div class="alert alert-success">
            {!! Session::get('success') !!}
        </div>
    @endif

    @if (Session::has('message'))
        <div class="alert alert-danger">
            {!! Session::get('message') !!}
        </div>
    @endif

    @includeWhen(count($internalAuths), 'front::Login.partials.internal', $internalAuths)

    @if(count($externalAuths))
        <hr class="divider">
        <p style="text-align:center;font-size:0.8125rem;color:#9ca3af;margin-bottom:0.75rem;">{{ trans('front.external_login') }}</p>
        @foreach($externalAuths as $auth)
            <div class="form-group">
                @include("front::Login.partials.{$auth->getKey()}")
            </div>
        @endforeach
    @endif

    @if ( Appearance::getSetting('google_play_link') || Appearance::getSetting('apple_store_link') )
        <div class="app-links-row">
            @if ( Appearance::getSetting('google_play_link') )
                <a href="{{ Appearance::getSetting('google_play_link') }}" target="_blank">
                    <img src="{{ asset('assets/images/google-play.png') }}" alt="Google Play" />
                </a>
            @endif
            @if ( Appearance::getSetting('apple_store_link') )
                <a href="{{ Appearance::getSetting('apple_store_link') }}" target="_blank">
                    <img src="{{ asset('assets/images/apple-store.png') }}" alt="App Store" />
                </a>
            @endif
        </div>
    @endif

    @if ( Appearance::getSetting('bottom_text') )
        <p class="sign-in-text">{!! Appearance::getSetting('bottom_text') !!}</p>
    @endif
@stop
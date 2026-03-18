{!! Form::open(array('route' => 'authentication.store', 'class' => 'form')) !!}

@php /** @var \Tobuli\Services\Auth\InternalInterface[] $internalAuths */@endphp

<div class="form-group">
    {!! Form::text(
        'identifier',
        null,
        [
            'class' => 'form-control',
            'placeholder' => implode(' / ', array_map(fn ($auth) => $auth->getInputTitle(), $internalAuths)),
            'id' => 'sign-in-form-email',
            'autocomplete' => 'username',
        ]
    ) !!}
</div>

<div class="form-group">
    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('validation.attributes.password'), 'id' => 'sign-in-form-password', 'autocomplete' => 'current-password']) !!}
</div>

@include('Frontend.Captcha.form')

@if (config('session.remember_me'))
    <div class="form-group">
        <div class="checkbox">
            {!! Form::checkbox('remember_me', 1, ['id' => 'sign-in-form-remember']) !!}
            <label for="sign-in-form-remember">{!! trans('validation.attributes.remember_me') !!}</label>
        </div>
    </div>
@endif

<button class="btn btn-primary" name="Submit" value="Login" type="Submit">{!! trans('front.sign_in') !!}</button>

<hr class="divider">

<a href="{!! route('password_reminder.create') !!}" class="btn btn-default">{!! trans('front.cant_sign_in') !!}</a>

@if (settings('main_settings.allow_users_registration'))
    <a href="{!! route('registration.create') !!}" class="btn btn-default">{!! trans('front.not_a_member') !!}</a>
@endif

{!! Form::close() !!}

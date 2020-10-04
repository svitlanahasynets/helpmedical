@extends('layouts.admin.login')

@section('title')
    <title>{{ trans('auth.login') }}</title>
@endsection

@section('content')

<div class="login-container">
    <div class="logo">
        <img src="/assets/img/login/logo.png" />
    </div>
    <div class="main-box">
        <div class="title">
            {{ trans('auth.title') }}
        </div>

        <div class="alert alert-danger" style="display:none;">
            <button class="close" data-close="alert"></button>
            <span></span>
        </div>

        <form class="login-form" role="form" method="POST" action="{{ route('admin.login') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input id="username" type="name" placeholder="{{ trans('auth.username') }}" class="form-control" name="username" value="{{ old('username') }}">                    
                </div>
            </div>

            <div class="form-group">
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input id="password" type="password" placeholder="{{ trans('auth.password') }}" class="form-control" name="password">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ trans('auth.remember') }}
                            </label>
                        </div>
                    </div>        
                </div>
                <div class="col-sm-5">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">
                            {{ trans('auth.login') }}
                            <i class="icon-arrow-right"></i>
                        </button>
                    </div>        
                </div>
            </div>
            

            
        </form>
    </div>
    <div class="copyright">{{ trans('global.copyright') }}</div>
</div>
@endsection

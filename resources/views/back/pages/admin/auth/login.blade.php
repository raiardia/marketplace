@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Admin Login')
@section('content')

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Admin Login</h2>
    </div>
    <form>

        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Username">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>

        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" placeholder="**********">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>

        <div class="row pb-30">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Remember</label>
                </div>
            </div>
            <div class="col-6">
                <div class="forgot-password text-right">
                    <a href="forgot-password.html">Forgot Password</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    {{-- Ganti menjadi form submit jika perlu --}}
                    <a class="btn btn-primary btn-lg btn-block" href="index.html">Sign In</a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

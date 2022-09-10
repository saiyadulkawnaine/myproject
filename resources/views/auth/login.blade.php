@extends('layouts.app')

@section('content')
<div class="limiter">
        <div class="container-login100" style="background-image: url('images/login/bg-03.jpg');">
            <div class="wrap-login100 p-t-30 p-b-50">
                
                <form class="login100-form validate-form p-b-33 p-t-5" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="wrap-input100 validate-input" data-validate = "Enter username">
                        <label>E-Mail</label><input id="email" class="input100" type="text" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <label>Password</label><input id="password" class="input100" type="password" name="password" required>

                        @if ($errors->has('password'))
                        <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="container-login100-form-btn m-t-10">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

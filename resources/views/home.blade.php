@extends('layouts.app')

@section('content')
<div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-30 p-b-50">
                
                    @if (session('status'))
                    <div class="alert alert-success">
                    {{ session('status') }}
                    </div>
                    @endif

                    You are logged in! <a href="<?php echo url('/');?>" style="font-weight: bold;color: #000; font-size: 18px">Continue</a>  or 
                    <a class="easyui-linkbutton c5" style="font-weight: bold;color: #000; font-size: 18px" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    </form>
                    </a>
            </div>
        </div>
    </div>
@endsection

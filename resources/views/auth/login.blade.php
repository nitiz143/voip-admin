@extends('layouts.login')

@section('content')
@if(!empty(auth()->user()))
<script>window.location = "/home";</script>
@endif
<div class="login-box">
    <div class="login-logo">
      <a href="../../index2.html">{{env('APP_NAME')}}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
          <div class="input-group mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="input-group mb-3">
            <input type="password"  class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    {{ __('Remember Me') }}
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">{{ __('Sign In') }}</button>
            </div>
            <!-- /.col -->
          </div>
        </form>


        <p class="mb-1">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('I forgot my password') }}
            </a>
        @endif

        </p>
        <p class="mb-0">
          {{-- <a href="{{url('register')}}" class="text-center">Register a new membership</a> --}}
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
@endsection

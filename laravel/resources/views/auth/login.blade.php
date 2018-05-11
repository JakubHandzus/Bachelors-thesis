@extends('layouts.master')

@include('sb-admin/scripts')

@section('body_properties')class="bg-dark"@endsection

@section('content')
  <div class="container">
    <div class="card card-login mx-auto mt-5">

      <div class="card-header">Login</div>
      <div class="card-body">

        <form method="POST" action="{{ route('login') }}">
          {{ csrf_field() }}

          <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="email" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" type="password" placeholder="Password" name="password" required>

            @if ($errors->has('password'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif            
          </div>

          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Password</label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">
            Login
          </button>
        </form>

        <div class="text-center">
          <a class="d-block small mt-3" href="{{ route('register') }}">Register an Account</a>
          <a class="d-block small" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
  
  @yield('scripts')

@endsection

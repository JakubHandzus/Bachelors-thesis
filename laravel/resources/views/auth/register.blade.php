@extends('layouts.master')

@include('sb-admin/scripts')


@section('body_properties')class="bg-dark"@endsection

@section('content')
  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Register an Account</div>
      <div class="card-body">

        <form method="POST" action="{{ route('register') }}">
          {{ csrf_field() }}

          <div class="form-group">
            <div class="form-row">

              <div class="col-md-6">
                <label for="name">First name</label>
                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" type="text" aria-describedby="nameHelp" name="name" value="{{ old('name') }}" placeholder="Enter first name" required autofocus>

                @if ($errors->has('name'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>

              <div class="col-md-6">
                <label for="surname">Last name</label>
                <input class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" id="surname" type="text" aria-describedby="nameHelp" name="surname" value="{{ old('surname') }}" placeholder="Enter last name">

                @if ($errors->has('surname'))
                  <span class="invalid-feedback"> 
                    <strong>{{ $errors->first('surname') }}</strong>
                  </span>
                @endif
              </div>

            </div>
          </div>

          <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="email" aria-describedby="emailHelp" name="email" value="{{ old('email') }}" placeholder="Enter email" required>

            @if ($errors->has('email'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>

          <div class="form-group">
            <div class="form-row">

              <div class="col-md-6">
                <label for="password">Password</label>
                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" type="password" name="password" placeholder="Password" required>

                @if ($errors->has('password'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>

              <div class="col-md-6">
                <label for="password-confirm">Confirm password</label>
                <input class="form-control" id="password-confirm" type="password" placeholder="Confirm password" name="password_confirmation" required>
              </div>

            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">
            Register
          </button>

        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="{{ route('login') }}">Login Page</a>
          <a class="d-block small" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
  
  @yield('scripts')

@endsection
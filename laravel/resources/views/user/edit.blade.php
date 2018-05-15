@extends ('layouts.master')

@include('sb-admin/navbar')
@include('sb-admin/scripts')
@include('sb-admin/logout')
@include('sb-admin/footer')

@section('body_properties')class="fixed-nav sticky-footer bg-dark @yield('toggle')" id="page-top"@endsection

@section ('content')

  @yield('navbar')

  <div class="content-wrapper">
    <div class="container-fluid">
      
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">User</li>
      </ol>


      <div class="row">
        <div class="col-md-3 col-sm-2 col-0"></div>
        <div class="col-md-6 col-sm-8 col-12">
          <div class="card mb-3">

            <div class="card-header text-center lead">
              Edit user
            </div>

            <div class="card-body">
              <form method="POST" action="/user">
                {{ csrf_field() }}
                {{method_field('PATCH')}}

                <div class="form-group">
                <div class="form-row">

                  <div class="col-md-6">
                    <label for="name">* First name</label>
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" type="text" aria-describedby="nameHelp" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter first name" required>

                    @if ($errors->has('name'))
                      <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="col-md-6">
                    <label for="surname">* Last name</label>
                    <input class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" id="surname" type="text" aria-describedby="nameHelp" name="surname" value="{{ old('surname', $user->surname) }}" placeholder="Enter last name">

                    @if ($errors->has('surname'))
                      <span class="invalid-feedback"> 
                        <strong>{{ $errors->first('surname') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>
                </div>

                <div class="form-group">
                  <label for="email">* Email address</label>
                  <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="email" aria-describedby="emailHelp" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email" required>

                  @if ($errors->has('email'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>

                <hr>

                <div class="form-group">
                  <label for="email">Current password</label>
                  <input class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" id="current_password" type="password" name="current_password" value="{{ old('current_password') }}" placeholder="Enter password">

                  @if ($errors->has('current_password'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('current_password') }}</strong>
                    </span>
                  @endif
                </div>

                <div class="form-group">
                  <div class="form-row">

                    <div class="col-md-6">
                      <label for="password">New Password</label>
                      <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" type="password" name="password" placeholder="Password">

                      @if ($errors->has('password'))
                        <span class="invalid-feedback">
                          <strong>{{ $errors->first('password') }}</strong>
                        </span>
                      @endif
                    </div>

                    <div class="col-md-6">
                      <label for="password-confirm">Confirm new password</label>
                      <input class="form-control" id="password-confirm" type="password" placeholder="Confirm password" name="password_confirmation">
                    </div>

                  </div>
                </div>

                <button type="submit" class="form-group btn btn-primary btn-block">
                  Actualize
                </button>

              </form>

              <!-- Successuful message-->
              @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
              @endif

            </div>


          </div>
        </div>

      </div>

    </div>


    @yield('footer')

    @yield('logout')

    @yield('scripts')

  </div>

@endsection


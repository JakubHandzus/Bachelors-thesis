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
        <li class="breadcrumb-item">
          <a href="{{ route('sensors') }}">Sensors</a>
        </li>
        <li class="breadcrumb-item active">Register Sensor</li>
      </ol>



      <div class="row">
        <div class="col-md-3 col-sm-2 col-0"></div>
        <div class="col-md-6 col-sm-8 col-12">
          <div class="card mb-3">

            <div class="card-header text-center lead">
              New sensor
            </div>

            <div class="card-body">
              <form method="POST" action="/sensors/register">
                {{ csrf_field() }}

                <div class="form-group">
                  <label for="name">* Sensor name:</label>
                  <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="Enter name" name="name" value="{{ old('name') }}" required autofocus>
                  @if ($errors->has('name'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>

                  
                <hr>
                <p>Notify me when:</p>

                <div class="form-group row">

                  <label for="name" class="col-12 col-xl-5 col-form-label">Temperature lower than:</label>

                  <div class="col-12 col-xl-7">
                    <input type="number" step="0.1" class="form-control {{ $errors->has('min') ? ' is-invalid' : '' }}" id="min" placeholder="Min temperature notify" name="min" value="{{ old('min') }}">
                    @if ($errors->has('min'))
                      <span class="invalid-feedback">
                        <strong>{{ $errors->first('min') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>

                <div class="form-group row">
                  <label for="name" class="col-12 col-xl-5 col-form-label">Temperature higher than:</label>

                  <div class="col-12 col-xl-7">
                    <input type="number" step="0.1" class="form-control {{ $errors->has('max') ? ' is-invalid' : '' }}" id="max" placeholder="Max temperature notify" name="max" value="{{ old('max') }}">
                    @if ($errors->has('max'))
                      <span class="invalid-feedback">
                        <strong>{{ $errors->first('max') }}</strong>
                      </span>
                    @endif
                  </div>

                </div>

                <div class="form-group text-center">  
                  <button type="submit" class="btn btn-primary">Register Sensor</button>
                </div>

              </form>
            </div>

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
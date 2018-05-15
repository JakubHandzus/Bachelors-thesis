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
        <li class="breadcrumb-item active">Sensors</li>
      </ol>

      <div class="row">
        <div class="col-md-4 col-sm-12 py-2"> <!--class="float-right"-->
          <a class="btn btn-primary" href="/sensors/register" >Register sensor</a>
        </div>

        <div class="col-md-4 col-sm-12 text-center py-2">
          <div class="btn-group" role="group" aria-label="Basic example">
            <a role="button" class="btn btn-secondary {{ Route::current()->getName() == 'sensors' ? 'active' : '' }}" href="{{route('sensors')}}">All</a>
            <a role="button" class="btn btn-secondary {{ Route::current()->getName() == 'sensorsActive' ? 'active' : '' }}" href="{{route('sensors')}}/active">Active</a>
            <a role="button" class="btn btn-secondary {{ Route::current()->getName() == 'sensorsInactive' ? 'active' : '' }}" href="{{route('sensors')}}/inactive">Inactive</a>
            <a role="button" class="btn btn-secondary {{ Route::current()->getName() == 'sensorsNotConfirmed' ? 'active' : '' }}" href="{{route('sensors')}}/notconfirmed">Not Confirmed</a>
            <a role="button" class="btn btn-secondary {{ Route::current()->getName() == 'sensorsExceeded' ? 'active' : '' }}" href="{{route('sensors')}}/exceeded">Exceeded</a>
          </div>
        </div>
        
      </div>


      <div class="row">
        
        @foreach($sensors as $sensor)
        <div id="{{$sensor->id}}" class="col-lg-4 col-sm-6 col-12 py-2">
          <div class="card h-100">
            <div class="card-header text-center lead">
              <a href="/sensors/{{$sensor->id}}">{{$sensor->name}}</a>
            </div>
            <div class="card-body">

              <div class="form-group">
                <p>Last temperature: {{$sensor->lastTempPrint()}}</p>
              </div class="form-group">

              <div class="form-group">
                <p>Sensor values: {{$sensor->temperaturesCount()}}</p>
              </div class="form-group">

              <div class="form-group">
                @if ($sensor->confirmed)
                  <a href="/sensors/{{$sensor->id}}"><button type="button" class="btn btn-primary">View</button></a>
                @elseif ($sensor->device_id != null)
                  <a href="/sensors/{{$sensor->id}}/confirm"><button type="button" class="btn btn-warning">Confirm</button></a>
                @else
                  <button type="button" class="btn btn-warning" disabled>Confirm</button></a>
                @endif
                <div class="float-right">
                
                  <form method="POST" action="/sensors/{{$sensor->id}}">
                    {{ csrf_field() }}
                    {{method_field('DELETE')}}
                    <a href="/sensors/{{$sensor->id}}/qrcode"><button type="button" class="btn btn-outline-success"><i class="fa fas fa-qrcode"></i></button></a>
                    <a href="/sensors/{{$sensor->id}}/edit"><button type="button" class="btn btn-outline-secondary"><i class="fa far fa-edit"></i></button></a>

                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete sensor {{$sensor->name}}');" ><i class="fa fas fa-trash"></i></button>


                  </form>
                </div>
              </div>
            </div>
            <div class="card-footer small text-muted">{{$sensor->activeTime('diff')}}</div>
          </div>
        </div>
        @endforeach

      </div>

    </div>

    @yield('footer')

    @yield('logout')

    @yield('scripts')

  </div>

@endsection

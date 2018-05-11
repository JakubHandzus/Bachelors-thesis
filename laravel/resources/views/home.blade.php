@extends('layouts.master')

@include('sb-admin/navbar')
@include('sb-admin/scripts')
@include('sb-admin/logout')
@include('sb-admin/footer')


@section('body_properties')class="fixed-nav sticky-footer bg-dark @yield('toggle')" id="page-top"@endsection

@section('charts')
  <script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
  <script type="text/javascript" src="{{asset('js/highcharts.js')}}"></script>
@endsection


@section('content')
  
  <!-- Navigation bar -->
  @yield('navbar')
  
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Home</li>
      </ol>
      <!-- Icon Cards-->
      <div class="row">

        @if ($info['active'] > 0)

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-thermometer-half"></i>
              </div>
              <div class="mr-5">{{$info['active']}} Active Sensors!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="/sensors/active">
              <span class="float-left">View active sensors</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @else 

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card border-success text-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon text-success">
                <i class="fa fa-fw fa-thermometer-half"></i>
              </div>
              <div class="mr-5">{{$info['active']}} Active Sensors!</div>
            </div>
            <a class="card-footer border-success text-success clearfix small z-1" href="/sensors/active">
              <span class="float-left">View active sensors</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @endif

        @if ($info['inactive'] > 0)

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-minus-circle"></i>
              </div>
              <div class="mr-5">{{$info['inactive']}} Inactive Sensors!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="/sensors/inactive">
              <span class="float-left">View inactive sensors</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @else

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card border-warning text-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-minus-circle"></i>
              </div>
              <div class="mr-5">{{$info['inactive']}} Inactive Sensors!</div>
            </div>
            <a class="card-footer border-warning text-warning clearfix small z-1" href="/sensors/inactive">
              <span class="float-left">View inactive sensors</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @endif

        @if ($info['not_confirmed'] > 0) 

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-pencil-square""></i>
              </div>
              <div class="mr-5">{{$info['not_confirmed']}} Need to confirm!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="/sensors/notconfirmed">
              <span class="float-left">View not confirmed sensors</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @else

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card border-primary text-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-pencil-square""></i>
              </div>
              <div class="mr-5">{{$info['not_confirmed']}} Need to confirm!</div>
            </div>
            <a class="card-footer border-primary text-primary clearfix small z-1" href="/sensors/notconfirmed">
              <span class="float-left">View not confirmed sensors</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @endif

        @if ($info['exceeded'])

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-exclamation"></i>
              </div>
              <div class="mr-5">{{$info['exceeded']}} Sensors have exceeded range</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="/sensors/exceeded">
              <span class="float-left">View sensors with exceeded range</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @else 

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card border-danger text-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-exclamation"></i>
              </div>
              <div class="mr-5">{{$info['exceeded']}} Sensors have exceeded range</div>
            </div>
            <a class="card-footer border-danger text-danger clearfix small z-1" href="/sensors/exceeded">
              <span class="float-left">View sensors with exceeded range</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>

        @endif

      </div>

      <div class="text-center mt-4 mb-5">
        <div class="btn-group" role="group" aria-label="Basic example">
          <button id='day' type="button" class="btn btn-secondary">Day</button>
          <button id='week' type="button" class="btn btn-secondary">Week</button>
          <button id='month' type="button" class="btn btn-secondary">Month</button>
        </div>
      </div>

      @foreach($sensors as $sensor)
        <div id="chart{{$sensor->id}}" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <div id="table{{$sensor->id}}" class="row"></div>
        <div id="noData{{$sensor->id}}" class="row" style="display: none;">
          <div class="col-md-3 col-sm-2 col-0"></div>
          <div class="col-md-6 col-sm-8 col-12 alert alert-danger lead text-center my-5" role="alert">
            Sensor <a class="alert-link" href="/sensors/{{$sensor->id}}">{{$sensor->name}}</a> has no data to display
          </div>
        </div>

      @endforeach

      <script type="text/javascript">

        // on load check lastes cookie and click button
        var view_number = Cookies.get('default_view');

        if (view_number == 'month') {
          $(document).ready(function() {
            $("#month").click();
          });
        }
        else if (view_number == 'day') {
          $(document).ready(function() {
            $("#day").click();
          });
        }
        // default
        else {
          $(document).ready(function() {
            $("#week").click();
          });
        }

        // on button click - activate button and save to cookies

        $('#day').click(function() {
          // toggle this button
          $(this).siblings().removeClass('active')
          $(this).addClass('active');
          // save option to cookies
          Cookies.set('default_view', 'day', { expires: 365 });
        });

        $('#week').click(function() {
          // toggle this button
          $(this).siblings().removeClass('active')
          $(this).addClass('active');
          // save option to cookies
          Cookies.set('default_view', 'week', { expires: 365 });
        });

        $('#month').click(function() {
          // toggle this button
          $(this).siblings().removeClass('active')
          $(this).addClass('active');
          // save option to cookies
          Cookies.set('default_view', 'month', { expires: 365 });
        });

        
      @foreach($sensors as $sensor)

        $('#day').click(function() {
          var date = new Date();
          date.setDate(date.getDate() - 1);
          // download content
          $.ajax({
            url:"sensors/{{$sensor->id}}/json",
            type: "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "time": date
            },
            dataType: "json",
            success: function(result) {
              newHighCharts(result, "{{$sensor->name}}", {{$sensor->id}}, "{{$sensor->id}}", "{{$sensor->name}}");
            }
          })
        })

        $('#week').click(function() {
          var date = new Date();
          date.setDate(date.getDate() - 7);
          // download content
          $.ajax({
            url:"sensors/{{$sensor->id}}/json",
            type: "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "time": date
            },
            dataType: "json",
            success: function(result) {
              newHighCharts(result, "{{$sensor->name}}", {{$sensor->id}}, "{{$sensor->id}}", "{{$sensor->name}}");
            }
          })
        })

        $('#month').click(function() {
          var date = new Date();
          date.setDate(date.getDate() - 30);
          // download content
          $.ajax({
            url:"sensors/{{$sensor->id}}/json",
            type: "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "time": date
            },
            dataType: "json",
            success: function(result) {
              newHighCharts(result, "{{$sensor->name}}", {{$sensor->id}}, "{{$sensor->id}}", "{{$sensor->name}}");
            }
          })
        })

      @endforeach
      </script>

      
    </div>
    
    @yield('footer')
    
    @yield('logout')

    @yield('scripts')
    
  </div>

@endsection

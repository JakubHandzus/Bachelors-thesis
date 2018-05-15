@extends ('layouts.master')

@include('sb-admin/navbar')
@include('sb-admin/scripts')
@include('sb-admin/logout')
@include('sb-admin/footer')


@section('body_properties')class="fixed-nav sticky-footer bg-dark @yield('toggle')" id="page-top"@endsection

@section('charts')
  <script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
  <script type="text/javascript" src="{{asset('js/highcharts.js')}}"></script>
@endsection


@section ('content')

  @yield('navbar')

  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ route('sensors') }}#{{$sensor->id}}">Sensors</a>
        </li>
        <li class="breadcrumb-item active">Sensor {{$sensor->name}}</li>
      </ol>

      <div id="chartDay" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
      <div id="tableDay" class="row"></div>
      <div id="noDataDay" class="row" style="display: none;">
          <div class="col-md-3 col-sm-2 col-0"></div>
          <div class="col-md-6 col-sm-8 col-12 alert alert-danger lead text-center my-5" role="alert">
            Sensor has no data to display in last 24 hours
          </div>
      </div>

      <div id="chartWeek" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
      <div id="tableWeek" class="row"></div>
      <div id="noDataWeek" class="row" style="display: none;">
          <div class="col-md-3 col-sm-2 col-0"></div>
          <div class="col-md-6 col-sm-8 col-12 alert alert-danger lead text-center my-5" role="alert">
            Sensor has no data to display in last 7 days
          </div>
      </div>

      <div id="chartMonth" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
      <div id="tableMonth" class="row"></div>
      <div id="noDataMonth" class="row" style="display: none;">
          <div class="col-md-3 col-sm-2 col-0"></div>
          <div class="col-md-6 col-sm-8 col-12 alert alert-danger lead text-center my-5" role="alert">
            Sensor has no data to display in last month
          </div>
      </div>

      <script type="text/javascript">

        $(document).ready(function() {
          var date = new Date();
          date.setDate(date.getDate() - 1);
          // download content
          $.ajax({
            url:"{{$sensor->id}}/json",
            type: "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "time": date
            },
            dataType: "json",
            success: function(result) {
              newHighCharts(result, "{{$sensor->name}}", {{$sensor->id}}, "Day", "Day");
            }
          })
        })

        $(document).ready(function() {
          var date = new Date();
          date.setDate(date.getDate() - 7);
          // download content
          $.ajax({
            url:"{{$sensor->id}}/json",
            type: "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "time": date
            },
            dataType: "json",
            success: function(result) {
              newHighCharts(result, "{{$sensor->name}}", {{$sensor->id}}, "Week", "Week");
            }
          })
        })

        $(document).ready(function() {
          var date = new Date();
          date.setDate(date.getDate() - 30);
          // download content
          $.ajax({
            url:"{{$sensor->id}}/json",
            type: "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "time": date
            },
            dataType: "json",
            success: function(result) {
              newHighCharts(result, "{{$sensor->name}}", {{$sensor->id}}, "Month", "Month");
            }
          })
        })

      </script>
      

    </div>

    @yield('footer')

    @yield('logout')

    @yield('scripts')

  </div>

@endsection
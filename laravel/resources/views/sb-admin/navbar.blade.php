@section('toggle')<?php if(isset($_COOKIE['sidebar']) and $_COOKIE['sidebar'] == '0') {echo 'sidenav-toggled';}?>@endsection

@section ('navbar')

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{route('home')}}">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="{{ route('home') }}">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Sensors">
          <a class="nav-link" href="{{ route('sensors') }}">
            <i class="fa fa-fw fa-microchip"></i>
            <span class="nav-link-text">Sensors</span>
          </a>
        </li>

        <li class="nav-item d-none d-lg-block" data-toggle="tooltip" data-placement="right" title="User">
          <a class="nav-link" href="{{ route('user')}}">
            <i class="fa fa-fw fas fa-user"></i>
            <span class="nav-link-text">User</span>
          </a>
        </li>

      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>


      <ul class="navbar-nav ml-auto">

        <li class="nav-item">
          <a class="nav-link" href="{{ route('user')}}">
            <i class="fa fa-fw fas fa-user"></i> {{ Auth::user()->name }}
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fas fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>

@endsection
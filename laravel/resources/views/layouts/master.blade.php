<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Jakub Handzus">
  <meta name="description" content="System for monitoring temperature">
  <meta name="keywords" content="temperature, monitoring, monitor">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

  <!-- FontAwesome -->
  <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet" type="text/css">

  <!-- jQuery -->
  <script src="{{ asset('jquery/jquery.min.js') }}"></script>

  <!-- Cookie library -->
  <script src="{{asset('js/js.cookie.js')}}"></script>

  @yield('charts')

</head>

<body @yield('body_properties')>

  @yield('content')

</body>

</html>

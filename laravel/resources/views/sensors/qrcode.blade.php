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
          <a href="{{ route('sensors') }}#{{$sensor->id}}">Sensors</a>
        </li>
        <li class="breadcrumb-item active">QR Code</li>
      </ol>

      <!-- QR Code JS library -->
      <script src="{{ asset('js/qrcode.min.js') }}"></script>

      <div class="row">

        <div class="col-xl-3 col-md-2 col-sm-0"></div>
        <div class="col-xl-6 col-md-8 col-sm-12">
         

          <div class="card mb-3">
            <div class="card-header text-center lead">
              1. Set ESP to registration mode
            </div>

            <div class="card-body">
              <p class="card-text">
                Press reset button on ESP. The blue light indicates registration mode.
              </p>
            </div>
          </div>


          <div class="card mb-3">
            <div class="card-header text-center lead">
              2. Connect to ESP Wi-Fi
            </div>

            <div class="card-body">
              <p class="card-text">
                In registration mode ESP creates Wi-Fi hotspot called ESP. Connect your mobile to ESP's hotspot.
              </p>
            </div>
          </div>
        

          <div class="card mb-3">

            <div class="card-header text-center lead">
              3. Generate QR Code
            </div>

            <div class="card-body">

              <p class="card-text">
                If you setting up new ESP, please fill the Wi-Fi credentials for ESP and click "Generate QR code". If ESP has already setted Wi-Fi, you do not have to fill up the credentials.
              </p>
              <p class="card-text">
                After generating QR, scan it with your mobile phone and click on the link.
              </p>

              <div class="row">
                
                <div class="center col-sm-6 col-12" style="margin: auto">
                  <div id="qrcode"></div>
                  <script type="text/javascript">
                    qrcode = new QRCode(document.getElementById("qrcode"), "192.168.42.1/reg?addr={{config('app.url').':'.config('app.port')}}&api_key={{$sensor->api_key}}&ssid=&pass=");
                    $('#qrcode').children('img').css('margin', "auto");
                  </script>
                </div>

                <div class="col-sm-6 col-12">

                  <div class="form-group">
                    <label>Api-key:</label>
                    <div class="input-group">
                      <input name="api_key_show" class="form-control form-control-sm" type="text" value="{{$sensor->api_key}}" onclick="$(this).select();document.execCommand('copy');" readonly>
                      <!-- Copy button -->
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="$(this).parent().siblings().select();document.execCommand('copy');"><i class="fa fas fa-copy"></i></button>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="name">IP address:</label>
                    <input type="text" class="form-control" id="addr" placeholder="Enter IP" name="ssid" value="{{config('app.url').':'.config('app.port')}}">
                  </div>
                  
                  <div class="form-group">
                    <label for="name">Wi-Fi name:</label>
                    <input type="text" class="form-control" id="ssid" placeholder="Enter ssid" name="ssid">
                  </div>

                  <div class="form-group">
                    <label for="name">Wi-Fi password:</label>
                    <input type="password" class="form-control" id="pass" placeholder="Enter password" name="pass">
                  </div>

                  <div class="form-group text-center">  
                    <button type="submit" class="btn btn-primary" onclick="generateQrCode()">Generate QR code</button>
                  </div>
                  
                </div>

              </div>

              <script type="text/javascript">
                function generateQrCode() {
                  var url = "192.168.42.1/reg?addr={{config('app.url').':'.config('app.port')}}&api_key={{$sensor->api_key}}&ssid="+ document.getElementById("ssid").value +"&pass="+ document.getElementById("pass").value;
                  qrcode.clear();
                  qrcode.makeCode(url);
                }
              </script>
            
            </div>

          </div>


          <div class="card mb-3">
            <div class="card-header text-center lead">
              4. Confirm the sensor
            </div>

            <div class="card-body">
              <p class="card-text">
                Return to all sensors and click on sensor's "confirm" button.
              </p>
              <a href="{{ route('sensors') }}#{{$sensor->id}}">
                <button type="button" class="btn btn-secondary float-right">
                  Return and confirm
                </button>
              </a>
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
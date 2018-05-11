/**
 * Render HighCharts graph
 *
 * @author Jakub Handzus
 */


/**
 * Formatting time to human readable
 *
 * @param original time
 * @returns formatted string
 */
function formatTime(original) {
  var time = new Date(parseDate(original));
  return ("0"+time.getHours()).slice(-2) +':'+ ("0"+time.getMinutes()).slice(-2) +':'+ ("0"+time.getSeconds()).slice(-2) +' '+ ("0"+time.getDate()).slice(-2) +'.'+ ("0"+time.getMonth()).slice(-2) +'.'+ (time.getYear() + 1900);
}

/**
 * Parsing time and create object Date (this method fixing bug on IE and Safari)
 *
 * @param inputTime
 * @returns Date variable
 */
function parseDate(inputTime) {
  var parts = inputTime.split(/[ \/:-]/g);
  var dateFormated = parts[1] + "/" + parts[2] + "/" + parts[0] + " " + parts[3] + ":" + parts[4] + ":" + parts[5];
  return new Date(dateFormated);  
}

/**
 * Generate HighCharts graph and table
 *
 * @param data JSON data
 * @param sensor_name Sensor's name
 * @param sensor_id Sensor's id
 * @param container HTML container for rendering graph
 * @param graphName Name of the graph
 */
function newHighCharts(data, sensor_name, sensor_id, container, graphName) {
  var series = [];
  var count = 0;

  // For each data series
  Object.keys(data).forEach((item) => {
      count++;
      if (Object.keys(data).length != count) {
        var tmp = [];
        if (data[item].length != 0) {
          for (i = 0; i < data[item].length; i++){
            // Parse data from database
            tmpTime = parseDate(data[item][i].time);
            // Push data (time, temperature) to temporary array
            tmp.push([tmpTime.getTime() + (tmpTime.getTimezoneOffset() * -60000), parseFloat(data[item][i].temperature)]);
          }
          // Push name with data to chart's JSON
          series.push({'name': item, 'data': tmp});
        }
      }
      else if (series.length != 0) {
        var table = $('#table' + container);

        var last = data[item]['last'];
        var min = data[item]['min'];
        var max = data[item]['max'];
        var avg = parseFloat(data[item]['avg']).toFixed(1);

        table.empty();
        table.show();
        table.append(
         `<div class="col-xl-3 col-md-2 col-sm-12"></div>
          <div class="col-xl-6 col-md-8 col-sm-12">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">Sensor: `+sensor_name+`</th>
                <th scope="col">Time</th>
                <th scope="col">Temperature</th>
              </tr>
            </thead>
            <tbody id="tableDay">
              <tr><th scope="row">Latest</th><td>`+formatTime(last.time)+'</td><td>'+last.temperature+`</td></tr>
              <tr><th scope="row">Minimum</th><td>`+formatTime(min.time)+'</td><td>'+min.temperature+`</td></tr>
              <tr><th scope="row">Maximum</th><td>`+formatTime(max.time)+'</td><td>'+max.temperature+`</td></tr>
              <tr><th scope="row">Average</th><td></td><td>`+avg+`</td></tr>
            </tbody>
          </table>
          </div>`
          
        );
      }
  })


  // If result is empty, remove containers
  if (series.length == 0) {
    $('#chart'+container).hide();
    $('#table'+container).hide();
    $('#chart'+container).empty();
    $('#table'+container).empty();
    $('#noData'+container).show();
  }
  else {
    graphName = graphName +" ["+ series[0]['data'][series[0]['data'].length - 1][1] + "°C]";
    $('#chart'+container).show();
    $('#noData'+container).hide();
    Highcharts.chart('chart'+container, {
      chart: {
        type: 'spline',
        zoomType: 'x' 
      },
      title: {
        text: '<a href="/sensors/'+sensor_id+'">'+graphName+'</a>'
      },
      subtitle: {
        text: ''
      },
      credits: false,
      xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: {
          millisecond: '%H:%M:%S',
          second: '%H:%M:%S',
          minute: '%H:%M',
          hour: '%H:%M',
          day: '%e. %b',
          week: '%e. %b',
          month: '%b \'%y',
          year: '%Y'
        },
        title: {
          text: 'Time'
        }
      },
      yAxis: {
        title: {
          text: 'Temperature (°C)'
        }
        // min: 0
      },
      tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:Time: %H:%M:%S %e.%B}<br>Temperature: {point.y:.1f}°C'
      },

      plotOptions: {
        spline: {
          marker: {
            enabled: false
          }
        }
      },

      colors: ['#6CF', '#39F', '#06C', '#036', '#000'],

      series: series
    });

  }

}

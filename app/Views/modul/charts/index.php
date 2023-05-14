<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | ChartJS</title>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <!-- AREA CHART -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Area Chart</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 378px;" width="472" height="312" class="chartsJs-render-monitor">

                  </canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="lte/plugins/chart.js/Chart.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      /* ChartJS
           * -------
           * Here we will create a few charts using ChartJS
           */

          //--------------
          //- AREA CHART -
          //--------------

          // Get context with jQuery - using jQuery's .get() method.
          var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

          var areaChartData = {
            // labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            labels: <?php echo json_encode($labels); ?>,
            // datasets: [{
            //   label               : 'Digital Goods',
            //   backgroundColor     : 'rgba(60,141,188,0.9)',
            //   borderColor         : 'rgba(60,141,188,0.8)',
            //   pointRadius          : false,
            //   pointColor          : '#3b8bba',
            //   pointStrokeColor    : 'rgba(60,141,188,1)',
            //   pointHighlightFill  : '#fff',
            //   pointHighlightStroke: 'rgba(60,141,188,1)',
            //   data                : [28, 48, 40, 19, 86, 27, 90]
            // }]
            datasets: [{
                label: 'Total',
                data: <?php echo json_encode($totals); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
          }

          var areaChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                gridLines : {
                  display : false,
                }
              }],
              yAxes: [{
                gridLines : {
                  display : false,
                }
              }]
            }
          }

          // This will get the first returned node in the jQuery collection.
          new Chart(areaChartCanvas, {
            type: 'line',
            data: areaChartData,
            options: areaChartOptions
          });
        });
      </script>


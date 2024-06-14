<div>
    <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-pie-chart"></i>
            {{ $options['name'] }}
        </div>
        <div class="donutChart" style="min-height: 340px; padding: 30px 0;">
        <canvas id="productChart" class=""></canvas>
        </div>
    </div>
</div>
@push('js-scripts')
<script>
    var ctx3 = document.getElementById("productChart").getContext('2d');
    var productChart = new Chart(ctx3, {
      type: 'doughnut',
      data: {
        labels: ["{{ trans('app.admin') }}", "{{ trans('app.merchant') }}", "{{ trans('app.total') }}"],
        datasets: [{
          backgroundColor: [
            "#f39c12",
            "#ef486a",
            "#0abb75"
          ],
          data: [10, 20, 30]
        }]
      }
    });
  </script>
@endpush
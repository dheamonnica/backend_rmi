@extends('admin.layouts.master')

@section('page-style')
  @include('plugins.ionic')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  @livewireStyles
@endsection

@section('content')
  @include('admin.partials._check_misconfigured_subscription')

 @livewire('dashboard' ,[
  'customer_count' => $customer_count,
  'new_customer_last_30_days' => $new_customer_last_30_days,
  'merchant_count' => $merchant_count,
  'new_merchant_last_30_days' => $new_merchant_last_30_days,
  'total_order_count' => $total_order_count,
  'todays_all_order_count' => $todays_all_order_count,
  'yesterdays_all_order_count' => $yesterdays_all_order_count,
  'todays_sale_amount' => $todays_sale_amount,
  'yesterdays_sale_amount' => $yesterdays_sale_amount,
  'pending_verifications' => $pending_verifications,
  'pending_approvals' => $pending_approvals,
  'dispute_count' => $dispute_count,
  'last_60days_dispute_count' => $last_60days_dispute_count,
  'last_30days_dispute_count' => $last_30days_dispute_count,
])    
@endsection

@section('page-script')
  @livewireScripts

  @include('plugins.filter-orders')
  @include('plugins.chart')

  {!! $chart->script() !!}
  
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
  <script>
    var ctx = document.getElementById("productChart").getContext('2d');
    var productChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["{{ trans('app.admin') }}", "{{ trans('app.merchant') }}", "{{ trans('app.total') }}"],
        datasets: [{
          backgroundColor: [
            "#f39c12",
            "#ef486a",
            "#0abb75"
          ],
          data: [<?= $admin_created_total_product_count ?>, <?= $merchant_created_total_product_count ?>, <?= $admin_created_total_product_count + $merchant_created_total_product_count ?>]
        }]
      }
    });
  </script>

  <script>
    // Sample data for last week, last month, and last year
    const dataThisWeek = <?= $total_weekly_sale_report ?>;
    const dataThisMonth = <?= $total_monthly_sale_report ?>;
    const dataThisYear = <?= $total_yearly_sale_report ?>;

    // Initialize the chart with data for the last week
    const saleCtx = document.getElementById('saleChart').getContext('2d');
    const saleChart = new Chart(saleCtx, {
      type: 'line',
      data: {
        labels: <?= \App\Helpers\CharttHelper::getSaleAmount('week') ?>, // Adjust labels as needed
        datasets: [{
          label: "@lang('app.this_week')",
          data: dataThisWeek,
          backgroundColor: '#CAD8F8',
          borderColor: '#225DE4',
          borderWidth: 2,
          pointRadius: 4,
        }]
      },
      options: {
        // Chart options here
      }
    });

    // Function to update the chart with new data based on the selected time frame
    function updateChart(timeframe, data, color) {
      saleChart.data.datasets[0].label = `This ${timeframe.charAt(0).toUpperCase() + timeframe.slice(1)}`;
      saleChart.data.datasets[0].data = data;
      saleChart.data.datasets[0].borderColor = "#225DE4";
      saleChart.data.datasets[0].backgroundColor = color;
      saleChart.data.datasets[0].pointRadius = 4;

      saleChart.data.labels = getLabels(timeframe); // Update labels based on the selected time frame
      saleChart.update();
    }

    // Helper function to get labels based on the selected time frame
    function getLabels(timeframe) {
      switch (timeframe) {
        case 'week':
          return <?= \App\Helpers\CharttHelper::getSaleAmount('week') ?>;
        case 'month':
          return <?= \App\Helpers\CharttHelper::getSaleAmount('month') ?>;
        case 'year':
          return <?= \App\Helpers\CharttHelper::getSaleAmount('year') ?>;
        default:
          return [];
      }
    }

    // Add event listeners to tab buttons for user interaction
    document.querySelectorAll('.tab-button').forEach(tabButton => {
      tabButton.addEventListener('click', () => {
        const timeframe = tabButton.getAttribute('data-timeframe');
        const data = getDataForTimeframe(timeframe); // Replace with your data retrieval logic
        const color = getColorForTimeframe(timeframe); // Adjust color as needed
        updateChart(timeframe, data, color);

        // Toggle the 'active' class among tabs
        document.querySelectorAll('.tab-button').forEach(tab => {
          tab.classList.remove('active');
        });
        tabButton.classList.add('active');
      });
    });

    // Function to retrieve data based on the selected time frame
    function getDataForTimeframe(timeframe) {
      switch (timeframe) {
        case 'week':
          return dataThisWeek;
        case 'month':
          return dataThisMonth;
        case 'year':
          return dataThisYear;
        default:
          return [];
      }
    }

    // Function to get a color based on the selected time frame
    function getColorForTimeframe(timeframe) {
      switch (timeframe) {
        case 'week':
          return '#CAD8F8';
        case 'month':
          return '#CAD8F8';
        case 'year':
          return '#CAD8F8';
        default:
          return '';
      }
    }
  </script>
@endsection

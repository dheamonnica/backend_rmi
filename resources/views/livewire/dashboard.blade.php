<div>
    <div class="row">
        <div class="col-sm-12">
          <div id="filter-panel">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-2">
                    <h3>{{ trans('app.filters')}}</h3>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2 nopadding-right">
                    <div class="form-group">
                      <label>{{ trans('app.warehouse') }}</label>
                      <select style="width: 100%" id="select_warehouse" wire:model="selectedWarehouseOption" class="form-control" >
                        <option value="">{{ trans('app.select_warehouse') }}</option>
                        @foreach ($warehouses as $key => $item)  
                          <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.client') }}</label>
                      <select style="width: 100%" id="select_client" placeholder="placeholder" wire:model="selectedClientOption" class="form-control" >
                      <option value="">{{ trans('app.select_client') }}</option>
                      @foreach ($clients as $key => $item)  
                        <option value="{{ $key }}">{{ $item }}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.client_group') }}</label>
                      <select style="width: 100%" id="select_client_group" wire:model="selectedClientGroupOption" class="form-control" >
                        <option value="">{{ trans('app.select_client_group') }}</option>
                        @foreach ($client_groups as $key => $item)  
                          <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.category_group') }}</label>
                      <select style="width: 100%" id="select_category_group" wire:model="selectedCategoryGroupOption" class="form-control" >
                        <option value="">{{ trans('app.select_category_group') }}</option>
                        @foreach ($category_groups as $key => $item)  
                          <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.category_sub_group') }}</label>
                      <select style="width: 100%" id="select_category_sub_group" wire:model="selectedCategorySubGroupOption" class="form-control" >
                        <option value="">{{ trans('app.category_sub_group') }}</option>
                        @foreach ($category_sub_groups as $key => $item)  
                          <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2 nopadding-right ">
                    <div class="form-group">
                      <label>{{ trans('app.username') }}</label>
                      <input type="text" id="orderNumber" name="order_number" value="{{ request()->get('order_number') }}" class="form-control" placeholder="{{ trans('app.user_name') }}" wire:model="userName">
                    </div>
                  </div>
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.product') }}</label>
                      <input type="text" id="orderNumber" name="order_number" value="{{ request()->get('order_number') }}" class="form-control" placeholder="{{ trans('app.product_name') }}" wire:model="productName">
                    </div>
                  </div>
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.order_status') }}</label>
                      <select id="orderStatus" class="form-control" name="order_status" wire:model="selectedOrderStatusOption" >
                        <option value="" @if (request()->get('order_status') == 'all') selected @endif>{{ trans('app.all') }}</option>
                        <option value="STATUS_WAITING_FOR_PAYMENT" @if (request()->get('order_status') == 'STATUS_WAITING_FOR_PAYMENT') selected @endif>{{ trans('app.waiting_for_payment') }}</option>
                        <option value="STATUS_CONFIRMED" @if (request()->get('order_status') == 'STATUS_CONFIRMED') selected @endif>{{ trans('app.confirmed') }}</option>
                        <option value="STATUS_FULFILLED" @if (request()->get('order_status') == 'STATUS_FULFILLED') selected @endif>{{ trans('app.fulfilled') }}</option>
                        <option value="STATUS_AWAITING_DELIVERY" @if (request()->get('order_status') == 'STATUS_AWAITING_DELIVERY') selected @endif>{{ trans('app.awaiting_delivery') }}</option>
                        <option value="STATUS_DELIVERED" @if (request()->get('order_status') == 'STATUS_DELIVERED') selected @endif>{{ trans('app.delivered') }}</option>
                        <option value="STATUS_CANCELED" @if (request()->get('order_status') == 'STATUS_CANCELED') selected @endif>{{ trans('app.canceled') }}</option>
                        <option value="STATUS_PAYMENT_ERROR" @if (request()->get('order_status') == 'STATUS_PAYMENT_ERROR') selected @endif>{{ trans('app.payment_error') }}</option>
                        <option value="STATUS_RETURNED" @if (request()->get('order_status') == 'STATUS_RETURNED') selected @endif>{{ trans('app.returns') }}</option>
                        <option value="STATUS_DISPUTED" @if (request()->get('order_status') == 'STATUS_DISPUTED') selected @endif>{{ trans('app.disputed') }}</option>
                        <option value="STATUS_PACKED" @if (request()->get('order_status') == 'STATUS_PACKED') selected @endif>{{ trans('app.packed') }}</option>
                      </select>
                    </div>
                  </div>
    
                  <div class="col-md-2 nopadding-right nopadding-left">
                    <div class="form-group">
                      <label>{{ trans('app.payment_status') }}</label>
                      <select id="paymentStatus"class="form-control" name="payment_status" wire:model="selectedPaymentStatusOption" >
                        <option value="" @if (request()->get('order_status') == 'all') selected @endif>{{ trans('app.all') }}</option>
                        <option value="PAYMENT_STATUS_UNPAID" @if (request()->get('order_status') == 'PAYMENT_STATUS_UNPAID') selected @endif>{{ trans('app.unpaid') }}</option>
                        <option value="PAYMENT_STATUS_PENDING" @if (request()->get('order_status') == 'PAYMENT_STATUS_PENDING') selected @endif>{{ trans('app.pending') }}</option>
                        <option value="PAYMENT_STATUS_PAID" @if (request()->get('order_status') == 'PAYMENT_STATUS_PAID') selected @endif>{{ trans('app.paid') }}</option>
                        <option value="PAYMENT_STATUS_REFUNDED" @if (request()->get('order_status') == 'PAYMENT_STATUS_REFUNDED') selected @endif>{{ trans('app.refunded') }}</option>
                      </select>
                    </div>
                  </div>
    
                  <div class="col-md-2 nopadding-left">
                    <div class="form-group">
                      <label>&nbsp;</label>
                      <button type="button" class="btn btn-default pull-right" name="search" value="1"><i class="fa fa-caret-left"></i> {{ trans('app.clear') }}</button>
                    </div>
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-md-2 nopadding-right">
                    <div class="form-group">
                      <label>{{ trans('app.interval') }}</label>
                      <select id="time_interval"class="form-control" name="interval" wire:model="selectedIntervalOption" >
                        <option value="DAILY">{{ trans('app.daily') }}</option>
                        <option value="WEEK">{{ trans('app.week') }}</option>
                        <option value="MONTH">{{ trans('app.month') }}</option>
                        <option value="YEAR">{{ trans('app.year') }}</option>
                      </select>
                    </div>
                  </div>
                  @if ($selectedIntervalOption == 'DAILY')
                    <div class="col-md-2 nopadding-right">
                      <div class="form-group">
                        <label>{{ trans('app.start_date') }}</label>
                        <input type="text" id="datepicker_start_date" class="form-control" wire:model="selectedStartDate">
                      </div>
                    </div>
                    <div class="col-md-2 nopadding-right">
                      <div class="form-group">
                        <label>{{ trans('app.end_date') }}</label>
                        <input type="text" id="datepicker_end_date" class="form-control" wire:model="selectedEndDate">
                      </div>
                    </div>
                  @elseif ($selectedIntervalOption == 'WEEK')
                    <div class="col-md-2 nopadding-right">
                        <div class="form-group">
                            <label>{{ trans('app.year') }}</label>
                            <input type="text" id="yearPicker" wire:model="selectedYearWeek" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 nopadding-right">
                        <div class="form-group">
                            <label>{{ trans('app.week') }}</label>
                            <input type="text" id="weekPicker" wire:model="selectedWeek" class="form-control">
                        </div>
                    </div>
                  @elseif ($selectedIntervalOption == 'MONTH')
                    <div class="col-md-2 nopadding-right">
                      <div class="form-group">
                        <label>{{ trans('app.month_start') }}</label>
                        <input type="text" id="yearMonthStartPicker" wire:model="selectedYearMonthStart" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-2 nopadding-right">
                      <div class="form-group">
                        <label>{{ trans('app.month_end') }}</label>
                        <input type="text" id="yearMonthEndPicker" wire:model="selectedYearMonthEnd" class="form-control">
                      </div>
                    </div>
                  @else  
                    <div class="col-md-2 nopadding-right ">
                      <div class="form-group">
                        <label>{{ trans('app.year_start') }}</label>
                        <input type="text" id="yearStartPicker" wire:model="selectedYearStart" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-2 nopadding-right ">
                      <div class="form-group">
                        <label>{{ trans('app.year_end') }}</label>
                        <input type="text" id="yearEndPicker" wire:model="selectedYearEnd" class="form-control">
                      </div>
                    </div>
                  @endif
                </div>  
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row dashboard-total">

      {{-- @dump([
          'warehouse' => $selectedWarehouseOption,
          'client' => $selectedClientOption,
          'client_group' => $selectedClientGroupOption,
          'category_group' => $selectedCategoryGroupOption,
          'category_sub_group' => $selectedCategorySubGroupOption,
          'order_status' => $selectedOrderStatusOption,
          'payment_status' => $selectedPaymentStatusOption,
      ]) --}}
      <div class="row">
        <div class="col-sm-12">
          <div id="filter-panel">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="row">
                    <div class="col-md-2 nopadding-right">
                  Filter value : <br> 
                  warehouse : {{ $selectedWarehouseOption }} <br>
                  client : {{ $selectedClientOption }} <br>
                  client_group : {{ $selectedClientGroupOption }} <br>
                  category group : {{ $selectedCategoryGroupOption }} <br>
                  category sub group : {{ $selectedCategorySubGroupOption }} <br>
                  order status : {{ $selectedOrderStatusOption }} <br>
                  payment status : {{ $selectedPaymentStatusOption }} <br>
                  username : {{ $productName }} <br>
                  product name : {{ $userName }} <br>
                  interval: {{ $selectedIntervalOption }} <br>
                  start_date: {{ $selectedStartDate }} <br>
                  end_date : {{ $selectedEndDate }} <br>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        
      @livewire('card-counter',[
        'customer_count' => $customer_count,
        'new_customer_last_30_days' => $new_customer_last_30_days,
        'merchant_count' => $merchant_count,
        'new_merchant_last_30_days' => $new_merchant_last_30_days,
        'total_order_count' => $total_order_count,
        'todays_all_order_count' => $todays_all_order_count,
        'yesterdays_all_order_count' => $yesterdays_all_order_count,
        'todays_sale_amount' => $todays_sale_amount,
        'yesterdays_sale_amount' => $yesterdays_sale_amount,
      ])

      @livewire('info-boxes', [
        'pending_verifications' => $pending_verifications,
        'pending_approvals' => $pending_approvals,
        'dispute_count' => $dispute_count,
        'last_60days_dispute_count' => $last_60days_dispute_count,
        'last_30days_dispute_count' => $last_30days_dispute_count,
      ])

      {{-- @livewire('po-chart-all-filter')

      <div class="row">
        @livewire('po-chart-time-filter', [ 'chart ' => $chart])
  
        @livewire('warehouse-pie-chart')
      </div> --}}
    </div>

    {{-- <div class="row dashboard-ticket-section">
      @livewire('customer-table')

      @livewire('warehouse-table')

      @livewire('log-activity-table')

      <div class="row dashboard-product-section">
        @livewire('top-worst-product-table')

        @livewire('latest-product-table')
      </div>

      <div class="row dashboard-product-section">
        @livewire('stock-table')

        @livewire('key-performance-table')
      </div> --}}
    {{-- </div> --}}
    {{-- <div class="row">
        <div class="col-sm-12">
            @include('admin.partials.reports.timeframe')
        </div>
    </div> --}}
</div>

<script>
  function initializeDatepicker(interval) {
          $('#datepicker').datepicker('destroy'); // Destroy any existing datepicker
          let options = {};

          switch(interval) {
              case 'DAILY':
                  $('#datepicker_start_date').datepicker({
                      format: 'yyyy-mm-dd'
                  }).on('changeDate', function(e) {
                      @this.set('startDateUpdated', e.format(0, 'yyyy-mm-dd'));
                  });

                  $('#datepicker_end_date').datepicker({
                      format: 'yyyy-mm-dd'
                  }).on('changeDate', function(e) {
                      @this.set('endDateUpdated', e.format(0, 'yyyy-mm-dd'));
                  });
                  break;
              case 'WEEK':

                  $('#yearPicker').datepicker({ format: 'yyyy', startView: 'years', minViewMode: 'years', autoclose: true }).on('changeDate', function(e) {
                      @this.set('yearWeekUpdated', e.format(0, 'yyyy'));
                  });

                  $('#weekPicker').datepicker({ format: 'yyyy-WW', autoclose: true, calendarWeeks: true }).on('changeDate', function(e) {
                      @this.set('weekUpdated', e.format(0, 'yyyy-WW'));
                  });
                  break;
              case 'MONTH':
                  options = { format: 'yyyy-mm', startView: 'months', minViewMode: 'months', autoclose: true };


                  $('#yearMonthStartPicker').datepicker(options).on('changeDate', function(e) {
                      @this.set('monthStartUpdated', e.format(0, options.format));
                  });


                  $('#yearMonthEndPicker').datepicker(options).on('changeDate', function(e) {
                      @this.set('monthEndUpdated', e.format(0, options.format));
                  });
                  break;
              case 'YEAR':
                  options = { format: 'yyyy-mm', startView: 'months', minViewMode: 'months', autoclose: true };

                  $('#yearMonthStartPicker').datepicker(options).on('changeDate', function(e) {
                      @this.set('yearStartUpdated', e.format(0, options.format));
                  });

                  $('#yearMonthEndPicker').datepicker(options).on('changeDate', function(e) {
                      @this.set('yearEndUpdated', e.format(0, options.format));
                  });
                  break;
          }
      }

  document.addEventListener('livewire:load', function () {
      // $('#select_warehouse').select2();

      // $('#select_warehouse').on('change', function (e) {
      //     var data = $('#select_warehouse').select2("val");
      //     @this.set('selectedWarehouseOption', data);
      // });

      // $('#select_client').select2({
      //   minimumInputLength: 3,
      // });

      // $('#select_client').on('change', function (e) {
      //     var data = $('#select_client').select2("val");
      //     @this.set('selectedClientOption', data);
      // });

      // $('#select_client_group').select2({
      //   placeholder: "{{ trans('app.select_client_group') }}",
      //   allowClear: true, 
      //   minimumInputLength: 3,
      // });

      // $('#select_client_group').on('change', function (e) {
      //     var data = $('#select_client_group').select2("val");
      //     @this.set('selectedClientGroupOption', data);
      // });

      // $('#select_category_group').select2({
      //   minimumInputLength: 3,
      // });

      // $('#select_category_group').on('change', function (e) {
      //     var data = $('#select_category_group').select2("val");
      //     @this.set('selectedCategoryGroupOption', data);
      // });

      // $('#select_category_sub_group').select2({
      //   minimumInputLength: 3,
      // });

      // $('#select_category_sub_group').on('change', function (e) {
      //     var data = $('#select_category_sub_group').select2("val");
      //     @this.set('selectedCategorySubGroupOption', data);
      // });

      // $('#orderStatus').select2({
      //     minimumResultsForSearch: -1
      // });

      // $('#orderStatus').on('change', function (e) {
      //     var data = $('#orderStatus').select2("val");
      //     @this.set('selectedOrderStatusOption', data);
      // });

      // $('#paymentStatus').select2({
      //     minimumResultsForSearch: -1
      // });

      // $('#paymentStatus').on('change', function (e) {
      //     var data = $('#paymentStatus').select2("val");
      //     @this.set('selectedPaymentStatusOption', data);
      // });

      initializeDatepicker(@this.selectedIntervalOption);

      window.addEventListener('reinitialize-datepicker', event => {
          initializeDatepicker(event.detail.interval);
      });

      // $('#yearPicker').datepicker({
      //     format: "yyyy",
      //     viewMode: "years",
      //     minViewMode: "years",
      //     autoclose: true
      // }).on('changeDate', function(e) {
      //     @this.emit('yearWeekUpdated', e.format(0, 'yyyy'));
      // });
  });
</script>

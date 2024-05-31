<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Charts\VisitorsOfMonths;
<<<<<<< HEAD
use App\Helpers\Status;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Warehouse\WarehouseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Order\OrderRepository;
=======
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Warehouse\WarehouseRepository;
use Illuminate\Support\Facades\DB;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)

class Dashboard extends Component
{
    public $customer_count = 0;
    public $new_customer_last_30_days = 0;

<<<<<<< HEAD
    public $total_profit = 0;
    public $total_order_created = 0;

    public $qty_ordered = 0;
    public $gross_value = 0;

    public $orders_process = 0;
    public $packing_process = 0;
    public $delivery_process = 0;
    public $payment_process = 0;
=======
    public $merchant_count = 0;
    public $new_merchant_last_30_days = 0;

    public $total_order_count = 0;
    public $todays_all_order_count = 0;
    public $yesterdays_all_order_count = 0;

    public $todays_sale_amount = 0;
    public $yesterdays_sale_amount = 0;

    public $pending_verifications= 0;
    public $pending_approvals= 0;
    public $dispute_count= 0;
    public $last_60days_dispute_count= 0;
    public $last_30days_dispute_count= 0;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)

    //option
    public $customers;
    public $shops;
    public $warehouses;
    public $clients;
    public $client_groups;
    public $category_groups;
    public $category_sub_groups;

    //filter #1
    public $selectedWarehouseOption = '';
    public $selectedClientOption = '';
    public $selectedClientGroupOption = '';
    public $selectedCategoryGroupOption = '';
    public $selectedCategorySubGroupOption = '';
    public $selectedOrderStatusOption = 'all';
    public $selectedPaymentStatusOption = 'all';
    public $productName = '';
    public $userName = '';

    //filter #2
<<<<<<< HEAD
    public $selectedIntervalOption = '';
=======
    public $selectedIntervalOption = 'WEEK';
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    public $selectedStartDate = '';
    public $selectedEndDate = '';
    public $selectedYearWeek = '';
    public $selectedWeek = '';
    public $selectedYearMonthStart = '';
    public $selectedYearMonthEnd = '';
    public $selectedYearStart = '';
    public $selectedYearEnd = '';


    protected $listeners = [
        'startDateUpdated' => 'updatedSelectedStartDate',
        'endDateUpdated' => 'updatedSelectedEndDate',
        'yearWeekUpdated' => 'updatedSelectedYearWeek',
        'weekUpdated' => 'updatedSelectedWeek',
        'monthStartUpdated' => 'updatedselectedYearMonthStart',
        'monthEndUpdated' => 'updatedselectedYearMonthEnd',
        'yearStartUpdated' => 'updatedselectedYearStart',
        'yearEndUpdated' => 'updatedselectedYearEnd',
<<<<<<< HEAD
        'resetTimeFrameFilter' => 'resettingTimeFrameFilter',
    ];
    //card
    public $card1_options = [];

    //charts
    public $chart1_data_d1 = [];
    public $chart1_data_d2 = [];
    public $chart1_data_d3 = [];

    //table
    public $table1_options = [];
    public $table1_data;
    public $table2_data;
    public $table3_data;
    public $table4_data;
    public $table5_data;
    public $table6_data;
    public $table7_data;
=======
    ];
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)

    public function mount()
    {
        $this->warehouses = User::where('warehouse_name', 'LIKE', 'Warehouse%')
        ->groupBy('warehouse_name')
        ->pluck('warehouse_name', 'id');
        $this->clients = Customer::select('id', 'name')->distinct('name')->get()  ->pluck('name', 'id');
        $this->client_groups = Customer::whereNotNull('hospital_group')
            ->distinct()
            ->pluck('hospital_group');
        $this->category_groups = DB::table('products as p')
            ->leftJoin('category_product as cp', 'p.id', '=', 'cp.product_id')
            ->leftJoin('categories as c', 'cp.category_id', '=', 'c.id')
            ->leftJoin('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
            ->where('p.manufacture_skuid', '!=', '')
            ->groupBy('c.name', 'cp.category_id')
            ->select('cp.category_id', 'c.name')
            ->pluck('c.name', 'cp.category_id');
<<<<<<< HEAD
=======
        $this->category_groups = DB::table('products as p')
            ->leftJoin('category_product as cp', 'p.id', '=', 'cp.product_id')
            ->leftJoin('categories as c', 'cp.category_id', '=', 'c.id')
            ->leftJoin('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
            ->where('p.manufacture_skuid', '!=', '')
            ->groupBy('c.name', 'cp.category_id')
            ->select('cp.category_id', 'c.name')
            ->pluck('c.name', 'cp.category_id');
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
        $this->category_sub_groups = DB::table('products as p')
            ->leftJoin('category_product as cp', 'p.id', '=', 'cp.product_id')
            ->leftJoin('categories as c', 'cp.category_id', '=', 'c.id')
            ->leftJoin('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
            ->where('p.manufacture_skuid', '!=', '')
            ->groupBy('csg.name', 'c.category_sub_group_id')
            ->select('c.category_sub_group_id', 'csg.name')
            ->pluck('csg.name', 'c.category_sub_group_id');
<<<<<<< HEAD

        $this->customer_count = DB::table('orders')->whereNotNull('customer_id')
            ->distinct()->count('customer_id');
        $totalPurchasePrice = DB::table('order_items as oi')
                    ->select(DB::raw('SUM((oi.quantity * p.purchase_price)) as total_profit'))
                    ->join('orders as o', 'oi.order_id', '=', 'o.id')
                    ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
                    ->join('products as p', 'i.product_id', '=', 'p.id')
                    ->whereNotNull('oi.inventory_id')->first('total_profit');
        $grandTotal = DB::table('orders')
                    ->select(DB::raw('SUM(grand_total) as grand_total'))
                    ->first('grand_total');
    
        $totalOrder = DB::table('orders')->whereNotNull('customer_id')->count();
        $totalQuantityOrders = DB::table('orders')->whereNotNull('customer_id')->sum('quantity');
        $totalGrandTotalofOrders = DB::table('orders')->whereNotNull('customer_id')->sum('grand_total');
        $totalOrderProcessed = DB::table('orders')->whereNotNull('customer_id')->count();
        $totalPackingProcessed = DB::table('orders')->whereNotNull(['customer_id', 'order_status_id'])->whereIn('order_status_id', [10, 6])->where('payment_status' ,3)->count();
        $totalDeliveredProcessed = DB::table('orders')->whereNotNull(['customer_id', 'order_status_id'])->where('order_status_id', 6)->where('payment_status' ,3)->count();
        $totalpaymentProcessed = DB::table('orders')->whereNotNull(['customer_id', 'order_status_id', 'payment_status'])->where('payment_status' ,3)->count();
    
        $this->total_profit = intval($grandTotal->grand_total) - intval($totalPurchasePrice->total_profit);
        $this->new_customer_last_30_days = 0;
        $this->total_order_created = $totalOrder;
        $this->qty_ordered = $totalQuantityOrders;
        $this->gross_value = $totalGrandTotalofOrders;
        $this->orders_process = $totalOrderProcessed;
        $this->packing_process = $totalPackingProcessed;
        $this->delivery_process = $totalDeliveredProcessed;
        $this->payment_process = $totalpaymentProcessed;

        //charts
        // $currentMonth = date('Y-m');
        $currentMonth = "02";

        $orderCounts = DB::table('orders')
            ->select(DB::raw('DATE(created_at) AS order_date'), DB::raw('COUNT(*) AS count'))
            ->where('order_status_id', '<>', '8')  // Exclude cancelled orders
            ->groupBy('order_date')  // Group by date
            ->get();

        $orderMTDCounts = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) AS order_date'),
                DB::raw('COUNT(*) AS count'),
                DB::raw('SUM(CASE WHEN DATE(created_at) = DATE(CURDATE()) THEN 1 ELSE 0 END) AS current_day_count'),
                DB::raw('SUM(COUNT(*)) OVER (PARTITION BY MONTH(created_at) ORDER BY created_at ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS mtd_count')
            )
                ->where('order_status_id', '<>', '8') // Exclude cancelled orders
                // ->whereMonth('created_at', '=', $currentMonth)  // Filter for current month
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->groupBy('order_date')
                ->orderBy('order_date')
                ->get();

        $warehouseCount = DB::table('order_items as oi')
            ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
            ->join('users as u', 'i.user_id', '=', 'u.id')
            ->select(
                'u.warehouse_name as name', 
                DB::raw('COUNT(*) as count_order'), 
            )
            ->groupBy('u.warehouse_name')
            ->get();


        $inventories = DB::table('inventories as i')
            ->join('users as u', 'i.user_id', '=', 'u.id')
            ->join('products', 'i.product_id', '=', 'products.id')
            ->select(
                'u.warehouse_name as warehouse_name',
                'products.name as product_name',
                'i.expired_date as expired_date',
                'i.stock_quantity as qty',
                DB::raw('(i.sold_quantity / DATEDIFF(CURDATE(), i.available_from)) as avg_selling_qty'),
                'i.sale_price as selling_price',
                'i.purchase_price as buying_price',
                DB::raw('(i.sale_price * i.stock_quantity) as total'),
                'i.condition_note as note',
                DB::raw('SUM(i.sale_price * i.stock_quantity) OVER () as grand_total')
            )
            ->get();

        $log_activity = DB::table('activity_log as al')
            ->join('orders as o', 'al.subject_id', '=', 'o.id')
            ->join('users as u', 'al.causer_id', '=', 'u.id')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->select(
                'o.created_at as date_order',
                'u.name as username',
                'c.name as hospital_name',
                'o.po_number_ref as no_po_ref',
                'al.properties as status'
            )
            ->where('al.log_name', 'order')
            ->limit(10)
            ->orderBy('o.updated_at', 'DESC')
            ->get();

        $top_customers = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->select(
                'c.name as name', 
                DB::raw('COUNT(o.id) as count_order'), 
                DB::raw('SUM(o.total) as revenue'), 
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN o.total ELSE 0 END) as last_month'),
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) THEN o.total ELSE 0 END) as last_year'),
            )
            ->groupBy('c.id', 'c.name')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        $warehouse_revenue = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->select(
                'c.name as name', 
                DB::raw('COUNT(o.id) as count_order'), 
                DB::raw('SUM(o.total) as revenue'), 
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN o.total ELSE 0 END) as last_month'),
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) THEN o.total ELSE 0 END) as last_year'),
            )
            ->groupBy('c.id', 'c.name')
            ->orderBy('c.name', 'desc')
            ->take(10)
            ->get();

        $processedResults = $log_activity->map(function ($item) {
            $properties = json_decode($item->status, true);
            
            $statusChange = '';
        
            if (isset($properties['attributes']['order_status_id']) && isset($properties['old']['order_status_id'])) {
                $fromStatus = Order::$statusMessages[$properties['old']['order_status_id']] ?? $properties['old']['order_status_id'];
                $toStatus = Order::$statusMessages[$properties['attributes']['order_status_id']] ?? $properties['attributes']['order_status_id'];
                $statusChange = "Order from $fromStatus to $toStatus";
            }
        
            if (isset($properties['attributes']['payment_status']) && isset($properties['old']['payment_status'])) {
                $fromPaymentStatus = Order::$paymentStatusMessages[$properties['old']['payment_status']] ?? $properties['old']['payment_status'];
                $toPaymentStatus = Order::$paymentStatusMessages[$properties['attributes']['payment_status']] ?? $properties['attributes']['payment_status'];
                $statusChange = "Payment from $fromPaymentStatus to $toPaymentStatus";
            }
        
            return [
                'date_order' => $item->date_order,
                'username' => $item->username,
                'hospital_name' => $item->hospital_name,
                'no_po_ref' => $item->no_po_ref,
                'status' => $statusChange
            ];
        });

        $worst_product = DB::table('order_items AS oi')
            ->select('p.name', DB::raw('COUNT(*) AS count_order'), DB::raw('SUM(oi.unit_price * oi.quantity) AS revenue'),
                DB::raw('(SELECT SUM(oi2.unit_price * oi2.quantity) FROM order_items AS oi2 WHERE oi2.inventory_id = oi.inventory_id AND MONTH(oi2.created_at) = MONTH(SUBDATE(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(oi2.created_at) = YEAR(SUBDATE(CURDATE(), INTERVAL 1 MONTH))) AS last_month_revenue'),
                DB::raw('(SELECT SUM(oi2.unit_price * oi2.quantity) FROM order_items AS oi2 WHERE oi2.inventory_id = oi.inventory_id AND YEAR(oi2.created_at) = YEAR(SUBDATE(CURDATE(), INTERVAL 1 YEAR))) AS last_year_revenue'))
            ->join('inventories AS i', 'oi.inventory_id', '=', 'i.id')
            ->join('products AS p', 'i.product_id', '=', 'p.id')
            ->groupBy('p.id', 'i.id')
            ->orderBy('revenue', 'ASC')
            ->get();

        $kpi_users = $query = DB::table('users AS u')
            ->select([
                'u.name AS employee_name',
                'u.warehouse_name',
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.created_by = u.id) AS confirmed'),
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.packed_by = u.id) AS packed'),
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.delivery_by = u.id) AS delivered'),
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.paid_by = u.id) AS paided'),
                DB::raw('(((SELECT COUNT(*) FROM orders o2 WHERE o2.created_by = u.id))+ (SELECT COUNT(*) FROM orders o2 WHERE o2.packed_by = u.id) + (SELECT COUNT(*) FROM orders o2 WHERE o2.delivery_by = u.id) + (SELECT COUNT(*) FROM orders o2 WHERE o2.paid_by = u.id)) AS total'),
            ])
            ->leftJoin('orders AS o', 'u.id', '=', 'o.created_by')
            ->where('u.warehouse_name', '<>', '')
            ->groupBy('u.id')
            ->orderBy('total', 'desc')
            ->orderBy('u.warehouse_name')
            ->get();

        // $stock_movement = DB::table('activity_log as al')
        //     ->join('inventories as i', 'al.subject_id', '=', 'i.id')
        //     ->join('products as p', 'i.product_id', '=', 'p.id')
        //     ->join('users as u', 'i.updated_by', '=', 'u.id')
        //     ->select(
        //         'o.created_at as date',
        //         'u.name as from',
        //         'c.name as to',
        //         'o.po_number_ref as product_',
        //         'u.name as updated_by'
        //     )
        //     ->where('al.log_name', 'inventory')
        //     ->limit(10)
        //     ->orderBy('o.updated_at', 'DESC')
        //     ->get();

        $this->table1_data = json_decode(json_encode($inventories), true);

        $this->table2_data = [
            [
              "date" => "2024-06-14",
              "from" => "10",
              "to" => "5",
              "product_desc" => "Product A (10 units)",
              "qty" => 5,
              "updated_by" => "John Doe",
            ],
            [
              "date" => "2024-06-13",
              "from" => "100",
              "to" => "75",
              "product_desc" => "Product B (5 units)",
              "qty" => 25,
              "updated_by" => "Jane Smith",
            ],
            // Add more data entries following the same structure
          ];

          //charts
          $this->chart1_data_d1 = $orderCounts;
          $this->chart1_data_d2 = $warehouseCount;
          $this->chart1_data_d3 = $orderMTDCounts;
        //   $this->table2_data = ;
          $this->table3_data = json_decode(json_encode($processedResults), true); 
          $this->table4_data = json_decode(json_encode($top_customers), true);
          $this->table5_data = json_decode(json_encode($warehouse_revenue), true);
          $this->table6_data = json_decode(json_encode($worst_product), true);
          $this->table7_data = json_decode(json_encode($kpi_users), true);
        
=======
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

<<<<<<< HEAD
    public function clear()
    {
        $this->selectedWarehouseOption = '';
        $this->selectedClientOption = '';
        $this->selectedClientGroupOption = '';
        $this->selectedCategoryGroupOption = '';
        $this->selectedCategorySubGroupOption = '';
        $this->selectedOrderStatusOption = 'all';
        $this->selectedPaymentStatusOption = 'all';
        $this->productName = '';
        $this->userName = '';

        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
    }

=======
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    public function updatedSelectedIntervalOption()
    {
        $this->dispatchBrowserEvent('reinitialize-datepicker', ['interval' => $this->selectedIntervalOption]);
    }

    public function updatedSelectedWarehouseOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedClientOption($value)
    {
<<<<<<< HEAD
        $this->selectedClientOption = $value;
        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
=======
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedSelectedClientGroupOption($value)
    {
<<<<<<< HEAD
        $this->selectedClientGroupOption = $value;
        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
=======
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedSelectedCategorySubGroupOption($value)
    {
<<<<<<< HEAD
        $this->selectedCategorySubGroupOption = $value;
        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
=======
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedSelectedOrderStatusOption($value)
    {
<<<<<<< HEAD
        $this->selectedOrderStatusOption = $value;
        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
=======
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedSelectedPaymentStatusOption($value)
    {
<<<<<<< HEAD
        $this->selectedPaymentStatusOption = $value;
        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
=======
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedProductName($value)
    {
<<<<<<< HEAD
        $this->selectedproductName = $value;
        $this->updateCardSection();
        $this->updateProcessCountSection();
        $this->updateChartSection();
        $this->updateTableSection();
=======
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedUserName($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedIntervalOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedStartDate($value)
    {
        $this->selectedStartDate = $value;
    }

    public function updatedSelectedEndDate($value)
    {
        $this->selectedEndDate = $value;
    }

    public function updatedSelectedYearWeek($value)
    {
        $this->selectedYearWeek = $value;
    }

    public function updatedSelectedWeek($value)
    {
        $this->selectedWeek = $value;
    }

    public function updatedselectedYearMonthStart($value)
    {
<<<<<<< HEAD
        $this->selectedYearMonthStart = $value;
=======
        $this->selectedWeek = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedselectedYearMonthEnd($value)
    {
<<<<<<< HEAD
        $this->selectedYearMonthEnd = $value;
=======
        $this->selectedWeek = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedselectedYearStart($value)
    {
<<<<<<< HEAD
        $this->selectedYearStart = $value;
=======
        $this->selectedWeek = $value;
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
    }

    public function updatedselectedYearEnd($value)
    {
        $this->selectedWeek = $value;
    }
<<<<<<< HEAD

    public function updateTableSection()
    {
        $this->updatedTable1Data();
        $this->updatedTable2Data();
        $this->updatedTable3Data();
        $this->updatedTable4Data();
        $this->updatedTable5Data();
        $this->updatedTable6Data();
        $this->updatedTable7Data();
    }

    protected function ordersBaseQuery()
    {
        return DB::table('orders as o')
        ->leftJoin('order_items as oi', 'o.id', '=', 'oi.order_id')
        ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
        ->join('products as p', 'i.product_id', '=', 'p.id')
        ->join('customers as cust', 'o.customer_id', '=', 'cust.id');
    }

    protected function orderGeneralFilter()
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;
        
        return $this->ordersBaseQuery()
        ->when($this->selectedWarehouseOption !== '', function($q) {
            return $q->where('o.shop_id', $this->selectedWarehouseOption);
        })
        ->when($this->selectedClientOption !== '', function($q) {
            return $q->where('o.customer_id', $this->selectedClientOption);
        })
        ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
            return $q
                ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                // ->where('p.manufacture_skuid', '!=', '')
                ->where('cust.hospital_group', 'LIKE' ,'%'.$selectedClientGroupOption.'%');
        })
        ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
            // return $q->where('shop_id', $this->selectedWarehouseOption);
            return $q
                ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                // ->where('p.manufacture_skuid', '!=', '')
                ->where('csg.category_group_id',  $selectedCategoryGroupOption);
        })
        ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
            return $q
                ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                // ->where('p.manufacture_skuid', '!=', '')
                ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
        })
        ->when($this->selectedOrderStatusOption != 'all', function($q) {
            return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
        })
        ->when($this->selectedPaymentStatusOption != 'all', function($q) {
            return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
        })
        ->when($this->productName != '', function($q) use ($productName) {
            return $q
                ->where('oi.item_description', 'LIKE', "%{$productName}%");
        })
        ->when($this->userName != '', function($q) use ($userName) {
            return $q->where('cust.name', 'LIKE', "%{$this->userName}%");
        })->whereNotNull('customer_id');
    }

    protected function ordersFilters()
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;

        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        $selectedYearWeek = $this->selectedYearWeek;
        $selectedWeek = $this->selectedWeek;
        $selectedYearMonthStart = $this->selectedYearMonthStart;
        $selectedYearMonthEnd = $this->selectedYearMonthEnd;
        $selectedYearStart = $this->selectedYearStart;
        $selectedYearEnd = $this->selectedYearEnd;

        return $this->ordersBaseQuery()
        ->when($this->selectedWarehouseOption !== '', function($q) {
            return $q->where('o.shop_id', $this->selectedWarehouseOption);
        })
        ->when($this->selectedClientOption !== '', function($q) {
            return $q->where('o.customer_id', $this->selectedClientOption);
        })
        ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
            return $q
                ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                // ->where('p.manufacture_skuid', '!=', '')
                ->where('cust.hospital_group', 'LIKE' ,'%'.$selectedClientGroupOption.'%');
        })
        ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
            // return $q->where('shop_id', $this->selectedWarehouseOption);
            return $q
                ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                // ->where('p.manufacture_skuid', '!=', '')
                ->where('csg.category_group_id',  $selectedCategoryGroupOption);
        })
        ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
            return $q
                ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                // ->where('p.manufacture_skuid', '!=', '')
                ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
        })
        ->when($this->selectedOrderStatusOption != 'all', function($q) {
            return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
        })
        ->when($this->selectedPaymentStatusOption != 'all', function($q) {
            return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
        })
        ->when($this->productName != '', function($q) use ($productName) {
            return $q
                ->where('oi.item_description', 'LIKE', "%{$productName}%");
        })
        ->when($this->userName != '', function($q) use ($userName) {
            return $q->where('cust.name', 'LIKE', "%{$this->userName}%");
        })
        ->when($this->selectedIntervalOption !== '', function($q) use (
            $selectedStartDate,
            $selectedEndDate,
            $selectedYearWeek,
            $selectedWeek,
            $selectedYearMonthStart,
            $selectedYearMonthEnd,
            $selectedYearStart,
            $selectedYearEnd
        ){
            if ($this->selectedIntervalOption == 'DAILY') {
                // Filter for records between start and end date (inclusive)
                return $q->whereBetween('o.created_at', [$selectedStartDate, $selectedEndDate]);
            } else if ($this->selectedIntervalOption == 'MONTH') {
               // Extract start and end month/year
                $startMonth = (int)date('m', strtotime($selectedYearMonthStart));
                $startYear = (int)date('Y', strtotime($selectedYearMonthStart));
                $endMonth = (int)date('m', strtotime($selectedYearMonthEnd));
                $endYear = (int)date('Y', strtotime($selectedYearMonthEnd));

                // Handle filtering based on start and end month/year
                if ($startYear === $endYear) {
                    // Same year, filter for records within the specified month range (inclusive)
                    return $q->whereMonth('o.created_at', '>=', $startMonth)
                            ->whereMonth('o.created_at', '<=', $endMonth)
                            ->whereYear('o.created_at', $startYear);
                } else {
                    // Different years, handle filtering across year boundaries
                    $q = $q->where(function ($subquery) use ($startMonth, $startYear, $endMonth, $endYear) {
                        $subquery->whereMonth('o.created_at', '>=', $startMonth)
                                ->whereYear('o.created_at', $startYear);
                        if ($startYear !== $endYear - 1) {
                            // Filter for all months in between start and end year (excluding end year)
                            $subquery->orWhere(function ($subsubquery) use ($endYear) {
                                $subsubquery->whereYear('o.created_at', '>', $startYear)
                                        ->whereYear('o.created_at', '<', $endYear);
                            });
                        }
                        $subquery->orWhereMonth('o.created_at', '<=', $endMonth)
                                ->whereYear('o.created_at', $endYear);
                    });
                }
                return $q;
            } else if ($this->selectedIntervalOption == 'YEAR') {
                // Extract start and end year
                $startYear = (int)date('Y', strtotime($selectedYearStart));
                $endYear = (int)date('Y', strtotime($selectedYearEnd));

                // Filter for records within the specified year range (inclusive)
                return $q->whereYear('o.created_at', '>=', $startYear)
                        ->whereYear('o.created_at', '<=', $endYear);
            } else {
                // Handle invalid interval option (optional)
                return $q;
            }
        })
        ->whereNotNull('customer_id');
    }

    protected function ordersTimeFrameFilter()
    {
        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        $selectedYearWeek = $this->selectedYearWeek;
        $selectedWeek = $this->selectedWeek;
        $selectedYearMonthStart = $this->selectedYearMonthStart;
        $selectedYearMonthEnd = $this->selectedYearMonthEnd;
        $selectedYearStart = $this->selectedYearStart;
        $selectedYearEnd = $this->selectedYearEnd;

        //timeframe filter
        return $this->ordersBaseQuery()
        ->when($this->selectedIntervalOption !== '', function($q) use (
            $selectedStartDate,
            $selectedEndDate,
            $selectedYearWeek,
            $selectedWeek,
            $selectedYearMonthStart,
            $selectedYearMonthEnd,
            $selectedYearStart,
            $selectedYearEnd
        ){
            if ($this->selectedIntervalOption == 'DAILY') {
                // Filter for records between start and end date (inclusive)
                return $q->whereBetween('o.created_at', [$selectedStartDate, $selectedEndDate]);
            } else if ($this->selectedIntervalOption == 'MONTH') {
               // Extract start and end month/year
                $startMonth = (int)date('m', strtotime($selectedYearMonthStart));
                $startYear = (int)date('Y', strtotime($selectedYearMonthStart));
                $endMonth = (int)date('m', strtotime($selectedYearMonthEnd));
                $endYear = (int)date('Y', strtotime($selectedYearMonthEnd));

                // Handle filtering based on start and end month/year
                if ($startYear === $endYear) {
                    // Same year, filter for records within the specified month range (inclusive)
                    return $q->whereMonth('o.created_at', '>=', $startMonth)
                            ->whereMonth('o.created_at', '<=', $endMonth)
                            ->whereYear('o.created_at', $startYear);
                } else {
                    // Different years, handle filtering across year boundaries
                    $q = $q->where(function ($subquery) use ($startMonth, $startYear, $endMonth, $endYear) {
                        $subquery->whereMonth('o.created_at', '>=', $startMonth)
                                ->whereYear('o.created_at', $startYear);
                        if ($startYear !== $endYear - 1) {
                            // Filter for all months in between start and end year (excluding end year)
                            $subquery->orWhere(function ($subsubquery) use ($endYear) {
                                $subsubquery->whereYear('o.created_at', '>', $startYear)
                                        ->whereYear('o.created_at', '<', $endYear);
                            });
                        }
                        $subquery->orWhereMonth('o.created_at', '<=', $endMonth)
                                ->whereYear('o.created_at', $endYear);
                    });
                }
                return $q;
            } else if ($this->selectedIntervalOption == 'YEAR') {
                // Extract start and end year
                $startYear = (int)date('Y', strtotime($selectedYearStart));
                $endYear = (int)date('Y', strtotime($selectedYearEnd));

                // Filter for records within the specified year range (inclusive)
                return $q->whereYear('o.created_at', '>=', $startYear)
                        ->whereYear('o.created_at', '<=', $endYear);
            } else {
                // Handle invalid interval option (optional)
                return $q;
            }
        });
    }
    
    //filter
    public function updatedCustomerCount()
    {   

        $this->customer_count = $this->ordersFilters()
            ->distinct()
            ->count('o.customer_id');

        //updated
    }

    public function updatedTotalProfit()
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;

        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        $selectedYearWeek = $this->selectedYearWeek;
        $selectedWeek = $this->selectedWeek;
        $selectedYearMonthStart = $this->selectedYearMonthStart;
        $selectedYearMonthEnd = $this->selectedYearMonthEnd;
        $selectedYearStart = $this->selectedYearStart;
        $selectedYearEnd = $this->selectedYearEnd;

        $totalPurchasePrice = DB::table('order_items as oi')
                    ->select(DB::raw('SUM((oi.quantity * p.purchase_price)) as total_profit'))
                    ->join('orders as o', 'oi.order_id', '=', 'o.id')
                    ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
                    ->join('products as p', 'i.product_id', '=', 'p.id')
                    ->when($this->selectedWarehouseOption !== '', function($q) {
                        return $q->where('o.shop_id', $this->selectedWarehouseOption);
                    })
                    ->when($this->selectedClientOption !== '', function($q) {
                        return $q->where('o.customer_id', $this->selectedClientOption);
                    })
                    ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
                        return $q
                            ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                            // ->where('p.manufacture_skuid', '!=', '')
                            ->where('cp.category_id',  $selectedClientGroupOption);
                    })
                    ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
                        // return $q->where('shop_id', $this->selectedWarehouseOption);
                        return $q
                            ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                            ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                            ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                            // ->where('p.manufacture_skuid', '!=', '')
                            ->where('csg.category_group_id',  $selectedCategoryGroupOption);
                    })
                    ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
                        return $q
                            ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                            ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                            ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                            // ->where('p.manufacture_skuid', '!=', '')
                            ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
                    })
                    ->when($this->selectedOrderStatusOption != 'all', function($q) {
                        return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
                    })
                    ->when($this->selectedPaymentStatusOption != 'all', function($q) {
                        return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
                    })
                    ->when($this->productName != '', function($q) use ($productName) {
                        return $q
                            ->where('oi.item_description', 'LIKE', "%{$productName}%");
                    })
                    ->when($this->userName != '', function($q) use ($userName) {
                        return $q->join('customers as cust', 'o.customer_id', '=', 'cust.id')
                                ->where('cust.name', 'LIKE', "%{$this->userName}%");
                    })
                    ->whereNotNull('o.customer_id')
                    //timeframe filter
                    ->when($this->selectedIntervalOption !== '', function($q) use (
                        $selectedStartDate,
                        $selectedEndDate,
                        $selectedYearWeek,
                        $selectedWeek,
                        $selectedYearMonthStart,
                        $selectedYearMonthEnd,
                        $selectedYearStart,
                        $selectedYearEnd
                    ){
                        if ($this->selectedIntervalOption == 'DAILY') {
                            // Filter for records between start and end date (inclusive)
                            return $q->whereBetween('o.created_at', [$selectedStartDate, $selectedEndDate]);
                        } else if ($this->selectedIntervalOption == 'MONTH') {
                           // Extract start and end month/year
                            $startMonth = (int)date('m', strtotime($selectedYearMonthStart));
                            $startYear = (int)date('Y', strtotime($selectedYearMonthStart));
                            $endMonth = (int)date('m', strtotime($selectedYearMonthEnd));
                            $endYear = (int)date('Y', strtotime($selectedYearMonthEnd));
        
                            // Handle filtering based on start and end month/year
                            if ($startYear === $endYear) {
                                // Same year, filter for records within the specified month range (inclusive)
                                return $q->whereMonth('o.created_at', '>=', $startMonth)
                                        ->whereMonth('o.created_at', '<=', $endMonth)
                                        ->whereYear('o.created_at', $startYear);
                            } else {
                                // Different years, handle filtering across year boundaries
                                $q = $q->where(function ($subquery) use ($startMonth, $startYear, $endMonth, $endYear) {
                                    $subquery->whereMonth('o.created_at', '>=', $startMonth)
                                            ->whereYear('o.created_at', $startYear);
                                    if ($startYear !== $endYear - 1) {
                                        // Filter for all months in between start and end year (excluding end year)
                                        $subquery->orWhere(function ($subsubquery) use ($endYear) {
                                            $subsubquery->whereYear('o.created_at', '>', $startYear)
                                                    ->whereYear('o.created_at', '<', $endYear);
                                        });
                                    }
                                    $subquery->orWhereMonth('o.created_at', '<=', $endMonth)
                                            ->whereYear('o.created_at', $endYear);
                                });
                            }
                            return $q;
                        } else if ($this->selectedIntervalOption == 'YEAR') {
                            // Extract start and end year
                            $startYear = (int)date('Y', strtotime($selectedYearStart));
                            $endYear = (int)date('Y', strtotime($selectedYearEnd));
        
                            // Filter for records within the specified year range (inclusive)
                            return $q->whereYear('o.created_at', '>=', $startYear)
                                    ->whereYear('o.created_at', '<=', $endYear);
                        } else {
                            // Handle invalid interval option (optional)
                            return $q;
                        }
                    })
                    ->whereNotNull('oi.inventory_id')->first('total_profit');

        $grandTotal = $this->ordersFilters()
                    ->select(DB::raw('SUM(grand_total) as grand_total'))
                    ->first('grand_total');

        $this->total_profit = intval($grandTotal->grand_total) - intval($totalPurchasePrice->total_profit);
    }

    public function updatedTotalOrders()
    {

        $totalOrder = $this->ordersFilters()->whereNotNull('o.customer_id')->count();

        $this->total_order_created = $totalOrder;
    }

    public function updatedQtyOrdered()
    {

        $totalQuantityOrders = $this->ordersFilters()->whereNotNull('o.customer_id')->sum('o.quantity');

        $this->qty_ordered = $totalQuantityOrders;
    }

    public function updatedGrandTotal() {

        $totalGrandTotalofOrders = $this->ordersFilters()->whereNotNull('o.customer_id')->sum('o.grand_total');

        $this->gross_value = $totalGrandTotalofOrders;
    }

    public function toggleFilter($filter)
    {
        // Reset all filters to false
        $this->selectedThisWeekFilter = false;
        $this->selectedThisMonthFilter = false;
        $this->selectedThisYearFilter = false;

        // Set the clicked filter to true
        $this->$filter = true;
    }

    //updated count process
    public function updatedOrdersProcess()
    {
        $totalOrderProcessed = $this->ordersFilters()
        ->whereNotNull('o.customer_id')->count();

        $this->orders_process = $totalOrderProcessed;
    }

    public function updatedPackingProcess()
    {
        $totalPackingProcessed = $this->ordersFilters()
        ->whereNotNull(['o.customer_id', 'o.order_status_id'])->whereIn('o.order_status_id', [10, 6])->where('o.payment_status' ,3)->count();

        $this->packing_process = $totalPackingProcessed;
    }

    public function updatedDeliveryProcess()
    {
        $totalDeliveredProcessed = $this->ordersFilters()
        ->whereNotNull(['o.customer_id', 'o.order_status_id'])->where('o.order_status_id', 6)->where('o.payment_status' ,3)->count();

        $this->delivery_process = $totalDeliveredProcessed;
    }

    public function updatedPaymentProcess()
    {
        $totalpaymentProcessed = $this->ordersFilters()
        ->whereNotNull(['o.customer_id', 'o.order_status_id', 'o.payment_status'])->where('o.payment_status' ,3)->count();

        $this->payment_process = $totalpaymentProcessed;
    }

    //updated chart
    public function updatedChart1DataD1() //PO
    {
        $orderCounts = $this->ordersFilters()
                ->when($this->selectedThisWeekFilter, function($q) {
                    // Filter for current week (Sunday to Saturday)
                    $startOfWeek = (new Carbon\Carbon('this week'))->startOfWeek()->format('Y-m-d');
                    $endOfWeek = (new Carbon\Carbon('this week'))->endOfWeek()->format('Y-m-d');
                    return $q->whereBetween('o.created_at', [$startOfWeek, $endOfWeek]);
                })
                ->when($this->selectedThisMonthFilter, function($q) {
                    // Filter for current month
                    $startOfMonth = (new Carbon\Carbon('this month'))->startOfMonth()->format('Y-m-d');
                    $endOfMonth = (new Carbon\Carbon('this month'))->endOfMonth()->format('Y-m-d');
                    return $q->whereBetween('o.created_at', [$startOfMonth, $endOfMonth]);
                })
                ->when($this->selectedThisYearFilter, function($q) {
                    // Filter for current year
                    $startOfYear = (new Carbon\Carbon('this year'))->startOfYear()->format('Y-m-d');
                    $endOfYear = (new Carbon\Carbon('this year'))->endOfYear()->format('Y-m-d');
                    return $q->whereBetween('o.created_at', [$startOfYear, $endOfYear]);
                })
                ->select(DB::raw('DATE(o.created_at) AS order_date'), DB::raw('COUNT(o.id) AS count'))
                ->where('o.order_status_id', '<>', '8')  // Exclude cancelled orders
                ->groupBy('order_date')  // Group by date
                ->get();

        $this->chart1_data_d1 = $orderCounts;
    }

    public function updatedChart1DataD3() //po mtd
    {
        // $currentMonth = date('Y-m');
        $currentMonth = "02";

        $orderMTDCounts = 
            DB::table('orders as o')
            //$this->ordersFilters()
            // ->when($this->selectedThisWeekFilter, function($q) {
            //     // Filter for current week (Sunday to Saturday)
            //     $startOfWeek = (new Carbon\Carbon('this week'))->startOfWeek();
            //     $endOfWeek = (new Carbon\Carbon('this week'))->endOfWeek();
            //     return $q->whereBetween('o.created_at', [$startOfWeek, $endOfWeek]);
            // })
            // ->when($this->selectedThisMonthFilter, function($q) {
            //     // Filter for current month
            //     $startOfMonth = (new Carbon\Carbon('this month'))->startOfMonth();
            //     $endOfMonth = (new Carbon\Carbon('this month'))->endOfMonth();
            //     return $q->whereBetween('o.created_at', [$startOfMonth, $endOfMonth]);
            // })
            // ->when($this->selectedThisYearFilter, function($q) {
            //     // Filter for current year
            //     $startOfYear = (new Carbon\Carbon('this year'))->startOfYear();
            //     $endOfYear = (new Carbon\Carbon('this year'))->endOfYear();
            //     return $q->whereBetween('o.created_at', [$startOfYear, $endOfYear]);
            // })
            ->select(
                DB::raw('DATE(o.created_at) AS order_date'),
                DB::raw('COUNT(*) AS count'),
                DB::raw('SUM(CASE WHEN DATE(o.created_at) = DATE(CURDATE()) THEN 1 ELSE 0 END) AS current_day_count'),
                DB::raw('SUM(COUNT(*)) OVER (PARTITION BY MONTH(o.created_at) ORDER BY o.created_at ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS mtd_count')
            )
            ->where('order_status_id', '<>', '8') // Exclude cancelled orders
            ->whereMonth('o.created_at', '=', $currentMonth)  // Filter for current month
            ->whereRaw('MONTH(o.created_at) = ?', [$currentMonth])
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get();

        $this->chart1_data_d3 = $orderMTDCounts;        
    }

    public function updatedChart1DataD2() //pie
    {
        $warehouseCount = DB::table('order_items as oi')
        ->join('orders as o', 'o.id', '=', 'oi.order_id')
        ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
        ->join('users as u', 'i.user_id', '=', 'u.id')
        ->select(
            'u.warehouse_name as name', 
            DB::raw('COUNT(*) as count_order'), 
        )
        ->when($this->selectedThisWeekFilter, function($q) {
            // Filter for current week (Sunday to Saturday)
            $startOfWeek = (new Carbon\Carbon('this week'))->startOfWeek()->format('Y-m-d');
            $endOfWeek = (new Carbon\Carbon('this week'))->endOfWeek()->format('Y-m-d');
            return $q->whereBetween('o.created_at', [$startOfWeek, $endOfWeek]);
        })
        ->when($this->selectedThisMonthFilter, function($q) {
            // Filter for current month
            $startOfMonth = (new Carbon\Carbon('this month'))->startOfMonth()->format('Y-m-d');
            $endOfMonth = (new Carbon\Carbon('this month'))->endOfMonth()->format('Y-m-d');
            return $q->whereBetween('o.created_at', [$startOfMonth, $endOfMonth]);
        })
        ->when($this->selectedThisYearFilter, function($q) {
            // Filter for current year
            $startOfYear = (new Carbon\Carbon('this year'))->startOfYear()->format('Y-m-d');
            $endOfYear = (new Carbon\Carbon('this year'))->endOfYear()->format('Y-m-d');
            return $q->whereBetween('o.created_at', [$startOfYear, $endOfYear]);
        })
        ->groupBy('u.warehouse_name')
        ->get();
        
        $this->chart1_data_d2 = $warehouseCount;
    }

    //updated table
    public function updatedTable1Data() //stock
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;

        $inventories = DB::table('inventories as i')
            ->join('users as u', 'i.user_id', '=', 'u.id')
            ->join('products', 'i.product_id', '=', 'products.id')
            ->leftJoin('orders AS o', 'u.id', '=', 'o.created_by')
            ->leftJoin('order_items as oi', 'o.id', '=', 'oi.order_id')
            ->join('customers as cust', 'o.customer_id', '=', 'cust.id')
            ->select(
                'u.warehouse_name as warehouse_name',
                'products.name as product_name',
                'i.expired_date as expired_date',
                'i.stock_quantity as qty',
                DB::raw('(i.sold_quantity / DATEDIFF(CURDATE(), i.available_from)) as avg_selling_qty'),
                'i.sale_price as selling_price',
                'i.purchase_price as buying_price',
                DB::raw('(i.sale_price * i.stock_quantity) as total'),
                'i.condition_note as note',
                DB::raw('SUM(i.sale_price * i.stock_quantity) OVER () as grand_total')
            )
            ->when($this->selectedWarehouseOption !== '', function($q) {
                return $q->where('o.shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedClientOption !== '', function($q) {
                return $q->where('o.customer_id', $this->selectedClientOption);
            })
            ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
                return $q
                    ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('cust.hospital_group', 'LIKE' ,'%'.$selectedClientGroupOption.'%');
            })
            ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
                // return $q->where('shop_id', $this->selectedWarehouseOption);
                return $q
                    ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                    ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                    ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('csg.category_group_id',  $selectedCategoryGroupOption);
            })
            ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
                return $q
                    ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                    ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                    ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
            })
            ->when($this->selectedOrderStatusOption != 'all', function($q) {
                return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
            })
            ->when($this->selectedPaymentStatusOption != 'all', function($q) {
                return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
            })
            ->when($this->productName != '', function($q) use ($productName) {
                return $q
                    ->where('oi.item_description', 'LIKE', "%{$productName}%");
            })
            ->when($this->userName != '', function($q) use ($userName) {
                return $q->where('cust.name', 'LIKE', "%{$this->userName}%");
            })
            
            ->whereNotNull('customer_id')
            ->get();

        $this->table1_data = $inventories->map(function ($inventory) use ($inventories) {
            $inventory->selling_price = get_formated_currency($inventory->selling_price, 2);
            $inventory->buying_price = get_formated_currency($inventory->buying_price, 2);
            $inventory->total = get_formated_currency($inventory->total, 2);
            $inventory->grand_total = get_formated_currency($inventory->grand_total, 2);
            return $inventory;
        });

        $this->table1_data = json_decode(json_encode($inventories), true);
    }
    
    public function updatedTable2Data() //log stock movement
    {
        $this->table2_data = [
            [
              "date" => "2024-06-14",
              "from" => "10",
              "to" => "5",
              "product_desc" => "Product A (10 units)",
              "qty" => 5,
              "updated_by" => "John Doe",
            ],
            [
              "date" => "2024-06-13",
              "from" => "100",
              "to" => "75",
              "product_desc" => "Product B (5 units)",
              "qty" => 25,
              "updated_by" => "Jane Smith",
            ],
            // Add more data entries following the same structure
          ];
    }

    public function updatedTable3Data() //log activity
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;

        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        $selectedYearWeek = $this->selectedYearWeek;
        $selectedWeek = $this->selectedWeek;
        $selectedYearMonthStart = $this->selectedYearMonthStart;
        $selectedYearMonthEnd = $this->selectedYearMonthEnd;
        $selectedYearStart = $this->selectedYearStart;
        $selectedYearEnd = $this->selectedYearEnd;

        $log_activity = DB::table('activity_log as al')
            ->join('orders as o', 'al.subject_id', '=', 'o.id')
            ->join('users as u', 'al.causer_id', '=', 'u.id')
            ->join('customers as cust', 'o.customer_id', '=', 'cust.id')
            ->leftJoin('order_items as oi', 'o.id', '=', 'oi.order_id')
            ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->select(
                'o.created_at as date_order',
                'u.name as username',
                'cust.name as hospital_name',
                'o.po_number_ref as no_po_ref',
                'al.properties as status'
            )
            ->when($this->selectedWarehouseOption !== '', function($q) {
                return $q->where('o.shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedClientOption !== '', function($q) {
                return $q->where('o.customer_id', $this->selectedClientOption);
            })
            ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
                return $q
                    ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('cust.hospital_group', 'LIKE' ,'%'.$selectedClientGroupOption.'%');
            })
            ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
                // return $q->where('shop_id', $this->selectedWarehouseOption);
                return $q
                    ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                    ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                    ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('csg.category_group_id',  $selectedCategoryGroupOption);
            })
            ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
                return $q
                    ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                    ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                    ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
            })
            ->when($this->selectedOrderStatusOption != 'all', function($q) {
                return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
            })
            ->when($this->selectedPaymentStatusOption != 'all', function($q) {
                return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
            })
            ->when($this->productName != '', function($q) use ($productName) {
                return $q
                    ->where('oi.item_description', 'LIKE', "%{$productName}%");
            })
            ->when($this->userName != '', function($q) use ($userName) {
                return $q->where('cust.name', 'LIKE', "%{$this->userName}%");
            })
            ->when($this->selectedIntervalOption !== '', function($q) use (
                $selectedStartDate,
                $selectedEndDate,
                $selectedYearWeek,
                $selectedWeek,
                $selectedYearMonthStart,
                $selectedYearMonthEnd,
                $selectedYearStart,
                $selectedYearEnd
            ){
                if ($this->selectedIntervalOption == 'DAILY') {
                    // Filter for records between start and end date (inclusive)
                    return $q->whereBetween('o.created_at', [$selectedStartDate, $selectedEndDate]);
                } else if ($this->selectedIntervalOption == 'MONTH') {
                   // Extract start and end month/year
                    $startMonth = (int)date('m', strtotime($selectedYearMonthStart));
                    $startYear = (int)date('Y', strtotime($selectedYearMonthStart));
                    $endMonth = (int)date('m', strtotime($selectedYearMonthEnd));
                    $endYear = (int)date('Y', strtotime($selectedYearMonthEnd));
    
                    // Handle filtering based on start and end month/year
                    if ($startYear === $endYear) {
                        // Same year, filter for records within the specified month range (inclusive)
                        return $q->whereMonth('o.created_at', '>=', $startMonth)
                                ->whereMonth('o.created_at', '<=', $endMonth)
                                ->whereYear('o.created_at', $startYear);
                    } else {
                        // Different years, handle filtering across year boundaries
                        $q = $q->where(function ($subquery) use ($startMonth, $startYear, $endMonth, $endYear) {
                            $subquery->whereMonth('o.created_at', '>=', $startMonth)
                                    ->whereYear('o.created_at', $startYear);
                            if ($startYear !== $endYear - 1) {
                                // Filter for all months in between start and end year (excluding end year)
                                $subquery->orWhere(function ($subsubquery) use ($endYear) {
                                    $subsubquery->whereYear('o.created_at', '>', $startYear)
                                            ->whereYear('o.created_at', '<', $endYear);
                                });
                            }
                            $subquery->orWhereMonth('o.created_at', '<=', $endMonth)
                                    ->whereYear('o.created_at', $endYear);
                        });
                    }
                    return $q;
                } else if ($this->selectedIntervalOption == 'YEAR') {
                    // Extract start and end year
                    $startYear = (int)date('Y', strtotime($selectedYearStart));
                    $endYear = (int)date('Y', strtotime($selectedYearEnd));
    
                    // Filter for records within the specified year range (inclusive)
                    return $q->whereYear('o.created_at', '>=', $startYear)
                            ->whereYear('o.created_at', '<=', $endYear);
                } else {
                    // Handle invalid interval option (optional)
                    return $q;
                }
            })
            ->whereNotNull('customer_id')
            ->where('al.log_name', 'order')
            ->limit(10)
            ->orderBy('o.updated_at', 'DESC')
            ->get();

        $processedResults = $log_activity->map(function ($item) {
            $properties = json_decode($item->status, true);
            
            $statusChange = '';
        
            if (isset($properties['attributes']['order_status_id']) && isset($properties['old']['order_status_id'])) {
                $fromStatus = Order::$statusMessages[$properties['old']['order_status_id']] ?? $properties['old']['order_status_id'];
                $toStatus = Order::$statusMessages[$properties['attributes']['order_status_id']] ?? $properties['attributes']['order_status_id'];
                $statusChange = "Order from $fromStatus to $toStatus";
            }
        
            if (isset($properties['attributes']['payment_status']) && isset($properties['old']['payment_status'])) {
                $fromPaymentStatus = Order::$paymentStatusMessages[$properties['old']['payment_status']] ?? $properties['old']['payment_status'];
                $toPaymentStatus = Order::$paymentStatusMessages[$properties['attributes']['payment_status']] ?? $properties['attributes']['payment_status'];
                $statusChange = "Payment from $fromPaymentStatus to $toPaymentStatus";
            }
        
            return [
                'date_order' => $item->date_order,
                'username' => $item->username,
                'hospital_name' => $item->hospital_name,
                'no_po_ref' => $item->no_po_ref,
                'status' => $statusChange
            ];
        });

        $this->table3_data = json_decode(json_encode($processedResults), true);
    }
    public function updatedTable4Data() //top customer table
    {
        $top_customers = $this->ordersFilters() //filter 1,2
            //filter 3
            ->when($this->selectedThisWeekFilter, function($q) {
                // Filter for current week (Sunday to Saturday)
                $startOfWeek = (new Carbon\Carbon('this week'))->startOfWeek()->format('Y-m-d');
                $endOfWeek = (new Carbon\Carbon('this week'))->endOfWeek()->format('Y-m-d');
                return $q->whereBetween('o.created_at', [$startOfWeek, $endOfWeek]);
            })
            ->when($this->selectedThisMonthFilter, function($q) {
                // Filter for current month
                $startOfMonth = (new Carbon\Carbon('this month'))->startOfMonth()->format('Y-m-d');
                $endOfMonth = (new Carbon\Carbon('this month'))->endOfMonth()->format('Y-m-d');
                return $q->whereBetween('o.created_at', [$startOfMonth, $endOfMonth]);
            })
            ->when($this->selectedThisYearFilter, function($q) {
                // Filter for current year
                $startOfYear = (new Carbon\Carbon('this year'))->startOfYear()->format('Y-m-d');
                $endOfYear = (new Carbon\Carbon('this year'))->endOfYear()->format('Y-m-d');
                return $q->whereBetween('o.created_at', [$startOfYear, $endOfYear]);
            })
            ->select(
                'cust.name as name', 
                DB::raw('COUNT(o.id) as count_order'), 
                DB::raw('SUM(o.total) as revenue'), 
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN o.total ELSE 0 END) as last_month'),
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) THEN o.total ELSE 0 END) as last_year'),
            )
            ->groupBy('cust.id', 'cust.name')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        $this->table4_data = $top_customers->map(function ($cust) use ($top_customers) {
            $cust->revenue = get_formated_currency($cust->revenue, 2);
            $cust->last_month = get_formated_currency($cust->last_month, 2);
            $cust->last_year = get_formated_currency($cust->last_year, 2);

            return $top_customers;
        });

        $this->table4_data = json_decode(json_encode($top_customers), true);
    }
    public function updatedTable5Data() //warehouse table
    {
        $warehouse_revenue = $this->ordersFilters() //filter 1,2
                //filter 3
                ->when($this->selectedThisWeekFilter, function($q) {
                    // Filter for current week (Sunday to Saturday)
                    $startOfWeek = (new Carbon\Carbon('this week'))->startOfWeek()->format('Y-m-d');
                    $endOfWeek = (new Carbon\Carbon('this week'))->endOfWeek()->format('Y-m-d');
                    return $q->whereBetween('o.created_at', [$startOfWeek, $endOfWeek]);
                })
                ->when($this->selectedThisMonthFilter, function($q) {
                    // Filter for current month
                    $startOfMonth = (new Carbon\Carbon('this month'))->startOfMonth()->format('Y-m-d');
                    $endOfMonth = (new Carbon\Carbon('this month'))->endOfMonth()->format('Y-m-d');
                    return $q->whereBetween('o.created_at', [$startOfMonth, $endOfMonth]);
                })
                ->when($this->selectedThisYearFilter, function($q) {
                    // Filter for current year
                    $startOfYear = (new Carbon\Carbon('this year'))->startOfYear()->format('Y-m-d');
                    $endOfYear = (new Carbon\Carbon('this year'))->endOfYear()->format('Y-m-d');
                    return $q->whereBetween('o.created_at', [$startOfYear, $endOfYear]);
                })
            ->select(
                'cust.name as name', 
                DB::raw('COUNT(o.id) as count_order'), 
                DB::raw('SUM(o.total) as revenue'), 
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN o.total ELSE 0 END) as last_month'),
                DB::raw('SUM(CASE WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) THEN o.total ELSE 0 END) as last_year'),
            )
            ->groupBy('cust.id', 'cust.name')
            ->orderBy('cust.name', 'desc')
            ->take(10)
            ->get();
        
        $this->table5_data = $warehouse_revenue->map(function ($warehouses) use ($warehouse_revenue) {
                $warehouses->revenue = get_formated_currency($warehouses->revenue, 2);
                $warehouses->last_month = get_formated_currency($warehouses->last_month, 2);
                $warehouses->last_year = get_formated_currency($warehouses->last_year, 2);
    
                return $warehouse_revenue;
            });

        $this->table5_data = json_decode(json_encode($warehouse_revenue), true);
    }
    public function updatedTable6Data() //top worst product
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;

        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        $selectedYearWeek = $this->selectedYearWeek;
        $selectedWeek = $this->selectedWeek;
        $selectedYearMonthStart = $this->selectedYearMonthStart;
        $selectedYearMonthEnd = $this->selectedYearMonthEnd;
        $selectedYearStart = $this->selectedYearStart;
        $selectedYearEnd = $this->selectedYearEnd;

        $worst_product = DB::table('order_items AS oi')
            ->select('p.name', DB::raw('COUNT(*) AS count_order'), DB::raw('SUM(oi.unit_price * oi.quantity) AS revenue'),
                DB::raw('(SELECT SUM(oi2.unit_price * oi2.quantity) FROM order_items AS oi2 WHERE oi2.inventory_id = oi.inventory_id AND MONTH(oi2.created_at) = MONTH(SUBDATE(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(oi2.created_at) = YEAR(SUBDATE(CURDATE(), INTERVAL 1 MONTH))) AS last_month_revenue'),
                DB::raw('(SELECT SUM(oi2.unit_price * oi2.quantity) FROM order_items AS oi2 WHERE oi2.inventory_id = oi.inventory_id AND YEAR(oi2.created_at) = YEAR(SUBDATE(CURDATE(), INTERVAL 1 YEAR))) AS last_year_revenue'))
                ->join('orders as o', 'oi.order_id', '=', 'o.id')
                ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
                ->join('products as p', 'i.product_id', '=', 'p.id')
                ->when($this->selectedWarehouseOption !== '', function($q) {
                    return $q->where('o.shop_id', $this->selectedWarehouseOption);
                })
                ->when($this->selectedClientOption !== '', function($q) {
                    return $q->where('o.customer_id', $this->selectedClientOption);
                })
                ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
                    return $q
                        ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                        // ->where('p.manufacture_skuid', '!=', '')
                        ->where('cp.category_id',  $selectedClientGroupOption);
                })
                ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
                    // return $q->where('shop_id', $this->selectedWarehouseOption);
                    return $q
                        ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                        ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                        ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                        // ->where('p.manufacture_skuid', '!=', '')
                        ->where('csg.category_group_id',  $selectedCategoryGroupOption);
                })
                ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
                    return $q
                        ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                        ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                        ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                        // ->where('p.manufacture_skuid', '!=', '')
                        ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
                })
                ->when($this->selectedOrderStatusOption != 'all', function($q) {
                    return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
                })
                ->when($this->selectedPaymentStatusOption != 'all', function($q) {
                    return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
                })
                ->when($this->productName != '', function($q) use ($productName) {
                    return $q
                        ->where('oi.item_description', 'LIKE', "%{$productName}%");
                })
                ->when($this->userName != '', function($q) use ($userName) {
                    return $q->join('customers as cust', 'o.customer_id', '=', 'cust.id')
                            ->where('cust.name', 'LIKE', "%{$this->userName}%");
                })
                ->whereNotNull('o.customer_id')
                //timeframe filter
                ->when($this->selectedIntervalOption !== '', function($q) use (
                    $selectedStartDate,
                    $selectedEndDate,
                    $selectedYearWeek,
                    $selectedWeek,
                    $selectedYearMonthStart,
                    $selectedYearMonthEnd,
                    $selectedYearStart,
                    $selectedYearEnd
                ){
                    if ($this->selectedIntervalOption == 'DAILY') {
                        // Filter for records between start and end date (inclusive)
                        return $q->whereBetween('o.created_at', [$selectedStartDate, $selectedEndDate]);
                    } else if ($this->selectedIntervalOption == 'MONTH') {
                    // Extract start and end month/year
                        $startMonth = (int)date('m', strtotime($selectedYearMonthStart));
                        $startYear = (int)date('Y', strtotime($selectedYearMonthStart));
                        $endMonth = (int)date('m', strtotime($selectedYearMonthEnd));
                        $endYear = (int)date('Y', strtotime($selectedYearMonthEnd));
    
                        // Handle filtering based on start and end month/year
                        if ($startYear === $endYear) {
                            // Same year, filter for records within the specified month range (inclusive)
                            return $q->whereMonth('o.created_at', '>=', $startMonth)
                                    ->whereMonth('o.created_at', '<=', $endMonth)
                                    ->whereYear('o.created_at', $startYear);
                        } else {
                            // Different years, handle filtering across year boundaries
                            $q = $q->where(function ($subquery) use ($startMonth, $startYear, $endMonth, $endYear) {
                                $subquery->whereMonth('o.created_at', '>=', $startMonth)
                                        ->whereYear('o.created_at', $startYear);
                                if ($startYear !== $endYear - 1) {
                                    // Filter for all months in between start and end year (excluding end year)
                                    $subquery->orWhere(function ($subsubquery) use ($endYear) {
                                        $subsubquery->whereYear('o.created_at', '>', $startYear)
                                                ->whereYear('o.created_at', '<', $endYear);
                                    });
                                }
                                $subquery->orWhereMonth('o.created_at', '<=', $endMonth)
                                        ->whereYear('o.created_at', $endYear);
                            });
                        }
                        return $q;
                    } else if ($this->selectedIntervalOption == 'YEAR') {
                        // Extract start and end year
                        $startYear = (int)date('Y', strtotime($selectedYearStart));
                        $endYear = (int)date('Y', strtotime($selectedYearEnd));
    
                        // Filter for records within the specified year range (inclusive)
                        return $q->whereYear('o.created_at', '>=', $startYear)
                                ->whereYear('o.created_at', '<=', $endYear);
                    } else {
                        // Handle invalid interval option (optional)
                        return $q;
                    }
                })
            ->when($this->selectedThisWeekFilter, function($q) {
                // Filter for current week (Sunday to Saturday)
                $startOfWeek = (new Carbon\Carbon('this week'))->startOfWeek()->format('Y-m-d');
                $endOfWeek = (new Carbon\Carbon('this week'))->endOfWeek()->format('Y-m-d');
                return $q->whereBetween('o.created_at', [$startOfWeek, $endOfWeek]);
            })
            ->when($this->selectedThisMonthFilter, function($q) {
                // Filter for current month
                $startOfMonth = (new Carbon\Carbon('this month'))->startOfMonth()->format('Y-m-d');
                $endOfMonth = (new Carbon\Carbon('this month'))->endOfMonth()->format('Y-m-d');
                return $q->whereBetween('o.created_at', [$startOfMonth, $endOfMonth]);
            })
            ->when($this->selectedThisYearFilter, function($q) {
                // Filter for current year
                $startOfYear = (new Carbon\Carbon('this year'))->startOfYear()->format('Y-m-d');
                $endOfYear = (new Carbon\Carbon('this year'))->endOfYear()->format('Y-m-d');
                return $q->whereBetween('o.created_at', [$startOfYear, $endOfYear]);
            })
            ->groupBy('p.id', 'i.id')
            ->orderBy('revenue', 'ASC')
            ->get();

        $this->table6_data = $worst_product->map(function ($worst) use ($worst_product) {
                $worst->last_month_revenue = get_formated_currency($worst->last_month_revenue, 2);
                $worst->last_year_revenue = get_formated_currency($worst->last_year_revenue, 2);
                $worst->revenue = get_formated_currency($worst->revenue, 2);
    
                return $worst_product;
            });
        
        $this->table6_data = json_decode(json_encode($worst_product), true);
    }
    public function updatedTable7Data() //kpi table
    {
        $productName = $this->productName;
        $userName = $this->userName;
        $selectedClientGroupOption = $this->selectedClientGroupOption;
        $selectedCategorySubGroupOption = $this->selectedCategorySubGroupOption;
        $selectedCategoryGroupOption = $this->selectedCategoryGroupOption;

        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        $selectedYearWeek = $this->selectedYearWeek;
        $selectedWeek = $this->selectedWeek;
        $selectedYearMonthStart = $this->selectedYearMonthStart;
        $selectedYearMonthEnd = $this->selectedYearMonthEnd;
        $selectedYearStart = $this->selectedYearStart;
        $selectedYearEnd = $this->selectedYearEnd;

        $kpi_users = $query = DB::table('users AS u')
            ->leftJoin('orders AS o', 'u.id', '=', 'o.created_by')
            ->leftJoin('order_items as oi', 'o.id', '=', 'oi.order_id')
            ->leftJoin('inventories as i', 'oi.inventory_id', '=', 'i.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('customers as cust', 'o.customer_id', '=', 'cust.id')
            ->select([
                'u.name AS employee_name',
                'u.warehouse_name',
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.created_by = u.id) AS confirmed'),
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.packed_by = u.id) AS packed'),
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.delivery_by = u.id) AS delivered'),
                DB::raw('(SELECT COUNT(*) FROM orders o2 WHERE o2.paid_by = u.id) AS paided'),
                DB::raw('(((SELECT COUNT(*) FROM orders o2 WHERE o2.created_by = u.id))+ (SELECT COUNT(*) FROM orders o2 WHERE o2.packed_by = u.id) + (SELECT COUNT(*) FROM orders o2 WHERE o2.delivery_by = u.id) + (SELECT COUNT(*) FROM orders o2 WHERE o2.paid_by = u.id)) AS total'),
            ])
            ->when($this->selectedWarehouseOption !== '', function($q) {
                return $q->where('o.shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedClientOption !== '', function($q) {
                return $q->where('o.customer_id', $this->selectedClientOption);
            })
            ->when($this->selectedClientGroupOption !== '', function($q) use ($selectedClientGroupOption){
                return $q
                    ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('cust.hospital_group', 'LIKE' ,'%'.$selectedClientGroupOption.'%');
            })
            ->when($this->selectedCategoryGroupOption !== '', function($q) use ($selectedCategoryGroupOption){
                // return $q->where('shop_id', $this->selectedWarehouseOption);
                return $q
                    ->join('category_product as cp2', 'p.id', '=', 'cp2.product_id')
                    ->join('categories as c', 'cp2.category_id', '=', 'c.id')
                    ->join('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('csg.category_group_id',  $selectedCategoryGroupOption);
            })
            ->when($this->selectedCategorySubGroupOption !== '', function($q) use ($selectedCategorySubGroupOption){
                return $q
                    ->join('category_product as cp3', 'p.id', '=', 'cp3.product_id')
                    ->join('categories as c2', 'cp3.category_id', '=', 'c2.id')
                    ->join('category_sub_groups as csg2', 'c2.category_sub_group_id', '=', 'csg2.id')
                    // ->where('p.manufacture_skuid', '!=', '')
                    ->where('c2.category_sub_group_id',  $selectedCategorySubGroupOption);
            })
            ->when($this->selectedOrderStatusOption != 'all', function($q) {
                return $q->where('o.order_status_id', Status::getStatusCode($this->selectedOrderStatusOption));
            })
            ->when($this->selectedPaymentStatusOption != 'all', function($q) {
                return $q->where('o.payment_status', Status::getStatusCode($this->selectedPaymentStatusOption));
            })
            ->when($this->productName != '', function($q) use ($productName) {
                return $q
                    ->where('oi.item_description', 'LIKE', "%{$productName}%");
            })
            ->when($this->userName != '', function($q) use ($userName) {
                return $q->where('cust.name', 'LIKE', "%{$this->userName}%");
            })
            ->when($this->selectedIntervalOption !== '', function($q) use (
                $selectedStartDate,
                $selectedEndDate,
                $selectedYearWeek,
                $selectedWeek,
                $selectedYearMonthStart,
                $selectedYearMonthEnd,
                $selectedYearStart,
                $selectedYearEnd
            ){
                if ($this->selectedIntervalOption == 'DAILY') {
                    // Filter for records between start and end date (inclusive)
                    return $q->whereBetween('o.created_at', [$selectedStartDate, $selectedEndDate]);
                } else if ($this->selectedIntervalOption == 'MONTH') {
                   // Extract start and end month/year
                    $startMonth = (int)date('m', strtotime($selectedYearMonthStart));
                    $startYear = (int)date('Y', strtotime($selectedYearMonthStart));
                    $endMonth = (int)date('m', strtotime($selectedYearMonthEnd));
                    $endYear = (int)date('Y', strtotime($selectedYearMonthEnd));
    
                    // Handle filtering based on start and end month/year
                    if ($startYear === $endYear) {
                        // Same year, filter for records within the specified month range (inclusive)
                        return $q->whereMonth('o.created_at', '>=', $startMonth)
                                ->whereMonth('o.created_at', '<=', $endMonth)
                                ->whereYear('o.created_at', $startYear);
                    } else {
                        // Different years, handle filtering across year boundaries
                        $q = $q->where(function ($subquery) use ($startMonth, $startYear, $endMonth, $endYear) {
                            $subquery->whereMonth('o.created_at', '>=', $startMonth)
                                    ->whereYear('o.created_at', $startYear);
                            if ($startYear !== $endYear - 1) {
                                // Filter for all months in between start and end year (excluding end year)
                                $subquery->orWhere(function ($subsubquery) use ($endYear) {
                                    $subsubquery->whereYear('o.created_at', '>', $startYear)
                                            ->whereYear('o.created_at', '<', $endYear);
                                });
                            }
                            $subquery->orWhereMonth('o.created_at', '<=', $endMonth)
                                    ->whereYear('o.created_at', $endYear);
                        });
                    }
                    return $q;
                } else if ($this->selectedIntervalOption == 'YEAR') {
                    // Extract start and end year
                    $startYear = (int)date('Y', strtotime($selectedYearStart));
                    $endYear = (int)date('Y', strtotime($selectedYearEnd));
    
                    // Filter for records within the specified year range (inclusive)
                    return $q->whereYear('o.created_at', '>=', $startYear)
                            ->whereYear('o.created_at', '<=', $endYear);
                } else {
                    // Handle invalid interval option (optional)
                    return $q;
                }
            })
            // ->whereNotNull('customer_id')
            ->where('u.warehouse_name', '<>', '')
            ->groupBy('u.id')
            ->orderBy('total', 'desc')
            ->orderBy('u.warehouse_name')
            ->get();

        $this->table7_data = json_decode(json_encode($kpi_users), true);
    }

=======
>>>>>>> 82d7c7fd1 (feat: :sparkles: [WIP] - new dashboard)
}

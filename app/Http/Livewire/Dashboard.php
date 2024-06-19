<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Charts\VisitorsOfMonths;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Warehouse\WarehouseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Order\OrderRepository;

class Dashboard extends Component
{
    public $customer_count = 0;
    public $new_customer_last_30_days = 0;

    public $total_profit = 0;
    public $total_order_created = 0;

    public $qty_ordered = 0;
    public $gross_value = 0;

    public $orders_process = 0;
    public $packing_process = 0;
    public $delivery_process = 0;
    public $payment_process = 0;

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
    public $selectedIntervalOption = '';
    public $selectedStartDate = '';
    public $selectedEndDate = '';
    public $selectedYearWeek = '';
    public $selectedWeek = '';
    public $selectedYearMonthStart = '';
    public $selectedYearMonthEnd = '';
    public $selectedYearStart = '';
    public $selectedYearEnd = '';

    public $selectedThisWeekFilter = true; // Set initial selection to This Week
    public $selectedThisMonthFilter = false;
    public $selectedThisYearFilter = false;

    protected $listeners = [
        'startDateUpdated' => 'updatedSelectedStartDate',
        'endDateUpdated' => 'updatedSelectedEndDate',
        'yearWeekUpdated' => 'updatedSelectedYearWeek',
        'weekUpdated' => 'updatedSelectedWeek',
        'monthStartUpdated' => 'updatedselectedYearMonthStart',
        'monthEndUpdated' => 'updatedselectedYearMonthEnd',
        'yearStartUpdated' => 'updatedselectedYearStart',
        'yearEndUpdated' => 'updatedselectedYearEnd',
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

    public function mount()
    {
        $this->warehouses = User::where('warehouse_name', 'LIKE', 'Warehouse%')
        ->groupBy('warehouse_name')
        ->pluck('warehouse_name', 'shop_id');
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
        $this->category_groups = DB::table('products as p')
            ->leftJoin('category_product as cp', 'p.id', '=', 'cp.product_id')
            ->leftJoin('categories as c', 'cp.category_id', '=', 'c.id')
            ->leftJoin('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
            ->where('p.manufacture_skuid', '!=', '')
            ->groupBy('c.name', 'cp.category_id')
            ->select('cp.category_id', 'c.name')
            ->pluck('c.name', 'cp.category_id');
        $this->category_sub_groups = DB::table('products as p')
            ->leftJoin('category_product as cp', 'p.id', '=', 'cp.product_id')
            ->leftJoin('categories as c', 'cp.category_id', '=', 'c.id')
            ->leftJoin('category_sub_groups as csg', 'c.category_sub_group_id', '=', 'csg.id')
            ->where('p.manufacture_skuid', '!=', '')
            ->groupBy('csg.name', 'c.category_sub_group_id')
            ->select('c.category_sub_group_id', 'csg.name')
            ->pluck('csg.name', 'c.category_sub_group_id');

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
        
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function updatedSelectedIntervalOption()
    {
        $this->dispatchBrowserEvent('reinitialize-datepicker', ['interval' => $this->selectedIntervalOption]);
    }

    public function updatedSelectedWarehouseOption($value)
    {
        $this->selectedWarehouseOption = $value;
        $this->updateCardSection();
    }

    public function updatedSelectedClientOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedClientGroupOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedCategorySubGroupOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedOrderStatusOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedSelectedPaymentStatusOption($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedProductName($value)
    {
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
    }

    public function updatedUserName($value)
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
        $this->selectedYearMonthStart = $value;
    }

    public function updatedselectedYearMonthEnd($value)
    {
        $this->selectedYearMonthEnd = $value;
    }

    public function updatedselectedYearStart($value)
    {
        $this->selectedYearStart = $value;
    }

    public function updatedselectedYearEnd($value)
    {
        $this->selectedYearEnd = $value;
    }

    public function resettingTimeFrameFilter() 
    {
        $this->selectedStartDate = '';
        $this->selectedEndDate = '';
        $this->selectedYearWeek = '';
        $this->selectedWeek = '';
        $this->selectedYearMonthStart = '';
        $this->selectedYearMonthEnd = '';
        $this->selectedYearStart = '';
        $this->selectedYearEnd = '';
    }

    public function updateCardSection()
    {
        $this->updatedCustomerCount();
        $this->updatedTotalProfit();
        $this->updatedTotalOrders();
        $this->updatedQtyOrdered();
        $this->updatedGrandTotal();
    }
    
    public function updatedCustomerCount()
    {
        // $this->customer_count = $this->customer_count + 1;
        $this->customer_count = DB::table('orders')
            ->when($this->selectedWarehouseOption !== '', function($q) {
                return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedClientOption !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedClientGroupOption !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedCategoryGroupOption !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedCategorySubGroupOption !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedOrderStatusOption !== 'all', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->selectedPaymentStatusOption !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->productName !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->when($this->userName !== '', function($q) {
                // return $q->where('shop_id', $this->selectedWarehouseOption);
            })
            ->whereNotNull('customer_id')
            //timeframe filter
            ->when($this->selectedIntervalOption !== '', function($q) {
                if ($this->selectedIntervalOption == 'DAILY') {
                    return $q;
                } else if ($this->selectedIntervalOption == 'WEEK') {
                    return $q;
                } else if ($this->selectedIntervalOption == 'MONTH') {
                    return $q;
                } else if ($this->selectedIntervalOption == 'YEAR') {
                    return $q;
                } else {
                    return $q;
                }
            })
            ->distinct()
            ->count('customer_id');

        //updated
    }

    public function updatedTotalProfit()
    {
        $totalProfit = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->join('inventories as i', 'oi.inventory_id', '=', 'i.id')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->when($this->selectedWarehouseOption !== '', function($q) {
                return $q->where('o.shop_id', $this->selectedWarehouseOption);
            })
            ->whereNotNull('oi.inventory_id')
            ->select(DB::raw('SUM(oi.unit_price - (oi.quantity * p.purchase_price)) as total_profit'))
            ->first();

        $this->total_profit = $totalProfit->total_profit;
    }

    public function updatedTotalOrders()
    {
        $totalOrder = DB::table('orders')
        ->when($this->selectedWarehouseOption !== '', function($q) {
            return $q->where('shop_id', $this->selectedWarehouseOption);
        })->whereNotNull('customer_id')->count();

        $this->total_order_created = $totalOrder;
    }

    public function updatedQtyOrdered()
    {
        $totalQuantityOrders = DB::table('orders')
        ->when($this->selectedWarehouseOption !== '', function($q) {
            return $q->where('shop_id', $this->selectedWarehouseOption);
        })->whereNotNull('customer_id')->sum('quantity');

        $this->qty_ordered = $totalQuantityOrders;
    }

    public function updatedGrandTotal() {
        $totalGrandTotalofOrders = DB::table('orders')
        ->when($this->selectedWarehouseOption !== '', function($q) {
            return $q->where('shop_id', $this->selectedWarehouseOption);
        })->whereNotNull('customer_id')->sum('grand_total');

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
}

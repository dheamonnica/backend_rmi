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

    //table
    public $table1_options = [];
    public $table1_datas = [];

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

        $this->table1_option = [
            'table_name' => 'ABC'
        ];
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

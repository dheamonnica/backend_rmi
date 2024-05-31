<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Charts\VisitorsOfMonths;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Warehouse\WarehouseRepository;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $customer_count = 0;
    public $new_customer_last_30_days = 0;

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
    public $selectedIntervalOption = 'WEEK';
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
    ];

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
        // Handle the updated select2 value
        // Example: $this->selectedOption = $value;
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
        $this->selectedWeek = $value;
    }

    public function updatedselectedYearMonthEnd($value)
    {
        $this->selectedWeek = $value;
    }

    public function updatedselectedYearStart($value)
    {
        $this->selectedWeek = $value;
    }

    public function updatedselectedYearEnd($value)
    {
        $this->selectedWeek = $value;
    }
}

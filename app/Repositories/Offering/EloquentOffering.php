<?php

namespace App\Repositories\Offering;

use App\Models\Offering;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentOffering extends EloquentRepository implements BaseRepository, OfferingRepository
{
    protected $model;

    public function __construct(Offering $offering)
    {
        $this->model = $offering;
    }

    public function all()
    {
        return Offering::all();
    }

    public function find($id)
    {
        return Offering::find($id);
    }

    public function store(Request $request)
    {
        $offering = parent::store($request);

        return $offering;
    }
}

<?php

namespace App\Models;

use App\Enums\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $table = 'supply';

    protected $fillable = [
        'user_id'
        ,'reference'
        , 'quarries_id'
        , 'transportation_companies_id'
        , 'transport_workers_id','customers_id'
        , 'companies_id'
        , 'supply_orders_id'
        , 'materials_id'
        , 'ton'
        , 'price_per_ton'
        , 'profit'
        , 'total_invoice'
        , 'status'
        , 'opening_amount'
        , 'cost_of_transporting_a_ton'
    ];

    public $timestamps = true;


    protected $casts = [
        'status' => Order::class,
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function quarrie(){
        return $this->belongsTo(Quarries::class , 'quarries_id');
    }

    public function transportation_companies(){
        return $this->belongsTo(TransportationCompanies::class , 'transportation_companies_id');
    }

    public function transport_workers(){
        return $this->belongsTo(TransportWorkers::class , 'transport_workers_id');
    }

    public function customers(){
        return $this->belongsTo(Customers::class , 'customers_id');
    }


    public function company(){
        return $this->belongsTo(Companies::class , 'companies_id');
    }

    public function materials(){
        return $this->belongsTo(Materials::class , 'materials_id');
    }


    public function supply_order(){
        return $this->belongsTo(SupplyOrder::class , 'supply_orders_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;

    protected $table = 'supply_orders';

    protected $fillable = ['customers_id' , 'supply_number' , 'ton' , 'show' , 'status' , 'comment'];

    public $timestamps = true;

    public function invoices(){
        return $this->hasMany(Supply::class , 'supply_orders_id');
    }

    public function customer(){
        return $this->belongsTo(Customers::class , 'customers_id');
    }
}

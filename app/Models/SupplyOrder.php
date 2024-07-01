<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;

    protected $table = 'supply_orders';

    protected $fillable = ['user_id' , 'customers_id' , 'supply_number' , 'ton' , 'show' , 'status' , 'comment'];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function invoices(){
        return $this->hasMany(Supply::class , 'supply_orders_id');
    }

    public function getTotalTonsAttribute()
    {
        return $this->invoices()->sum('ton');
    }

    public function customer(){
        return $this->belongsTo(Customers::class , 'customers_id');
    }
}

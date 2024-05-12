<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = ['name' ,'phone' , 'email' , 'address'];

    protected $casts = [
        'address' => 'array',
    ];

    public $timestamps = true;


    public function orders(){
        return $this->hasMany(SupplyOrder::class , 'customers_id');
    }

}

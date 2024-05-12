<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportWorkers extends Model
{
    use HasFactory;

    protected $table = 'transport_workers';

    protected $fillable = ['transportation_companies_id' , 'name' , 'phone' , 'car_number'];

    public $timestamps = true;

    public function Company(){
        return $this->belongsTo(TransportationCompanies::class , 'transportation_companies_id');
    }
}

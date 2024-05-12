<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationCompanies extends Model
{
    use HasFactory;

    protected $table = 'transportation_companies';

    protected $fillable = ['name' , 'phone' , 'transportation_cost'];

    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $fillable = [
        'expense_type',
        'supply_id',
        'workers_number',
        'trucks_number',
        'workers_hours_number',
        'trucks_hours_number',
        'workers_hourly_price',
        'trucks_hourly_price',
        'transportation_expenses',
        'isSystemuser',
        'userName',
        'user_salary',
        'description',
        'user_id',
        'date',
        'expense',
    ];

    public $timestamps = true;

    public function supply(){
        return $this->belongsTo(Supply::class , 'supply_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function user_salary(){
        return $this->belongsTo(User::class , 'user_salary');
    }
}

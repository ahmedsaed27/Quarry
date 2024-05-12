<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarries extends Model
{
    use HasFactory;

    protected $table = 'quarries';

    protected $fillable = ['name' , 'phone' , 'address'];

    public $timestamps = true;

    public $casts = [
        'address' => 'array'
    ];

    public function materials(){
        return $this->belongsToMany(Materials::class , 'materials_quarries')
        ->withPivot([
            'price'
        ])
        ->withTimestamps();
    }
}

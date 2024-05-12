<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = ['name'];

    public $timestamps = true;


    public function quarries(){
        return $this->belongsToMany(Quarries::class , 'materials_quarries' , 'materials_id'  , 'quarries_id')
        ->withPivot([
            'price'
        ])
        ->withTimestamps();
    }
}

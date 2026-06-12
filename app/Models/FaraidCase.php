<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaraidCase extends Model
{
    protected $table = 'faraid_cases';

    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'deceased_name',
        'death_date',
        'total_estate'
    ];

    public function heirs()
    {
        return $this->hasMany(FaraidHeir::class,'case_id');
    }

    public function results()
    {
        return $this->hasMany(FaraidResult::class,'case_id');
    }
}
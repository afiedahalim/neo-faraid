<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaraidResult extends Model
{
    protected $table = 'faraid_results';

    public $timestamps = false;

    protected $fillable = [
        'case_id',
        'heir_name',
        'fraction',
        'percentage',
        'amount',
        'status'
    ];

    public function case()
    {
        return $this->belongsTo(FaraidCase::class,'case_id');
    }
}
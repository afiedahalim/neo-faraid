<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaraidHeir extends Model
{
    protected $table = 'faraid_heirs';

    public $timestamps = false;

    protected $fillable = [
        'case_id',
        'heir_key',
        'quantity'
    ];

    public function case()
    {
        return $this->belongsTo(FaraidCase::class,'case_id');
    }
}
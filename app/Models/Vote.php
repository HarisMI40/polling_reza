<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    function choise()
    {
        return $this->belongsTo(Choise::class, 'choise_id');
    }

    function user()
    {
        // return 
    }
}

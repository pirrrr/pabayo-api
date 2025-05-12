<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments_tbl'; // match your actual table name

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'modeID', 'modeID');
    }
}


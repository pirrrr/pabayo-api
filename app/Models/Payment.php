<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments_tbl'; // match your actual table name

    protected $fillable = [
        'requestID',
        'modeID', 
        'paymentStatusID', 
        'paymentDate',
        'userID',
        'amount',
    ];



    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'modeID', 'modeID');
    }

    public $timestamps = false; // Disable timestamps if not used


}


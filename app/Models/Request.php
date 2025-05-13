<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\User;
use App\Models\Payment;

class Request extends Model
{
    protected $table = 'requests_tbl';
    protected $primaryKey = 'requestID';
    public $timestamps = false; // because you're manually handling timestamps

    protected $fillable = [
        'ownerID',
        'customerID',
        'serviceID',
        'courierID',
        'statusID',
        'pickupDate',
        'paymentMethod',
        'deliveryDate',
        'sackQuantity',
        'comment',
        'dateCreated',
        'dateUpdated'
    ];


    public function customer()
    {
        return $this->belongsTo(User::class, 'customerID', 'userID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'serviceID', 'serviceID');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'requestID', 'requestID');
    }

}

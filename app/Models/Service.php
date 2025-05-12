<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services_tbl'; // ensure this matches your actual table name

    protected $primaryKey = 'serviceID'; // use the correct PK

    public $timestamps = false;

    // Add the inverse relationship (optional but helpful)
    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerID');
    }
}

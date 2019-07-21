<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerLog extends Model {
    protected $fillable = ['uid', 'trackable_type', 'trackable_id', 'found_at', 'presence'];
}
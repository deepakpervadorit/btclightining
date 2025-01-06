<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DepositTransaction extends Model
{
    
    public function user(){
    	return $this->belongsTo(User::class, 'user_id');
    }
}
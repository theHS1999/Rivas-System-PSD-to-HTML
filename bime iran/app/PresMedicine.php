<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresMedicine extends Model
{
    protected $table = 'presmedicines';
    protected $fillable= ['pres_id','medicine_id','medicine_name','medicine_price',
        'count','open_market_price','total','others_difference','base_insure',
        'franshiz','iran_pay','order_per_hour'];
}

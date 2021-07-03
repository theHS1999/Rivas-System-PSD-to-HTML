<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable= ['user_id','presc_date','reception_date','serviceCenter_id','insured_id','status','city','doctor_type','doctor_expertise',
        'total','total_others_difference', 'total_franshiz','insured_pay','total_base_insure'];
}

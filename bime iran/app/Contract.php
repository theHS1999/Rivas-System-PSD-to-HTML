<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['insurer_id','contract_num','type','start_date',
        'finish_date','others','user_id','taked_sum','taked_insure'];
}

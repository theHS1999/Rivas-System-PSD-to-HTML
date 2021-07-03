<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contractscommit extends Model
{
    protected $fillable = ['commit_id','contract_id','farnshiz_control',
        'insured_f','depend_f','non_depend_f','unit','max_commit'];
}

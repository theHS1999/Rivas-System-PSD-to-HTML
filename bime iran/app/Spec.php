<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spec extends Model
{
    protected $fillable=['spec_id','contract_id','value','insured_id'];
}

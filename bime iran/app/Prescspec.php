<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescspec extends Model
{
    protected $fillable=['presc_id','spec_id','medicines','value'];
}

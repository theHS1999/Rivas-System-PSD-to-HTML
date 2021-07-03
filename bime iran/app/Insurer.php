<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
    protected $fillable= [
        'name','address','phone','fax','mobile','website','mail','treatment_name'
    ];
}

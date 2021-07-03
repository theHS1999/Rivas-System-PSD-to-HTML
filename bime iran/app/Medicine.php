<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable= ['name','price','type','shape','first_insure_percent','equal_for','iran_percent'];
}

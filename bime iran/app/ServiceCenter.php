<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    protected $table = 'servicecenters';
    protected $fillable =['user_id','name','technical_user','sahebe_emtiaz','medical_code','address',
        'phone','fax', 'mobile','website','insures_under_contract','shift','bank','account_num'];
}

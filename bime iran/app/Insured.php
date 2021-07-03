<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insured extends Model
{
    protected $fillable= [
        'relation','insurer_id','contract_id','type','insured_id','sponser_status','status','melli_code','personal_code','fname','lname','father_name',
         'birth_date','birth_cert_num','gender','marrige_status','employed_date','janbaz_percent',
        'base_insure', 'insure_num','group','bank','account','phone','mobile'
    ];
}

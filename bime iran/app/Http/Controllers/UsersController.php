<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Insurer;
use Maatwebsite\Excel\Facades\Excel;
use App\Medicine;


class UsersController extends Controller
{
    public function getTest()
    {
/*array(11) {
        ["srf_ob"]=>
        string(6) "خير"
        ["skf_tgoz"]=>
        float(3)
        ["rnh"]=>
        string(10) "ندارد"
        ["drsd_shm_szmn"]=>
        string(3) "---"
        ["kmt"]=>
        float(23000)
        ["hdor_dr_dftr_snd"]=>
        string(6) "خير"
        ["rondh"]=>
        string(6) "خير"
        ["bmrstn"]=>
        string(8) "نيست"
        ["bmh"]=>
        string(6) "است"
        ["nm_dro"]=>
        string(35) "THEOPHYLLINE-G 120ML SYRUP    SYRUP"
        ["d_dro"]=>
        string(5) "01211"
      }
    }
 *
 * *//*set_time_limit(3000);

        Excel::load('Farma-Original.xls', function($reader) {

       foreach($reader->toObject() as $a){
$med=new Medicine;
                 $med->name=$a['nm_dro'].' '.$a['doz'];
            $med->type=$a['l'];

                 $med->price=str_replace(",", "", $a['kymt']);
                 $med->first_insure_percent=$a['shm_szmn'];
                 $med->save();

             }
            //$reader->dump();



        });
        /*$insurers=Insurer::all();
        return view('index.test',compact('insurers'));*/
        return view('admin.test');
    }
    public function postTest(Request $request){
        $data=$request->all();
        return $data;

    }

    public function getRegister(){
        return view('user/register');
    }
    public function postRegister(Request $request){

    }
    public function getLogin(){
        return view('user/login');
    }



}

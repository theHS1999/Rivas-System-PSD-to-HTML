<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Insured;
use App\Insurer;
use App\Contract;
use App\Commit;
use App\Service;
use App\ContractsCommit;
use App\ContractService;
use App\ServiceCenter;
use App\Medicine;
use App\Prescription;
use App\PresMedicine;
use App\Spec;
use Validator;
use Crypt;
use App\Prescspec;

class HomeController extends Controller
{

    public function getIndex(){
        if($user=Auth::user()){

            if($user->type=='admin'){
                return redirect()->intended('/admin');
            }
            if($user->type=='expert'){
                return redirect()->intended('/expert');
            }
            if($user->type=='center'){
                return redirect()->intended('/center');
            }
        }

        return view('user/login');
    }
    public function postLogin(Request $request){
        $data= $request->all();

        if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
            $user=Auth::user();
            if($user->status==0){
                if($user->type=='admin'){
                    return redirect()->intended('/admin');
                }
                if($user->type=='expert'){
                    return redirect()->intended('/expert');
                }
                if($user->type=='center'){
                    return redirect()->intended('/center');
                }
            }else{
                Auth::logout();
                return redirect('/')->withError('اکانت شما غیر فعال میباشد.');
            }
        }
        else{
            return redirect('/')->withError('اطلاعات وارد شده صحیح نمی باشد.');
        }
    }
    public function getLogout(){
        Auth::logout();
        return redirect('/');
    }

    public function getPrint($id){
        $pr=Prescription::find($id);
        $medicines=Medicine::all();
        $insured=Insured::find($pr->insured_id);
        $prescs=Prescription::whereIn('status',[1,4,5])->orderBy('created_at', 'desc')->get();
        $prescMed=PresMedicine::all();
        $prmeds=PresMedicine::where('pres_id',$pr->id)->get();
        $prescspecs=Prescspec::where('presc_id',$id)->get();
        $contract=Contract::find($insured->contract_id);

        return view('index.print',compact('contract','prescspecs','prmeds','pr','prescMed','medicines','insured','prescs'));

    }
    public function getError(){
        return view('errors.404');
    }

}

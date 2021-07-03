<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Medicine;
use App\Insured;
use App\Insurer;
use App\Contract;
use App\ContractsCommit;
use App\Commit;
use App\Prescription;
use App\PresMedicine;
use App\ServiceCenter;
use Auth;
use App\Setting;
use App\Spec;
use App\Prescspec;
use App\User;
use File;
use App\Specialmed;
use App\Specialuse;
use App\Doctor;
use App\Expertise;
use App\Medscat;
use DB;

class ExpertController extends Controller
{
    public function __construct() {

        $this->middleware('expert');

    }

    public function getIndex()
    {
        return view('expert.index');
    }
    public function getPrescs($status= null){
        switch($status){
            case 'registered':
                $status=[1,8];
                $status1=1;
                break;
            case 'send-to-expert':
                $status=[2,4,6];
                $status1=2;
                break;
            case 'tickets':
                $status=[3,5,7];
                $status1=3;
                break;
            default:
                $status=0;
                $status1=0;
        }

        if($status!=0){
        $prescs=Prescription::whereIn('status',$status)->latest()->paginate(25);
        }else{
            $prescs=Prescription::latest()->paginate(25);
        }
        $centers=ServiceCenter::all();
        return view('expert.prescs',compact('prescs','centers','status1'));
    }
    public function getPresc($id){
        /*$medicines=Medicine::all();
        $contracts=Contract::all();
        $contractcommits=ContractsCommit::all();
        $insurers=Insurer::all();
        $commits=Commit::all();
        $prescs=Prescription::whereIn('status',[1,4,5])->orderBy('created_at', 'desc')->get();
        $prescMed=PresMedicine::all();
        $setting=Setting::all();
        $pr=Prescription::find($id);
        $prmeds=PresMedicine::where('pres_id',$pr->id)->get();
        $specs=Spec::all();
        $contarcts=Contract::all();
        $prescspecs=Prescspec::where('presc_id',$id)->get();

        return view('expert.presc',compact('prescspecs','contracts','specs','prmeds','pr','setting','prescMed','medicines','insureds','insurers','contractcommits','commits','prescs'));
*/
        $presc=Prescription::find($id);
        $insured=Insured::find($presc->insured_id);
        $prescmeds=PresMedicine::where('pres_id',$presc->id)->get();
        $doctor=Doctor::find($presc->doctor);
        $insurer=Insurer::find($insured->insurer_id);
        $doctorexp=Expertise::find($doctor->expertise);
        $contract=Contract::find($insured->contract_id);
        if($insured->type=='depend'){

            $contract_specs=Spec::where('contract_id',$contract->id)->where('insured_id',$insured->insured_id)->get();
        }else{
            $contract_specs=Spec::where('contract_id',$contract->id)->where('insured_id',$insured->id)->get();

        }

        foreach($contract_specs as $contract_spec){
            $use=Specialuse::find($contract_spec->spec_id);
            $contract_spec->name=$use->name;
        }

        return view('expert.presc',compact('presc','insured','prescmeds','doctor','insurer','doctorexp','contract','contract_specs'));


    }
    public function postPresc(Request $request,$id){

        $error=0;
        $data= $request->all();
        $presc=Prescription::find($id);
        $status=$presc->status;
        $numbers=$data['number'];
        $presc->total_others_difference=$data['presc_others'];
        $presc->total_base_insure=$data['presc_first'];
        $presc->total_franshiz=$data['presc_insured'];
        $presc->iran_pay=$data['presc_iran'];
        $presc->total=$data['presc_total'];
        $presc->total_specs=$data['total_specs'];
        $presc->payable=$data['payable'];
        $presc->left_over=$data['left_over'];
        $presc->total_insured=$data['total_insured'];
        if( $data['submit']=='ثبت نسخه'){
            switch($status){
                case 1:
                    $status=1;
                    $route='registered';
                break;
                case 2:
                    $status=4;
                    $route='send-to-expert';
                    break;
                case 3:
                    $status=5;
                    $route='tickets';
                    break;
                case 4:
                    $status=4;
                    $route='send-to-expert';
                    break;
                case 5:
                    $status=5;
                    $route='tickets';
                    break;
                case 6:
                    $status=4;
                    $route='send-to-expert';
                    break;
                case 7:
                    $status=5;
                    $route='tickets';
                    break;
                case 8:
                    $status=1;
                    $route='registered';
                    break;
            }
        }

        if( $data['submit']=='رد نسخه'){
            $error=1;
            switch($status){
                case 1:
                    $status=8;
                    $route='registered';
                    break;
                case 2:
                    $status=6;
                    $route='send-to-expert';
                    break;
                case 3:
                    $status=7;
                    $route='tickets';
                    break;
                case 4:
                    $status=6;
                    $route='send-to-expert';
                    break;
                case 5:
                    $status=7;
                    $route='tickets';
                    break;
                case 6:
                    $status=6;
                    $route='send-to-expert';
                    break;
                case 7:
                    $status=7;
                    $route='tickets';
                    break;
                case 8:
                    $status=8;
                    $route='registered';
                    break;
            }
        }
        $presc->user_id=Auth::user()->id;
        $presc->status=$status;
        if($data['spec_en']==1 && $status == 1) {
            $s=Spec::find($data['insured_spec']);
            $presc->spec_id=$s->id;
            $s->value = $s->value - $data['payable'];
            $s->save();
        }
        $presc->save();

        if($status==1 || $status==4 || $status==5){
        $prmeds=PresMedicine::where('pres_id',$presc->id)->get();
        foreach ($prmeds as $prmed) {
            $prmed->delete();
        }

        foreach ($numbers as $number){
            $prescMed=new PresMedicine;
            $prescMed->pres_id=$presc->id;
            $prescMed->medicine_id=$data['medicine'][$number];
            $prescMed->medicine_name=$data['medicine_name'][$number];
            $prescMed->count=$data['count'][$number]*$data['unit'][$number];
            $prescMed->open_market_price=$data['open_market'][$number];
            if($data['hour'][$number]=='ساعت'){
                $prescMed->order_per_hour=$data['order_per_hour'][$number];
            }else{
                $prescMed->order_per_hour=$data['order_per_hour'][$number]*24;
            }
            $prescMed->m_order=$data['order_per_hour'][$number];
            $prescMed->hour=$data['hour'][$number];
            $prescMed->total=$data['total'][$number];
            $prescMed->others_difference=$data['others'][$number];
            $prescMed->base_insure=$data['first_insure_percent'][$number];
            $prescMed->franshiz=$data['franshiz'][$number];
            $prescMed->iran_pay=$data['iran_percent'][$number];
            $prescMed->save();
        }
        }
        if($error==1){
            return redirect('expert/prescs/'.$route)->withError('نسخه با موفقیت رد شد.');
        }
        return redirect('expert/prescs/'.$route)->withMessage('نسخه با موفقیت ثبت شد');
    }
    public function getEditProfile()
    {
        $user=User::findOrFail(Auth::User()->id);
        return view('expert.editProfile',compact('user'));
    }

    public function postEditProfile(Request $request)
    {
        $user=User::findOrFail(Auth::User()->id);
        $data=$request->all();
        if ($request->hasFile('image')) {
            File::delete('uploads/'.$user->pic);
            $file = $request->file('image');
            $file->move('uploads',$file->getClientOriginalName());
            $user->pic=$file->getClientOriginalName();
        }
        if($data['password']!=null){
            $user->password=bcrypt($data['password']);
        }
        $user->fullname = $data['name'];
        $user->email= $data['email'];

       
        $user->save();
        return redirect('expert')->withMessage('کاربر با موفقیت ویرایش شد.');
    }

    public function postSearch(Request $request)
    {
        $data=$request->all();
        $insured=trim($data['insured']);
        $insureds=collect();
        $keywords=explode(" ",$insured);
        foreach($keywords as $keyword){
            $result=Insured::where('contract_id','!=','0')->where('fname','like','%'.$keyword.'%')->orwhere('lname','like','%'.$keyword.'%')->orwhere('melli_code','like','%'.$keyword.'%')->get();
            if(count($result)>0){
                $insureds->push($result);
            }
        }
        $insureds = $insureds->collapse();
        $insureds = $insureds->unique();
        return view('center.search',compact('insureds'));
    }


    public function getSelectinsured($id)
    {
        $insured=Insured::where('id',$id)->where('contract_id','!=','0')->first();
        $insurer=Insurer::find($insured->insurer_id);
        $contract=Contract::find($insured->contract_id);
        $presc_commit=Setting::find(222);
        $insured_prsc_commit=ContractsCommit::where('contract_id',$contract->id)->where('commit_id',$presc_commit->value)->first();
        $insured_prescs=Prescription::where('insured_id',$id)->whereIn('status',[1,4,5])->orderBy('created_at','desc')->get();
        $insured_prescs_ids=Prescription::where('insured_id',$id)->whereIn('status',[1,4,5])->orderBy('created_at','desc')->lists('id');
        /*$insured_meds=DB::table('presmedicines')
            ->join('medicines', 'presmedicines.medicine_id', '=', 'medicines.id')
            ->whereIn('presmedicines.pres_id', $insured_prescs_ids)->get();*/
        $insured_meds=PresMedicine::whereIn('pres_id',$insured_prescs_ids)->get();

        if($insured->type=='depend'){
            $contract_specs=Spec::where('contract_id',$contract->id)->where('insured_id',$insured->insured_id)->get();
        }else{
            $contract_specs=Spec::where('contract_id',$contract->id)->where('insured_id',$id)->get();

        }
        foreach($contract_specs as $contract_spec){
            $use=Specialuse::find($contract_spec->spec_id);
            $contract_spec->name=$use->name;
        }
        /*$last_prescs=Prescription::where('insured_id',$id)-->get();
        $last_prescs=collect();
        foreach ($last_prescs1 as $last_presc){
            $medicines=PresMedicine::where('pres_id',$last_presc->id)->get();
            $last_prescs->push($medicines);
        }*/
        return [$insured,$insurer,$contract,$presc_commit,$insured_prsc_commit,
            $insured_prescs,$insured_meds,$contract_specs];
    }



    public function getPrescmeds($id){
        $medicines=PresMedicine::where('pres_id',$id)->get();
        return $medicines;
    }
    public function postFindmed(Request $request){
        $data=$request->all();
        $id=$data['id'];
        $medicine=trim($data['medicine']);
        $medicines=collect();
        $keywords=explode(" ",$medicine);
        foreach($keywords as $keyword){
            $result=Medicine::where('name','like','%'.$keyword.'%')->get();
            if(count($result)>0){
                $medicines->push($result);
            }
        }
        $medicines = $medicines->collapse();
        $medicines = $medicines->unique();
        return view('center.findMed',compact('medicines','id'));
    }
    public function getSelectmed($id,$doctor_id,$insured_id)
    {
        $medicine=Medicine::find($id);
        $med_cat=Medscat::where('med_id',$id)->lists('expertise_id');

        if(count($med_cat)>0){
            $doctor=Doctor::where('id',$doctor_id)->whereIn('expertise',$med_cat)->get();
            if(count($doctor)>0){
                $insured=Insured::find($insured_id);
                if($insured->type=='depend'){
                    $insured=Insured::find($insured->insured_id);
                }
                $contarct=Contract::find($insured->contract_id);
                $contarct_special_uses=Spec::where('contract_id',$contarct->id)->where('insured_id',$insured->id)->lists('spec_id');
                $med_special_uses=Specialmed::where('med_id',$id)->lists('use_id');

                $specs=[];
                foreach($contarct_special_uses as $contarct_special_use){
                    foreach($med_special_uses as $med_special_use){
                        if($med_special_use==$contarct_special_use){
                            array_push($specs,$contarct_special_use);
                        }
                    }
                }

                if(count($specs)==0){
                    $specs='no';
                }else{
                    $specs=Specialuse::whereIn('id',$specs)->get();
                }
                return [$medicine,$specs];
            }
            else{
                return 'no';
            }
        }
        else{
            $specs=[];
            if(count($specs)==0){
                $specs='no';
            }
            return [$medicine,$specs];

        }

    }

    public function getMedname($id,$c){
        $medicine=Medicine::find($id);
        return [$medicine,$c];
    }

    public function postDoctorearch(Request $request)
    {
        $data=$request->all();
        $doctor=trim($data['doctor']);
        $doctors=collect();
        $keywords=explode(" ",$doctor);
        foreach($keywords as $keyword){
            $result=Doctor::where('lname','like','%'.$keyword.'%')->orwhere('fname','like','%'.$keyword.'%')->orwhere('medical_code','like','%'.$keyword.'%')->get();
            if(count($result)>0){
                $doctors->push($result);
            }
        }
        $doctors = $doctors->collapse();
        $doctors = $doctors->unique();
        return view('center.findDoctor',compact('doctors'));
    }

    public function getSelectdoc($id)
    {
        $doctor=Doctor::find($id);
        $exp=Expertise::find($doctor->expertise);
        return [$doctor,$exp];
    }

    public function getSelcetmedicine($id)
    {
        $med=Medicine::find($id);
        return $med;
    }

    public function getTest()
    {
        return view('center.test');
    }
}

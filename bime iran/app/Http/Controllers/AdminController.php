<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\Expertise;
use App\Medscat;
use App\Setting;
use App\Specialmed;
use App\Specialuse;
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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Validator;
use Crypt;
use App\Prescspec;
use File;

class AdminController extends Controller
{
    public function __construct()
    {

        $this->middleware('admin');

    }

    public function getIndex()
    {
        return view('admin.index.index');

    }

    public function getSetting()
    {
        $settings = Setting::all();
        $commits = Commit::all();
        return view('admin.index.setting', compact('settings', 'commits'));
    }

    public function postSetting(Request $request)
    {
        $data = $request->all();

        $setting1 = Setting::find(111);
        $setting1->value = $data['max'];
        $setting1->save();
        $setting2 = Setting::find(222);
        $setting2->value = $data['commit'];
        $setting2->save();
        $setting3 = Setting::find(333);
        $setting3->value = $data['message'];
        $setting3->save();
        $commits = Commit::all();
        $settings = Setting::all();
        return redirect('admin/setting')->withMessage('تنظیمات با موفقیت اعمال شد.');
    }

    public function getMedicines($type = null)
    {
        switch ($type) {
            case 'all':
                $types = [2, 1];
                $status = 'all';
                break;
            case 'farma':
                $types = [1];
                $status = 'farma';
                break;
            case 'foreign':
                $types = [2];
                $status = 'foreign';
                break;
            default:
                $types = [2, 1];
                $status = 'all';
        }

        $medicines = Medicine::whereIn('type', $types)->paginate(15);
        $medicines1 = Medicine::all();

        return view('admin.index.medicines', ['medicines' => $medicines, 'medicines1' => $medicines1, 'status' => $status]);
    }

    public function getNewMedicine()
    {
        $expertises=Expertise::all();
        $special_uses=Specialuse::all();
        return view('admin.create.newMedicine',compact('expertises','special_uses'));
    }

    public function postNewMedicine(Request $request)
    {
        $data = $request->all();
        $medicine=new Medicine;
        $medicine->name = $data['name'];
        $medicine->price = $data['price'];
        $medicine->type = $data['type'];
        $medicine->shape = $data['shape'];
        $medicine->first_insure_percent = $data['first_insure_percent'];
        $medicine->iran_percent = $data['iran_percent'];
        if($medicine->save()){
            $med_cats=$data['med_cats'];
            foreach($med_cats as $med_cat){
                $cat=new Medscat;
                $cat->expertise_id=$med_cat;
                $cat->med_id=$medicine->id;
                $cat->save();
            }
            $special_uses=$data['special_uses'];
            foreach($special_uses as $special_use){
                $use=new Specialmed();
                $use->use_id=$special_use;
                $use->med_id=$medicine->id;
                $use->save();
            }
        }


        return redirect('admin/medicines')->withMessage('دارو جدید با موفقیت ایجاد  شد.');
    }
    public function postEditMedicine(Request $request,$id){
        $data = $request->all();
        $medicine=Medicine::find($id);
        $medicine->name = $data['name'];
        $medicine->price = $data['price'];
        $medicine->type = $data['type'];
        $medicine->shape = $data['shape'];
        $medicine->first_insure_percent = $data['first_insure_percent'];
        $medicine->iran_percent = $data['iran_percent'];
        if($medicine->save()){
            $med_cats1=Medscat::where('med_id',$id)->get();
            foreach($med_cats1 as $med_cat1){
                $med_cat1->delete();
            }
            $med_cats=$data['med_cats'];
            foreach($med_cats as $med_cat){
                $cat=new Medscat;
                $cat->expertise_id=$med_cat;
                $cat->med_id=$medicine->id;
                $cat->save();
            }
            $special_uses1=Specialmed::where('med_id',$id)->get();
            foreach($special_uses1 as $special_use1){
                $special_use1->delete();
            }
            $special_uses=$data['special_uses'];
            foreach($special_uses as $special_use){
                $use=new Specialmed();
                $use->use_id=$special_use;
                $use->med_id=$medicine->id;
                $use->save();
            }
        }
        return redirect('admin/medicines')->withMessage('دارو با موفقیت ویرایش شد.');
    }

    public function getCommits()
    {
        $services = Service::all();
        $commits = Commit::all();
        return view('admin.index.commits', compact('commits', 'services'));
    }

    public function getNewCommit()
    {
        return view('admin.create.newCommit');
    }

    public function postNewCommit(Request $request)
    {
        $data = $request->all();
        Commit::create(['name' => $data['name']]);
        return redirect('admin/commits')->withMessage('خدمت جدید با موفقیت ایجاد شد.');

    }

    public function getNewService()
    {
        $commits = Commit::all();
        return view('admin.create.newService', compact('commits'));

    }

    public function postNewService(Request $request)
    {
        $data = $request->all();
        Service::create(['name' => $data['name'],
            'commit_id' => $data['commit_id']
        ]);
        return redirect('admin/commits')->withMessage('تعهد جدید با موفقیت ایجاد شد.');
    }

    public function getContracts()
    {
        $contracts = Contract::latest()->get();
        $insureds = Insured::all();
        return view('admin.index.contracts', compact('contracts', 'insureds'));
    }

    public function getNewContract()
    {
        $insurers = Insurer::all();
        $commits = Commit::all();
        $services = Service::all();
        $specials=Specialuse::all();
        return view('admin.create.newContract', compact('insurers', 'commits', 'services','specials'));
    }

    public function getServiceCenters()
    {
        $centers = ServiceCenter::all();
        return view('admin.index.serviceCenters', compact('centers'));
    }

    public function getNewCenter()
    {
        return view('admin.create.newCenter');
    }

    public function postNewCenter(Request $request)
    {

        $data = $request->all();
        $insures = '';
        $i = 1;
        foreach ($data['insures_under_contract'] as $a) {
            if ($i == 1) {
                $insures = $a;
                $i++;
            } else {
                $insures = $insures . '/' . $a;
            }
        }
        $user = new User;
        $user->fullname = $data['name'];
        $user->email = $data['mail'];
        $user->username = $data['username'];
        $user->password = bcrypt($data['password']);
        $user->type = 'center';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move('uploads', $file->getClientOriginalName());
            $user->pic = $file->getClientOriginalName();
        }
        if ($user->save()) {
            ServiceCenter::create(['user_id' => $user->id, 'name' => $data['name'],
                'technical_user' => $data['technical_user'], 'insures_under_contract' => $insures,
                'shift' => $data['shift'], 'address' => $data['address'], 'medical_code' => $data['medical_code'],
                'sahebe_emtiaz' => $data['sahebe_emtiaz'], 'bank' => $data['bank'], 'account_num' => $data['account_num'],
                'website' => $data['website'], 'mobile' => $data['mobile'], 'fax' => $data['fax'],
                'phone' => $data['phone']]);
        }
        return redirect('admin/service-centers')->withMessage('مرکز جدید با موفقیت ایجاد شد.');

    }

    public function postNewContract(Request $request)
    {

        $data = $request->all();
        $rules=['start_date'=>'required','finish_date'=>'required'];
        $messages = [
            'start_date.required' => 'فیلد تاریخ اخذ قرارداد اجباری است',
            'finish_date.required' => 'فیلد تاریخ پایان قرارداد اجباری است',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/new-contract')
                ->withErrors($validator)
                ->withInput();
        }
        $contract = new Contract;
        $contract->insurer_id = $data['insurer_id'];
        $contract->contract_num = $data['contract_num'];
        $contract->type = $data['type'];
        $contract->start_date = $data['start_date'];
        $contract->finish_date = $data['finish_date'];
        $finish = explode('-', $data['finish_date']);
        $gfinish = jalali_to_gregorian($finish[0], $finish[1], $finish[2]);
        $contract->gfinish_date = $gfinish[0] . '-' . $gfinish[1] . '-' . $gfinish[2];
        $contract->user_id = Auth::user()->id;
        $contract->others = $data['others'];
        if ($contract->save()) {
            $commits = $data['commit'];
            $franshize_controls = $data['franshiz_control'];
            $insured_fs = $data['insured_f'];
            $depend_fs = $data['depend_f'];
            $non_depend_fs = $data['non_depend_f'];
            $units = $data['unit'];
            $max_commits = $data['max_commit'];
            $test=0;
            if(isset($data['service'])){
                $services = $data['service'];
                $test=1;
            }

            unset($commits[0]);

            foreach ($commits as $commit) {
                $ContractsCommit = new ContractsCommit;
                $ContractsCommit->commit_id = $commit;
                $ContractsCommit->contract_id = $contract->id;
                $ContractsCommit->farnshiz_control = $franshize_controls[$commit];
                $ContractsCommit->insured_f = $insured_fs[$commit];
                $ContractsCommit->depend_f = $depend_fs[$commit];
                $ContractsCommit->non_depend_f = $non_depend_fs[$commit];
                $ContractsCommit->unit = $units[$commit];
                $ContractsCommit->max_commit = $max_commits[$commit];
                if ($ContractsCommit->save()) {
                    if( $test==1 && array_key_exists($commit, $services)){
                        foreach ($services[$commit] as $service) {
                            $ContractsService = new ContractService;
                            $ContractsService->contract_id = $contract->id;
                            $ContractsService->contractCommit_id = $ContractsCommit->id;
                            $ContractsService->service_id = $service;
                            if ($ContractsService->save()) {
                                $insureds = Insured::where('insurer_id', '=', $data['insurer_id'])->get();
                                foreach ($insureds as $insured) {
                                    $insured->contract_id = $contract->id;
                                    $insured->save();
                                }
                            }
                        }
                    }

                }
            }
        }

        if ($data['specs'] != 0) {
            $expert = $data['expert'];
            $seil = $data['seil'];
            $specs = $data['count'];
            foreach ($specs as $spec) {
                $spec1 = new Spec;
                $spec1->contract_id = $contract->id;
                $spec1->spec_id = $expert[$spec];
                $spec1->value = $seil[$spec];
                $spec1->save();
            }
        }
        return redirect('admin/contracts')->withMessage('قرارداد جدید با موفقیت ایجاد شد.');


    }

    public function getAddToContract($contract_id = null, $insured_id = null)
    {

        if ($contract_id == 'no') {
            $contract_id = null;
        }
        if ($insured_id == 'no') {
            $insured_id = null;
        }
        $contracts = Contract::latest()->get();
        $insureds = Insured::where('type', '=', 'main')->get();

        return view('admin.edit.addToContract', compact('contracts', 'insureds', 'contract_id', 'insured_id'));
    }

    public function getAddToInsurer($insurer_id = null, $insured_id = null)
    {
        if ($insurer_id == 'no') {
            $insurer_id = null;
        }
        if ($insured_id == 'no') {
            $insured_id = null;
        }
        $insurers = Insurer::latest()->get();
        $insureds = Insured::where('type', '=', 'main')->get();

        return view('admin.edit.addToInsurer', compact('insurers', 'insureds', 'insurer_id', 'insured_id'));
    }

    public function postAddToInsurer(Request $request)
    {
        $data = $request->all();
        $insured = Insured::find($data['insured_id']);
        $insured->insurer_id = $data['insurer_id'];
        $insured->save();
        $insureds = Insured::where('insured_id', $data['insured_id'])->get();
        foreach ($insureds as $insured) {
            $insured->insurer_id = $data['insurer_id'];
            $insured->save();
        }
        return redirect('admin/show-insurer/' . $data['insurer_id'])->withMessage('بیمه شده با موفقیت اضافه شد.');

    }

    public function postAddToContract(Request $request)
    {
        $data = $request->all();
        $insured = Insured::find($data['insured_id']);
        $insured->contract_id = $data['contract_id'];
        if ($insured->save()) {
            $peoples = Insured::where('insured_id', $data['insured_id'])->get();
            foreach ($peoples as $people) {
                $people->contract_id = $data['contract_id'];
                $people->save();
            }
            $insured_specs=Spec::where('contract_id',$data['contract_id'])->where('insured_id' ,$data['insured_id'])->get();
            foreach ($insured_specs as $insured_spec) {
                $insured_spec->delete();
            }
            $specs = Spec::where('contract_id', $data['contract_id'])->where('insured_id', 0)->get();
            foreach ($specs as $spec) {
                Spec::create(['contract_id' => $data['contract_id'], 'insured_id' => $data['insured_id'],
                    'spec_id' => $spec->id, 'value' => $spec->value]);
            }
        }
        return redirect('admin/show-contract/' . $data['contract_id'])->withMessage('بیمه شده با موفقیت به قرارداد اضافه شد.');

    }

    public function getInsurers()
    {
        $insurers = Insurer::all();
        return view('admin.index.insurers', compact('insurers'));

    }

    public function getNewInsurer()
    {
        return view('admin.create.newInsurer');
    }

    public function postNewInsurer(Request $request)
    {
        $data = $request->all();
        $rules = ['name' => 'required', 'address' => 'required', 'phone' => 'required', 'mobile' => 'required', 'mail' => 'required'];
        $messages = ['name.required' => 'پر کردن فیلد نام الزامی است ',
            'phone.required' => 'پر کردن فیلد تلفن الزامی است ',
            'address.required' => 'پر کردن فیلد آدرس الزامی است ',
            'mobile.required' => 'پر کردن فیلد همراه الزامی است ',
            'mail.required' => 'پر کردن فیلد پست الکترونیکی الزامی است ',
        ];
        $validator = $this->validate($request, $rules, $messages);


        Insurer::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'fax' => $data['fax'],
            'mobile' => $data['mobile'],
            'website' => $data['website'],
            'mail' => $data['mail'],
            'treatment_name' => $data['treatment_name']
        ]);
        return redirect('admin/insurers')->withMessage('بیمه گزار جدید با موفقیت ایجاد شد.');


    }

    public function getInsureds($type = null)
    {
        if ($type == 'all' || $type == null) {
            $insureds = Insured::latest()->get();
            return view('admin.index.insureds', compact('insureds', 'type'));
        }
        if ($type == 'main') {
            $insureds = Insured::where('type', '=', 'main')->get();
            return view('admin.index.insureds', compact('insureds', 'type'));
        }
        if ($type == 'depend') {
            $insureds = Insured::where('type', '=', 'depend')->get();
            return view('admin.index.insureds', compact('insureds', 'type'));
        }
        $type = 'all';
        $insureds = Insured::latest()->get();
        return view('admin.index.insureds', compact('insureds', 'type'));
    }

    public function getNewInsured($insurer1 = null, $contract1 = null)
    {
        if ($insurer1 == 'no') {
            $insurer1 = null;
        }
        if ($contract1 == 'no') {
            $contract1 = null;
        }
        $insurers = Insurer::all();
        $contracts = Contract::all();
        return view('admin.create.newInsured', compact('insurers', 'contracts', 'insurer1', 'contract1'));
    }

    public function postNewInsured(Request $request)
    {
        $data = $request->all();
        Insured::create(['insurer_id' => $data['insurer_id'],
            'contract_id' => $data['contract_id'],
            'status' => $data['status'],
            'melli_code' => $data['melli_code'],
            'personal_code' => $data['personal_code'],
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'father_name' => $data['father_name'],
            'birth_date' => $data['birth_date'],
            'birth_cert_num' => $data['birth_cert_num'],
            'gender' => $data['gender'],
            'marrige_status' => $data['marrige_status'],
            'employed_date' => $data['employed_date'],
            'janbaz_percent' => $data['janbaz_percent'],
            'base_insure' => $data['base_insure'],
            'insure_num' => $data['insure_num'],
            'group' => $data['group'],
            'bank' => $data['bank'],
            'account' => $data['account'],
            'phone' => $data['phone'],
            'mobile' => $data['mobile'],
            'type' => 'main'
        ]);
        return redirect('admin/insureds')->withMessage('بیمه شده جدید با موفقیت ایجاد شد.');

    }

    public function getNewPeople($id = null)
    {
        if ($insured = Insured::find($id)) {
            if ($insured->type != 'main') {
                $id = null;
            }
        } else {
            $id = null;
        }
        $insureds = Insured::where('type', '=', 'main')->get();
        return view('admin.create.newPeople', compact('insureds', 'id'));
    }

    public function postNewPeople(Request $request)
    {
        $data = $request->all();
        $insured = Insured::find($data['insured_id']);
        Insured::create(['relation' => $data['relation'],
            'insurer_id' => $insured->insurer_id,
            'contract_id' => $insured->contract_id,
            'insured_id' => $data['insured_id'],
            'melli_code' => $data['melli_code'],
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'father_name' => $data['father_name'],
            'birth_date' => $data['birth_date'],
            'birth_cert_num' => $data['birth_cert_num'],
            'gender' => $data['gender'],
            'marrige_status' => $data['marrige_status'],
            'sponser_status' => $data['sponser_status'],
            'base_insure' => $data['base_insure'],
            'insure_num' => $data['insure_num'],
            'type' => 'depend',
        ]);
        return redirect('admin/insureds')->withMessage('فرد وابسته جدید با موفقیت ایجاد شد.');

    }



    public function getShowInsured($id)
    {
        if ($insured = Insured::find($id)) {
            $people;
            if ($insured->type == 'main') {
                $people = Insured::where('insured_id', $insured->id)->get();
            }
            $prescs = Prescription::where('insured_id', $insured->id)->get();
            $startdate = '';
            $finishdate ='';

            $all = Prescription::where('insured_id', $id)->count();
            $allp = Prescription::where('insured_id', $id)->sum('total');
            $registred = Prescription::where('insured_id', $id)->whereIn('status', [1, 8])->count();
            $send_to_expert = Prescription::where('insured_id', $id)->whereIn('status', [2, 4, 6])->count();
            $ticket = Prescription::where('insured_id', $id)->whereIn('status', [3, 5, 7])->count();
            $registredp = Prescription::where('insured_id', $id)->whereIn('status', [1, 8])->sum('total');
            $send_to_expertp = Prescription::where('insured_id', $id)->whereIn('status', [2, 4, 6])->sum('total');
            $ticketp = Prescription::where('insured_id', $id)->whereIn('status', [3, 5, 7])->sum('total');
            return view('admin.show.showInsured', compact('insured', 'people',
                'prescs', 'startdate', 'finishdate', 'registred', 'send_to_expert'
                , 'ticket', 'registredp', 'send_to_expertp', 'ticketp', 'all', 'allp'));

        } else {
            abort(404);
        }

    }
    public function postShowInsured(Request $request,$id)
    {
        $data = $request->all();
        $rules = ['start_date' => 'required', 'finish_date' => 'required'];
        $messages = [
            'start_date.required' => 'فیلد تاریخ اخذ قرارداد اجباری است',
            'finish_date.required' => 'فیلد تاریخ پایان قرارداد اجباری است',
        ];
        $this->validate($request, $rules, $messages);
        $startdate = $data['start_date'];
        $finishdate = $data['finish_date'];

        $all = Prescription::where('insured_id', $id)->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $allp = Prescription::where('insured_id', $id)->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');
        $registred = Prescription::where('insured_id', $id)->whereIn('status', [1, 8])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $send_to_expert = Prescription::where('insured_id', $id)->whereIn('status', [2, 4, 6])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $ticket = Prescription::where('insured_id', $id)->whereIn('status', [3, 5, 7])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $registredp = Prescription::where('insured_id', $id)->whereIn('status', [1, 8])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');
        $send_to_expertp = Prescription::where('insured_id', $id)->whereIn('status', [2, 4, 6])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');
        $ticketp = Prescription::where('insured_id', $id)->whereIn('status', [3, 5, 7])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');

        $insured = Insured::find($id);
        $people;
        if ($insured->type == 'main') {
            $people = Insured::where('insured_id', $insured->id)->get();
        }
        $prescs = Prescription::where('insured_id', $insured->id)->get();
        return view('admin.show.showInsured', compact('insured', 'people',
            'prescs', 'startdate', 'finishdate', 'registred', 'send_to_expert'
            , 'ticket', 'registredp', 'send_to_expertp', 'ticketp', 'all', 'allp'));




    }

    public function getShowContract($id)
    {
        if ($contract = Contract::find($id)) {
            $commits = ContractsCommit::where('contract_id', $id)->get();
            $services = ContractService::where('contract_id', $id)->get();
            $insurer = Insurer::find($contract->insurer_id);
            $insureds = Insured::where('contract_id', $id)->where('type', 'main')->get();
            return view('admin.show.showContract', compact('insureds', 'contract', 'commits', 'services', 'insurer'));
        } else {
            abort(404);
        }
    }

    public function getShowCenter($id)
    {
        if ($center = ServiceCenter::find($id)) {
            $prescs = Prescription::where('serviceCenter_id', $id)->orderBy('created_at', 'desc')->get();
            $startdate="";
            $finishdate="";
            $registred=Prescription::where('serviceCenter_id',$id)->whereIn('status',[1,8])->count();
            $send_to_expert=Prescription::where('serviceCenter_id',$id)->whereIn('status',[2,4,6])->count();
            $ticket=Prescription::where('serviceCenter_id',$id)->whereIn('status',[3,5,7])->count();

            $registredp=Prescription::where('serviceCenter_id',$id)->whereIn('status',[1,8])->sum('total');
            $send_to_expertp=Prescription::where('serviceCenter_id',$id)->whereIn('status',[2,4,6])->sum('total');
            $ticketp=Prescription::where('serviceCenter_id',$id)->whereIn('status',[3,5,7])->sum('total');
            $all=Prescription::where('serviceCenter_id',$id)->count();
            $allp=Prescription::where('serviceCenter_id',$id)->sum('total');

            return view('admin.show.showCenter', compact('prescs', 'center',
                'startdate','finishdate','registred','send_to_expert'
                ,'ticket','registredp','send_to_expertp','ticketp','all','allp'));
        } else {
            abort(404);
        }
    }
    public function postShowCenter(Request $request,$id){
        $data=$request->all();
        $rules=['start_date'=>'required','finish_date'=>'required'];
        $messages = [
            'start_date.required' => 'فیلد تاریخ اخذ قرارداد اجباری است',
            'finish_date.required' => 'فیلد تاریخ پایان قرارداد اجباری است',
        ];
        $this->validate($request, $rules, $messages);
        $startdate=$data['start_date'];
        $finishdate=$data['finish_date'];

        $all=Prescription::where('serviceCenter_id',$id)->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->count();
        $allp=Prescription::where('serviceCenter_id',$id)->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->sum('total');
        $registred=Prescription::where('serviceCenter_id',$id)->whereIn('status',[1,8])->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->count();
        $send_to_expert=Prescription::where('serviceCenter_id',$id)->whereIn('status',[2,4,6])->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->count();
        $ticket=Prescription::where('serviceCenter_id',$id)->whereIn('status',[3,5,7])->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->count();
        $registredp=Prescription::where('serviceCenter_id',$id)->whereIn('status',[1,8])->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->sum('total');
        $send_to_expertp=Prescription::where('serviceCenter_id',$id)->whereIn('status',[2,4,6])->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->sum('total');
        $ticketp=Prescription::where('serviceCenter_id',$id)->whereIn('status',[3,5,7])->whereBetween('reception_date',array($data['start_date'],$data['finish_date']))->sum('total');

        $center = ServiceCenter::find($id);
        $prescs = Prescription::where('serviceCenter_id', $id)->orderBy('created_at', 'desc')->get();

        return view('admin.show.showCenter', compact('prescs', 'center',
            'startdate','finishdate','registred','send_to_expert'
            ,'ticket','registredp','send_to_expertp','ticketp','all','allp'));
    }
    public function getShowInsurer($id)
    {
        if ($insurer = Insurer::find($id)) {
            $insureds = Insured::where('insurer_id', $id)->where('type', 'main')->get();
            $insuredsId = Insured::where('insurer_id', $id)->where('type', 'main')->lists('id');
            $contracts = Contract::where('insurer_id', $id)->get();
            $startdate = '';
            $finishdate = '';
            $all = Prescription::whereIn('insured_id', $insuredsId)->count();
            $allp = Prescription::whereIn('insured_id', $insuredsId)->sum('total');
            $registred = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [1, 8])->count();
            $send_to_expert = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [2, 4, 6])-> count();
            $ticket = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [3, 5, 7])->count();
            $registredp = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [1, 8])->sum('total');
            $send_to_expertp = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [2, 4, 6])->sum('total');
            $ticketp = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [3, 5, 7])->sum('total');

            return view('admin.show.showInsurer', compact('insurer', 'insureds',
                'contracts','startdate','finishdate','registred','send_to_expert'
                ,'ticket','registredp','send_to_expertp','ticketp','all','allp'));
        } else {
            abort(404);
        }

    }
    public function postShowInsurer(Request $request,$id)
    {
        $data = $request->all();
        $rules = ['start_date' => 'required', 'finish_date' => 'required'];
        $messages = [
            'start_date.required' => 'فیلد تاریخ اخذ قرارداد اجباری است',
            'finish_date.required' => 'فیلد تاریخ پایان قرارداد اجباری است',
        ];
        $this->validate($request, $rules, $messages);
        $startdate = $data['start_date'];
        $finishdate = $data['finish_date'];
        $insuredsId = Insured::where('insurer_id', $id)->where('type', 'main')->lists('id');

        $all = Prescription::whereIn('insured_id', $insuredsId)->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $allp = Prescription::whereIn('insured_id', $insuredsId)->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');
        $registred = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [1, 8])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $send_to_expert = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [2, 4, 6])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $ticket = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [3, 5, 7])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->count();
        $registredp = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [1, 8])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');
        $send_to_expertp = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [2, 4, 6])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');
        $ticketp = Prescription::whereIn('insured_id', $insuredsId)->whereIn('status', [3, 5, 7])->whereBetween('reception_date', array($data['start_date'], $data['finish_date']))->sum('total');

        $insurer = Insurer::find($id);
        $insureds = Insured::where('insurer_id', $id)->where('type', 'main')->get();
        $contracts = Contract::where('insurer_id', $id)->get();

        return view('admin.show.showInsurer', compact('insurer', 'insureds',
            'contracts', 'startdate', 'finishdate', 'registred', 'send_to_expert'
            , 'ticket', 'registredp', 'send_to_expertp', 'ticketp', 'all', 'allp'));
    }

    public function getAddToInsured($insured_id = null, $people_id = null)
    {
        if ($insured_id == 'no') {
            $insured_id = null;
        }
        if ($people_id == 'no') {
            $people_id = null;
        }
        $insureds = Insured::where('type', '=', 'main')->get();
        $peoples = Insured::where('type', '=', 'depend')->get();

        return view('admin.edit.addToInsured', compact('peoples', 'insureds', 'people_id', 'insured_id'));
    }

    public function postAddToInsured(Request $request)
    {
        $data = $request->all();
        $people = Insured::find($data['people_id']);
        $insured = Insured::find($data['insured_id']);
        $people->Insured_id = $data['insured_id'];
        $people->insurer_id = $insured->insurer_id;
        $people->contract_id = $insured->contract_id;
        $people->save();
        return redirect('admin/show-insured/' . $insured->id)->withMessage('فرد وابسته با موفقیت اضافه شد.');
    }

    public function getRemoveFromInsured($id)
    {
        if ($insured = Insured::find($id)) {
            if ($insured->type == 'main') {
                abort(404);
            }
            $insured_id = $insured->insured_id;
            $insured->insured_id = 0;
            $insured->save();
            return redirect('admin/show-insured/' . $insured_id)->withMessage('فرد وابسته با موفقیت از بیمه حذف گردید.');
        } else {
            abort(404);
        }
    }

    public function getEditInsurer($id)
    {
        if ($insurer = Insurer::find($id)) {
            return view('admin.edit.editInsurer', compact('insurer'));
        } else {
            abort(404);
        }
    }

    public function getEditInsured($id)
    {
        if ($insured = Insured::find($id)) {
            $insurers = Insurer::all();
            $contracts = Contract::all();
            return view('admin.edit.editInsured', compact('insured', 'insurers', 'contracts'));
        } else {
            abort(404);
        }
    }

    public function postEditInsured(Request $request, $id)
    {
        $data = $request->all();
        $insured = Insured::find($id);
        $insured->insurer_id = $data['insurer_id'];
        $insured->contract_id = $data['contract_id'];
        $insured->status = $data['status'];
        $insured->melli_code = $data['melli_code'];
        $insured->personal_code = $data['personal_code'];
        $insured->fname = $data['fname'];
        $insured->lname = $data['lname'];
        $insured->father_name = $data['father_name'];
        $insured->birth_date = $data['birth_date'];
        $insured->birth_cert_num = $data['birth_cert_num'];
        $insured->gender = $data['gender'];
        $insured->marrige_status = $data['marrige_status'];
        $insured->employed_date = $data['employed_date'];
        $insured->janbaz_percent = $data['janbaz_percent'];
        $insured->base_insure = $data['base_insure'];
        $insured->insure_num = $data['insure_num'];
        $insured->group = $data['group'];
        $insured->bank = $data['bank'];
        $insured->account = $data['account'];
        $insured->phone = $data['phone'];
        $insured->mobile = $data['mobile'];
        $insured->save();
        return redirect('admin/show-insured/' . $id)->withMessage('اطلاعات بیمه شده با موفقیت ویرایش شد.');

    }

    public function postEditInsurer(Request $request, $id)
    {
        $data = $request->all();
        $insurer = Insurer::find($id);
        $insurer->name = $data['name'];
        $insurer->address = $data['address'];
        $insurer->phone = $data['phone'];
        $insurer->fax = $data['fax'];
        $insurer->mobile = $data['mobile'];
        $insurer->website = $data['website'];
        $insurer->mail = $data['mail'];
        $insurer->treatment_name = $data['treatment_name'];
        $insurer->save();
        return redirect('admin/show-insurer/' . $id)->withMessage('اطلاعات بیمه گذار با موفقیت وبرایش شد.');
    }

    public function getRemoveFromInsurer($insured_id)
    {
        $insured = Insured::find($insured_id);
        $insurer = $insured->insurer_id;
        $insured->insurer_id = 0;
        $insured->contract_id = 0;
        $insured->save();
        $insureds = Insured::where('insured_id', $insured_id)->get();
        foreach ($insureds as $insured) {
            $insured->insurer_id = 0;
            $insured->contract_id = 0;
            $insured->save();
        }
        return redirect('admin/show-insurer/' . $insurer)->withMessage('بیمه شده با موفقیت از بیمه گذار حذف شد.');
    }

    public function getEditPeople($id)
    {
        if ($insured1 = Insured::findorfail($id)) {
            $insurers = Insurer::all();
            $insureds = Insured::all();
            return view('admin.edit.editPeople', compact('insurers', 'insureds', 'insured1'));
        }
    }

    public function postEditPeople(Request $request, $id)
    {
        $data = $request->all();
        $insured = Insured::find($id);
        $insured->relation = $data['relation'];
        $insured->insured_id = $data['insured_id'];
        $insured->melli_code = $data['melli_code'];
        $insured->fname = $data['fname'];
        $insured->lname = $data['lname'];
        $insured->father_name = $data['father_name'];
        $insured->birth_date = $data['birth_date'];
        $insured->birth_cert_num = $data['birth_cert_num'];
        $insured->sponser_status = $data['sponser_status'];
        $insured->gender = $data['gender'];
        $insured->marrige_status = $data['marrige_status'];
        $insured->base_insure = $data['base_insure'];
        $insured->insure_num = $data['insure_num'];
        $insured->save();
        return redirect('admin/show-insured/' . $id)->withMessage('اطلاعات فرد وابسته با موفقیت ویرایش شد.');
    }

    public function getEditContract($id)
    {
        $contract = Contract::findOrFail($id);
        $contractCommits = ContractsCommit::where('contract_id', $id)->get();
        $contractServices = ContractService::where('contract_id', $id)->get();
        $insurers = Insurer::all();
        $commits = Commit::all();
        $services = Service::all();
        return view('admin.edit.editContract', compact('insurers', 'commits', 'services', 'contract', 'contractCommits', 'contractServices'));
    }

    public function postEditContract(Request $request, $id)
    {
        $data = $request->all();
        $contract = Contract::findOrFail($id);
        $coms = ContractsCommit::where('contract_id', $contract->id)->get();
        $sers = ContractService::where('contract_id', $contract->id)->get();
        foreach ($coms as $com) {
            $com->delete();
        }
        foreach ($sers as $ser) {
            $ser->delete();
        }
        $contract->insurer_id = $data['insurer_id'];
        $contract->contract_num = $data['contract_num'];
        $contract->type = $data['type'];
        $contract->start_date = $data['start_date'];
        $contract->finish_date = $data['finish_date'];
        $contract->user_id = Auth::user()->id;
        $contract->others = $data['others'];
        if ($contract->save()) {
            $commits = $data['commit'];
            $franshize_controls = $data['franshiz_control'];
            $insured_fs = $data['insured_f'];
            $depend_fs = $data['depend_f'];
            $non_depend_fs = $data['non_depend_f'];
            $units = $data['unit'];
            $max_commits = $data['max_commit'];
            $services = $data['service'];
            unset($commits[0]);

            foreach ($commits as $commit) {
                $ContractsCommit = new ContractsCommit;
                $ContractsCommit->commit_id = $commit;
                $ContractsCommit->contract_id = $contract->id;
                $ContractsCommit->farnshiz_control = $franshize_controls[$commit];
                $ContractsCommit->insured_f = $insured_fs[$commit];
                $ContractsCommit->depend_f = $depend_fs[$commit];
                $ContractsCommit->non_depend_f = $non_depend_fs[$commit];
                $ContractsCommit->unit = $units[$commit];
                $ContractsCommit->max_commit = $max_commits[$commit];
                if ($ContractsCommit->save()) {
                    foreach ($services[$commit] as $service) {
                        $ContractsService = new ContractService;
                        $ContractsService->contract_id = $contract->id;
                        $ContractsService->contractCommit_id = $ContractsCommit->id;
                        $ContractsService->service_id = $service;
                        if ($ContractsService->save()) {
                            $insureds = Insured::where('insurer_id', '=', $data['insurer_id'])->get();
                            foreach ($insureds as $insured) {
                                $insured->contract_id = $contract->id;
                                $insured->save();
                            }
                        }
                    }
                }
            }
        }
        return redirect('admin/show-contract/' . $contract->id)->withMessage('قرارداد با موفقیت ویرایش شد.');
        if ($data['specs'] != 0) {
            $expert = $data['expert'];
            $seil = $data['seil'];
            $specs = $data['count'];
            foreach ($specs as $spec) {
                $spec1 = new Spec;
                $spec1->contract_id = $contract->id;
                $spec1->name = $expert[$spec];
                $spec1->value = $seil[$spec];
                $spec1->save();
            }
        }

    }

    public function getEditCenter($id)
    {
        $center = ServiceCenter::findOrFail($id);
        $user = User::find($center->user_id);
        return view('admin.edit.editCenter', compact('center', 'user'));
    }

    public function postEditCenter(Request $request, $id)
    {
        $center = ServiceCenter::findOrFail($id);
        $user = User::find($center->user_id);
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        $data = $request->all();

        $insures = '';
        $i = 1;
        foreach ($data['insures_under_contract'] as $a) {
            if ($i == 1) {
                $insures = $a;
                $i++;
            } else {
                $insures = $insures . '/' . $a;
            }
        }

        $user->fullname = $data['name'];
        $user->email = $data['mail'];
        $user->username = $data['username'];
        if ($request->hasFile('image')) {
            File::delete('uploads/' . $user->pic);
            $file = $request->file('image');
            $file->move('uploads', $file->getClientOriginalName());
            $user->pic = $file->getClientOriginalName();
        }
        if (!$validator->fails()) {
            $user->password = bcrypt($data['password']);
        }
        $user->type = 'center';
        if ($user->save()) {
            $center->user_id = $user->id;
            $center->name = $data['name'];
            $center->technical_user = $data['technical_user'];
            $center->insures_under_contract = $insures;
            $center->shift = $data['shift'];
            $center->address = $data['address'];
            $center->medical_code = $data['medical_code'];
            $center->sahebe_emtiaz = $data['sahebe_emtiaz'];
            $center->bank = $data['bank'];
            $center->account_num = $data['account_num'];
            $center->website = $data['website'];
            $center->mobile = $data['mobile'];
            $center->fax = $data['fax'];
            $center->phone = $data['phone'];
            $center->save();
        }
        return redirect('admin/show-center/' . $center->id)->withMessage('مرکز خدماتی با موفقیت ویرایش شد.');

    }

    public function getEditCommit($id)
    {
        $commit = Commit::findOrFail($id);
        return view('admin.edit.editCommit', compact('commit'));
    }

    public function postEditCommit(Request $request, $id)
    {
        $data = $request->all();
        $commit = Commit::findOrFail($id);
        $commit->name = $data['name'];
        $commit->save();
        return redirect('admin/commits')->withMessage('تعهد با موفقیت ویرایش شد.');
    }

    public function getEditService($id)
    {
        $service = Service::findOrFail($id);
        $commits = Commit::all();
        return view('admin.edit.editService', compact('commits', 'service'));

    }

    public function postEditService(Request $request, $id)
    {
        $data = $request->all();
        $service = Service::findOrFail($id);
        $service->name = $data['name'];
        $service->commit_id = $data['commit_id'];
        $service->save();
        return redirect('admin/commits')->withMessage('خدمت با موفقیت ویرایش شد.');
    }

    public function getUsers()
    {
        $users = User::latest()->get();
        $pass = User::first();

        return view('admin.index.users', compact('users'));
    }

    public function getChangeStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 0) {
            $user->status = 1;
            $message = 'کاربر با موفقیت غیر فعال شد.';
        } else {
            $user->status = 0;
            $message = 'کاربر با موفقیت فعال شد.';
        }
        $user->save();
        return redirect('admin/users')->withMessage($message);

    }

    public function getNewUser()
    {
        return view('admin.create.newUser');
    }

    public function postNewUser(Request $request)
    {
        $data = $request->all();
        $user = new User;

        $user->fullname = $data['name'];
        $user->email = $data['email'];
        $user->type = $data['type'];
        $user->username = $data['username'];
        $user->password = bcrypt($data['password']);
        $user->status = 0;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move('uploads', $file->getClientOriginalName());
            $user->pic = $file->getClientOriginalName();
        }
        return redirect('admin/users')->withMessage('کاربر جدید با موفقیت اضافه شد.');
    }

    public function getEditUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit.editUser', compact('user'));
    }

    public function postEditUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            File::delete('uploads/' . $user->pic);
            $file = $request->file('image');
            $file->move('uploads', $file->getClientOriginalName());
            $user->pic = $file->getClientOriginalName();
        }
        if ($data['password'] != null) {
            $user->password = bcrypt($data['password']);
        }
        $user->fullname = $data['name'];
        $user->email = $data['email'];
        $user->type = $data['type'];
        $user->username = $data['username'];
        $user->save();
        return redirect('admin/users')->withMessage('کاربر با موفقیت ویرایش شد.');
    }

    public function getShowPresc($id)
    {

        $medicines = Medicine::all();
        $insureds = Insured::all();
        $contracts = Contract::all();
        $contractcommits = ContractsCommit::all();
        $insurers = Insurer::all();
        $commits = Commit::all();
        $prescs = Prescription::orderBy('created_at', 'desc')->get();
        $prescMed = PresMedicine::all();
        $setting = Setting::all();
        $pr = Prescription::find($id);
        $prmeds = PresMedicine::where('pres_id', $pr->id)->get();
        $specs = Spec::all();
        $contarcts = Contract::all();
        $prescspecs = Prescspec::where('presc_id', $id)->get();

        return view('admin.show.showPresc', compact('prescspecs', 'contracts', 'specs', 'prmeds', 'pr', 'setting', 'prescMed', 'medicines', 'insureds', 'insurers', 'contractcommits', 'commits', 'prescs'));

    }

    public function getRemoveFromContract($insured_id)
    {
        $insured = Insured::findOrFail($insured_id);
        $contract = $insured->contract_id;
        $specs=Spec::where('contract_id',$contract)->where('insured_id',$insured_id)->get();
        foreach($specs as $spec){
            $spec->delete();
        }
        $insureds = Insured::where('insured_id', $insured_id)->get();
        $insured->contract_id = 0;
        $insured->save();
        foreach ($insureds as $i) {
            $i->contract_id = 0;
            $i->save();
        }


        return redirect('admin/show-contract/' . $contract)->withMessage('بیمه شده با موفقیت از قرارداد حذف شد.');
    }

    public function postSearch(Request $request)
    {
        return redirect($request['search']);
    }

    public function getShowUser($id)
    {
        $user1 = User::findOrFail($id);
        $users = User::all();
        return view('admin.show.showUser', compact('users', 'user1'));
    }

    public function getShowMedicine($id)
    {
        $medicine1 = Medicine::findOrFail($id);
        $medicines = Medicine::all();
        return view('admin.show.showMedicine', compact('medicines', 'medicine1'));
    }

    public function getEditProfile()
    {
        $user = User::findOrFail(Auth::User()->id);
        return view('admin.editProfile', compact('user'));
    }

    public function postEditProfile(Request $request)
    {
        $user = User::findOrFail(Auth::User()->id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            File::delete('uploads/' . $user->pic);
            $file = $request->file('image');
            $file->move('uploads', $file->getClientOriginalName());
            $user->pic = $file->getClientOriginalName();
        }
        if ($data['password'] != null) {
            $user->password = bcrypt($data['password']);
        }
        $user->fullname = $data['name'];
        $user->email = $data['email'];
        $user->type = $data['type'];
        $user->username = $data['username'];
        $user->save();
        return redirect('admin/users')->withMessage('کاربر با موفقیت ویرایش شد.');
    }

    public function getDoctors()
    {
        $doctors=Doctor::all();
        return view('admin.index.doctors',compact('doctors'));
    }
    public function getExpertises()
    {
        $expertises=Expertise::all();
        return view('admin.index.expertises',compact('expertises'));
    }

    public function getNewDoctor()
    {
        $expertises=Expertise::all();
        return view('admin.create.newDoctor',compact('expertises'));
    }
    public function getEditDoctor($id)
    {
        $doctor=Doctor::findorfail($id);
        $expertises=Expertise::all();
        return view('admin.edit.editDoctor',compact('doctor','expertises'));
    }

    public function postNewDoctor(Request $request)
    {
        $data = $request->all();
        $docotr = New Doctor;
        $docotr->lname = $data['lname'];
        $docotr->fname = $data['fname'];
        $docotr->medical_code = $data['medical_code'];
        $docotr->expertise = $data['expertise'];
        $docotr->status=1;
        $docotr->save();
        return redirect('admin/doctors')->withMessage('پزشک جدید با موفقیت اضافه شد.');
    }
    public function postEditDoctor(Request $request,$id)
    {
        $data = $request->all();
        $docotr = Doctor::findOrFail($id);
        $docotr->lname = $data['lname'];
        $docotr->fname = $data['fname'];
        $docotr->medical_code = $data['medical_code'];
        $docotr->expertise = $data['expertise'];
        $docotr->status=1;
        $docotr->save();
        return redirect('admin/doctors')->withMessage('پزشک با موفقیت ویرایش شد.');
    }
    public function getNewExpertise()
    {
        return view('admin.create.newExpertise');
    }
    public function getEditExpertise($id)
    {
        $expertise=Expertise::findorfail($id);
        return view('admin.edit.editExpertise',compact('expertise'));
    }
    public function postNewExpertise(Request $request)
    {
        $data=$request->all();
        $expertise=New Expertise();
        $expertise->name=$data['name'];
        $expertise->code=$data['code'];
        $expertise->save();
        return redirect('admin/expertises')->withMessage('تخصص با موفقیت ثبت شد.');
    }
    public function postEditExpertise(Request $request,$id)
    {
        $expertise=Expertise::findorfail($id);
        $data=$request->all();
        $expertise->name=$data['name'];
        $expertise->code=$data['code'];
        $expertise->save();
        return redirect('admin/expertises')->withMessage('تخصص با موفقیت ویرایش شد.');
    }

    public function getEditMedicine($id)
    {
        $medicine=Medicine::findorfail($id);
        $med_cats=Medscat::where('med_id',$id)->get();
        $expertises=Expertise::all();
        $special_meds=Specialmed::where('med_id',$id)->get();
        $special_uses=Specialuse::all();

        return view('admin.edit.editMedicine',compact('medicine','med_cats','expertises','special_meds','special_uses'));
    }
    public function getSpecialuses(){
        $uses=Specialuse::all();
        return view('admin.index.specialUses',compact('uses'));
    }
    public function getNewSpecialuses(){
        return view('admin.create.newSpecialUse');
    }
    public function postNewSpecialuses(Request $request){
        $data=$request->all();
        $use=New Specialuse();
        $use->name=$data['name'];
        $use->save();
        return redirect('admin/specialuses')->withMessage(' با موفقیت ثبت شد.');;
    }
    public function getEditSpecialuses($id){
        $use=Specialuse::findorfail($id);
        return view('admin.edit.editSpecialUse',compact('use'));
    }
    public function postEditSpecialuses(Request $request,$id){
        $data=$request->all();
        $use=Specialuse::findorfail($id);
        $use->name=$data['name'];
        $use->save();
        return redirect('admin/specialuses')->withMessage(' با موفقیت ویرایش شد.');;
    }

    public function getShowUse($id)
    {
        $use=Specialuse::findorfail($id);
        $smeds=Specialmed::where('use_id',$id)->lists('med_id');
        $meds=Medicine::whereIn('id',$smeds)->get();
        return view('admin.show.showUse',compact('meds','use'));
    }
    public function getMedicineGroupAdd(){
        $uses=Specialuse::all();
        $meds=Medicine::all();
        $exps=Expertise::all();
        return view('admin.edit.editMedicineGroupAdd',compact('meds','uses','exps'));
    }
    public function postMedicineGroupAdd(Request $request){
        $data=$request->all();
        $meds=$data['meds'];
        $exps=$data['exps'];
        $uses=$data['uses'];
        foreach($meds as $med){
            $med_uses=Medscat::where('med_id',$med)->get();
            $med_exps=Specialmed::where('med_id',$med)->get();
            foreach($med_uses as $med_use){
                $med_use->delete();
            }
            foreach($med_exps as $med_exp){
                $med_exp->delete();
            }
            foreach($exps as $exp){
                $med_cat=new Medscat();
                $med_cat->expertise_id=$exp;
                $med_cat->med_id=$med;
                $med_cat->save();
            }
            foreach($uses as $use){
                $special_med=new Specialmed();
                $special_med->use_id=$use;
                $special_med->med_id=$med;
                $special_med->save();
            }
        }
        return redirect('admin/medicines')->withMessage('عملیات با موفقیت انجام شد.');

    }

    public function getReports($start_date,$finish_date,$year)
    {
        if($start_date!='no' && $finish_date!='no'){

            $prescs_count=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->count();
            $prescs_total=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->sum('total');
            $prescs_iran_pay=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->sum('iran_pay');
            $prescs_payable=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->sum('payable');
        }else{

            if($start_date!='no' && $finish_date=='no'){
                $finish_date=jdate('Y/n/j','','','','en');
                $prescs_count=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->count();
                $prescs_total=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->sum('total');
                $prescs_iran_pay=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->sum('iran_pay');
                $prescs_payable=Prescription::whereBetween('reception_date', array($start_date,$finish_date))->sum('payable');
            }else{
                $prescs_count=Prescription::all()->count();
                $prescs_total=Prescription::all()->sum('total');
                $prescs_iran_pay=Prescription::all()->sum('iran_pay');
                $prescs_payable=Prescription::all()->sum('payable');
            }
        }
        $centers_count=ServiceCenter::all()->count();
        $insurers_count=Insurer::all()->count();
        $insureds_count=Insured::all()->count();
        $contracts_count=Contract::all()->count();
        $medicines_count=Medicine::all()->count();
        $doctors_count=Doctor::all()->count();

        $total[0]=Prescription::whereBetween('reception_date',array($year.'/01/01',$year.'/01/31'))->sum('total');
        $total[1]=Prescription::whereBetween('reception_date',array($year.'/02/01',$year.'/02/31'))->sum('total');
        $total[2]=Prescription::whereBetween('reception_date',array($year.'/03/01',$year.'/03/31'))->sum('total');
        $total[3]=Prescription::whereBetween('reception_date',array($year.'/04/01',$year.'/04/31'))->sum('total');
        $total[4]=Prescription::whereBetween('reception_date',array($year.'/05/01',$year.'/05/31'))->sum('total');
        $total[5]=Prescription::whereBetween('reception_date',array($year.'/06/01',$year.'/06/31'))->sum('total');
        $total[6]=Prescription::whereBetween('reception_date',array($year.'/07/01',$year.'/07/30'))->sum('total');
        $total[7]=Prescription::whereBetween('reception_date',array($year.'/08/01',$year.'/08/30'))->sum('total');
        $total[8]=Prescription::whereBetween('reception_date',array($year.'/09/01',$year.'/09/30'))->sum('total');
        $total[9]=Prescription::whereBetween('reception_date',array($year.'/10/01',$year.'/10/30'))->sum('total');
        $total[10]=Prescription::whereBetween('reception_date',array($year.'/11/01',$year.'/11/30'))->sum('total');
        $total[11]=Prescription::whereBetween('reception_date',array($year.'/12/01',$year.'/12/30'))->sum('total');

        $iran_pay[0]=Prescription::whereBetween('reception_date',array($year.'/01/01',$year.'/01/31'))->sum('iran_pay');
        $iran_pay[1]=Prescription::whereBetween('reception_date',array($year.'/02/01',$year.'/02/31'))->sum('iran_pay');
        $iran_pay[2]=Prescription::whereBetween('reception_date',array($year.'/03/01',$year.'/03/31'))->sum('iran_pay');
        $iran_pay[3]=Prescription::whereBetween('reception_date',array($year.'/04/01',$year.'/04/31'))->sum('iran_pay');
        $iran_pay[4]=Prescription::whereBetween('reception_date',array($year.'/05/01',$year.'/05/31'))->sum('iran_pay');
        $iran_pay[5]=Prescription::whereBetween('reception_date',array($year.'/06/01',$year.'/06/31'))->sum('iran_pay');
        $iran_pay[6]=Prescription::whereBetween('reception_date',array($year.'/07/01',$year.'/07/30'))->sum('iran_pay');
        $iran_pay[7]=Prescription::whereBetween('reception_date',array($year.'/08/01',$year.'/08/30'))->sum('iran_pay');
        $iran_pay[8]=Prescription::whereBetween('reception_date',array($year.'/09/01',$year.'/09/30'))->sum('iran_pay');
        $iran_pay[9]=Prescription::whereBetween('reception_date',array($year.'/10/01',$year.'/10/30'))->sum('iran_pay');
        $iran_pay[10]=Prescription::whereBetween('reception_date',array($year.'/11/01',$year.'/11/30'))->sum('iran_pay');
        $iran_pay[11]=Prescription::whereBetween('reception_date',array($year.'/12/01',$year.'/12/30'))->sum('iran_pay');

        $payable[0]=Prescription::whereBetween('reception_date',array($year.'/01/01',$year.'/01/31'))->sum('payable');
        $payable[1]=Prescription::whereBetween('reception_date',array($year.'/02/01',$year.'/02/31'))->sum('payable');
        $payable[2]=Prescription::whereBetween('reception_date',array($year.'/03/01',$year.'/03/31'))->sum('payable');
        $payable[3]=Prescription::whereBetween('reception_date',array($year.'/04/01',$year.'/04/31'))->sum('payable');
        $payable[4]=Prescription::whereBetween('reception_date',array($year.'/05/01',$year.'/05/31'))->sum('payable');
        $payable[5]=Prescription::whereBetween('reception_date',array($year.'/06/01',$year.'/06/31'))->sum('payable');
        $payable[6]=Prescription::whereBetween('reception_date',array($year.'/07/01',$year.'/07/30'))->sum('payable');
        $payable[7]=Prescription::whereBetween('reception_date',array($year.'/08/01',$year.'/08/30'))->sum('payable');
        $payable[8]=Prescription::whereBetween('reception_date',array($year.'/09/01',$year.'/09/30'))->sum('payable');
        $payable[9]=Prescription::whereBetween('reception_date',array($year.'/10/01',$year.'/10/30'))->sum('payable');
        $payable[10]=Prescription::whereBetween('reception_date',array($year.'/11/01',$year.'/11/30'))->sum('payable');
        $payable[11]=Prescription::whereBetween('reception_date',array($year.'/12/01',$year.'/12/30'))->sum('payable');

        $registred=Prescription::whereIn('status',[1,8])->whereBetween('reception_date',array($year.'/01/01',$year.'/12/30'))->count();
        $send_to_expert=Prescription::whereIn('status',[2,4,6])->whereBetween('reception_date',array($year.'/01/01',$year.'/12/30'))->count();
        $ticket=Prescription::whereIn('status',[3,5,7])->whereBetween('reception_date',array($year.'/01/01',$year.'/12/30'))->count();
        return view('admin.index.reports',compact('prescs_count','prescs_total',
            'prescs_iran_pay', 'prescs_payable','centers_count','insurers_count',
            'insureds_count','contracts_count','medicines_count','doctors_count',
            'year','total','payable','iran_pay','registred','send_to_expert',
            'ticket'));
    }
    public function postReports(Request $request,$start_date,$finish_date,$year)
    {
        $data=$request->all();
        $sdate=$data['start_date'];
        $fdate=$data['finish_date'];
        if($data['finish_date']==null){
            $data['finish_date']='no';
            $fdate='';
        }
        if($data['start_date']==null){
            $data['start_date']='no';
            $sdate='';
        }

        return redirect('admin/reports/'.$data['start_date'].'/'.$data['finish_date'].'/'.$year)->withStartdate($sdate)->withFinishdate($fdate);
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myAccount(Request $request)
    {  
         
    
        $user_id =  authUserIdNull();
        $user_result = User::where('id', $user_id)->first();


        $inputs = $request->all();
        if (count($inputs) > 0) {

            $validator = (new User)->validatePassword($inputs);
            if ($validator->fails()) {
            if($user_result->user_type==1){
                return redirect()->route('setting.manage-account')
                    ->withErrors($validator);
            }
            else if($user_result->user_type==5){

                return redirect()->route('setting.manage-account')
                    ->withErrors($validator);
            } else if($user_result->user_type==3){

                return redirect()->route('setting.manage-account')
                    ->withErrors($validator);
            } else{
                return redirect()->route('setting.manage-account')
                    ->withErrors($validator);
            // return view('frontend.pages.change-password');
            }
            }


            $password = \Auth::user()->password;
            if(!(\Hash::check($inputs['password'], $password))){

            if($user_result->user_type==1){
                return redirect()->route('setting.manage-account')
                    ->with("error", lang('messages.invalid_password'));
            }
            else if($user_result->user_type==5){
                return redirect()->route('setting.manage-account')
                    ->with("error", lang('messages.invalid_password'));
            }
            else{
                return view('frontend.pages.change-password')->with('not_update', 'error');;
            }
            }

            (new User)->updatePassword(\Hash::make($inputs['new_password']));

            if($user_result->user_type==1){
            return redirect()->route('setting.manage-account')
                ->with('success', lang('messages.password_updated'));
            }
            else if($user_result->user_type==5){
            return redirect()->route('setting.manage-account')
                ->with('success', lang('messages.password_updated'));
            }
            else{
                // return view('frontend.pages.change-password')->with('update', 'done');
                return redirect()->back()->with('update', 'success');
            }
        }
        if($user_result->user_type==1){
          return view('admin.setting.account');
        }
        else if($user_result->user_type==5){
          return view('admin.setting.account');
        }
        else{
             return view('admin.setting.account');
             // return view('frontend.pages.change-password');
       }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $profile = (new Laboratory)->laboratoryDetail();
        $inputs = $request->all();
        if (count($inputs) > 0) {

            $validator = (new LaboratoryProfile)->validateProfile($inputs);
            if ($validator->fails()) {
                return redirect()->route('setting.myprofile')
                    ->withInput()
                    ->withErrors($validator);
            }

            $inputs = $inputs + [
                    'address1'      => $inputs['address'],
                    'updated_by' => \Auth::user()->id
                ];
            (new Laboratory)->store($inputs, \Auth::user()->laboratory_id);

            $data = [
                'complete_name' => $inputs['complete_name'],
                'laboratory_id' => \Auth::user()->laboratory_id,
                'created_by'    => \Auth::user()->id,
                'updated_by'    => \Auth::user()->id
            ];
            (new LaboratoryProfile)->store($data);
            return redirect()->route('setting.myprofile')
                ->with('success', lang('messages.updated', lang('setting.profile_detail')));
        }
        $profileDetail = LaboratoryProfile::where('laboratory_id', \Auth::user()->laboratory_id)->first();
        return view('admin.setting.profile', compact('profile', 'profileDetail'));
    }

    public function profiles()
    {

        $company = Company::first();
        if (!$company) {
            abort(404);
        }
        $insurancePolicyDetails = InsurancePolicy::count();
        $bankDetails = BankMaster::count();
        $result = BankMaster::first();
        


        $tab = \Input::get('tab', 1);
        $show_profile = \Input::get('show_profile', 0);
        $timezone = (new Timestamp)->getTimeStampsService();
        $banks = (new BankMaster)->getBanks(['company' => $company['id']]);
        return view('admin.company.edit', compact('company', 'timezone', 'tab', 'show_profile', 'banks', 'insurancePolicyDetails' , 'bankDetails' , 'result'));
    }

    
}

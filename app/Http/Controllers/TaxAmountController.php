<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\TaxAmount;
use App\User;
use Illuminate\Http\Request;

class TaxAmountController extends  Controller{
    
    public function index() {
       $id = 1;
       $result = (new TaxAmount)->find($id);
        if (!$result) {
            abort(401);
        }
       // dd($result);
        return view('admin.tax_setting.create', compact('result'));
    }


    public function update(Request $request){
        $id = 1;
        $result = (new TaxAmount)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();
        try {

            // $inputs = $inputs + [
            //     'updated_by' => Auth::id(),
            // ];  

            (new TaxAmount)->store($inputs, $id);

            return redirect()->route('tax-amounts')->with('success', lang('messages.updated', lang('Tax Amount')));

        } catch (\Exception $exception) {
            return redirect()->route('tax-amounts')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    

}

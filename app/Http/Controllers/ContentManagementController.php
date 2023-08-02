<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\ContentManagement;
use Illuminate\Http\Request;

class ContentManagementController extends  Controller{
    
    public function index() {
       $id = 1;
       $result = (new ContentManagement)->find($id);
        if (!$result) {
            abort(401);
        }
       // dd($result);
        return view('admin.content_management.create', compact('result'));
    }


    public function update(Request $request){
        $id = 1;
        $result = (new ContentManagement)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {

            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];  
            
            (new ContentManagement)->store($inputs, $id);
            
            return redirect()->route('content-management')
                ->with('success', lang('messages.updated', lang('Content Management')));

        } catch (\Exception $exception) {
        
            return redirect()->route('content-management')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

  
    public function edit($id = null) {
        $result = (new ContentManagement)->find($id);
        if (!$result) {
            abort(401);
        }
       // dd($result);
        return view('admin.content_management.create', compact('result'));
    }
    

}

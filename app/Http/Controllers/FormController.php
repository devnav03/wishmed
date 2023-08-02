<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController  extends  Controller{
    
    public function  index() {
        
        return view('admin.form.index');
    }

    public function  create()
    {
        return view('admin.form.create');
    }


    public function  store(Request $request)
    {
        
        $inputs = $request->all();

        try {
            $validator = (new Form)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if(isset($inputs['file']) or !empty($inputs['file'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('file')) {
                    $file = $request->file('file') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/forms/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/forms/';
                $file = $fname.$fileName;
            }
            else{
                $file = null;
            }
            unset($inputs['file']);            
            $inputs['file'] = $file;


            //\DB::beginTransaction();
            (new Form)->store($inputs);
           

            return redirect()->route('form.index')
                ->with('success', lang('messages.created', lang('Form')));
        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('form.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    public function update(Request $request, $id = null)
    {
        $result = (new Form)->find($id);
        if (!$result) {
            abort(401);
        }
       
        $inputs = $request->all();

        try {
          
            if(isset($inputs['file']) or !empty($inputs['file'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('file')) {
                    $file = $request->file('file') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/forms/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/forms/';
                $file = $fname.$fileName;
            }
            else{
                $file = $result->file;
            }
            unset($inputs['file']);            
            $inputs['file'] = $file;
            
            (new Form)->store($inputs, $id);
   
            return redirect()->route('form.index')
                ->with('success', lang('messages.updated', lang('Form')));

        } catch (\Exception $exception) {
            
            return redirect()->route('form.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null)
    {
        $result = (new Form)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.form.create', compact('result'));
    }


    public function Paginate(Request $request, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new Form)->getForm($inputs, $start, $perPage);
            $totalFaq = (new Form)->totalForm($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new Form)->getForm($inputs, $start, $perPage);
            $totalFaq = (new Form)->totalForm();
            $total = $totalFaq->total;
        }

        return view('admin.form.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function Toggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Form::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Form')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

 
    public function Action(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('form.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Form'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        Form::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('form.index')
            ->with('success', lang('messages.updated', lang('Form')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Form)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Form)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('Form in use')];
             }
             else {
                 (new Form)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Form'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}
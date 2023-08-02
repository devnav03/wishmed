<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseDeal;
use App\Models\Product;
use Illuminate\Http\Request;

class CaseDealController extends  Controller{
    
    public function  index() {
        return view('admin.case_deal.index');
    }

  
    public function  create(){
                
        return view('admin.case_deal.create');
    }

    public function store(Request $request) {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new CaseDeal)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                ];  

           
            (new CaseDeal)->store($inputs);
           
            return redirect()->route('case-deal.index')
                ->with('success', lang('messages.created', lang('Case Deal')));
        } catch (\Exception $exception) {
     
            return redirect()->route('case-deal.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


    public function update(Request $request, $id = null)
    {
        $result = (new CaseDeal)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {

            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];  
            
            (new CaseDeal)->store($inputs, $id);
            
            return redirect()->route('case-deal.index')
                ->with('success', lang('messages.updated', lang('Case Deal')));

        } catch (\Exception $exception) {
        
            return redirect()->route('case-deal.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

  
    public function edit($id = null)
    {
        $result = (new CaseDeal)->find($id);
        if (!$result) {
            abort(401);
        }
        
        $product = Product::where('id', $result->product_id)->select('name')->first();

       // dd($result);
        return view('admin.case_deal.create', compact('result', 'product'));
    }


    public function case_dealPaginate(Request $request, $pageNumber = null)
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

            $data = (new CaseDeal)->getCaseDeal($inputs, $start, $perPage);
            $totalHsnCode = (new CaseDeal)->totalCaseDeal($inputs);
            $total = $totalHsnCode->total;
        } else {

            $data = (new CaseDeal)->getCaseDeal($inputs, $start, $perPage);
            $totalHsnCode = (new CaseDeal)->totalCaseDeal();
            $total = $totalHsnCode->total;
        }
         

        return view('admin.case_deal.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function case_dealToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = CaseDeal::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Case Deal')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

 
    public function case_dealAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('case-deal.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Case Deal'))));
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

        CaseDeal::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('case-deal.index')
            ->with('success', lang('messages.updated', lang('Case Deal')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new CaseDeal)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new CaseDeal)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('Case Deal in use')];
             }
             else {
                 (new CaseDeal)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Case Deal'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

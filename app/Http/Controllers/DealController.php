<?php

namespace App\Http\Controllers;
/**
 * :: Deals Controller ::
 * To manage lecture.
 *
 **/
use Auth;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController  extends  Controller{

    public function  index() {
        
        return view('admin.deals.index');
    }

    public function  create() {
        return view('admin.deals.create');
    }

    public function  store(Request $request) {
        
        $inputs = $request->all();

        try {
            //   $validator = (new Deal)->validate($inputs);
            // if( $validator->fails() ) {
            //     return back()->withErrors($validator)->withInput();
            // }
            
            // $inputs = $inputs + [
            //         'created_by' => Auth::id(),
            //     ];   
            (new Deal)->store($inputs);
           
            return redirect()->route('deals.index')
                ->with('success', lang('messages.created', lang('Deal')));
        } catch (\Exception $exception) {
     
            return redirect()->route('deals.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    public function update(Request $request, $id = null) {
        $result = (new Deal)->find($id);
        if (!$result) {
            abort(401);
        }
       
        $inputs = $request->all();

        try {
            //  $validator = (new Faqs)->validate($inputs, $id);
            // if( $validator->fails() ) {
            //     return back()->withErrors($validator)->withInput();
            // }

            // $inputs = $inputs + [
            //         'updated_by' => Auth::id(),
            //     ];    
            
            (new Deal)->store($inputs, $id);

            return redirect()->route('deals.index')
                ->with('success', lang('messages.updated', lang('Deal')));
        } catch (\Exception $exception) {
            return redirect()->route('deals.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    public function edit($id = null) {
        $result = (new Deal)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.deals.create', compact('result'));
    }

    public function dealsPaginate(Request $request, $pageNumber = null) {

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

            $data = (new Deal)->getDeal($inputs, $start, $perPage);
            $totalFaq = (new Deal)->totalDeal($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new Deal)->getDeal($inputs, $start, $perPage);
            $totalFaq = (new Deal)->totalDeal();
            $total = $totalFaq->total;
        }

        return view('admin.deals.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

  
    public function dealsToggle($id = null)  {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Deal::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Deal')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }


    public function dealsAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('deals.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Deal'))));
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

        Deal::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('deals.index')
            ->with('success', lang('messages.updated', lang('Deal')));
    }


    public function drop($id) {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Deal)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Deal)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('Deal in use')];
             }
             else {
                 (new Deal)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Deal'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}
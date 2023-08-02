<?php

namespace App\Http\Controllers;
/**
 * :: Order Controller ::
 * 
 *
 **/
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\States;
use Illuminate\Http\Request;

class StatesController extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {
        return view('admin.states.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
              
        return view('admin.states.create');
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        $result = (new States)->find($id);
        if (!$result) {
            abort(401);
        }

       // dd($result);
        return view('admin.states.create', compact('result'));
    }

     public function  store(Request $request)
    {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new States)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'created_by' => Auth::id(),
                ];  

           // dd('wdwe');
            //\DB::beginTransaction();
            (new States)->store($inputs);
            //\DB::commit(); 
            /* $route = route('game-master.index');
            $lang  = lang('messages.created', lang('game_master.game'));*/
            /*return validationResponse(true, 201, $lang, $route);*/
            return redirect()->route('states.index')
                ->with('success', lang('messages.created', lang('states.states')));
        } catch (\Exception $exception) {
            //\DB::rollBack(); 
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('states.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


     public function update(Request $request, $id = null)
    {
        $result = (new States)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();
        /*$validator = (new FinancialYear)->validateFinancialYear($inputs, $id);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }*/

        try {
           $validator = (new States)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'updated_by' => Auth::id(),
                ];   
            
            (new States)->store($inputs, $id);
            //\DB::commit();
            /*$route = route('financial-year.index');
            $lang = lang('messages.updated', lang('financial_year.financial_year'));
            return validationResponse(true, 201, $lang, $route);*/
            return redirect()->route('states.index')
                ->with('success', lang('messages.updated', lang('states.states')));

        } catch (\Exception $exception) {
            //\DB::rollBack();
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('states.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function statesPaginate(Request $request, $pageNumber = null)
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

            $data = (new States)->getStates($inputs, $start, $perPage);
            $totalStates = (new States)->totalStates($inputs);
            $total = $totalStates->total;
        } else {

            $data = (new States)->getStates($inputs, $start, $perPage);
            $totalStates = (new States)->totalStates();
            $total = $totalStates->total;
        }
         

        return view('admin.states.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function statesToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = States::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('states.states')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    /**
     * Method is used to update status of group enable/disable
     *
     * @return \Illuminate\Http\Response
     */
    public function statesAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('states.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('states.states'))));
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

        States::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('states.index')
            ->with('success', lang('messages.updated', lang('states.states')));
    }

    /**
     * Remove the specified resource from storage
     * @param $id
     * @return string
     */
    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new States)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new States)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('states.states_in_use')];
             }
             else {
                 (new States)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('states.states'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }

}

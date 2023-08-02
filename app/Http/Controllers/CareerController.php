<?php

namespace App\Http\Controllers;
/**
 * :: Category Controller ::
 * To manage games.
 *
 **/
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends  Controller{
   

    public function  index()
    {
        return view('admin.career.index');
    }

  
    public function  create()
    {
        return view('admin.career.create');
    }

   
    public function  store(Request $request)
    {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new Career)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0
                ];  

 
            (new Career)->store($inputs);
           
            return redirect()->route('career.index')
                ->with('success', lang('messages.created', lang('career.career')));
        } catch (\Exception $exception) {

            return redirect()->route('career.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }

    /**
     * Updating the record
     * @param null $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id = null)
    {
        $result = (new Career)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {
          
            $validator = (new Career)->validate($inputs, $id);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0
                ];   
            
            (new Career)->store($inputs, $id);
            
            return redirect()->route('career.index')
                ->with('success', lang('messages.updated', lang('career.career')));

        } catch (\Exception $exception) {
           
            return redirect()->route('career.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

  
    public function edit($id = null)
    {
        $result = (new Career)->find($id);
        if (!$result) {
            abort(401);
        }
       

       // dd($result);
        return view('admin.career.create', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function careerPaginate(Request $request, $pageNumber = null)
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

            $data = (new Career)->getTax($inputs, $start, $perPage);
            $totalBrand = (new Career)->totalTax($inputs);
            $total = $totalBrand->total;
        } else {

            $data = (new Career)->getTax($inputs, $start, $perPage);
            $totalBrand = (new Career)->totalTax();
            $total = $totalBrand->total;
        }


        return view('admin.career.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function careerToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Career::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('career.career')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }


    public function careerAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('career.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('career.career'))));
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

        Career::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('career.index')
            ->with('success', lang('messages.updated', lang('career.career')));
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

        $result = (new Career)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Career)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('career.career_in_use')];
             }
             else {
                 (new Career)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('career.career'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

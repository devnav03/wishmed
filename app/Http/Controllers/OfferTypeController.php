<?php

namespace App\Http\Controllers;
/**
 * :: OfferType Controller ::
 * 
 *
 **/
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\OfferType;
use Illuminate\Http\Request;

class OfferTypeController  extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {
        return view('admin.offer_type.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
              
        return view('admin.offer_type.create');
    }

    /**
     * Store a newly created resource in storage.
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function  store(Request $request)
    {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new OfferType)->validate($inputs);
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
            (new OfferType)->store($inputs);
            //\DB::commit(); 
           
            return redirect()->route('offer-type.index')
                ->with('success', lang('messages.created', lang('offer_type.offer_type')));
        } catch (\Exception $exception) {
           // dd($exception);

            //\DB::rollBack(); 
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('offer-type.create')
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
        $result = (new OfferType)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {
            //\DB::beginTransaction();
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'updated_by' => Auth::id(),
                ];   
            
            (new OfferType)->store($inputs, $id);
            //\DB::commit();
            
            return redirect()->route('offer-type.index')
                ->with('success', lang('messages.updated', lang('offer_type.offer_type')));

        } catch (\Exception $exception) {
            //\DB::rollBack();
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('offer-type.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        $result = (new OfferType)->find($id);
        if (!$result) {
            abort(401);
        }
       
       // dd($result);
        return view('admin.offer_type.create', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function offer_typePaginate(Request $request, $pageNumber = null)
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

            $data = (new OfferType)->getOfferType($inputs, $start, $perPage);
            $totalOfferType = (new OfferType)->totalOfferType($inputs);
            $total = $totalOfferType->total;
        } else {

            $data = (new OfferType)->getOfferType($inputs, $start, $perPage);
            $totalOfferType = (new OfferType)->totalOfferType();
            $total = $totalOfferType->total;
        }
         

        return view('admin.offer_type.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function offer_typeToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = OfferType::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('offer_type.offer_type')));
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
    public function offer_typeAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('offer-type.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('offer_type.offer_type'))));
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

        OfferType::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('offer_type.index')
            ->with('success', lang('messages.updated', lang('offer_type.offer_type')));
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

        $result = (new OfferType)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new OfferType)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('offer_type.offer_type_in_use')];
             }
             else {
                 (new OfferType)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('offer_type.offer_type'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

<?php

namespace App\Http\Controllers;
/**
 * :: Color Controller ::
 * To manage games.
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController  extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {
        return view('admin.color.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
        return view('admin.color.create');
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
            $validator = (new Color)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'created_by' => Auth::id(),
                ];  

            //\DB::beginTransaction();
            (new Color)->store($inputs);
            //\DB::commit(); 
          
            return redirect()->route('color.index')
                ->with('success', lang('messages.created', lang('color.color')));
        } catch (\Exception $exception) {
            //dd($exception);
            //\DB::rollBack(); 
            return redirect()->route('color.create')
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
        $result = (new Color)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'updated_by' => Auth::id(),
                ];   
            
            (new Color)->store($inputs, $id);
            //\DB::commit();
         
            return redirect()->route('color.index')
                ->with('success', lang('messages.updated', lang('color.color')));

        } catch (\Exception $exception) {
            //\DB::rollBack();
            return redirect()->route('color.edit', [$id])
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
        $result = (new Color)->find($id);
        if (!$result) {
            abort(401);
        }
       
       // dd($result);
        return view('admin.color.create', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function colorPaginate(Request $request, $pageNumber = null)
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

            $data = (new Color)->getColor($inputs, $start, $perPage);
            $totalFlavour = (new Color)->totalColor($inputs);
            $total = $totalFlavour->total;
        } else {

            $data = (new Color)->getColor($inputs, $start, $perPage);
            $totalFlavour = (new Color)->totalColor();
            $total = $totalFlavour->total;
        }


        return view('admin.color.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function colorToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Color::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('color.color')));
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
    public function colorAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('color.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('color.color'))));
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

        Color::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('color.index')
            ->with('success', lang('messages.updated', lang('color.color')));
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

        $result = (new Color)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Color)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('color.color_in_use')];
             }
             else {
                 (new Color)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('color.color'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

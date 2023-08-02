<?php

namespace App\Http\Controllers;
/**
 * :: Category Controller ::
 * To manage games.
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController  extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {
        return view('admin.client.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
        return view('admin.client.create');
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
            $validator = (new Client)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if(isset($inputs['logo']) or !empty($inputs['logo']))
            {
                $image_name1 = rand(100000, 999999);
                $fileName1 = '';
                if($file1 = $request->hasFile('logo')) 
                {
                    $file1 = $request->file('logo') ;
                    $img_name1 = $file1->getClientOriginalName();
                    $image_resize1 = Image::make($file1->getRealPath()); 
                    $image_resize1->resize(512, 512);

                    $fileName1 = $image_name1.$img_name1;
                    $image_resize1->save(public_path('/uploads/client_logo/' .$fileName1));

                    // $fileName1 = $image_name1.$img_name1;
                    // $destinationPath1 = public_path().'/uploads/brand_logo/' ;
                    // $file1->move($destinationPath1, $fileName1);
                }
                $fname1 ='/uploads/client_logo/';
                $icon = $fname1.$fileName1;
            }
            else{
                $icon = null;
            }

            unset($inputs['logo']);

            $inputs['logo'] = $icon;

            $slug_name = str_replace(' ', '-', $inputs['name']);
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'created_by' => Auth::id(),
                    'logo' => $icon,
                    'url' =>  $slug_name,
                ];  

           // dd('wdwe');
            //\DB::beginTransaction();
            (new Client)->store($inputs);
            //\DB::commit(); 
            /* $route = route('game-master.index');
            $lang  = lang('messages.created', lang('game_master.game'));*/
            /*return validationResponse(true, 201, $lang, $route);*/
            return redirect()->route('client.index')
                ->with('success', lang('messages.created', lang('client.client')));
        } catch (\Exception $exception) {
            //\DB::rollBack(); 
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('client.create')
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
        $result = (new Client)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();
        /*$validator = (new FinancialYear)->validateFinancialYear($inputs, $id);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }*/

        try {
            //\DB::beginTransaction();

            if(isset($inputs['logo']) or !empty($inputs['logo']))
            {
                $image_name1 = rand(100000, 999999);
                $fileName1 = '';
                if($file1 = $request->hasFile('logo')) 
                {
                    $file1 = $request->file('logo') ;
                    $img_name1 = $file1->getClientOriginalName();
                    $image_resize1 = Image::make($file1->getRealPath()); 
                    $image_resize1->resize(512, 512);

                    $fileName1 = $image_name1.$img_name1;
                    $image_resize1->save(public_path('/uploads/client_logo/' .$fileName1));;
                }
                $fname1 ='/uploads/client_logo/';
                $icon = $fname1.$fileName1;
            }
            else{
                $icon = $result->logo;
            }

            unset($inputs['logo']);

            $inputs['logo'] = $icon;
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'updated_by' => Auth::id(),
                ];   
            
            (new Client)->store($inputs, $id);
            //\DB::commit();
            /*$route = route('financial-year.index');
            $lang = lang('messages.updated', lang('financial_year.financial_year'));
            return validationResponse(true, 201, $lang, $route);*/
            return redirect()->route('client.index')
                ->with('success', lang('messages.updated', lang('client.client')));

        } catch (\Exception $exception) {
            //\DB::rollBack();
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('client.edit', [$id])
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
        $result = (new Client)->find($id);
        if (!$result) {
            abort(401);
        }
       

       // dd($result);
        return view('admin.client.create', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function clientPaginate(Request $request, $pageNumber = null)
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

            $data = (new Client)->getClient($inputs, $start, $perPage);
            $totalBrand = (new Client)->totalClient($inputs);
            $total = $totalBrand->total;
        } else {

            $data = (new Client)->getClient($inputs, $start, $perPage);
            $totalBrand = (new Client)->totalClient();
            $total = $totalBrand->total;
        }


        return view('admin.client.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function clientToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Client::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('client.client')));
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
    public function clientAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('client.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('client.client'))));
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

        Client::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('client.index')
            ->with('success', lang('messages.updated', lang('client.client')));
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

        $result = (new Client)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Client)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('client.client_in_use')];
             }
             else {
                 (new Client)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('client.client'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Ecatalog;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class EcatalogsController  extends  Controller{
    
    public function  index() {
        
        return view('admin.e_catalog.index');
    }

    public function  create() {
        return view('admin.e_catalog.create');
    }


    public function  store(Request $request) {
        
        $inputs = $request->all();

        try {
              $validator = (new Ecatalog)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            if(isset($inputs['background_image']) or !empty($inputs['background_image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('background_image')) {
                    $file = $request->file('background_image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/background_images/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/background_images/';
                $image = $fname.$fileName;
            }
            else{
                $image = null;
            }
            unset($inputs['background_image']);            
            $inputs['background_image'] = $image;

            if(isset($inputs['catalog_file']) or !empty($inputs['catalog_file'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('catalog_file')) {
                    $file = $request->file('catalog_file') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/catalog_files/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/catalog_files/';
                $catalog_file = $fname.$fileName;
            }
            else{
                $catalog_file = null;
            }
            unset($inputs['catalog_file']);            
            $inputs['catalog_file'] = $catalog_file;


            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                ];   

            //\DB::beginTransaction();
            (new Ecatalog)->store($inputs);
           

            return redirect()->route('e-catalog.index')
                ->with('success', lang('messages.created', lang('e.Catalog')));
        } catch (\Exception $exception) {
            dd($exception);
            return redirect()->route('e-catalog.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    public function update(Request $request, $id = null)
    {
        $result = (new Ecatalog)->find($id);
        if (!$result) {
            abort(401);
        }
       
        $inputs = $request->all();

        try {
            //  $validator = (new Ecatalog)->validate($inputs, $id);
            // if( $validator->fails() ) {
            //     return back()->withErrors($validator)->withInput();
            // }


            if(isset($inputs['background_image']) or !empty($inputs['background_image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('background_image')) {
                    $file = $request->file('background_image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/background_images/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/background_images/';
                $image = $fname.$fileName;
            }
            else{
                $image = $result->background_image;
            }
            unset($inputs['background_image']);            
            $inputs['background_image'] = $image;

            if(isset($inputs['catalog_file']) or !empty($inputs['catalog_file'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('catalog_file')) {
                    $file = $request->file('catalog_file') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/catalog_files/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/catalog_files/';
                $catalog_file = $fname.$fileName;
            }
            else{
                $catalog_file = $result->catalog_file;
            }
            unset($inputs['catalog_file']);            
            $inputs['catalog_file'] = $catalog_file;

            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];  
            
            (new Ecatalog)->store($inputs, $id);
   
            return redirect()->route('e-catalog.index')
                ->with('success', lang('messages.updated', lang('e.Catalog')));

        } catch (\Exception $exception) {
            
            return redirect()->route('e-catalog.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null)
    {
        $result = (new Ecatalog)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.e_catalog.create', compact('result'));
    }


    public function e_catalogsPaginate(Request $request, $pageNumber = null)
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

            $data = (new Ecatalog)->getEcatalog($inputs, $start, $perPage);
            $totalFaq = (new Ecatalog)->totalEcatalog($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new Ecatalog)->getEcatalog($inputs, $start, $perPage);
            $totalFaq = (new Ecatalog)->totalEcatalog();
            $total = $totalFaq->total;
        }

        return view('admin.e_catalog.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function e_catalogsToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Ecatalog::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('E catalog')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

 
    public function e_catalogsAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('tradeshow.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('E Catalog'))));
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

        Ecatalog::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('e-catalog.index')
            ->with('success', lang('messages.updated', lang('E Catalog')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Ecatalog)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Ecatalog)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('E Catalog in use')];
             }
             else {
                 (new Ecatalog)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('E Catalog'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}
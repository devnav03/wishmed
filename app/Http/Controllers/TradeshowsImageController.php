<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TradeshowsImage;
use Illuminate\Http\Request;

class TradeshowsImageController  extends  Controller{
    
    public function  index() {
        
        return view('admin.tradeshow_image.index');
    }

    public function  create()
    {
        return view('admin.tradeshow_image.create');
    }


    public function  store(Request $request)
    {
        
        $inputs = $request->all();

        try {
              $validator = (new TradeshowsImage)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
          

            if(isset($inputs['image']) or !empty($inputs['image']))
            {

                $image_name = rand(100000, 999999);
                $fileName = '';

                if($file = $request->hasFile('image')) 
                {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/tradeshow/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/tradeshow/';
                $image = $fname.$fileName;

            }
            else{
                $image = null;
            }
            
            unset($inputs['image']);
            $inputs['image'] = $image;

            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                ];   

            (new TradeshowsImage)->store($inputs);
           
            return redirect()->route('tradeshow-images.index')
                ->with('success', lang('messages.created', lang('Tradeshow Image')));
        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('tradeshow-images.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    public function update(Request $request, $id = null) {
        $result = (new TradeshowsImage)->find($id);
        if (!$result) {
            abort(401);
        }
        $inputs = $request->all();
        try {
            //  $validator = (new TradeshowsImage)->validate($inputs, $id);
            // if( $validator->fails() ) {
            //     return back()->withErrors($validator)->withInput();
            // }

            if(isset($inputs['image']) or !empty($inputs['image'])){
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('image')) {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/tradeshow/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/tradeshow/';
                $image = $fname.$fileName;

            }
            else{
                $image = $result->image;
            }

            unset($inputs['image']);
            $inputs['image'] = $image;

            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];  

            (new TradeshowsImage)->store($inputs, $id);
            return redirect()->route('tradeshow-images.index')
                ->with('success', lang('messages.updated', lang('Tradeshow Image')));
        } catch (\Exception $exception) {
            return redirect()->route('tradeshow-images.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null)
    {
        $result = (new TradeshowsImage)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.tradeshow_image.create', compact('result'));
    }


    public function tradeshow_imagesPaginate(Request $request, $pageNumber = null)
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

            $data = (new TradeshowsImage)->getTradeshow($inputs, $start, $perPage);
            $totalFaq = (new TradeshowsImage)->totalTradeshow($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new TradeshowsImage)->getTradeshow($inputs, $start, $perPage);
            $totalFaq = (new TradeshowsImage)->totalTradeshow();
            $total = $totalFaq->total;
        }

        return view('admin.tradeshow_image.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function tradeshow_imagesToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = TradeshowsImage::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Tradeshow Image')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

 
    public function tradeshow_imagesAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('tradeshow-images.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Tradeshow Image'))));
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

        TradeshowsImage::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('tradeshow-images.index')
            ->with('success', lang('messages.updated', lang('Tradeshow Image')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new TradeshowsImage)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new TradeshowsImage)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('Tradeshow in use')];
             }
             else {
                 (new TradeshowsImage)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Tradeshow'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}
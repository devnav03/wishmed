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
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends  Controller{
 
    public function  index() {
        return view('admin.supplier.index');
    }
 
    public function  create() {        
        return view('admin.supplier.create');
    }

    
    public function  store(Request $request) {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new Supplier)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if(isset($inputs['image']) or !empty($inputs['image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('image')) {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/home_slider/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/home_slider/';
                $image = $fname.$fileName;
            } else{
                $image = null;
            }
            unset($inputs['image']);            
            $inputs['image'] = $image;
        
            (new Supplier)->store($inputs);
           
            return redirect()->route('suppliers.index')
                ->with('success', lang('messages.created', lang('Supplier')));
        } catch (\Exception $exception) {
   
            return redirect()->route('suppliers.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


    public function update(Request $request, $id = null) {
        $result = (new Feedback)->find($id);
        if (!$result) {
            abort(401);
        }
        $inputs = $request->all();
        try {

            if(isset($inputs['image']) or !empty($inputs['image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('image')) {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/home_slider/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/home_slider/';
                $image = $fname.$fileName;
            } else{
                $image = $result->image;
            }
            unset($inputs['image']);            
            $inputs['image'] = $image;
            
            (new Supplier)->store($inputs, $id);
    
            return redirect()->route('suppliers.index')
                ->with('success', lang('messages.updated', lang('Supplier')));

        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('suppliers.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    
    public function edit($id = null) {
        $result = (new Supplier)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.supplier.create', compact('result'));
    }


    public function Paginate(Request $request, $pageNumber = null) {

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

            $data = (new Supplier)->getReviews($inputs, $start, $perPage);
            $totalReviews = (new Supplier)->totalReviews($inputs);
            $total = $totalReviews->total;
        } else {

            $data = (new Supplier)->getReviews($inputs, $start, $perPage);
            $totalReviews = (new Supplier)->totalReviews();
            $total = $totalReviews->total;
        }
         
        return view('admin.supplier.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function Toggle($id = null) {

         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {

            $game = Supplier::find($id);

        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Supplier')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }


    public function Action(Request $request) {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('suppliers.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Supplier'))));
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

        Supplier::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('suppliers.index')
            ->with('success', lang('messages.updated', lang('Supplier')));
    }


    public function drop($id) {

        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Supplier)->find($id);
        if (!$result) {
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Supplier)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('reviews.reviews_in_use')];
             }
             else {
                 (new Supplier)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('reviews.reviews'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }

}

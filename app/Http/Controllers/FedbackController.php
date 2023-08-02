<?php

namespace App\Http\Controllers;
/**
 * :: Feedback Controller ::
 * 
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FedbackController  extends  Controller{
 
    public function index() {
        return view('admin.fedback.index');
    }

    public function create() {
        return view('admin.fedback.create');
    }

   
    public function  store(Request $request) {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new Feedback)->validate($inputs);
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

            // $inputs = $inputs + [
            //         'image' => $icon,
            //     ];  
        
            (new Feedback)->store($inputs);
           
            return redirect()->route('fedbacks.index')
                ->with('success', lang('messages.created', lang('Fedback')));
        } catch (\Exception $exception) {
   
            return redirect()->route('fedbacks.create')
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
            //\DB::beginTransaction();

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
            
            (new Feedback)->store($inputs, $id);
    
            return redirect()->route('fedbacks.index')
                ->with('success', lang('messages.updated', lang('Fedback')));

        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('fedbacks.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null)  {
        $result = (new Feedback)->find($id);
        if (!$result) {
            abort(401);
        }
       
        return view('admin.fedback.create', compact('result'));
    }


 
    public function fedbacksPaginate(Request $request, $pageNumber = null) {
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

            $data = (new Feedback)->getFedback($inputs, $start, $perPage);
            $totalGoal = (new Feedback)->totalFedback($inputs);
            $total = $totalGoal->total;
        } else {

            $data = (new Feedback)->getFedback($inputs, $start, $perPage);
            $totalGoal = (new Feedback)->totalFedback();
            $total = $totalGoal->total;
        }


        return view('admin.fedback.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

   
    public function fedbacksToggle($id = null) {

        $chk_status = Feedback::where('id', $id)->select('status', 'name')->first();
        if($chk_status->status == 1){
            Feedback::where('id', $id)
            ->update([
            'status' => 0,
            ]);

            return redirect()->route('fedbacks.index')
                ->with('success', lang(''.$chk_status->title.' successfully Deactivate'));

        } else {

            Feedback::where('id', $id)
            ->update([
            'status' => 1,
            ]);

          return redirect()->route('fedbacks.index')
                ->with('success', lang(''.$chk_status->title.' successfully Activate'));

        }

        //  if (!\Request::isMethod('post') && !\Request::ajax()) {
        //     return lang('messages.server_error');
        // }

        // try {
        //     $game = Slider::find($id);
        // } catch (\Exception $exception) {
        //     return lang('messages.invalid_id', string_manip(lang('slider.slider')));
        // }

        // $game->update(['status' => !$game->status]);
        // $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // // return json response
        // return json_encode($response);
    }

  
    public function sliderAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('slider.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('slider.slider'))));
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

        Slider::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('slider.index')
            ->with('success', lang('messages.updated', lang('slider.slider')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new Slider)->find($id);
        if (!$result) {
            abort(401);
        }
        try {
             $result = (new Slider)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('slider.slider_in_use')];
             }
             else {
                 (new Slider)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('slider.slider'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        return json_encode($response);
    }
    

}

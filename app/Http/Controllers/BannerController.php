<?php

namespace App\Http\Controllers;
/**
 * :: Banner Controller ::
 * 
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController  extends  Controller{
 
    public function  index()
    {
        return view('admin.banner.index');
    }

   
    public function  create()
    {
        return view('admin.banner.create');
    }

   
    public function  store(Request $request)
    {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new Banner)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if(isset($inputs['image']) or !empty($inputs['image']))
            {
                $image_name1 = rand(100000, 999999);
                $fileName1 = '';
                if($file1 = $request->hasFile('image')) 
                {
                    $file1 = $request->file('image') ;
                    $img_name1 = $file1->getClientOriginalName();
                    $image_resize1 = Image::make($file1->getRealPath()); 
                    $image_resize1->resize(1050, 300);
                    $fileName1 = $image_name1.$img_name1;
                    $image_resize1->save(public_path('/uploads/home_slider/' .$fileName1));
                }
                $fname1 ='/uploads/home_slider/';
                $icon = $fname1.$fileName1;
            }
            else{
                $icon = null;
            }
            unset($inputs['image']);
            $inputs['image'] = $icon;


            if(isset($inputs['web_image']) or !empty($inputs['web_image']))
            {
                $image_name2 = rand(100000, 999999);
                $fileName2 = '';
                if($file2 = $request->hasFile('web_image')) 
                {
                    $file2 = $request->file('web_image') ;
                    $img_name2 = $file2->getClientOriginalName();
                    $image_resize2 = Image::make($file2->getRealPath()); 
                    $image_resize2->resize(1600, 300);
                    $fileName2 = $image_name2.$img_name2;
                    $image_resize2->save(public_path('/uploads/home_slider/' .$fileName2));
                }
                $fname2 ='/uploads/home_slider/';
                $icon2 = $fname2.$fileName2;
            }
            else{
                $icon2 = null;
            }
            unset($inputs['web_image']);
            $inputs['web_image'] = $icon2;
            

            $main_id = $request->type;
            if($main_id == "Product"){
             $category = \DB::table('products')->where('id', $request->slider_id)->select('url')->first();
            }

            if($main_id == "Category"){
             $category = \DB::table('categories')->where('id', $request->slider_id)->select('url')->first();
            }

            if($main_id == "Brand"){
             $category = \DB::table('brands')->where('id', $request->slider_id)->select('url')->first();
            }  

            $inputs['link'] = $category->url;
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'created_by' => Auth::id(),
                    'image' => $icon,
                    'web_image' => $icon2,
                ];  

        
            (new Banner)->store($inputs);
           
            return redirect()->route('banner.index')
                ->with('success', lang('messages.created', lang('banner.banner')));
        } catch (\Exception $exception) {
   
            return redirect()->route('banner.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


    public function update(Request $request, $id = null)
    {
        $result = (new Banner)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {
            //\DB::beginTransaction();

            if(isset($inputs['image']) or !empty($inputs['image']))
            {
                $image_name1 = rand(100000, 999999);
                $fileName1 = '';
                if($file1 = $request->hasFile('image')) 
                {
                    $file1 = $request->file('image') ;
                    $img_name1 = $file1->getClientOriginalName();
                    $image_resize1 = Image::make($file1->getRealPath()); 
                    $image_resize1->resize(1050, 300);
                    $fileName1 = $image_name1.$img_name1;
                    $image_resize1->save(public_path('/uploads/home_slider/' .$fileName1));

                }
                $fname1 ='/uploads/home_slider/';
                $icon = $fname1.$fileName1;
            }
            else{
                $icon = $result->image;
            }
            unset($inputs['image']);
            $inputs['image'] = $icon;


            if(isset($inputs['web_image']) or !empty($inputs['web_image']))
            {
                $image_name2 = rand(100000, 999999);
                $fileName2 = '';
                if($file2 = $request->hasFile('web_image')) 
                {
                    $file2 = $request->file('web_image') ;
                    $img_name2 = $file2->getClientOriginalName();
                    $image_resize2 = Image::make($file2->getRealPath()); 
                    $image_resize2->resize(1600, 300);
                    $fileName2 = $image_name2.$img_name2;
                    $image_resize2->save(public_path('/uploads/home_slider/' .$fileName2));

                }
                $fname2 ='/uploads/home_slider/';
                $icon2 = $fname2.$fileName2;
            }
            else{
                $icon2 = $result->web_image;
            }
            unset($inputs['web_image']);
            $inputs['web_image'] = $icon2;


            $main_id = $request->type;

            if($main_id == "Product"){
             $category = \DB::table('products')->where('id', $request->slider_id)->select('url')->first();
            }

            if($main_id == "Category"){
             $category = \DB::table('categories')->where('id', $request->slider_id)->select('url')->first();
            }

            if($main_id == "Brand"){
             $category = \DB::table('brands')->where('id', $request->slider_id)->select('url')->first();
            }  

            $inputs['link'] = $category->url;
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'updated_by' => Auth::id(),
                ];   
            
            (new Banner)->store($inputs, $id);
    
            return redirect()->route('banner.index')
                ->with('success', lang('messages.updated', lang('banner.banner')));

        } catch (\Exception $exception) {
          
            return redirect()->route('banner.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null)
    {
        $result = (new Banner)->find($id);
        if (!$result) {
            abort(401);
        }
       
            if($result->type == "Product"){
             $pages = \DB::table('products')->where('status', 1)->select('name', 'id')->get();
            }

            if($result->type == "Category"){
             $pages = \DB::table('categories')->where('status', 1)->select('name', 'id')->get();
            }

            if($result->type == "Brand"){
             $pages = \DB::table('brands')->where('status', 1)->select('name', 'id')->get();
            }  

       // dd($result);
        return view('admin.banner.create', compact('result', 'pages'));
    }


 
    public function sliderPaginate(Request $request, $pageNumber = null)
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

            $data = (new Banner)->getSlider($inputs, $start, $perPage);
            $totalGoal = (new Banner)->totalSlider($inputs);
            $total = $totalGoal->total;
        } else {

            $data = (new Banner)->getSlider($inputs, $start, $perPage);
            $totalGoal = (new Banner)->totalSlider();
            $total = $totalGoal->total;
        }


        return view('admin.banner.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

   
    public function sliderToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Banner::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('banner.banner')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

  
    public function sliderAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('banner.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('banner.banner'))));
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

        Banner::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('banner.index')
            ->with('success', lang('messages.updated', lang('banner.banner')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Banner)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Banner)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('banner.banner_in_use')];
             }
             else {
                 (new Banner)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('banner.banner'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

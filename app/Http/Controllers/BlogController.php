<?php

namespace App\Http\Controllers;
/**
 * :: Blog Controller ::
 * 
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController  extends  Controller{
 
    public function index() {

        return view('admin.blog.index');
    }

    public function create() {
        $BlogCategory = BlogCategory::where('status', 1)->select('id', 'name')->get();
        return view('admin.blog.create', compact('BlogCategory'));
    }

   
    public function store(Request $request) {
        $inputs = $request->all();
        try {

            $validator = (new Blog)->validate($inputs);
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
                    $destinationPath = public_path().'/uploads/slider_image/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/slider_image/';
                $image = $fname.$fileName;
            } else{
                $image = null;
            }

            unset($inputs['image']);
            $inputs['image'] = $image;


            $url = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $inputs['title'])));
            $inputs['url'] = $url;

            (new Blog)->store($inputs);
           
            return redirect()->route('blogs.index')
                ->with('success', lang('messages.created', 'Blog'));
        } catch (\Exception $exception) {
            return redirect()->route('blogs.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


    public function update(Request $request, $id = null) {

        $result = (new Blog)->find($id);
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
                    $destinationPath = public_path().'/uploads/slider_image/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/slider_image/';
                $image = $fname.$fileName;

            } else {
                $image = $result->image;
            }

            unset($inputs['image']);
            $inputs['image'] = $image;

            
            (new Blog)->store($inputs, $id);
    
            return redirect()->route('blogs.index')
                ->with('success', lang('messages.updated', 'Blog'));

        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('blogs.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null) {
        $result = (new Blog)->find($id);
        if (!$result) {
            abort(401);
        }
        $BlogCategory = BlogCategory::where('status', 1)->select('id', 'name')->get();
        return view('admin.blog.create', compact('result', 'BlogCategory'));
    }


 
    public function blogPaginate(Request $request, $pageNumber = null) {

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

            $data = (new Blog)->getSlider($inputs, $start, $perPage);
            $totalGoal = (new Blog)->totalSlider($inputs);
            $total = $totalGoal->total;
        } else {

            $data = (new Blog)->getSlider($inputs, $start, $perPage);
            $totalGoal = (new Blog)->totalSlider();
            $total = $totalGoal->total;
        }

        return view('admin.blog.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

   
    public function blogToggle($id = null) {

        $chk_status = Blog::where('id', $id)->select('status', 'title')->first();
        if($chk_status->status == 1){
            Blog::where('id', $id)
            ->update([
            'status' => 0,
            ]);

            return redirect()->route('blogs.index')
                ->with('success', lang(''.$chk_status->title.' successfully Deactivate'));

        } else {

            Blog::where('id', $id)
            ->update([
            'status' => 1,
            ]);

          return redirect()->route('blogs.index')
                ->with('success', lang(''.$chk_status->title.' successfully Activate'));

        }

    }

  
    public function blogAction(Request $request) {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('blogs.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Blog'))));
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

        Blog::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('blogs.index')
            ->with('success', lang('messages.updated', lang('Blog')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new Blog)->find($id);
        if (!$result) {
            abort(401);
        }
        try {
             $result = (new Blog)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('slider.slider_in_use')];
             }
             else {
                 (new Blog)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('slider.slider'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        return json_encode($response);
    }
    

}

<?php

namespace App\Http\Controllers;
/**
 * :: BlogCategory Controller ::
 * To manage games.
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogCategory;
use ZipArchive;
use Illuminate\Http\Request;

class BlogCategoryController  extends  Controller{
 
    public function index() {
        return view('admin.blog_category.index');
    }
  
    public function create() {
        return view('admin.blog_category.create');
    }


    public function store(Request $request) {
        $inputs = $request->all();
        try {
            $validator = (new BlogCategory)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            $slug_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $inputs['name'])));
            $inputs['url'] = $slug_name; 

            (new BlogCategory)->store($inputs);
           
            return redirect()->route('blog-category.index')
                ->with('success', lang('messages.created', lang('category.category')));
        } catch (\Exception $exception) {
            return redirect()->route('blog-category.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }

  
    public function update(Request $request, $id = null) {

        $result = (new BlogCategory)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();
        try {

            (new BlogCategory)->store($inputs, $id);
           
            return redirect()->route('blog-category.index')
                ->with('success', lang('messages.updated', lang('category.category')));

        } catch (\Exception $exception) {
            return redirect()->route('blog-category.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

  
    public function edit($id = null) {

        $result = (new BlogCategory)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.blog_category.create', compact('result'));
    }


    public function categoryPaginate(Request $request, $pageNumber = null)
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
            $data = (new BlogCategory)->getCategory($inputs, $start, $perPage);
            $totalGameMaster = (new BlogCategory)->totalCategory($inputs);
            $total = $totalGameMaster->total;
        } else {
            $data = (new BlogCategory)->getCategory($inputs, $start, $perPage);
            $totalGameMaster = (new BlogCategory)->totalCategory();
            $total = $totalGameMaster->total;
        }


        return view('admin.blog_category.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

 
    public function categoryToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = BlogCategory::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('category.category')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

  
    public function categoryAction(Request $request) {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('blog-category.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('category.category'))));
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

        BlogCategory::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('blog-category.index')
            ->with('success', lang('messages.updated', lang('game_master.game')));
    }


    public function drop($id) {
        
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new BlogCategory)->find($id);
        if (!$result) {
            abort(401);
        }

        try {

            $result = (new BlogCategory)->find($id);
            (new BlogCategory)->tempDelete($id);

            $response = ['status' => 1, 'message' => lang('messages.deleted', lang('category.category'))];

        } catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }     

        return json_encode($response);
    }

}

<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends  Controller{
    
    public function index() {
        return view('admin.media.index');
    }

    public function  create(){
                
        return view('admin.media.create');
    }



    public function mediaPaginate(Request $request, $pageNumber = null)
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

            $data = (new Media)->getMedia($inputs, $start, $perPage);
            $totalHsnCode = (new Media)->totalMedia($inputs);
            $total = $totalHsnCode->total;
        } else {

            $data = (new Media)->getMedia($inputs, $start, $perPage);
            $totalHsnCode = (new Media)->totalMedia();
            $total = $totalHsnCode->total;
        }
         

        return view('admin.media.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function case_dealToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = CaseDeal::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Case Deal')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

 
    public function case_dealAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('case-deal.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Case Deal'))));
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

        CaseDeal::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('case-deal.index')
            ->with('success', lang('messages.updated', lang('Case Deal')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new CaseDeal)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new CaseDeal)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('Case Deal in use')];
             }
             else {
                 (new CaseDeal)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Case Deal'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

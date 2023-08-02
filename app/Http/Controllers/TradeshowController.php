<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tradeshow;
use Illuminate\Http\Request;

class TradeshowController  extends  Controller{
    
    public function  index() {
        
        return view('admin.tradeshow.index');
    }

    public function  create()
    {
        return view('admin.tradeshow.create');
    }


    public function  store(Request $request)
    {
        
        $inputs = $request->all();

        try {
              $validator = (new Tradeshow)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
          

            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                ];   

            //\DB::beginTransaction();
            (new Tradeshow)->store($inputs);
           

            return redirect()->route('tradeshow.index')
                ->with('success', lang('messages.created', lang('Tradeshow')));
        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('tradeshow.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    public function update(Request $request, $id = null)
    {
        $result = (new Tradeshow)->find($id);
        if (!$result) {
            abort(401);
        }
       
        $inputs = $request->all();

        try {
             $validator = (new Tradeshow)->validate($inputs, $id);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if($request->hotel_booking){
            } else {
                $inputs['hotel_booking'] = 0;
            }
            if($request->logistics){
            } else {
                $inputs['logistics'] = 0;
            }


            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];  
            
            (new Tradeshow)->store($inputs, $id);
   
            return redirect()->route('tradeshow.index')
                ->with('success', lang('messages.updated', lang('Tradeshow')));

        } catch (\Exception $exception) {
            
            return redirect()->route('tradeshow.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null)
    {
        $result = (new Tradeshow)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.tradeshow.create', compact('result'));
    }


    public function tradeshowPaginate(Request $request, $pageNumber = null)
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

            $data = (new Tradeshow)->getTradeshow($inputs, $start, $perPage);
            $totalFaq = (new Tradeshow)->totalTradeshow($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new Tradeshow)->getTradeshow($inputs, $start, $perPage);
            $totalFaq = (new Tradeshow)->totalTradeshow();
            $total = $totalFaq->total;
        }

        return view('admin.tradeshow.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function tradeshowToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Tradeshow::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Tradeshow')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

 
    public function tradeshowAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('tradeshow.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Tradeshow'))));
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

        Tradeshow::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('tradeshow.index')
            ->with('success', lang('messages.updated', lang('Tradeshow')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Tradeshow)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Tradeshow)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('Tradeshow in use')];
             }
             else {
                 (new Tradeshow)->tempDelete($id);
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
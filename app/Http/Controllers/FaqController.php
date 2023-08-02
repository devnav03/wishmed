<?php

namespace App\Http\Controllers;
/**
 * :: Blogs Controller ::
 * To manage lecture.
 *
 **/
use Auth;
use App\Models\Faqs;
use Illuminate\Http\Request;

class FaqController  extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {
        
        return view('admin.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
        return view('admin.faq.create');
    }



    /**
     * Store a newly created resource in storage.
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function  store(Request $request)
    {
        
        $inputs = $request->all();

        try {
              $validator = (new Faqs)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            

            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                ];   
            //\DB::beginTransaction();
            (new Faqs)->store($inputs);
           

            return redirect()->route('faq.index')
                ->with('success', lang('messages.created', lang('faq.faq')));
        } catch (\Exception $exception) {
     
         // dd($exception);
            //\DB::rollBack(); 
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('faq.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    /**
     * Updating the record
     * @param null $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id = null)
    {
        $result = (new Faqs)->find($id);
        if (!$result) {
            abort(401);
        }
       
        $inputs = $request->all();
      

        try {
             $validator = (new Faqs)->validate($inputs, $id);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }


            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];    
            
            (new Faqs)->store($inputs, $id);

            return redirect()->route('faq.index')
                ->with('success', lang('messages.updated', lang('faq.faq')));

        } catch (\Exception $exception) {
            
           
            return redirect()->route('faq.edit', [$id])
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
        $result = (new Faqs)->find($id);
        if (!$result) {
            abort(401);
        }

        return view('admin.faq.create', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function faqPaginate(Request $request, $pageNumber = null)
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

            $data = (new Faqs)->getFaq($inputs, $start, $perPage);
            $totalFaq = (new Faqs)->totalFaq($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new Faqs)->getFaq($inputs, $start, $perPage);
            $totalFaq = (new Faqs)->totalFaq();
            $total = $totalFaq->total;
        }

        return view('admin.faq.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function faqToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Faqs::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('faq.faq')));
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
    public function faqAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('faq.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('faq.faq'))));
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

        Faqs::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('faq.index')
            ->with('success', lang('messages.updated', lang('faq.faq')));
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

        $result = (new Faqs)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Faqs)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('faq.faq_in_use')];
             }
             else {
                 (new Faqs)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('faq.faq'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}
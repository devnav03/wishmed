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
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {
        return view('admin.reviews.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
              
        return view('admin.reviews.create');
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        $result = (new Review)->find($id);
        if (!$result) {
            abort(401);
        }

       // dd($result);
        return view('admin.review.create', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function reviewsPaginate(Request $request, $pageNumber = null)
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

            $data = (new Review)->getReviews($inputs, $start, $perPage);
            $totalReviews = (new Reviews)->totalReviews($inputs);
            $total = $totalReviews->total;
        } else {

            $data = (new Review)->getReviews($inputs, $start, $perPage);
            $totalReviews = (new Review)->totalReviews();
            $total = $totalReviews->total;
        }
         

        return view('admin.reviews.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function reviewsToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Review::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('reviews.review')));
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
    public function reviewsAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('reviews.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('reviews.reviews'))));
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

        Review::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('reviews.index')
            ->with('success', lang('messages.updated', lang('reviews.reviews')));
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

        $result = (new Review)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Review)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('reviews.reviews_in_use')];
             }
             else {
                 (new Review)->tempDelete($id);
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

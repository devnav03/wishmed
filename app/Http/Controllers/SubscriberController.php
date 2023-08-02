<?php

namespace App\Http\Controllers;
/**
 * :: Subscriber Controller ::
 * To manage Subscriber.
 *
 **/
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController  extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index()
    {


        return view('admin.subscriber.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
        return view('admin.subscriber.create');
    }

   

    public function subscriberPaginate(Request $request, $pageNumber = null)
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

            $data = (new Subscriber)->getSubscriber($inputs, $start, $perPage);
            $totalAboutEnergyXpo = (new Subscriber)->totalSubscriber($inputs);
            $total = $totalPrivacyPolicy->total;
        } else {

            $data = (new Subscriber)->getSubscriber($inputs, $start, $perPage);
            //dd($data);
            $totalPreference = (new Subscriber)->totalSubscriber();
            $total = $totalPreference->total;
        }

        return view('admin.subscriber.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function subscriberToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Subscriber::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('subscriber.subscriber')));
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
    public function subscriberAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('subscriber.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('subscriber.subscriber'))));
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

        Subscriber::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('subscriber.index')
            ->with('success', lang('messages.updated', lang('subscriber.subscriber')));
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

        $result = (new Subscriber)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Subscriber)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('subscriber.subscriber_in_use')];
             }
             else {
                 (new Subscriber)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('subscriber.subscriber'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

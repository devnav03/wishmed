<?php

namespace App\Http\Controllers;
/**
 * :: ShippingZone Controller ::
 * 
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController  extends  Controller{
 
    public function index() {

        return view('admin.shipping_zone.index');
    }

   
    public function create() {

        return view('admin.shipping_zone.create');
    }

   
    public function  store(Request $request) {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new ShippingZone)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
        
            (new ShippingZone)->store($inputs);
           
            return redirect()->route('shipping-zone.index')
                ->with('success', lang('messages.created', lang('Shipping zone')));
        } catch (\Exception $exception) {
   
            return redirect()->route('shipping-zone.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


    public function update(Request $request, $id = null) {
        $result = (new ShippingZone)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {


            if(isset($request->flat_rate)){
                $inputs['flat_rate'] = 1;
            } else {
                $inputs['flat_rate'] = 0;
            }

            if(isset($request->delivery)){
                $inputs['delivery'] = 1;
            } else {
                $inputs['delivery'] = 0;
            }

            if(isset($request->local_pickup)){
                $inputs['local_pickup'] = 1;
            } else {
                $inputs['local_pickup'] = 0;
            }
            

     
            (new ShippingZone)->store($inputs, $id);
    
            return redirect()->route('shipping-zone.index')
                ->with('success', lang('messages.updated', lang('Shipping zone')));

        } catch (\Exception $exception) {
           // dd($exception);
            return redirect()->route('shipping-zone.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function edit($id = null) {
        $result = (new ShippingZone)->find($id);
        if (!$result) {
            abort(401);
        }
       
        return view('admin.shipping_zone.create', compact('result'));
    }


 
    public function shipping_zonePaginate(Request $request, $pageNumber = null) {

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

            $data = (new ShippingZone)->getSlider($inputs, $start, $perPage);
            $totalGoal = (new ShippingZone)->totalSlider($inputs);
            $total = $totalGoal->total;
        } else {

            $data = (new ShippingZone)->getSlider($inputs, $start, $perPage);
            $totalGoal = (new ShippingZone)->totalSlider();
            $total = $totalGoal->total;
        }


        return view('admin.shipping_zone.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

   
    public function shipping_zoneToggle($id = null) {

        $chk_status = ShippingZone::where('id', $id)->select('status', 'title')->first();
        if($chk_status->status == 1){
            ShippingZone::where('id', $id)
            ->update([
            'status' => 0,
            ]);

            return redirect()->route('shipping-zone.index')
                ->with('success', lang(''.$chk_status->title.' successfully Deactivate'));

        } else {

            ShippingZone::where('id', $id)
            ->update([
            'status' => 1,
            ]);

          return redirect()->route('shipping-zone.index')
                ->with('success', lang(''.$chk_status->title.' successfully Activate'));
        }
    }

  
    public function shipping_zoneAction(Request $request) {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('shipping-zone.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('shipping-zone.shipping-zone'))));
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

        ShippingZone::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('shipping-zone.index')
            ->with('success', lang('messages.updated', lang('shipping-zone.shipping-zone')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new ShippingZone)->find($id);
        if (!$result) {
            abort(401);
        }
        try {
             $result = (new ShippingZone)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('slider.slider_in_use')];
             }
             else {
                 (new ShippingZone)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('shipping-zone.shipping-zone'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        return json_encode($response);
    }
    

}

<?php

namespace App\Http\Controllers;
/**
 * :: Offer Controller ::
 * 
 *
 **/
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Offer;
use App\Models\OfferType;
use App\Models\Category;
use Ixudra\Curl\Facades\Curl;
use App\Models\Product;
use App\Models\UserDevice;
use App\Models\Notification;
use App\User;
use Illuminate\Http\Request;

class OfferController  extends  Controller{
   
    public function  index(){

        return view('admin.offer.index');
    }

 
    public function  create()
    {
              
        $OfferType = OfferType::where('status', 1)->get();
        $Category = Category::where('status', 1)->get();

        return view('admin.offer.create', compact('OfferType','Category'));
    }

   
    public function  store(Request $request)
    {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new Offer)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $status = 1;
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'created_by' => Auth::id(),
                ];  
            
            $id = (new Offer)->store($inputs);


            // $users_devs = UserDevice::where('status', 1)->get();
            // if($users_devs){
            //     foreach ($users_devs as $users_dev) {
            //         $noti_message = $request->message;
            //         $title = $request->title;
            //         $image = 'https://abc.com'.$image;
            //         $user_dev = $users_dev->device_token;
            //         $this->sendGCM($noti_message, $user_dev, $title, $image);
            //     }
            // } 

            return redirect()->route('offer.index')
                ->with('success', lang('messages.created', lang('offer.offer')));
        } catch (\Exception $exception) {
        
            return redirect()->route('offer.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }

   
    public function update(Request $request, $id = null)
    {
        $result = (new Offer)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {
           

            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];   
            
            (new Offer)->store($inputs, $id);

            
            // $users_devs = UserDevice::where('status', 1)->get();
            // if($users_devs){
            //     foreach ($users_devs as $users_dev) {
            //         $noti_message = $request->message;
            //         $title = $request->title;
            //         $image = 'https://abc.com'.$image;
            //         $user_dev = $users_dev->device_token;
            //         $this->sendGCM($noti_message, $user_dev, $title, $image);
            //     }
            // } 

            return redirect()->route('offer.index')
                ->with('success', lang('messages.updated', lang('offer.offer')));

        } catch (\Exception $exception) {
           
            return redirect()->route('offer.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

   
    public function edit($id = null)
    {
        $result = (new Offer)->find($id);
        if (!$result) {
            abort(401);
        }

        $OfferType = OfferType::where('status', 1)->get();


        $Category = Category::where('status', 1)->get();
        if($result->type_id == 4) {
        $category_id = $result->category_id;
        $cat = Category::where('id', $category_id)->first();
        $category_list = Category::where('status', 1)->get();
        
       
       // dd($result);
        return view('admin.offer.create', compact('result','OfferType','Category','category_list'));

    } else {
        return view('admin.offer.create', compact('result','OfferType','Category'));

    }
    }


  function sendGCM($message, $id, $title, $image) {

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
          
            'notification' => array(
                "body" => $message,
                "title" => $title,
                "image" => $image,
        )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAALR2J_pM:APA91bGk9Us71j8qaH5BRTVzmrlx405BOohlNH78JZvRdHvA1loa_jJADVs0qBAYYuNsTLjQqzLeDQwxjVuiZAPBw_NrqdgmKLyG7vHUdBiWgk1zkP4FKg15R8aJ-jOVF2Rki8rJP3AW",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    $result = curl_exec ( $ch );
   //dd($result);
    curl_close ( $ch );
  }
  
    public function offerPaginate(Request $request, $pageNumber = null)
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

            $data = (new Offer)->getOffer($inputs, $start, $perPage);
            $totalOffer = (new Offer)->totalOffer($inputs);
            $total = $totalOffer->total;
        } else {

            $data = (new Offer)->getOffer($inputs, $start, $perPage);
            $totalOffer = (new Offer)->totalOffer();
            $total = $totalOffer->total;
        }
         

        return view('admin.offer.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

   
    public function offerToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Offer::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('offer.offer')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

  
    public function offerAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('offer.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('offer.offer'))));
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

        Offer::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('offer.index')
            ->with('success', lang('messages.updated', lang('offer.offer')));
    }

   
    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Offer)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Offer)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('offer.offer_in_use')];
             }
             else {
                 (new Offer)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('Offer.offer'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
    

}

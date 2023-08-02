<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Reviews Controller ::
 * To manage lecture.
 *
 **/
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\User;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Order;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ReviewsFrontController extends  Controller{
  
    public function store_review(Request $request)
    {
         // dd('here');

        $request['user_id'] = Auth::id();
        $inputs = $request->all();

        try {
              $validator = (new Review)->validate_front($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $user_order =OrderProduct::where('user_id',  $request['user_id'])->where('product_id', $request['product_id'])->first();


            if($user_order){
             if(isset($inputs['image']) or !empty($inputs['image']))
            {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('image')) 
                {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/review_images/' ;
                    $file->move($destinationPath, $fileName);
                }

                $image = $fileName;
            }
            else{
                $image = null;
            }

            unset($inputs['image']);     

            $inputs = $inputs + [
                    'status'    => 0,
                    'image' => $image,
                    'created_by' => Auth::id(),
                    'user_id' => Auth::id(),
                ];  

            (new Review)->store($inputs);

            $user = User::where('id', $request['user_id'])->first();
            $email = $user['email'];
            $data['email'] = $user['email'];
            $data['name'] = $user['name'];
            $data['review'] = $request['review'];
            $data['rating'] = $request['rating'];
            $product = Product::where('id', $request['product_id'])->first();
            $data['product_name'] = $product['name'];
            $order = Order::where('id', $user_order->order_id)->first();
            $data['order_nr'] = $order['order_nr'];

            \Mail::send('email.review', $data, function($message) use ($email){
                $message->from($email);
                $message->to('navjot@thegirafe.com');
                $message->subject('Uphaar - Product Review');
            }); 


            return redirect()->back()
                ->with('rev_by', lang('messages.created', lang('done')));
            } else {
             
 
           return redirect()->back()
                ->with('never_by', lang('messages.created', lang('You Never Buy this product')));
            }

        } catch (\Exception $exception) {
      
        //dd($exception);
            return redirect()->back()
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

   
}
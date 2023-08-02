<?php

namespace App\Http\Controllers;
/**
 * :: Product Controller ::
 * To manage games.
 *
 **/
use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\CategoryProducts;
use App\Models\ConfigureProduct;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class ProductController  extends  Controller {
  
    public function index() {
        $Categorys = (new Category)->getCategoryService(); 
        if(((\Auth::user()->user_type)) == 1){
            return view('admin.product.index', compact('Categorys'));
        }
    }

    public function  create() {
        $Categorys = Category::where('status', 1)->where('parent_id', null)->get();
        $simple_products = Product::where('product_type', 1)->select('id', 'name', 'sku')->get();
        $simple_ids = [];

        if(((\Auth::user()->user_type)) == 1 || ((\Auth::user()->user_type)) == 7){
          return view('admin.product.create', compact('Categorys', 'simple_products', 'simple_ids'));
        }
    }
    
    public function delete_selected(Request $request){
        foreach($request->id as $id){
          (new Product)->tempDelete($id);
        }
        $response = ['status' => 200, 'message' => 'product deleted'];
        return json_encode($response);    
    }
        
        
    public function category_products($id = null){
       try{
          $category = Category::where('id', $id)->select('name')->first();
          $data = Product::join('categories', 'categories.id' ,'=', 'products.category_id')
                ->leftjoin('categories as c2', 'c2.id' ,'=', 'products.sub_category')
                ->leftjoin('categories as c3', 'c3.id' ,'=', 'products.sub_sub_category')
                ->leftjoin('categories as c4', 'c4.id' ,'=', 'products.four_lavel')
                ->leftjoin('categories as c5', 'c5.id' ,'=', 'products.five_lavel')
                ->leftjoin('categories as c6', 'c6.id' ,'=', 'products.six_lavel')
                ->leftjoin('categories as c7', 'c7.id' ,'=', 'products.seven_lavel')
                ->select('products.id', 'products.name', 'products.sku', 'products.status', 'products.offer_price', 'products.regular_price', 'products.quantity',
            'products.featured_image', 'categories.name as category', 'c2.name as cat2', 'c3.name as cat3', 'c4.name as cat4', 'c5.name as cat5', 'c6.name as cat6', 'c7.name as cat7')->where('products.status', 1)->where('products.category_id', $id)->orwhere('products.sub_category', $id)->orwhere('products.sub_sub_category', $id)->orwhere('products.four_lavel', $id)->orwhere('products.five_lavel', $id)->orwhere('products.six_lavel', $id)->orwhere('products.seven_lavel', $id)->get();

         return view('admin.product.cat_pro', compact('data', 'category'));
          
        } catch (\Exception $exception) {
            //dd($exception);
            return redirect()->route('product.index')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }

 
    public function store(Request $request) {   
     // dd($request);
        $inputs = $request->all();

        try{
            $validator = (new Product)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if(isset($inputs['featured_image']) or !empty($inputs['featured_image'])) {
                $image_name1 = rand(100000, 999999);
                $fileName1 = '';
                if($file1 = $request->hasFile('featured_image')) {
                    $file1 = $request->file('featured_image') ;
                    $img_name1 = $file1->getClientOriginalName();
                    $image_resize1 = Image::make($file1->getRealPath()); 
                    $image_resize1->resize(250, 348);
                    $fileName1 = $image_name1.$img_name1;
                    $image_resize1->save(public_path('/uploads/featured_images/' .$fileName1));                  
                }
                $fname1 ='/uploads/featured_images/';
                $image1 = $fname1.$fileName1;
            }
            else{
                $image1 = null;
            }

            if(isset($inputs['featured_image']) or !empty($inputs['featured_image'])) {
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('featured_image')) {
                    $file = $request->file('featured_image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/featured_images/' ;
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/featured_images/';
                $image = $fname.$fileName;
            } else{
                $image = null;
            }
            
            unset($inputs['featured_image']);
            $inputs['thumbnail'] = $image1;
            
            $inputs['featured_image'] = $image;
            $category_id = 0;
            if(isset($request->category_id[0])){  
            $category_id = $request->category_id[0];
            }
            if(isset($request->category_id[1])){
            $inputs['sub_category'] = $request->category_id[1];
            }
            
            if(isset($request->category_id[2])){
            $inputs['sub_sub_category'] = $request->category_id[2];
            }
            
            if(isset($request->category_id[3])){
            $inputs['four_lavel'] = $request->category_id[3];
            }
            
            if(isset($request->category_id[4])){
            $inputs['five_lavel'] = $request->category_id[4];
            }
            
            if(isset($request->category_id[5])){
            $inputs['six_lavel'] = $request->category_id[5];
            }
            
            if(isset($request->category_id[6])){
            $inputs['seven_lavel'] = $request->category_id[6];
            }
            
            unset($inputs['category_id']);
            
            $inputs['category_id'] = $category_id;

           $slug_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $inputs['name'])));
            
            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                    'featured_image' => $image,
                    'url' =>  $slug_name,
            
                ];  
 
           $product = (new Product)->store($inputs);

            if($request->category_id){
                CategoryProducts::create([
                  'category_id'  =>  $category_id,
                  'product_id'  => $product,
                ]);
            }

            if($request->product_type == 2){
                if(isset($request->simple_id)){
                   foreach ($inputs['simple_id'] as $simple_id) {
                        ConfigureProduct::create([
                          'group_id'  =>  $product,
                          'simple_id'  => $simple_id,
                        ]);
                   }
                }
            }

            

            // if($request->sub_category){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->sub_category,
            //       'product_id'  => $product,
            //     ]);

            // }
            // if($request->sub_sub_category){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->sub_sub_category,
            //       'product_id'  => $product,
            //     ]);

            // }
            // if($request->four_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->four_lavel,
            //       'product_id'  => $product,
            //     ]);

            // }
            // if($request->five_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->five_lavel,
            //       'product_id'  => $product,
            //     ]);

            // }
            // if($request->six_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->six_lavel,
            //       'product_id'  => $product,
            //     ]);

            // }
            // if($request->seven_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->seven_lavel,
            //       'product_id'  => $product,
            //     ]);

            // }
        
                  if(isset($inputs['gallery']) or !empty($inputs['gallery'])) {
                          $image_name1 = rand(100000, 999999);
                          $fileName1 = '';
                              foreach($inputs['gallery'] as $file1){
                                  $img_name1 = $file1->getClientOriginalName();
                                  $image_resize1 = Image::make($file1->getRealPath()); 
                                  $image_resize1->resize(612, 852);
                                  $fileName1 = $image_name1.$img_name1;
                                  $image_resize1->save(public_path('/uploads/product_images/' .$fileName1)); 
                                  ProductImage::create([
                                      'product_image'  =>  '/uploads/product_images/'.$fileName1,
                                      'product_id'    => $product,

                                  ]);
                              }
                      }


            return redirect()->route('product.index')
                ->with('success', lang('messages.created', lang('product.product')));
        
        } catch (\Exception $exception) {
           // dd($exception);
          
            return redirect()->route('product.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }


    public function update(Request $request, $id = null) {
        $result = (new Product)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();
       
        try {
            $validator = (new Product)->validate_update($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            if(isset($inputs['featured_image']) or !empty($inputs['featured_image'])) {

                $image_name1 = rand(100000, 999999);
                $fileName1 = '';

                if($file1 = $request->hasFile('featured_image')) 
                {
                    $file1 = $request->file('featured_image') ;
                    $img_name1 = $file1->getClientOriginalName();
                    $image_resize1 = Image::make($file1->getRealPath()); 
                    $image_resize1->resize(250, 348);

                    $fileName1 = $image_name1.$img_name1;
                    $image_resize1->save(public_path('/uploads/featured_images/' .$fileName1));                      
                }

                $fname1 ='/uploads/featured_images/';
                $image1 = $fname1.$fileName1;
            }
            else{
                $image1 = $result->thumbnail;
            }

            if(isset($inputs['featured_image']) or !empty($inputs['featured_image'])){
                $image_name = rand(100000, 999999);
                $fileName = '';
                if($file = $request->hasFile('featured_image')) {

                    // $file = $request->file('featured_image') ;
                    // $img_name = $file->getClientOriginalName();
                    // $image_resize = Image::make($file->getRealPath()); 
                    // $image_resize->resize(1000, 1000);
                    // $fileName = $image_name.$img_name;
                    // $image_resize->save(public_path('/uploads/featured_images/' .$fileName));

                    $file = $request->file('featured_image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/featured_images/' ;
                    $file->move($destinationPath, $fileName);


                }
                $fname ='/uploads/featured_images/';
                $image = $fname.$fileName;

            }
            else{
                $image = $result->featured_image;
            }
            
            $category_id = $request->category_id[0];
            
            if(isset($request->category_id[1])){
            $inputs['sub_category'] = $request->category_id[1];
            }
            
            if(isset($request->category_id[2])){
            $inputs['sub_sub_category'] = $request->category_id[2];
            }
            
            if(isset($request->category_id[3])){
            $inputs['four_lavel'] = $request->category_id[3];
            }
            
            if(isset($request->category_id[4])){
            $inputs['five_lavel'] = $request->category_id[4];
            }
            
            if(isset($request->category_id[5])){
            $inputs['six_lavel'] = $request->category_id[5];
            }
            
            if(isset($request->category_id[6])){
            $inputs['seven_lavel'] = $request->category_id[6];
            }

            unset($inputs['featured_image']);
            unset($inputs['category_id']);
            
            $inputs['category_id'] = $category_id;
            
            $inputs['thumbnail'] = $image1;

            $inputs['featured_image'] = $image;

          
            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];   
            
            (new Product)->store($inputs, $id);

            \DB::table('configure_products')->where('group_id', $id)->delete();

            if($request->product_type == 2){
                if(isset($request->simple_id)){
                   foreach ($inputs['simple_id'] as $simple_id) {
                        ConfigureProduct::create([
                          'group_id'  =>  $id,
                          'simple_id'  => $simple_id,
                        ]);
                   }
                }
            }
           
            // \DB::table('category_products')->where('product_id', $id)->delete();
            // if($request->category_id){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->category_id,
            //       'product_id'  => $id,
            //     ]);

          //  }
            // if($request->sub_category){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->sub_category,
            //       'product_id'  => $id,
            //     ]);

            // }
            // if($request->sub_sub_category){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->sub_sub_category,
            //       'product_id'  => $id,
            //     ]);

            // }
            // if($request->four_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->four_lavel,
            //       'product_id'  => $id,
            //     ]);

            // }
            // if($request->five_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->five_lavel,
            //       'product_id'  => $id,
            //     ]);

            // }
            // if($request->six_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->six_lavel,
            //       'product_id'  => $id,
            //     ]);

            // }
            // if($request->seven_lavel){
            //     CategoryProducts::create([
            //       'category_id'  =>  $request->seven_lavel,
            //       'product_id'  => $id,
            //     ]);

            // }

            if(isset($inputs['gallery']) or !empty($inputs['gallery'])) {
                $image_name1 = rand(100000, 999999);
                $fileName1 = '';
                    foreach($inputs['gallery'] as $file1){
                        $img_name1 = $file1->getClientOriginalName();

                        $image_resize1 = Image::make($file1->getRealPath()); 
                        $image_resize1->resize(612, 852);

                        $fileName1 = $image_name1.$img_name1;
                        $image_resize1->save(public_path('/uploads/product_images/' .$fileName1));

                        ProductImage::create([
                            'product_image'  =>  '/uploads/product_images/'.$fileName1,
                            'product_id'    => $id,

                        ]);

                    }
            }
        
            return redirect()->route('product.index')
                 ->with('success', lang('messages.updated', lang('product.product'))); 

        } catch (\Exception $exception) {
         // dd($exception);
            return redirect()->route('product.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    

    public function edit($id = null)
    {
        $result = (new Product)->find($id);
        if (!$result) {
            abort(401);
        }
        
        $gallery= \DB::table('product_images')->where('product_id', $id)->get();
        $Categorys = Category::where('status', 1)->where('parent_id', null)->get();
        
        $selected_cat = array($result->category_id, $result->sub_category, $result->category_id, $result->sub_sub_category, $result->four_lavel, $result->five_lavel, $result->six_lavel);

        $simple_products = Product::where('product_type', 1)->select('id', 'name', 'sku')->get();

        $simple_ids = ConfigureProduct::where('group_id', $id)->pluck('simple_id')->toArray();

        return view('admin.product.create', compact('result', 'gallery','Categorys', 'selected_cat', 'simple_products', 'simple_ids'));
    }



  function delRelated($id)
    {
        \DB::table('related_products')->where('id', $id)->delete();
      return back();

    }
    public function productPaginate(Request $request, $pageNumber = null)
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

            $data = (new Product)->getProduct($inputs, $start, $perPage);
            $totalproduct = (new Product)->totalProduct($inputs);
            $total = $totalproduct->total;
        } else {

            $data = (new Product)->getProduct($inputs, $start, $perPage);
            $totalProduct = (new Product)->totalProduct();
            $total = $totalProduct->total;
        }

        return view('admin.product.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


     public function pending_stockPaginate(Request $request, $id, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $warehouse_id = $id;

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

            $data = (new ProductQuantitie)->getProduct($inputs, $start, $perPage, $id);
            $totalproduct = (new ProductQuantitie)->totalProduct($inputs, $id);
            $total = $totalproduct->total;
        } else {

            $data = (new ProductQuantitie)->getProduct($inputs, $start, $perPage, $id);
            $totalProduct = (new ProductQuantitie)->totalProduct('', $id, $warehouse_id);
            $total = $totalProduct->total;
        }

        return view('admin.quantity.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }



    public function productToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = Product::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('product.product')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    public function productToggle_variant($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = ProductLot::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('product.product')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    } 
  
    public function productAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('product.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('product.product'))));
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

        Product::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('product.index')
            ->with('success', lang('messages.updated', lang('product.product')));
    }

  
    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Product)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Product)->find($id);
             
            (new Product)->tempDelete($id);
            $response = ['status' => 1, 'message' => lang('messages.deleted', lang('product.product'))];
            
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }


    function delGallery($id){
        \DB::table('product_images')->where('id', $id)->delete();
      return back();

    }


public function export_products(){

        try{

            $products = \DB::table('products')
            ->join('categories', 'categories.id', '=','products.category_id') 
            ->leftjoin('categories as c2', 'c2.id', '=','products.sub_category')
            ->leftjoin('categories as c3', 'c3.id', '=','products.sub_sub_category')
            ->leftjoin('categories as c4', 'c4.id', '=','products.four_lavel')
            ->leftjoin('categories as c5', 'c4.id', '=','products.five_lavel')
            ->select('products.name', 'products.url', 'products.sku', 'products.offer_price', 'products.regular_price', 'products.srp', 'products.featured_image', 'products.id',
            'products.description', 'categories.name as category', 'c2.name as cat_2', 'c3.name as cat_3', 'c4.name as cat_4', 'c5.name as cat_5', 
            'products.product_description', 'products.status')
            ->get();
             
           // dd($products);


            \Excel::create('products', function($excel) use($products) {
            $excel->sheet('product', function($sheet) use($products) {
                $url = route('home');
                
                $excelData = [];
                $excelData[] = [
                'Name',
                'SKU',
                'Slug',
                'Product Id',
                'Category',
                'Offer Price',
                'Regular Price',
                'SRP',
                'Short Description',
                'Detail Description',
                'Image',
                'Status',

                ];
                foreach ($products as $key => $value) {
                $category = $value->category . $value->cat_2 . $value->cat_3 . $value->cat_4 . $value->cat_5;  
                    
                $excelData[] = [
                $value->name,
                $value->sku,
                $value->url,
                $value->id,
                $category,
                $value->offer_price,
                $value->regular_price,
                $value->srp,
                $value->description,
                $value->product_description,
                $url.$value->featured_image,
                $value->status,

                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }


    }



  function live_product_1(Request $request)
    {

       // dd($request);

     if($request->ajax())
     {
      $output = '';
      $query = $request->get('query');
      if($query != '')
      {

       // $check = ProductLot::pluck('code')->toArray();
        $data = \DB::table('products')
          ->where('name', 'like', '%'.$query.'%')
          // ->whereNotIn('code', $check)
          ->select('name', 'id', 'sku')
          ->orderBy('id', 'desc')
          ->get();
      }
    
     
       foreach($data as $row)
       {
        $output .= '
        <li><input value="'.$row->id.'" name="code_value" class="code_check" onChange="getProduct_Code_1(this.value);" type="radio"> <span>'.$row->name.' ('.$row->sku.')</span></li>
        ';
       }
 
    
      $data = array(
       'table_data'  => $output,
     
      );

      echo json_encode($data);
     }
    }

    public function check_product_code(Request $request){

        $code = Product::where('id', $request->code)->where('status', 1)->select('id', 'name', 'sku')->first();

        if($code){
            $data['product_name'] = $code->name.' ('.$code->name.')';
            $data['product_id'] = $code->id;

            return $data;
        }
        else{
            $ab['status'] = 'Fail';
            return $ab;
        }
       
    }





}

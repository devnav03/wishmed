<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::any('v1/user-reg', ['as' => 'user-reg','uses' => 'API\V1\UserController@signup_user']);
// Route::any('v1/login', ['as' => 'login','uses' => 'API\V1\UserController@login_user']);
// Route::any('v1/sliders', ['as' => 'sliders','uses' => 'API\V1\ProductController@sliders']);
// Route::any('v1/login-with-google', ['as' => 'login-with-google','uses' => 'API\V1\UserController@login_with_google']);
// Route::any('v1/category-grps', ['as' => 'category-grps','uses' => 'API\V1\ProductController@categoryData']);
// Route::any('v1/category-subgrps', ['as' => 'category-subgrps','uses' => 'API\V1\ProductController@subcategoryData']);
// Route::any('v1/addToCart', ['as' => 'addToCart','uses' => 'API\V1\CartController@addToCart']);
// Route::any('v1/delete-to-cart', ['as' => 'delete-to-cart','uses' => 'API\V1\CartController@deleteToCart']);
// Route::any('v1/carts', ['as' => 'carts','uses' => 'API\V1\CartController@myCart']);
// Route::any('v1/cart', ['as' => 'cart','uses' => 'API\V1\CartController@myCartCount']);
// Route::any('v1/product-by-category', ['as' => 'product-by-category','uses' => 'API\V1\ProductController@product_by_category']);
// Route::any('v1/product-details', ['as' => 'product-details','uses' => 'API\V1\ProductController@productDetail']);
// Route::any('v1/e-catalogs', ['as' => 'e-catalogs','uses' => 'API\V1\ProductController@e_catalogs']);
// Route::any('v1/tradeshows', ['as' => 'tradeshows','uses' => 'API\V1\ProductController@tradeshows']);
// Route::any('v1/tradeshows-images', ['as' => 'tradeshows-images','uses' => 'API\V1\ProductController@tradeshows_images']);
// Route::any('v1/my-wishlist', ['as' => 'my-wishlist','uses' => 'API\V1\ProductController@my_wishlist']);
// Route::any('v1/add-to-wishlist', ['as' => 'add-to-wishlist','uses' => 'API\V1\ProductController@addToWishlist']);
// Route::any('v1/delete-to-wishlist', ['as' => 'delete-to-wishlist','uses' => 'API\V1\ProductController@deleteToWishlist']);
// Route::any('v1/my-orders', ['as' => 'my-orders','uses' => 'API\V1\OrderController@myOrders']);
// Route::any('v1/order-products', ['as' => 'order-products','uses' => 'API\V1\OrderController@order_products']);
// Route::any('v1/order-detail', ['as' => 'order-detail','uses' => 'API\V1\OrderController@orderDetail']);
// Route::any('v1/home-products', ['as' => 'home-products','uses' => 'API\V1\ProductController@home_products']);
// Route::any('v1/create-user-address', ['as' => 'create-user-address','uses' => 'API\V1\UserAddressController@createUserAddress']);
// Route::any('v1/my-address', ['as' => 'my-address','uses' => 'API\V1\UserAddressController@myAddress']);
// Route::any('v1/update-user-address', ['as' => 'update-user-address','uses' => 'API\V1\UserAddressController@updateUserAddress']);
// Route::any('v1/delete-address', ['as' => 'delete-address','uses' => 'API\V1\UserAddressController@deleteUserAddress']);
// Route::any('v1/country', ['as' => 'country','uses' => 'API\V1\UserAddressController@country']);
// Route::any('v1/forgot-password', ['as' => 'forgot-password','uses' => 'API\V1\UserController@forgot_password']);
// Route::any('v1/forgot-password-otp', ['as' => 'forgot-password-otp','uses' => 'API\V1\UserController@forgot_password_otp']);
// Route::any('v1/force-update', ['as' => 'force-update','uses' => 'API\V1\UserController@force_update']);
// Route::any('v1/user-device', ['as' => 'user-device','uses' => 'API\V1\UserController@user_device']);
// Route::any('v1/profile', ['as' => 'profile','uses' => 'API\V1\UserController@profile']);
// Route::any('v1/update-profile', ['as' => 'update-profile','uses' => 'API\V1\UserController@updateProfile']);
// Route::any('v1/update-profile-image', ['as' => 'update-profile-image','uses' => 'API\V1\UserController@updateUserProfileImage']);
// Route::any('v1/logout', ['as' => 'logout','uses' => 'API\V1\UserController@logout']);  
// Route::any('v1/update-password', ['as' => 'update-password','uses' => 'API\V1\UserController@changePassword']);
// Route::any('v1/checkout', ['as' => 'checkout','uses' => 'API\V1\OrderController@checkout']);
// Route::any('v1/share-product', ['as' => 'share-product','uses' => 'API\V1\ProductController@share_product']);
// Route::any('v1/notifications', ['as' => 'notifications','uses' => 'API\V1\OrderController@notifications']);
// Route::any('v1/order', ['as' => 'order','uses' => 'API\V1\OrderController@payWallet']);
// Route::any('v1/order-status', ['as' => 'order-status','uses' => 'API\V1\OrderController@order_status']);
// Route::any('v1/search', ['as' => 'search','uses' => 'API\V1\ProductController@searchWords']);
// Route::any('v1/save-search-history', ['as' => 'save-search-history','uses' => 'API\V1\ProductController@save_search_history']);
// Route::any('v1/search-history', ['as' => 'search-history','uses' => 'API\V1\ProductController@search_history']);
// Route::any('v1/case-deal', ['as' => 'case-deal','uses' => 'API\V1\ProductController@case_deal']);
// Route::any('v1/case-deal-products', ['as' => 'case-deal-products','uses' => 'API\V1\ProductController@case_deal_products']);
// Route::any('v1/contact-save', ['as' => 'contact-save','uses' => 'API\V1\UserController@contact_save']);

// Route::any('v1/lab-supply-cat', ['as' => 'lab-supply-cat','uses' => 'API\V1\UserController@lab_supply_cat']);
// Route::any('v1/lab-equipment-cat', ['as' => 'lab-equipment-cat','uses' => 'API\V1\UserController@lab_equipment_cat']);
// Route::any('v1/medical-supply-cat', ['as' => 'medical-supply-cat','uses' => 'API\V1\UserController@medical_supply_cat']);

// Route::any('v1/dental-supply-cat', ['as' => 'dental-supply-cat','uses' => 'API\V1\UserController@dental_supply_cat']);
// Route::any('v1/zones', ['as' => 'zones','uses' => 'API\V1\UserAddressController@zones']);
// Route::any('v1/zone-city', ['as' => 'zone-city','uses' => 'API\V1\UserAddressController@ZoneCity']);











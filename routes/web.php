<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin/wishmed/admin-login', 'Auth\AuthController@getLogin')->name('admin');
Route::post('/admin/login', 'Auth\AuthController@postLogin');
Route::get('/admin/logout', array('as' => 'admin-logout','uses' => 'Auth\AuthController@adminLogout'));

Route::group(['middleware' => 'auth', 'after' => 'no-cache'], function () {

    Route::prefix('admin')->group(function () {

        Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
        Route::any('finance-report', 'DashboardController@financeReport')->name('finance-report');
        Route::any('out-of-stock', 'DashboardController@outOfstock')->name('out-of-stock');
        Route::any('max-sale-product-wise', 'DashboardController@maxSaleProduct')->name('max-sale-product-wise');  
        Route::any('max-sale-category-wise', 'DashboardController@maxSaleCategory')->name('max-sale-category-wise'); 
        Route::any('max-sale-customer-wise', 'DashboardController@maxSaleCustomer')->name('max-sale-customer-wise'); 

        Route::any('gst-calculation', 'DashboardController@gst_calculation')->name('gst-calculation'); 
               // Customer route start
            Route::resource('customer','CustomerController', [
                'names' => [
                    // 'index'     => 'customer.index',
                    'create'    => 'customer.create',
                    'store'     => 'customer.store',
                    'edit'      => 'customer.edit',
                    'update'    => 'customer.update',
                ],
                'except' => ['show','destroy']
            ]);
            Route::get('customer', 'CustomerController@index')->name('customer');

            Route::any('customer/paginate/{page?}', ['as' => 'customer.paginate',
                'uses' => 'CustomerController@customerPaginate']);
            Route::any('customer/paginate-data-entry/{page?}', ['as' => 'customer.paginate-data-entry',
                'uses' => 'CustomerController@customerPaginate_data_entry']);
            Route::any('customer/action', ['as' => 'customer.action',
                'uses' => 'CustomerController@customerAction']);
            Route::any('customer/toggle/{id?}', ['as' => 'customer.toggle',
                'uses' => 'CustomerController@customerToggle']);
            Route::any('customer/drop/{id?}', ['as' => 'customer.drop',
                'uses' => 'CustomerController@drop']);
            Route::any('customer/data-entry', 'CustomerController@customerdataentry')->name('customer.data-entry');
            Route::any('customer/action-data-entry', ['as' => 'customer-data-entry.action',
                'uses' => 'CustomerController@customerAction_data_entry']);
            Route::any('customer/export-users', 'CustomerController@export_users')->name('customer.export-users');
            Route::any('export-category', 'CustomerController@export_category')->name('export-category');
            Route::any('admin-users', 'CustomerController@admin_users')->name('admin_users');
            Route::any('export-order', 'CustomerController@export_order')->name('export-order');
            Route::any('customer-record', 'CustomerController@customerRecord')->name('customer-record');
            // Customer route end   
            Route::any('customer/upload-customer', 'CustomerController@upload_customer')->name('upload-customer');
            Route::any('customer/import', 'CustomerController@ImportCustomer')->name('customer.import');
            Route::any('customer/products/{user_id}', 'CustomerController@ImportProducts')->name('customer.products');
            Route::any('customer/update-products', 'CustomerController@updateProducts')->name('customer.update-products');

            // Change Password Routes
            Route::any('myaccount', ['as' => 'setting.manage-account',
                'uses' => 'SettingController@myAccount']);
            // Change Password Routes


            // Form route start
            Route::resource('form','FormController', [
                'names' => [
                    'index'     => 'form.index',
                    'create'    => 'form.create',
                    'store'     => 'form.store',
                    'edit'      => 'form.edit',
                    'update'    => 'form.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('form/paginate/{page?}', ['as' => 'form.paginate',
                'uses' => 'FormController@Paginate']);
            Route::any('form/action', ['as' => 'form.action',
                'uses' => 'FormController@Action']);
            Route::any('form/toggle/{id?}', ['as' => 'form.toggle',
                'uses' => 'FormController@Toggle']);
            Route::any('form/drop/{id?}', ['as' => 'form.drop',
                'uses' => 'FormController@drop']);
            // Form route end
            

            // Blog Category route start
            Route::resource('blog-category','BlogCategoryController', [
                'names' => [
                    'index'     => 'blog-category.index',
                    'create'    => 'blog-category.create',
                    'store'     => 'blog-category.store',
                    'edit'      => 'blog-category.edit',
                    'update'    => 'blog-category.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('blog-category/paginate/{page?}', ['as' => 'blog-category.paginate',
                'uses' => 'BlogCategoryController@categoryPaginate']);
            Route::any('blog-category/action', ['as' => 'blog-category.action',
                'uses' => 'BlogCategoryController@categoryAction']);
            Route::any('blog-category/toggle/{id?}', ['as' => 'blog-category.toggle',
                'uses' => 'BlogCategoryController@categoryToggle']);
            Route::any('blog-category/drop/{id?}', ['as' => 'blog-category.drop',
                'uses' => 'BlogCategoryController@drop']);
            // Blog Category route end

           // Category route start
            Route::resource('category','CategoryController', [
                'names' => [
                    'index'     => 'category.index',
                    'create'    => 'category.create',
                    'store'     => 'category.store',
                    'edit'      => 'category.edit',
                    'update'    => 'category.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('category/paginate/{page?}', ['as' => 'category.paginate',
                'uses' => 'CategoryController@categoryPaginate']);
            Route::any('category/action', ['as' => 'category.action',
                'uses' => 'CategoryController@categoryAction']);
            Route::any('category/toggle/{id?}', ['as' => 'category.toggle',
                'uses' => 'CategoryController@categoryToggle']);
            Route::any('category/drop/{id?}', ['as' => 'category.drop',
                'uses' => 'CategoryController@drop']);
            // Category route end
            
            Route::any('category/upload-category', 'CustomerController@upload_category')->name('upload-category');
            Route::any('category/import', 'CustomerController@ImportCategory')->name('category.import'); 


            // Faq route start
            Route::resource('faq','FaqController', [
                'names' => [
                    'index'     => 'faq.index',
                    'create'    => 'faq.create',
                    'store'     => 'faq.store',
                    'edit'      => 'faq.edit',
                    'update'    => 'faq.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('faq/paginate/{page?}', ['as' => 'faq.paginate',
                'uses' => 'FaqController@faqPaginate']);
            Route::any('faq/action', ['as' => 'faq.action',
                'uses' => 'FaqController@faqAction']);
            Route::any('faq/toggle/{id?}', ['as' => 'faq.toggle',
                'uses' => 'FaqController@faqToggle']);
            Route::any('faq/drop/{id?}', ['as' => 'faq.drop',
                'uses' => 'FaqController@drop']);
            // Faq route end


            // Content Management route start
            Route::resource('content-management','ContentManagementController', [
                'names' => [
                    'index'     => 'content-management',
                    'edit'      => 'content-management.edit',
                    'update'    => 'content-management.update',
                ],
                'except' => ['show','destroy']
            ]);
            // Content Management route end

            // Login Logs route start
            Route::resource('login-logs','LoginLogController', [
                'names' => [
                    'index'     => 'login-logs.index',
                    'create'    => 'login-logs.create',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('login-logs/paginate/{page?}', ['as' => 'login-logs.paginate',
                'uses' => 'LoginLogController@login_logsPaginate']);
            Route::any('login-logs/action', ['as' => 'login-logs.action',
                'uses' => 'LoginLogController@login_logsAction']);
            // Login Logs route end    


            // Email Setting route start
            Route::resource('email-settings','EmailSettingController', [
                'names' => [
                    'index'     => 'email-settings',
                    'edit'      => 'email-settings.edit',
                    'update'    => 'email-settings.update',
                ],
                'except' => ['show','destroy']
            ]);
            Route::any('email-settings-otp', 'EmailSettingController@email_settings_otp')->name('email-settings-otp');
            Route::any('email-settings-otp-submit', 'EmailSettingController@email_settings_otp_enter')->name('email-settings.otp');
            // Email Setting route end


            // Tax Setting route start
            Route::resource('tax-amounts','TaxAmountController', [
                'names' => [
                    'index'     => 'tax-amounts',
                    'edit'      => 'tax-amounts.edit',
                    'update'    => 'tax-amounts.update',
                ],
                'except' => ['show','destroy']
            ]);
            // Tax Setting route end


            // CaseDeal route start
            Route::resource('case-deal','CaseDealController', [
                'names' => [
                    'index'     => 'case-deal.index',
                    'create'    => 'case-deal.create',
                    'store'     => 'case-deal.store',
                    'edit'      => 'case-deal.edit',
                    'update'    => 'case-deal.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('case-deal/paginate/{page?}', ['as' => 'case-deal.paginate',
                'uses' => 'CaseDealController@case_dealPaginate']);
            Route::any('case-deal/action', ['as' => 'case-deal.action',
                'uses' => 'CaseDealController@case_dealAction']);
            Route::any('case-deal/toggle/{id?}', ['as' => 'case-deal.toggle',
                'uses' => 'CaseDealController@case_dealToggle']);
            Route::any('case-deal/drop/{id?}', ['as' => 'case-deal.drop',
                'uses' => 'CaseDealController@drop']);
            Route::get('live_product_1', 'ProductController@live_product_1')->name('live_product_1');
            Route::get('check_product_code', 'ProductController@check_product_code')->name('check_product_code');
            // CaseDeal route end            


            // Contact route start
            Route::resource('contact-enquiry','ContactController', [
                'names' => [
                    'index'     => 'contact-enquiry.index',
                    'create'    => 'contact-enquiry.create',
                    'store'     => 'contact-enquiry.store',
                    'edit'      => 'contact-enquiry.edit',
                    'update'    => 'contact-enquiry.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('contact-enquiry/paginate/{page?}', ['as' => 'contact-enquiry.paginate',
                'uses' => 'ContactController@ContactPaginate']);
            Route::any('contact-enquiry/action', ['as' => 'contact-enquiry.action',
                'uses' => 'ContactController@ContactAction']);
            Route::any('contact-enquiry/toggle/{id?}', ['as' => 'contact-enquiry.toggle',
                'uses' => 'ContactController@ContactToggle']);
            Route::any('contact-enquiry/drop/{id?}', ['as' => 'contact-enquiry.drop',
                'uses' => 'ContactController@drop']);

            Route::any('export-enquiry', 'CustomerController@export_enquiry')->name('export-enquiry');
            // Contact route end


            // Tradeshow route start
            Route::resource('tradeshow','TradeshowController', [
                'names' => [
                    'index'     => 'tradeshow.index',
                    'create'    => 'tradeshow.create',
                    'store'     => 'tradeshow.store',
                    'edit'      => 'tradeshow.edit',
                    'update'    => 'tradeshow.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('tradeshow/paginate/{page?}', ['as' => 'tradeshow.paginate',
                'uses' => 'TradeshowController@tradeshowPaginate']);
            Route::any('tradeshow/action', ['as' => 'tradeshow.action',
                'uses' => 'TradeshowController@tradeshowAction']);
            Route::any('tradeshow/toggle/{id?}', ['as' => 'tradeshow.toggle',
                'uses' => 'TradeshowController@tradeshowToggle']);
            Route::any('tradeshow/drop/{id?}', ['as' => 'tradeshow.drop',
                'uses' => 'TradeshowController@drop']);
            // Tradeshow route end

            // Tradeshow Images route start
            Route::resource('tradeshow-images','TradeshowsImageController', [
                'names' => [
                    'index'     => 'tradeshow-images.index',
                    'create'    => 'tradeshow-images.create',
                    'store'     => 'tradeshow-images.store',
                    'edit'      => 'tradeshow-images.edit',
                    'update'    => 'tradeshow-images.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('tradeshow-images/paginate/{page?}', ['as' => 'tradeshow-images.paginate',
                'uses' => 'TradeshowsImageController@tradeshow_imagesPaginate']);
            Route::any('tradeshow-images/action', ['as' => 'tradeshow-images.action',
                'uses' => 'TradeshowsImageController@tradeshow_imagesAction']);
            Route::any('tradeshow-images/toggle/{id?}', ['as' => 'tradeshow-images.toggle',
                'uses' => 'TradeshowsImageController@tradeshow_imagesToggle']);
            Route::any('tradeshow-images/drop/{id?}', ['as' => 'tradeshow-images.drop',
                'uses' => 'TradeshowsImageController@drop']);
            // Tradeshow route end


            // E- Catalogs route start
            Route::resource('e-catalog','EcatalogsController', [
                'names' => [
                    'index'     => 'e-catalog.index',
                    'create'    => 'e-catalog.create',
                    'store'     => 'e-catalog.store',
                    'edit'      => 'e-catalog.edit',
                    'update'    => 'e-catalog.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('e-catalog/paginate/{page?}', ['as' => 'e-catalog.paginate',
                'uses' => 'EcatalogsController@e_catalogsPaginate']);
            Route::any('e-catalog/action', ['as' => 'e-catalog.action',
                'uses' => 'EcatalogsController@e_catalogsAction']);
            Route::any('e-catalog/toggle/{id?}', ['as' => 'e-catalog.toggle',
                'uses' => 'EcatalogsController@e_catalogsToggle']);
            Route::any('e-catalog/drop/{id?}', ['as' => 'e-catalog.drop',
                'uses' => 'EcatalogsController@drop']);
            // E- Catalogs route end   


            // Slider route start
            Route::resource('slider','SliderController', [
                'names' => [
                    'index'     => 'slider.index',
                    'create'    => 'slider.create',
                    'store'     => 'slider.store',
                    'edit'      => 'slider.edit',
                    'update'    => 'slider.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('slider/paginate/{page?}', ['as' => 'slider.paginate',
                'uses' => 'SliderController@sliderPaginate']);
            Route::any('slider/action', ['as' => 'slider.action',
                'uses' => 'SliderController@sliderAction']);
            // Route::any('slider/toggle/{id?}', ['as' => 'slider.toggle',
            //     'uses' => 'SliderController@sliderToggle']);
            Route::any('slider/drop/{id?}', ['as' => 'slider.drop',
                'uses' => 'SliderController@drop']);

            // Route::any('slider/change-status/{id?}', ['as' => 'slider.drop',
            //     'uses' => 'SliderController@drop'])->name('sliderToggle');

            Route::any('slider/change-status/{id?}', 'SliderController@sliderToggle')->name('sliderToggle');

            // Slider route end




           //Blog Start

            Route::resource('blogs', 'BlogController', [
            'names' => [
            'index'  => 'blogs.index',
            'create' => 'blogs.create',
            'store'  => 'blogs.store',
            'edit'   =>  'blogs.edit',
            'update' => 'blogs.update',
            ],
            'except' => ['show', 'destroy']
            ]);
            Route::any('blogs/paginate/{page?}', ['as' => 'blogs.paginate',
            'uses' => 'BlogController@blogPaginate']);
            Route::any('blogs/toggle/{id?}', ['as' => 'blogs.toggle',
            'uses' => 'BlogController@blogToggle']);
            Route::any('blogs/drop/{id?}', ['as' => 'blogs.drop',
            'uses' => 'BlogController@drop']);
            Route::any('blogs/action', ['as' => 'blogs.action',
                'uses' => 'BlogController@blogAction']);

            //Record Comment Store
            Route::post('blog-comment-store', 'Frontend\BlogCommentController@store')->name('blog_comment_store');
            Route::resource('blog_comment','BlogCommentController', [
            'names' => [
            'index' => 'blog_comment.index',
            'create' => 'blog_comment.create',
            'store' => 'blog_commentes.store',
            'edit' => 'blog_comment.edit',
            'update' => 'blog_comment.update',
            ],
            'except' => ['show','destroy']
            ]);

            Route::any('blog_comment/paginate/{page?}', ['as' => 'blog_comment.paginate',
            'uses' => 'BlogCommentController@blogCommentPaginate']);
            Route::any('blog_comment/toggle/{id?}', ['as' => 'blog_comment.toggle',
            'uses' => 'BlogCommentController@blogCommentToggle']);
            Route::any('blog_comment/drop/{id?}', ['as' => 'blog_comment.drop',
            'uses' => 'BlogCommentController@drop']);

            // return-policy Code route start
            Route::resource('return-policy','ReturnPolicyController', [
                'names' => [
                    'index'     => 'return-policy.index',
                    'create'    => 'return-policy.create',
                    'store'     => 'return-policy.store',
                    'edit'      => 'return-policy.edit',
                    'update'    => 'return-policy.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('return-policy/paginate/{page?}', ['as' => 'return-policy.paginate',
                'uses' => 'ReturnPolicyController@return_policyPaginate']);
            Route::any('return-policy/action', ['as' => 'return-policy.action',
                'uses' => 'ReturnPolicyController@return_policyAction']);
            Route::any('return-policy/toggle/{id?}', ['as' => 'hsn_code.toggle',
                'uses' => 'ReturnPolicyController@return_policyToggle']);
            Route::any('return-policy/drop/{id?}', ['as' => 'return-policy.drop',
                'uses' => 'ReturnPolicyController@drop']);
            // return-policy Code route end


            // instruction_videos Code route start
            Route::resource('instruction-videos','InstructionVideosController', [
                'names' => [
                    'index'     => 'instruction-videos.index',
                    'create'    => 'instruction-videos.create',
                    'store'     => 'instruction-videos.store',
                    'edit'      => 'instruction-videos.edit',
                    'update'    => 'instruction-videos.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('instruction-videos/paginate/{page?}', ['as' => 'instruction-videos.paginate',
                'uses' => 'InstructionVideosController@instruction_videosPaginate']);
            Route::any('instruction-videos/action', ['as' => 'instruction-videos.action',
                'uses' => 'InstructionVideosController@instruction_videosAction']);
            Route::any('instruction-videos/toggle/{id?}', ['as' => 'instruction-videos.toggle',
                'uses' => 'InstructionVideosController@instruction_videosToggle']);
            Route::any('instruction-videos/drop/{id?}', ['as' => 'instruction-videos.drop',
                'uses' => 'InstructionVideosController@drop']);
            // instruction_videos Code route end

            
            // Offer Type route start
            Route::resource('offer-type','OfferTypeController', [
                'names' => [
                    'index'     => 'offer-type.index',
                    'create'    => 'offer-type.create',
                    'store'     => 'offer-type.store',
                    'edit'      => 'offer-type.edit',
                    'update'    => 'offer-type.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('offer-type/paginate/{page?}', ['as' => 'offer-type.paginate',
                'uses' => 'OfferTypeController@offer_typePaginate']);
            Route::any('offer-type/action', ['as' => 'offer-type.action',
                'uses' => 'OfferTypeController@offer_typeAction']);
            Route::any('offer-type/toggle/{id?}', ['as' => 'offer-type.toggle',
                'uses' => 'OfferTypeController@offer_typeToggle']);
            Route::any('offer-type/drop/{id?}', ['as' => 'offer-type.drop',
                'uses' => 'OfferTypeController@drop']);
            // Offer Type route end

            // Offer route start
            Route::resource('offer','OfferController', [
                'names' => [
                    'index'     => 'offer.index',
                    'create'    => 'offer.create',
                    'store'     => 'offer.store',
                    'edit'      => 'offer.edit',
                    'update'    => 'offer.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('offer/paginate/{page?}', ['as' => 'offer.paginate',
                'uses' => 'OfferController@offerPaginate']);
            Route::any('offer/action', ['as' => 'offer.action',
                'uses' => 'OfferController@offerAction']);
            Route::any('offer/toggle/{id?}', ['as' => 'offer.toggle',
                'uses' => 'OfferController@offerToggle']);
            Route::any('offer/drop/{id?}', ['as' => 'offer.drop',
                'uses' => 'OfferController@drop']);
            // Offer route end


            // ShippingZone route start
            Route::resource('shipping-zone','ShippingZoneController', [
                'names' => [
                    'index'     => 'shipping-zone.index',
                    'create'    => 'shipping-zone.create',
                    'store'     => 'shipping-zone.store',
                    'edit'      => 'shipping-zone.edit',
                    'update'    => 'shipping-zone.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('shipping-zone/paginate/{page?}', ['as' => 'shipping-zone.paginate',
                'uses' => 'ShippingZoneController@shipping_zonePaginate']);
            Route::any('shipping-zone/action', ['as' => 'shipping-zone.action',
                'uses' => 'ShippingZoneController@shipping_zoneAction']);
            Route::any('shipping-zone/toggle/{id?}', ['as' => 'shipping-zone.toggle',
                'uses' => 'ShippingZoneController@shipping_zoneToggle']);
            Route::any('shipping-zone/drop/{id?}', ['as' => 'shipping-zone.drop',
                'uses' => 'ShippingZoneController@drop']);
            // ShippingZone route end


            // Feedback route start
            Route::resource('fedbacks','FedbackController', [
                'names' => [
                    'index'     => 'fedbacks.index',
                    'create'    => 'fedbacks.create',
                    'store'     => 'fedbacks.store',
                    'edit'      => 'fedbacks.edit',
                    'update'    => 'fedbacks.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('fedbacks/paginate/{page?}', ['as' => 'fedbacks.paginate',
                'uses' => 'FedbackController@fedbacksPaginate']);
            Route::any('fedbacks/action', ['as' => 'fedbacks.action',
                'uses' => 'FedbackController@fedbacksAction']);
            Route::any('fedbacks/toggle/{id?}', ['as' => 'fedbacks.toggle',
                'uses' => 'FedbackController@fedbacksToggle']);
            Route::any('fedbacks/drop/{id?}', ['as' => 'fedbacks.drop',
                'uses' => 'FedbackController@drop']);
            // Feedback route end
            

            // Product route start
            Route::resource('product','ProductController', [
                'names' => [
                    'index'     => 'product.index',
                    'create'    => 'product.create',
                    'store'     => 'product.store',
                    'edit'      => 'product.edit',
                    'update'    => 'product.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('product/paginate/{page?}', ['as' => 'product.paginate',
                'uses' => 'ProductController@productPaginate']);
            Route::any('product/action', ['as' => 'product.action',
                'uses' => 'ProductController@productAction']);
            Route::any('product/toggle/{id?}', ['as' => 'product.toggle',
                'uses' => 'ProductController@productToggle']);
            Route::any('product/drop/{id?}', ['as' => 'product.drop',
                'uses' => 'ProductController@drop']);
            Route::any('product/toggle-variant/{id?}', ['as' => 'product.toggle_variant',
                'uses' => 'ProductController@productToggle_variant']);
          
            Route::get('gallery/delete/{id}', 'ProductController@delGallery')->name('gallery.delete');
            Route::any('export-products', 'ProductController@export_products')->name('export-products');
            Route::any('products/{id}', 'ProductController@category_products')->name('category-products');


            Route::get('product/product-configure/{product_id}', 'ProductController@productConfigure')->name('product.product-configure');

            Route::get('product/product-stack/{product_id}', 'ProductController@productStack')->name('product.product-stack');
            Route::any('product/related', 'ProductController@related')->name('product.related');
            Route::get('related/delete/{id}', 'ProductController@delRelated')->name('related.delete');
            Route::get('live_product_1', 'ProductController@live_product_1')->name('live_product_1');
            Route::any('change-gallery-image/{id}', 'ProductController@change_gallery_image')->name('change-gallery-image');
            Route::any('product/save-gallery-image', 'ProductController@save_gallery_image')->name('save-gallery-image');
            
            // Route::get('product', 'ProductController@index')->name('product');
            // Product route end
            Route::any('product/upload-product', 'CustomerController@upload_product')->name('upload-product');
            Route::any('product/import', 'CustomerController@ImportProduct')->name('product.import');
            
            Route::any('product/delete-selected', 'ProductController@delete_selected')->name('delete-selected');

            Route::any('product/featured-images', 'CategoryController@up_imgs')->name('featured-images');
            Route::any('product/save-featured-images', 'CategoryController@up_img')->name('save-featured-images');
            
 
           // Order route start
            Route::resource('order','OrderController', [
                'names' => [
                    'index'     => 'order.index',
                    'create'    => 'order.create',
                    'store'     => 'order.store',
                    'edit'      => 'order.edit',
                    'update'    => 'order.update',
                ],
                'except' => ['show','destroy']
            ]);

            Route::any('order/paginate/{page?}', ['as' => 'order.paginate',
                'uses' => 'OrderController@orderPaginate']);
            Route::any('order/action', ['as' => 'order.action',
                'uses' => 'OrderController@orderAction']);
            Route::any('order/toggle/{id?}', ['as' => 'order.toggle',
                'uses' => 'OrderController@orderToggle']);
            Route::any('order/drop/{id?}', ['as' => 'order.drop',
                'uses' => 'OrderController@drop']);
            Route::any('order/order-status/{id?}', 'OrderController@orderStatus')->name('order-status');
            Route::any('order/record', 'OrderController@orderRecord')->name('order-record');

            Route::any('order/order-product-status', 'OrderController@orderProductStatus')->name('order-product-status');
            
            Route::any('order/upload-order', 'CustomerController@upload_order')->name('upload-order');
            Route::any('order/import', 'CustomerController@ImportOrder')->name('order.import');

            Route::any('order/order-update', 'OrderController@orderUpdate')->name('order.order-update');
            // Order route end



Route::get('getSubcategory', 'CategoryController@getSubcategory')->name('getSubcategory');
Route::any('abondon-cart', 'DashboardController@abondon_cart')->name('abondon-cart');

Route::get('getPage', 'CategoryController@getPage')->name('getPage');

// Reporting

Route::get("/reporting", function(){
   return View::make("admin.reporting");
});


   });
});



// Cron Job
//Route::get('cart-delete', 'OrderController@delCart')->name('cart-delete');


// *******************    Frontend  **************************

Route::get('/', 'Frontend\HomeController@index')->name('home');
Route::get('shop', 'Frontend\HomeController@shop')->name('shop');
Route::any('save-cart', 'Frontend\HomeController@save_cart')->name('save-cart');

//Route::any('save-user', 'Frontend\HomeController@save_user')->name('save-user');
Route::get('contact-us', 'Frontend\HomeController@contact')->name('contact');
Route::get('product/{url}', 'Frontend\ProductController@productDetails')->name('productDetail');
Route::get('category/{url}', 'Frontend\CategoryController@categoryDetails')->name('categoryDetail');
//Route::any('top-trending-products', 'Frontend\CategoryController@top_trending_products')->name('top-trending-products');
//Route::get('tradeshows', 'Frontend\HomeController@tradeshows')->name('tradeshows');
//Route::get('e-catalogs', 'Frontend\HomeController@e_catalogs')->name('e-catalogs');
//Route::get('closeouts', 'Frontend\HomeController@closeouts')->name('closeouts');
Route::get('about-us', 'Frontend\HomeController@AboutUs')->name('about-us');
Route::post('contact-enquiry', 'Frontend\HomeController@contactEnquiry')->name('contact-enquiry');

Route::get('addToCart', 'Frontend\CartController@addToCart')->name('addToCart');
Route::get('cart','Frontend\CartController@cartDetail')->name('cartDetail');
Route::get('cart/delete/{id}', 'Frontend\CartController@deleteCart')->name('deleteCart');
Route::get('addQuantityCart', 'Frontend\CartController@addQuantityCart')->name('addQuantityCart');
Route::get('removeQuantityCart', 'Frontend\CartController@removeQuantityCart')->name('removeQuantityCart');

Route::get('refund-and-return', 'Frontend\HomeController@refund_return')->name('refund-and-return');
//Route::get('privacy-policy', 'Frontend\HomeController@privacy_policy')->name('privacy-policy');
Route::get('terms-and-conditions', 'Frontend\HomeController@terms_and_conditions')->name('terms-and-conditions');
//Route::any('forms', 'Frontend\HomeController@forms_page')->name('forms');
//Route::any('instruction-videos-and-presentations', 'Frontend\HomeController@instruction_videos')->name('instruction-videos');
//Route::any('order-form', 'Frontend\HomeController@order_form')->name('order-form');
//Route::any('independent-sales-rep-agreement', 'Frontend\HomeController@independent_sales')->name('independent-sales-rep-agreement');
//Route::any('credit-card-authorization-form', 'Frontend\HomeController@credit_card_authorization')->name('credit-card-authorization-form');
//Route::any('credit-terms-application-form', 'Frontend\HomeController@credit_terms_application')->name('credit-terms-application-form');
//Route::any('credit-reference-form', 'Frontend\HomeController@credit_reference_form')->name('credit-reference-form');
//Route::any('n30-agreement', 'Frontend\HomeController@n30_agreement')->name('n30-agreement');
//Route::any('personal-guarantee-letter', 'Frontend\HomeController@personal_guarantee_letter')->name('personal-guarantee-letter');

//Route::get("/sign-up", function(){
  // return View::make("frontend.pages.signup");
//});
//Route::get("/log-in", function(){
//   return View::make("frontend.pages.signin");
//});

Route::get('log-in', 'Frontend\ProfileController@log_in')->name('log-in');

//Forgot Password
Route::get('forgot-password', 'Frontend\ProfileController@forgotPassword')->name('forgot.password');
Route::post('forgot-password/check-email', 'Frontend\ProfileController@checkEmail')->name('forgot_password.check_email');

//Route::get('update-password/{id}', 'Frontend\ProfileController@updatePassword')->name('update_password');
//Route::post('change-password-new', 'Frontend\ProfileController@newPassword')->name('change_password-new'); 
//Route::post('/change-password-forgot', 'Frontend\ProfileController@changePasswordForgot')->name('change_password_forgot');
//Route::post('/change_password', 'Frontend\ProfileController@changePasswordStoreForgot')->name('change_password.store');

//Forgot Password End

Route::get('getOtp', 'Auth\AuthController@getOtpNo')->name('getOtp');
Route::any('login', 'Frontend\HomeController@postLogin')->name('login');
Route::any('logout', 'Auth\LoginController@logout')->name('logout');

//Route::get('oauth/{provider}', 'Auth\LoginController@redirectToProvider')->name('auth.social.redirect');
//Route::get('oauth/{provider}/callback', 'Auth\LoginController@handleprovidercallback')->name('auth.social.callback');

// // Registration Routes...
//Route::post('register', 'Frontend\UserController@store')->name('register');
//Route::get('/logout', array('as' => 'user-logout','uses' => 'Auth\AuthController@userLogout'));
//Route::get('/email-verify/{id}', 'Frontend\UserController@emailVerify')->name('emailverify');

Route::get('blogs/{url}', 'Frontend\HomeController@blogs_details')->name('blogs-details');

Route::get('getSortByCategory', 'Frontend\CategoryController@getSortByCategory')->name('getSortByCategory');
Route::get('getSort', 'Frontend\CategoryController@getSort')->name('getSort');
Route::get('getSort1', 'Frontend\CategoryController@getSort1')->name('getSort1');

Route::get('getCategory', 'Frontend\CategoryController@getCategory')->name('getCategory');
Route::get('/price_filter', 'Frontend\HomeController@priceFilter')->name('price_filter');
//Route::get('/available_pincode', 'Frontend\ProductController@availablePincode')->name('available_pincode');
//Route::get('getbyAvailability', 'Frontend\CategoryController@getbyAvailability')->name('getbyAvailability');
//Route::get('payment-method','Frontend\CartController@paymentMethod')->name('payment-method');
//Route::post('product-enquiry', 'Frontend\HomeController@productEnquiry')->name('product-enquiry');

// //Forgot Password
// Route::get('forgot-password', 'Frontend\ProfileController@forgotPassword')->name('forgot.password');
// Route::post('forgot-password/check-email', 'Frontend\ProfileController@checkEmail')->name('forgot_password.check_email');
// Route::get('update-password/{id}', 'Frontend\ProfileController@updatePassword')->name('update_password');
// Route::post('change-password-new', 'Frontend\ProfileController@newPassword')->name('change_password-new'); 
// Route::post('/change-password-forgot', 'Frontend\ProfileController@changePasswordForgot')->name('change_password_forgot');
// Route::post('/change_password', 'Frontend\ProfileController@changePasswordStoreForgot')->name('change_password.store');
// //Forgot Password End

Route::any('subscribe', 'Frontend\HomeController@subscriberStore')->name('subscribe.store');


// // search
Route::get('search', 'Frontend\HomeController@searchProduct')->name('search_product');
Route::get('autocomplete', 'Frontend\HomeController@searchAuto')->name('autocomplete');
// // Buy Now
Route::get('buy-now/{id}', 'Frontend\CartController@buyNow')->name('buy-now');
Route::get('live_search', 'Frontend\HomeController@action1')->name('live_search');
Route::group(['middleware' => 'user-auth', 'after' => 'no-cache'], function () {

Route::get('my-orders', 'Frontend\OrderController@index')->name('my-orders');
Route::get('order-detail/{id}', 'Frontend\OrderController@orderDetail')->name('order-detail');
Route::post('checkout-form', 'Frontend\CheckoutController@checkoutSubmit')->name('checkoutnew');

Route::any('shipping-calculation', 'Frontend\CheckoutController@shipping_calculation')->name('shipping-calculation');

Route::any('place-order', 'Frontend\HomeController@place_order')->name('place-order');
Route::any('save-cart-and-place-order', 'Frontend\HomeController@save_cart_and_place_order')->name('save-cart-and-place-order');


//Route::any('out-for-delivery', 'Frontend\ProfileController@out_for_delivery')->name('out-for-delivery'); 
//Route::any('enter-delivery-otp/{id}', 'Frontend\ProfileController@enter_delivery_otp')->name('enter-delivery-otp'); 
//Route::any('enter-return-otp/{id}', 'Frontend\ProfileController@enter_return_otp')->name('enter-return-otp'); 
Route::get('add_pincode', 'Frontend\HomeController@checkPincodeAddress')->name('add_pincode');
Route::any('delivery-address', 'Frontend\ProfileController@deliveryAddress')->name('delivery-address');
Route::get('wishlist/{id}', 'Frontend\ProfileController@addWishlist')->name('addWishlist');
Route::get('wishlist', 'Frontend\ProfileController@Wishlist')->name('wishlist');
Route::get('wishlist/delete/{id}', 'Frontend\ProfileController@deleteWishlist')->name('deleteWishlist');
Route::get('apply-offer/{id}', 'Frontend\OfferController@getOfferID')->name('apply-offer');
Route::post('coupon-code', 'Frontend\OfferController@couponCode')->name('coupon-code');
Route::get('checkout', 'Frontend\CheckoutController@CheckoutView')->name('checkout');
Route::get('placeorder/{id}', 'Frontend\CheckoutController@CheckoutAddress')->name('checkout-address');
Route::get('manage-address', 'Frontend\ProfileController@userAddress')->name('manage-address');
Route::get('/change-password', 'Frontend\ProfileController@changePassword')->name('change-password');
Route::post('/change-password', 'Frontend\ProfileController@changePasswordStore')->name('change-password.store');

// Route::get('return-products', 'Frontend\ProfileController@returnProducts')->name('return-products');
Route::get('my-profile', 'Frontend\ProfileController@Profile')->name('my-profile');
Route::get('my-profile/edit', 'Frontend\ProfileController@editProfile')->name('edit.my-profile');
Route::post('update-profile', 'Frontend\ProfileController@updateProfile')->name('update-profile');
// Route::get('return-accept/{id}', 'Frontend\ProfileController@returnAccept')->name('return-accept');
// Route::get('return-request-detail/{id}', 'Frontend\ProfileController@returnRequestDetail')->name('return-request-detail');
Route::get('user-address', 'Frontend\ProfileController@userAddress')->name('user-address');
Route::post('save-address/update','Frontend\ProfileController@updateAddress')->name('save-address.update');

Route::post('save-billing-address/update','Frontend\ProfileController@updateBillingAddress')->name('save-billing-address.update');
Route::get('user-address/edit/{id}','Frontend\ProfileController@editAddress')->name('user-address.edit');
Route::get('checkout-billing/edit/{id}','Frontend\ProfileController@billingAddress')->name('checkout-billing.edit');
Route::get('default-address/{id}', 'Frontend\OrderController@default_address')->name('default_address');

Route::get('address/delete/{id}', 'Frontend\ProfileController@delAddress')->name('delAddress');
Route::get('address/delete-billing/{id}', 'Frontend\ProfileController@delBilAddress')->name('delBilAddress');

Route::get('getCity', 'Frontend\ProfileController@getCity')->name('getCity');
Route::post('save-addresses','Frontend\ProfileController@saveAddress')->name('save-addresses');
Route::post('save-billing-addresses','Frontend\ProfileController@saveBillingAddress')->name('save-billing-addresses');
Route::post('order-gernate','Frontend\CheckoutController@OrderGernate')->name('order-gernate');

// Route::post('pay-wallet','Frontend\CheckoutController@payWallet')->name('pay-wallet');

Route::get('order-invoice/{id}', 'Frontend\OrderController@orderInvoice')->name('order-invoice');

Route::any('cancel-order/{id}', 'Frontend\OrderController@cancel_order')->name('cancel-order');
Route::any('cancel-order-submit', 'Frontend\OrderController@cancel_order_submit')->name('cancel-order-submit');
//Review Start
Route::post('product-review', 'Frontend\ReviewsFrontController@store_review')->name('product-review');
//Review End

});


Route::get('reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
});








<nav class="main-menu">
    @if(((\Auth::user()->user_type)) == 6)
    @else
    <ul>
        <li>
            <a href="{!! route('dashboard') !!}">
                <i class="fa fa-home nav_icon"></i><span class="nav-text">Dashboard</span>
            </a>
        </li>
        @if(((\Auth::user()->user_type)) == 3)

       <!--  <li class="has-subnav"> 
            <a href="javascript:;"> <i class="fa fa-gift"></i>  <span class="nav-text"> Offer </span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>  </a>
            <ul>
            <li><a class="subnav-text" href="{!! route('offer.create') !!}">Add Offer</a> </li>
            <li> <a class="subnav-text" href="{!! route('offer.index') !!}"> Offer List </a> </li>
            </ul>
        </li> -->

        <li class="has-subnav"> 
            <a href="{!! route('order.index') !!}"><i class="fa fa-globe" aria-hidden="true"></i><span class="nav-text">Orders</span></a>
        </li>
             

        @endif

        @if(((\Auth::user()->user_type)) == 1)
        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span class="nav-text"> Customers </span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li><a class="subnav-text" href="{!! route('customer.create') !!}">Add Customer</a></li>
                <li><a class="subnav-text" href="{!! route('customer') !!}">Customers</a></li>
                <li><a class="subnav-text" href="{!! route('admin_users') !!}">Admin Users</a></li>
            </ul>
        </li>
        <li class="has-subnav">
            <a href="{!! route('email-settings') !!}"><i class="fa fa-envelope" aria-hidden="true"></i><span class="nav-text">Email Settings</span></a>
        </li>
        @endif
        
  
        @if(((\Auth::user()->user_type)) == 1)
        <li class="has-subnav"> 
            <a href="javascript:;">
            <img src="{{ url('/') }}/images/product_ic.png" class="side_icon">
            <span class="nav-text"> Products </span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li><a class="subnav-text" href="{!! route('category.create') !!}"> Add Category </a> </li>
                <li><a class="subnav-text" href="{!! route('category.index') !!}"> Category List </a> </li>
                <li><a class="subnav-text" href="{!! route('product.create') !!}"> Add Product </a> </li>
                <li><a class="subnav-text" href="{!! route('product.index') !!}"> Product List </a></li>
            </ul>
        </li>
        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-picture-o" aria-hidden="true"></i>
            <span class="nav-text">Sliders</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li><a class="subnav-text" href="{!! route('slider.create') !!}">Add Slider</a></li>
                <li><a class="subnav-text" href="{!! route('slider.index') !!}">Slider List</a></li>
            </ul>
        </li>
       <!--  <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-tag"></i>
            <span class="nav-text">Case Deal</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li> <a class="subnav-text" href="{!! route('case-deal.create') !!}">Add Case Deal</a></li>
                <li><a class="subnav-text" href="{!! route('case-deal.index') !!}">Case Deal List</a></li>
            </ul>
        </li>
        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-map-marker"></i>
            <span class="nav-text">Tradeshow</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li> <a class="subnav-text" href="{!! route('tradeshow.create') !!}">Add Tradeshow</a></li>
                <li><a class="subnav-text" href="{!! route('tradeshow.index') !!}">Tradeshow List</a></li>
                <li> <a class="subnav-text" href="{!! route('tradeshow-images.create') !!}">Add Tradeshow Image</a></li>
                <li><a class="subnav-text" href="{!! route('tradeshow-images.index') !!}">Tradeshow Images List</a></li>
            </ul>
        </li>
        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-file"></i>
            <span class="nav-text">e.Catalog</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li> <a class="subnav-text" href="{!! route('e-catalog.create') !!}">Add e.Catalog</a></li>
                <li><a class="subnav-text" href="{!! route('e-catalog.index') !!}">e.Catalog List</a></li>
            </ul>
        </li>
       <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-file"></i>
            <span class="nav-text">Forms</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li> <a class="subnav-text" href="{!! route('form.create') !!}">Add Form</a></li>
                <li><a class="subnav-text" href="{!! route('form.index') !!}">Forms List</a></li>
            </ul>
        </li>
        .

        <li class="has-subnav"> 
            <a href="javascript:;"> <i class="fa fa-gift"></i>  <span class="nav-text"> Offer </span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>  </a>
            <ul>
            <li><a class="subnav-text" href="{!! route('offer.create') !!}">Add Offer</a> </li>
            <li> <a class="subnav-text" href="{!! route('offer.index') !!}"> Offer List </a> </li>
            </ul>
        </li> -->
        <li class="has-subnav"> 
            <a href="{!! route('order.index') !!}"><i class="fa fa-globe" aria-hidden="true"></i><span class="nav-text">Orders</span></a>
        </li>

        <li class="has-subnav"> 
            <a href="{!! route('shipping-zone.index') !!}"><i class="fa fa-truck" aria-hidden="true"></i><span class="nav-text">Shipping Options</span></a>
        </li>

        <li class="has-subnav"> 
            <a href="{!! route('tax-amounts') !!}"><i class="fa fa-pen" aria-hidden="true"></i><span class="nav-text">Tax Settings</span></a>
        </li>

        <li class="has-subnav"> 
            <a href="{!! route('content-management') !!}"><i class="fa fa-pen" aria-hidden="true"></i><span class="nav-text">CMS</span></a>
        </li>
        
        <!-- <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-video"></i>
            <span class="nav-text">Instruction Videos</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li> <a class="subnav-text" href="{!! route('instruction-videos.create') !!}">Add Instruction Videos & Presentations</a></li>
                <li><a class="subnav-text" href="{!! route('instruction-videos.index') !!}">Instruction Videos & Presentations List</a></li>
            </ul>
        </li> -->
        <li class="has-subnav"> 
        <a href="{{ route('contact-enquiry.index') }}"><i class="fa fa-comment"></i><span class="nav-text">Enquiry </span></a></li>
        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-pen"></i>
            <span class="nav-text">FAQs</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li><a class="subnav-text" href="{!! route('faq.create') !!}">Add FAQs</a></li>
                <li><a class="subnav-text" href="{!! route('faq.index') !!}">FAQs List</a></li>
            </ul>
        </li>

        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-pen"></i>
            <span class="nav-text">Blog</span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <li><a class="subnav-text" href="{!! route('blogs.create') !!}">Add Blog</a></li>
                <li><a class="subnav-text" href="{!! route('blogs.index') !!}">Blogs List</a></li>
                <li><a class="subnav-text" href="{!! route('blog-category.create') !!}">Add Category</a></li>
                <li><a class="subnav-text" href="{!! route('blog-category.index') !!}">Category List</a></li>
            </ul>
        </li>

        <li class="has-subnav"> 
            <a href="{!! route('suppliers.index') !!}"><i class="fa fa-pen" aria-hidden="true"></i><span class="nav-text">Suppliers</span></a>
        </li>
        
        <li class="has-subnav">
            <a href="{!! route('fedbacks.index') !!}"><i class="fa fa-star" aria-hidden="true"></i><span class="nav-text">Customer's Feedbacks</span></a>
        </li>

        <li class="has-subnav"> 
            <a href="javascript:;">
            <i class="fa fa-star"></i>
            <span class="nav-text">
              Reporting   
            </span>
            <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
            </a>
            <ul>
                <!--<li> <a class="subnav-text" href="/admin/reporting">Reporting</a></li> -->
                <li><a class="subnav-text" href="{{ route('max-sale-product-wise')}}">Product Wise Sale</a></li>
                <li><a class="subnav-text" href="{{ route('max-sale-category-wise')}}">Category Wise Sale</a></li>
                <li><a class="subnav-text" href="{{ route('max-sale-customer-wise')}}">Customer Wise Sale</a></li>
            </ul>
        </li>

        <li class="has-subnav">
            <a href="{!! route('login-logs.index') !!}"><i class="fa fa-user-lock" aria-hidden="true"></i><span class="nav-text">Login Attempts</span></a>
        </li>

        @endif

        </ul>
        </li>
        <li>
        <a href="{!! route('admin-logout') !!}">
        <i class="icon-off nav-icon"></i>
        <span class="nav-text"> Logout </span>
        </a>
        </li>
    </ul>
    @endif
</nav>
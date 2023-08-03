<header>
        <div class="header-top-area pl-165 pr-165">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6 col-md-6">
                            <div class="header-top-wrapper">
                                <div class="header-top-info d-none d-xl-block f-left">
                                    <span><i class="fa-solid fa-heart"></i> Welcome to Medibazae. We provides <a href="#">Covid-19 </a>medical accessories</span>
                                </div>
                                <div class="header-link f-left">
                                    <span><a href="tel:1234567879"><i class="fa-solid fa-phone"></i> +123 (456) 7879</a></span>
                                </div>
                            </div>
                        </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="header-top-right text-md-right">
                        <div class="shop-menu">
                        <ul id="menu-top-right" class="header_right">
                            @if(\Auth::check())
                            <li class="menu-item"><a href="#"><i class="fa-solid fa-user"></i>{{ \Auth::user()->name }}</a>
                            <ul class="header_sub-menu">
                                <li><a href="{{ route('my-profile') }}">My Profile</a></li>
                                <li><a href="{{ route('wishlist')}}">Wishlist</a></li>
                                <li><a href="{!! route('my-orders') !!}">My Orders</a></li>
                                <li><a href="{{ route('change-password')}}">Change Password</a></li>
                                <li><a href="{!! route('logout') !!}">Log Out</a></li>
                            </ul>
                            </li>

                        @else
                            <li class="menu-item"><a href="{{ route('log-in') }}"><i class="fa-solid fa-lock"></i>Sign In</a></li>
                        @endif
                   
                            <li class="menu-item"><a href="{{ route('wishlist') }}"><i class="fa-solid fa-heart"></i>Wishlist</a></li>
                                </ul>                              
                            </div>
                        </div>
                    </div>
        </div>
        </div>
    </div>
    @php $ip = $_SERVER['HTTP_USER_AGENT']; @endphp                    
        <div id="sticky-header" class="main-menu-area menu-01 pl-165 pr-165">
        <div class="container-fluid">
        <div class="row align-items-center">
        <div class="col-xl-3 col-lg-3">
        <div class="logo">
        <a href="{{ route('home') }}" title="Wishmed">
            <img class="logo_dark" src="{!! asset('assets/frontend/images/logo.png')  !!}" alt="Wishmed">
        </a>
        </div>
        </div>
        <div class="col-xl-9 col-lg-9 d-none d-lg-block">
        <div class="header-right f-right">                 
    <div class="header-lang f-right pos-rel d-none d-md-none d-lg-block">         
        <div class="top-cart-row">
            <div class="dropdown dropdown-cart"> 
                <a href="{{ route('cartDetail') }}" class="lnk-cart">
                <div class="items-cart-inner">
                <div class="basket"> <i class="fa-solid fa-cart-shopping"></i> </div>
                <div class="basket-item-count">   
                    <span class="cart-count"> @if(\Auth::check()) {{ user_cart_count(\Auth::user()->id) }} @else {{ cart_count($ip) }} @endif </span>

                </div>
                <div class="total-price-basket"> <span class="lbl">My Cart</span>  </div>
                </div>
                </a>
            </div>
        </div>
    </div>
                            
    <div class="menu-bar info-bar f-right d-none d-md-none d-lg-block">
        <a href="#"><i class="fa-solid fa-bars"></i></a>
    </div>
                                                        
    <div class="header-search f-right d-none d-xl-block">

        <form class="header-search-form" id="labnol" method="get" action="{{ route('search_product')}}">
            <input type="text" name="q" id="transcript" autocomplete="off" @if(isset($search_key)) value="{{ $search_key }}" @endif class="main-search" placeholder="Search">
            <ul id="total_records1"></ul>
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            <input type="hidden" name="post_type" value="product">
        </form>

    </div>
    </div>
    <div class="main-menu">
    <nav id="mobile-menu" style="display: block;">
    <ul id="menu-menu-1" class=""><li class="dropdown  menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="{{ route('home') }}">Home</a>
</li>
<li class="dropdown  menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children"><a href="{{ route('shop') }}">Shop</a>
</li>
<li class="dropdown"><a href="#">Categories</a>
<ul class="sub-menu text-left">
@php
$par_cats = get_par_cat();
@endphp
    @foreach($par_cats as $par_cat)
    <li><a href="{{ route('categoryDetail', $par_cat->url) }}">{{ $par_cat->name }}</a>
            @php
            $subcats = get_sub_cat($par_cat->id);
            @endphp
            @if($subcats)
            <ul>
              @foreach($subcats as $subcat) 
                <li><a href="{{ route('categoryDetail', $subcat->url) }}"> {{ $subcat->name}}</a> 

                 @php
              $subcats = get_sub_cat($subcat->id);
            @endphp
            @if($subcats)
            <ul>
            @foreach($subcats as $subcat) 
            <li><a href="{{ route('categoryDetail', $subcat->url) }}"> {{ $subcat->name}}</a></li>
            @endforeach
            </ul>
            @endif
                </li>
              @endforeach
            </ul>
            @endif
    </li>
    @endforeach
</ul>

</li>


<li class="dropdown  menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children"><a href="{{ route('blogs_page') }}">Blog</a>
<!-- <ul class="sub-menu text-left">
    <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Blog Standart</a></li>
    <li class=" menu-item menu-item-type-post_type menu-item-object-post"><a href="#">Blog Detail</a></li>
</ul> -->
</li>
<!-- <li class="dropdown menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="#">Pages</a>
<ul class="sub-menu text-left">
    <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="#">About</a></li>
    <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Contact Us</a></li>
    <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="#">FAQ</a></li>
    <li class=" menu-item menu-item-type-custom menu-item-object-custom"><a href="#">404 Error Page</a></li>
</ul>
</li> -->
<li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="{{ route('contact') }}">Contact Us</a></li>
</ul>                           </nav>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mobile-menu"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="extra-info" id="extra-info">
        <div class="close-icon">
            <button>
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
        </div>
        <div class="logo-side mb-30">
            <a href="#" title="Wishmed">
                <img src="{!! asset('assets/frontend/images/logo.png')  !!}" alt="Wishmed">
            </a>
        </div>  
        <div class="sidebar-modal-widget widget_contact_box">       
            <div class="side-info mb-30">
                <div class="contact-list mb-30">
                    <h4>Office Address</h4>
                    <p>Unit 2, 22-24 Steel Street, Blacktown, NSW 2148</p>
                </div>
                <div class="contact-list mb-30">
                    <h4>Brisbane Office</h4>
                    <p>Unit 11, 42 Smith Street, Capalaba 4157</p>
                </div>
                <div class="contact-list mb-30">
                    <h4>Phone Number</h4>
                    <p>+61 2 8678 0983<br>+61 2 8678 0993</p>
                </div>
                <div class="contact-list mb-30">
                    <h4>Email Address</h4>
                    <p>sales@wishmed.com.au</p>
                </div>
            </div>
        </div>
            <div class="sidebar-modal-widget widget_social_list">     
                <div class="social-icon-right mt-20">
                    <a href="https://www.facebook.com/wishmedptyltd" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/wishmedptyltd/?hl=en" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/channel/UCCAwH_aIW57zvNsrqy4axrA" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>         
        </div>
    <div class="sidebar-overlay"></div>
    </header>

    <div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
   <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('about-us') }}">About US</a></li>
        <li><a href="{{ route('shop') }}">Shop</a> </li>
        <li><a href="{{ route('blogs_page') }}">Blogs</a></li>
        <li><a href="{{ route('contact') }}">Contact Us</a></li>
    </ul>
</div>
<a onclick="openNav()" class="openNav">
    <span></span>
    <span></span>
    <span></span>
</a>
        
        <div class="top-cart-row phone_cart">
            <div class="dropdown dropdown-cart"> 
                <a href="{{ route('cartDetail') }}" class="lnk-cart">
                <div class="items-cart-inner">
                <div class="basket"> <i class="fa-solid fa-cart-shopping"></i> </div>
                <div class="basket-item-count">   
                    <span class="cart-count"> @if(\Auth::check()) {{ user_cart_count(\Auth::user()->id) }} @else {{ cart_count($ip) }} @endif </span>
                </div>
                </div>
                </a>
            </div>
        </div>
  
<div class="header-search phone_search">
    <form class="header-search-form" id="labnol" method="get" action="{{ route('search_product')}}">
        <input type="text" name="q" id="transcript" autocomplete="off" @if(isset($search_key)) value="{{ $search_key }}" @endif class="main-search" placeholder="Search">
        <ul id="total_records1"></ul>
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        <input type="hidden" name="post_type" value="product">
    </form>
</div>  
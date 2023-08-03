<footer>
<div class="footer-area pt-80 pb-45">
<div class="container">
<div class="row">
<div class="col-xl-3 col-lg-3 col-md-6">
	<div class="klbfooterwidget footer-wrapper mb-30 widget_footer_about">
		<h3 class="footer-title">About Company</h3>		
	<div class="footer-text">
	<p>Wishmed was established in March 2010 in Australia as a technical consultancy to support Lab equipment installations and repairs. In Feb 2011, we took steps to come in Medical and Laboratory supply field after finding unregulated priced product in the market.</p>
	</div>
	<div class="footer-icon">
	<a href="https://www.facebook.com/wishmedptyltd" target="_blank"><i class="fab fa-facebook-f"></i></a>
	<a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
	<a href="https://www.instagram.com/wishmedptyltd/?hl=en" target="_blank"><i class="fab fa-instagram"></i></a>
	<a href="https://www.youtube.com/channel/UCCAwH_aIW57zvNsrqy4axrA" target="_blank"><i class="fab fa-youtube"></i></a>
	</div>
	</div>								
</div>

<div class="col-xl-3 col-lg-3 col-md-6">
<div class="klbfooterwidget footer-wrapper ml-50 mb-30 widget_nav_menu">
	<h3 class="footer-title">Useful Links</h3>
	<div class="menu-useful-links-container">
		<ul id="menu-useful-links" class="menu">
			<li><a href="{{ route('home') }}">Home</a></li>
			<li><a href="{{ route('about-us') }}">About Us</a></li>
			<li><a href="{{ route('blogs_page') }}">Blogs</a></li>
			<li><a href="{{ route('contact') }}">Contact Us</a></li>
			<li><a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a></li>
			<li><a href="{{ route('refund-and-return') }}">Return & Cancellation Policy</a></li>
		</ul>
</div>
</div>
</div>

<div class="col-xl-3 col-lg-3 col-md-6">
	<div class="klbfooterwidget footer-wrapper ml-30 mb-30 widget_nav_menu">
		<h3 class="footer-title">Categories</h3>
		<div class="menu-footer-products-container">
			<ul id="menu-footer-products" class="menu">
	            @php
				    $par_cats = get_par_cat();
				@endphp

				@foreach($par_cats as $par_cat) 
					<li><a href="{{ route('categoryDetail', $par_cat->url) }}">{{ $par_cat->name }}</a></li>
		        @endforeach   
		    </ul>
		</div>
	</div>	
</div>


<div class="col-xl-3 col-lg-3 col-md-6">
<div class="klbfooterwidget footer-wrapper ml-20 mb-30 widget_footer_contact">
	<h3 class="footer-title">Contact Us</h3>
<div class="contacts-info">
<div class="phone-footer">
	<i class="fa-solid fa-location-dot"></i>
	<p>Unit 2, 22-24 Steel Street, Blacktown, NSW 2148
</p>
</div>
<div class="phone-footer">
	<i class="fa-solid fa-phone"></i><p> +61 2 8678 0983
</p>
</div>
<div class="phone-footer">
	<i class="fa-sharp fa-solid fa-envelope"></i><p>sales@wishmed.com.au</p>
</div>
</div>
</div>								
</div>

</div>
</div>
</div>

<div class="footer-bottom-area mr-70 ml-70 pt-25 pb-25">
<div class="container">
<div class="row">
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="copyright">
<p>Copyright 2023. All rights reserved.</p>
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-6">
<div class="footer-bottom-link f-right">
</div>
</div>
</div>
</div>
</div>
</footer>
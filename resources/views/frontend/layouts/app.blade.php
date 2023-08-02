<!DOCTYPE html>
<head class="wide wow-animation" lang="en">
<!-- Site Title-->
@if(isset($keyword))   
        @if(isset($keyword->title))
        <title>{{$keyword->title}}</title>
        <meta name="description" content="{{$keyword->description}}"/>
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="website" />
        @else
        <title>{{$keyword->meta_title}}</title>
        <meta name="description" content="{{$keyword->meta_description}}"/>
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="{{$keyword->meta_title}}" />
        <meta property="og:description" content="{{$keyword->meta_description}}" /> 
        <meta property="og:image" content="{{ route('home') }}/{{ str_replace( ' ', '%20', $keyword->featured_image) }}" />
        <meta property="og:image:width" content="1000" />
        <meta property="og:image:height" content="1000" />
        @endif
      
        @if(isset($keyword->keyword))

        <meta property="og:title" content="{{$keyword->keyword}}" />
        @else
        <meta property="og:title" content="{{$keyword->meta_tag}}" />
        @endif
        @if(isset($keyword->description))
        <meta property="og:description" content="{{$keyword->description}}" />
        @else
        <meta property="og:description" content="{{$keyword->meta_description}}" />
        @endif
        @if(isset($keyword->keyword))
        <meta name="twitter:title" content="{{$keyword->keyword}}" />
        @else
        <meta name="twitter:title" content="{{$keyword->meta_tag}}" />
        @endif
    @else

<title>Wishmed</title>

@endif

<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="utf-8">
<meta name="csrf-token" content="{!! csrf_token() !!}" />
    <link rel="icon" href="{!! asset('assets/frontend/images/favicon.png') !!}" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
   <!--  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 -->    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
    <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/956568d106.js" crossorigin="anonymous"></script>
{!! Html::style('assets/frontend/css/magnify.css') !!}
{!! HTML::style('assets/frontend/css/jquery.simpleGallery.css') !!}
{!! HTML::style('assets/frontend/css/jquery.simpleLens.css') !!}
{!! HTML::style('assets/frontend/css/zoom.css') !!}
{!! HTML::style('assets/frontend/css/validationEngine.jquery.css') !!}
{!! HTML::style('assets/frontend/css/pe-icon-7-stroke.css') !!}
{!! HTML::style('assets/frontend/css/stellarnav.min.css') !!}
{!! HTML::style('assets/frontend/css/fontaw.css') !!}

<!-- {!! HTML::style('assets/frontend/css/bootstrap.min1.css') !!} -->
<!--{!! HTML::style('assets/frontend/css/style.css') !!} -->
<link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet" type="text/css" >
<!--<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet" type="text/css" > -->


@yield('css')

</head>
<body class="content-pages">
    <!-- Page-->
    <div class="page">
        <!-- Header -->
        @include('frontend.layouts.header')      
        <!-- Main Content -->
        @yield('content')
        <!-- Footer -->
<!-- HEADER STARTS -->
        @include('frontend.layouts.footer') 

        <!-- Register - Login Popup -->
       <!--  @include('frontend.layouts.register') 
        @include('frontend.layouts.login') -->
    </div> 
<!-- Javascript-->
<!-- SCRIPT STARTS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
        {!! HTML::script('assets/frontend/js/stellarnav.min.js') !!}
        {!! HTML::script('assets/frontend/js/custom.js') !!}
        <script>
            function rdmore() {
              var dots = document.getElementById("dots");
              var moreText = document.getElementById("more");
              var btnText = document.getElementById("myBtn");

              if (dots.style.display === "none") {
                dots.style.display = "inline";
                btnText.innerHTML = "Read more"; 
                moreText.style.display = "none";
              } else {
                dots.style.display = "none";
                btnText.innerHTML = "Read less"; 
                moreText.style.display = "inline";
              }
            }
            </script>
            @if(session()->has('subscrive'))

            @else

            @if(session()->has('otp_sent'))

            @else
            @if(session()->has('you_not_register'))

            @else
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#onloadmodal").modal('show');
                });
            </script>
            @endif
            @endif
            @endif
    <!-- SCRIPT ENDS -->


{!! HTML::script('assets/frontend/js/jquery.validationEngine.js') !!}
{!! HTML::script('assets/frontend/js/jquery.validationEngine-en.js') !!}
{!! HTML::script('assets/frontend/js/jquery.simpleGallery.min.js') !!}
{!! HTML::script('assets/frontend/js/jquery.simpleGallery.js') !!}
{!! HTML::script('assets/frontend/js/jquery.simpleLens.js') !!}
{!! HTML::script('assets/frontend/js/jquery.simpleLens.min.js') !!}
{!! HTML::script('assets/frontend/js/jquery.elevatezoom.js') !!}
<!-- <script src="http://malsup.github.com/jquery.form.js"></script> -->

<!-- {!! HTML::script('assets/frontend/js/script.js') !!}

{!! HTML::script('assets/js/template.js') !!} -->
<script>

    function qtu_increase(val){
        var qty = $("#pid"+val).val();
        var f_qty = parseInt(qty) + 1;

        $("#pid"+val).val(f_qty);
       // alert(f_qty);
    }

    function qtu_decrease(val){
        var qty = $("#pid"+val).val();
        if(qty > 0){
        var f_qty = parseInt(qty) - 1;
        $("#pid"+val).val(f_qty);
        }
    }

    $(".mn-img").elevateZoom({
      gallery : "gal1",
      responsive:true
    });

    $(".ajax_subscribe").click(function(e){
        e.preventDefault();
        var email = $("input[name=subscribe]").val();
        var form = document.getElementById("mysubscribe");
        $.ajax({
           type:'GET',
           url: "{{ route('subscribe.store') }}",
           data:{email:email},
           success:function(data){
              $("#already_subs").html(data.already_subs);
              $("#email_subs").html(data.email_subs);
              $("#valid_email").html(data.valid_email);
              
               form.reset();
           }
        });
    });

  </script>


<script type="text/javascript">

var token = $('meta[name="csrf-token"]').attr('content');
var authType = false;

$(document).ready(function(){
    $('#sign-up-form').submit(function(e){
        if ($("#sign-up-form").validationEngine('validate') ) {
        e.preventDefault();
        submitForm($(this), authSuccess);
    }
    });
});

    function submitForm(_form, successFunc)
{
    removeErrors();
    // showLoader();
    // var data = additionalData;

    var data = {'_token' : $('meta[name="csrf-token"]').attr('content')};
    console.log(data);

    _form.ajaxSubmit({
        type: 'POST',
        data: data,
        success: function(response){
            hideLoader();
            successFunc(response);
        },
        error: function(response){
            showErrors(response, _form);
           // hideLoader();
        }
    });
}
function showErrors(response, _form)
{
    if (typeof response.responseJSON == 'undefined') {
        // swal('');
        alert('error occured');
    } else if (typeof response.responseJSON.error != 'undefined') {
        swal('Error:', response.responseJSON.error, 'error');
    } else {
        for (i in response.responseJSON.errors) {
            var fieldName = i;
            _form.find('[name="'+ fieldName +'"]').parent().addClass('err');
            _form.find('[name="'+ fieldName +'"]').parent().append('<span class="custom-validate-error-item">'+ response.responseJSON.errors[i][0] +'</span>');
        }
    }
}

function authSuccess(response)
{
    if (typeof response.intended != 'undefined' ) {
        if (authType == 'blog_comment') {
            window.location = response.intended + '#comments-reply';
            location.reload();
        } else {
            window.location = response.intended;
        }
    }
}

function removeErrors()
{
    $('.err').removeClass('err');
    $('.custom-validate-error-item').remove();
}

function removeInputsNErrors(formSelector)
{
    removeErrors();

    if (typeof formSelector != 'undefined') {
        $( formSelector ).find('input, select').val('');
        $( formSelector ).find('textarea').val('');
    }
}

function getCategoryID() {

    $(".color_value").prop("checked", false);

    var category_id = [];
    $('input.category_value[type=checkbox]').each(function () {
        if (this.checked)
            category_id.push($(this).val());
    });

    $.ajax({
        type: "GET",
        url: "#",
        data: {'category_id' : category_id},
        success: function(data){
            $("#category-list").html(data);
        }
    });
}

function SortFilter1(val) {
    var search_key = $("#search_key").val();
    $.ajax({
        type: "GET",
        url: "{{ route('getSort1') }}",
        data: {'sort_by' : val, 'search_key' : search_key},
        success: function(data){
            $("#category-list").html(data);
        }
    });
} 



function getSortByCategory(val) {
    $.ajax({
        type: "GET",
        url: "{{ route('getSortByCategory') }}",
        data: {'sort_by' : val},
        success: function(data){
            $("#category-list").html(data);
        }
    });
}



function addToCart(val) {

    var quantity = $('.qty'+val).val();
   // alert(quantity);

    $.ajax({
        type: "GET",
        url: "{{ route('addToCart') }}",
        data: {'product_id' : val, 'quantity' : quantity},
        success: function(data){
            $("#add-cart").hide();
            $("#add-cart_full").hide();
            $("#add-cart_full").html(data.add_cart1);
            $("#item_cart").html(data.item_cart);
            $("#cart_product_count").html(data.cart_product_count);
            $("#selected_product_price").html(data.selected_product_price);
            $('#cart_modal').modal('show');
            $('#exampleModalcart').modal('hide');
            $("#add-cart").html(data.add_cart);
            if(data.add_cart1 == ''){
               $("#add-cart").show(); 
               $("#add-cart").delay(7200).fadeOut(300);
            } else {
                $("#add-cart_full").show();
                $("#add-cart_full").delay(7200).fadeOut(300);
            }
            
            // smoothScrollTo('#add-cart', 1500, 100);
            
        }
    });
}



//  jQuery(document).ready(function(){
//     smoothScrollTo('#verified-purchase', 1500, 100);

// }); 

function addQuantityCart(val) {
    $.ajax({
        type: "GET",
        url: "{{ route('addQuantityCart') }}",
        data: {'cart_id' : val},
        success: function(data){
            console.log(data);
            $("#quantity"+data.cart_id).html(data.quantity);
            $("#1quantity"+data.cart_id).html(data.quantity);
            $("#list_price"+data.cart_id).html(data.list_price);
            $("#sale_price"+data.cart_id).html(data.sale_price1);
            $("#widthout_sale_price"+data.cart_id).html(data.widthout_sale_price1);
            $("#1list_price"+data.cart_id).html(data.list_price);
            $("#2sale_price"+data.cart_id).html(data.sale_price);
            $("#without_2sale_price"+data.cart_id).html(data.without_sale_price);
            $("#grand_total").html(data.grand_total);
            $("#total_sale_price").html(data.total_sale_price);
            $("#total_sale_price_cart").html(data.total_sale_price);
            $("#total_sale_price_cart1").html(data.total_sale_price);
            $("#total_list_price").html(data.total_list_price);
            $("#1total_list_price").html(data.total_list_price);
            $("#total_tax").html(data.total_tax);
            $("#total_saving").html(data.total_saving_);
            $("#discount").html(data.total_saving_1);
            $("#remove_q"+data.cart_id).html(data.remove_q);
            $("#1remove_q"+data.cart_id).html(data.remove_q);
            $("#add-cart").html(data.add_cart);
            $("#cart_product_count").html(data.cart_product_count);
        }
    });
}

function removeQuantityCart(val) {
    $.ajax({
        type: "GET",
        url: "{{ route('removeQuantityCart') }}",
        data: {'cart_id' : val},
        success: function(data){
            console.log(data);
            $("#quantity"+data.cart_id).html(data.quantity);
            $("#1quantity"+data.cart_id).html(data.quantity);
            $("#list_price"+data.cart_id).html(data.list_price);
            $("#sale_price"+data.cart_id).html(data.sale_price1);
            $("#widthout_sale_price"+data.cart_id).html(data.widthout_sale_price1);
            $("#1list_price"+data.cart_id).html(data.list_price);
            $("#2sale_price"+data.cart_id).html(data.sale_price);
            $("#without_2sale_price"+data.cart_id).html(data.without_sale_price);
            $("#grand_total").html(data.grand_total);
            $("#total_sale_price").html(data.total_sale_price);
            $("#total_sale_price_cart").html(data.total_sale_price);
            $("#1total_sale_price_cart").html(data.total_sale_price);
            $("#total_sale_price_cart1").html(data.total_sale_price);
            $("#total_list_price").html(data.total_list_price);
            $("#1total_list_price").html(data.total_list_price);
            $("#total_tax").html(data.total_tax);
            $("#total_saving").html(data.total_saving_);
            $("#discount").html(data.total_saving_1);
            $("#remove_q"+data.cart_id).html(data.remove_q);
            $("#1remove_q"+data.cart_id).html(data.remove_q);
         
            $("#cart_product_count").html(data.cart_product_count);
            
        }
    });
}


    $(".ajax_sub").click(function(e){
        e.preventDefault();
        var min = $("input[name=min]").val();
        var max = $("input[name=max]").val();
        var page = $("input[name=page]").val();
        var f_id = $("input[name=f_id]").val();
        $.ajax({
           type:'GET',
           url: "{{ route('price_filter') }}",
           data:{min:min, max:max, page:page, f_id:f_id},
           success:function(data){
              $("#category-list").html(data);
           }
        });
    });

jQuery(document).ready(function(){

jQuery("#sign-in-form").validationEngine();

});

$(".ajax_login").click(function(e){
if ($("#sign-in-form").validationEngine('validate') ) {
        e.preventDefault();
        var email = $("input[name=email]").val();
        var password = $("input[name=password]").val();
        $.ajax({
           type:'GET',
           url: "{{ route('login') }}",
           data:{password:password, email:email},
           success:function(data){

            if(data.error){
                $("#login-res").html(data.error);
            } else {
                $("#login-succes").html(data.succes);
                if(data.url){
                    window.location.href = "/"+data.url;  
                } else {
                  window.location='/';
                }
                
            }
              
           } 
        });
    } else {
    }
    });

    $("#success-alert").fadeTo(2000, 1000).slideUp(1000, function(){
    $("#success-alert").alert('close');
    });


$('.menu-bar.info-bar a').click(function(){
    document.getElementById("extra-info").classList.add("info-open");
});

$('.close-icon button').click(function(){
    document.getElementById("extra-info").classList.remove("info-open");
});




</script>

<!-- <script>
            // ZOOM IMG JS STARTS
                $(".mn-img").elevateZoom({
              gallery : "gal1",
              responsive:true
            });
            // ZOOM IMG JS ENDS
        </script> -->

@if(session()->has('message_reg'))
<script type="text/javascript">
    $('#modal1').modal('hide');
    $('#modal2').modal('show');
</script>
@endif

@if(session()->has('message_reg1'))
<script type="text/javascript">
    $('#modal1').modal('hide');
    $('#modal212').modal('show');
</script>
@endif

@if(session()->has('wishlist'))
<script type="text/javascript">
    $('#modalwishlist').modal('show');
</script>
@endif

@if(session()->has('cart_delete'))
<script type="text/javascript">
    $('#cart_delete').modal('show');
</script>
@endif
@if(session()->has('message_enq'))
<script type="text/javascript">
    $('#modal9').modal('show');
</script>
@endif
@yield('script')
@if(session()->has('message_reg'))
<script type="text/javascript">
    $('#modal1').modal('hide');
    $('#modal2').modal('show');
</script>
@endif

@if(session()->has('product_sub'))
<script type="text/javascript">
$(window).on('load',function(){
$('#product_sub').modal('show');
});
</script>
@endif

@if(session()->has('password_change'))
<script type="text/javascript">
$(window).on('load',function(){
$('#password_change').modal('show');
});
</script>
@endif
@if(session()->has('old_password_not_match'))
<script type="text/javascript">
$(window).on('load',function(){
$('#old_password_not_match').modal('show');
});
</script>
@endif


@if(session()->has('subscrive'))
<script type="text/javascript">
$(window).on('load',function(){
$('#subscrive').modal('show');
});
</script>
@endif
@if(session()->has('success_review'))
<script type="text/javascript">
$(window).on('load',function(){
$('#success_review').modal('show');
});
</script>
@endif
@if(session()->has('login_popup'))
<script type="text/javascript">
window.location.href = "{{ route('log-in') }}";
</script>
@endif

@if(session()->has('subscrive_already'))
<script type="text/javascript">
$(window).on('load',function(){
$('#subscrive_already').modal('show');
});
</script>
@endif

<script type="text/javascript">
 
function startDictation() {
    $('#speak_icon').addClass('red_speak_icon');
    if (window.hasOwnProperty('webkitSpeechRecognition')) {
      var recognition = new webkitSpeechRecognition();
      recognition.continuous = false;
      recognition.interimResults = false;
      recognition.lang = "en-US";
      recognition.start();
      recognition.onresult = function(e) {
        document.getElementById('transcript').value
                                 = e.results[0][0].transcript;
        recognition.stop();
        document.getElementById('labnol').submit();
      };
      recognition.onerror = function(e) {
        recognition.stop();
      }
    }
}   


function SortFilter() {
    
    var sort_by = $("#sortID").val();
    var category_id = $("#search_cat").val();
    
    $.ajax({
        type: "GET",
        url: "{{ route('getSort') }}",
        data: {'sort_by' : sort_by, 'category_id' : category_id},
        success: function(data){
            $("#category-list").html(data);
        }
    });
} 

</script>

<!-- <script src="dist/js/jquery.magnify.js"></script> -->
{!! HTML::script('assets/frontend/js/jquery.magnify.js') !!}
<script>
$(document).ready(function() {
  $('.img_zoo').magnify();
});

$(".myButton").click(function() {
   var lable = $(".mySelect").val();
   if(lable == "Show") {
     $(".mySelect").val('Hide'); 
     $("#myDiv").hide();
   }
   else {
     $(".mySelect").val('Show');
     $("#myDiv").show();
   }
 });


</script>


@if(session()->has('you_not_register'))
<script type="text/javascript">
$(window).on('load',function(){
$('#you_not_register').modal('show');
$('#login_modal').modal('hide');
$('#onloadmodal').modal('hide');
});
</script>
@endif
@if(session()->has('otp_sent'))
<script type="text/javascript">
$(window).on('load',function(){
$('#otp_sent').modal('show');
$('#login_modal').modal('hide');
});
</script>
@endif

@if(session()->has('account_confirm'))
<script type="text/javascript">
$(window).on('load',function(){
$('#modalcnfrm_ac').modal('show');
});
</script>
@endif

<script type="text/javascript">

$("#login_modal1").on("click", function () {

    $("#signup_modal").modal("hide");
    $("#login_modal").modal("show");
});

$("#login_modal2").on("click", function () {

    $("#signup_modal").modal("hide");
    $("#login_modal").modal("show");
});

$("#login_modal3").on("click", function () {

    $("#signup_modal").modal("show");
    $("#login_modal").modal("hide");
});

$("#login_modal4").on("click", function () {

    $("#signup_modal").modal("show");
    $("#login_modal").modal("hide");
});

</script>

<script type="text/javascript">
    function myFunction() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
            x.type = "text";
            $(".fa-eye").hide();
            $(".fa-eye-slash").show();
            } else {
            x.type = "password";
            $(".fa-eye").show();
            $(".fa-eye-slash").hide();
        }
    }

     function myFunction1() {
        var x = document.getElementById("myInput1");
        if (x.type === "password") {
            x.type = "text";
            $(".fa-eye").hide();
            $(".fa-eye-slash").show();
            } else {
            x.type = "password";
            $(".fa-eye").show();
            $(".fa-eye-slash").hide();
        }
    }


//  jQuery(document).ready(function(){
//     smoothScrollTo('#verified-purchase', 1500, 100);

// }); 

$('.blog-slider').owlCarousel({
    autoplay: true,
    smartSpeed: 900,
    loop: true,
    margin: 20,
    nav: false,
    center:false,
    autoplayHoverPause:true,
    navText: ['<i class="fas fa-angle-left"></i>','<i class="fas fa-angle-right"></i>'],
    dots: true,
    responsive:{
        0:{
            items:1,
            nav: false
        },
        575:{
            items:1,
            nav: false
        },
        768:{
            items:2,
            nav: false
        },
        992:{
            items:2,
        },
        1200:{
            items:2
           
        }
    }
});



$(document).ready(function(){

 fetch_customer_data();

 function fetch_customer_data(query = '', category_id)
{
  $.ajax({
   url:"{{ route('live_search') }}",
   method:'GET',
   data:{query:query, category_id:category_id},
   dataType:'json',
   success:function(data) {
    $('#total_records1').html(data.table_data);
   }
  })
 }

 $(document).on('keyup', '.main-search', function(){
  var query = $(this).val();
  var category_id = $( "#category_id" ).val();

if(query){
    fetch_customer_data(query, category_id);
}
  

 });
});



function getCategoryFilter(category_id) {
  var query = $( "#transcript" ).val();
  if(query){
  $.ajax({
    url:"{{ route('live_search') }}",
    method:'GET',
    data:{query:query, category_id:category_id},
    dataType:'json',
    success: function(data){
       $('#total_records1').html(data.table_data);
    }
  });
  }
}


$(document).ready(function() {
    $("input[name$='cars']").click(function() {
        var test = $(this).val();

        $("div.desc").hide();
        $("#Cars" + test).show();
    });
});

function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

</script>




<script type="text/javascript">
        $(function() {
                var fullWidth = 864; // Width in pixels of full-sized image
                var fullHeight = 648; // Height in pixels of full-sized image
                var thumbnailWidth = 389;  // Width in pixels of thumbnail image
                var thumbnailHeight = 292;  // Height in pixels of thumbnail image
                // Set size of div
                $('#picture').css({
                        'width': thumbnailWidth+'px',
                        'height': thumbnailHeight+'px'
                });
                // Hide the full-sized picture
                $('#full').hide();
                // Toggle pictures on click
                $('#picture').click(function() {
                        $('#thumbnail').toggle();
                        $('#full').toggle();
                });
                // Do some calculations
                $('#picture').mousemove(function(e) {
                        var mouseX = e.pageX - $(this).attr('offsetLeft'); 
                        var mouseY = e.pageY - $(this).attr('offsetTop'); 

                        var posX = (Math.round((mouseX/thumbnailWidth)*100)/100) * (fullWidth-thumbnailWidth);
                        var posY = (Math.round((mouseY/thumbnailHeight)*100)/100) * (fullHeight-thumbnailHeight);

                        $('#full').css({
                                'left': '-' + posX + 'px',
                                'top': '-' + posY + 'px'
                        });
                });
        });
    </script>






</body>
</html>

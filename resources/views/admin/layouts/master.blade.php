<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<title>Wishmed</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Custom Admin Theme Design" />
<meta name="csrf-token" content="{!! csrf_token() !!}" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
{!! Html::style('css/bootstrap.css') !!}
<!-- //bootstrap-css -->
<!-- Custom CSS -->
{!! HTML::style('css/style.css') !!}
<!-- font CSS -->
{!! HTML::style('https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic') !!}
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
{!! HTML::style('css/font.css') !!}
{!! HTML::style('css/font-awesome.css') !!}

<!-- //font-awesome icons -->
{!! HTML::script('js/jquery2.0.3.min.js') !!}
<script type="text/javascript">

 $("#sale_pri").on('keyup',function(){
    $(".zone_pr").val($(this).val());
});

$(document).ready(function(){

 fetch_product_name();

 function fetch_product_name(query = '')
 {
  
   $('.prod_name').val(query);
 }

 $(document).on('keyup', '.pro_name', function(){
     
  var query = $(this).val();
  fetch_product_name(query);
 });
});

    

$(document).ready(function(){

 fetch_product_data1();

 function fetch_product_data1(query = '')
 {
  $.ajax({
   url:"{{ route('live_product_1') }}",
   method:'GET',
   data:{query:query},
   dataType:'json',
   success:function(data)
   {

    $('#live_product_1').html(data.table_data);
   }
  })
 }

 $(document).on('keyup', '.live_product_1', function(){
  $("#live_product_1").show();    
  var query = $(this).val();
  fetch_product_data1(query);
 });
});



function getProduct_Code_1(val) {

    $.ajax({
        type: "GET",
        url: "{{ route('check_product_code') }}",
        data: {'code' : val},
        success: function(data){
            if(data.status == 'Fail'){
                
            } else{
                $(".product_id").val(data.product_id);
                $(".product_name").val(data.product_name);
            }
            $("#live_product_1").hide();   

        }
    });

}



</script>
{!! HTML::script('js/modernizr.js') !!}
{!! HTML::script('js/jquery.cookie.js') !!}
{!! HTML::script('js/screenfull.js') !!}

@yield('css')
    <script>
    $(function () {
        $('#supported').text('Supported/allowed: ' + !!screenfull.enabled);
        if (!screenfull.enabled) {
            return false;
        }            
        $('#toggle').click(function () {
            screenfull.toggle($('#container')[0]);
        }); 
    });
    </script>
<!-- charts -->
{!! HTML::script('js/raphael-min.js') !!}
{!! HTML::script('js/morris.js') !!}
{!! HTML::script('js/morris.js') !!}
{!! HTML::style('css/morris.css') !!}
{!! HTML::style('css/template.css') !!}
<!-- //charts -->
<!--skycons-icons-->
{!! HTML::script('js/skycons.js') !!}

<!--//skycons-icons-->
</head>
<body class="dashboard-page">
    @include('admin.layouts.sidebar')    
    <section class="wrapper scrollable">
        <nav class="user-menu">
            <a href="javascript:;" class="main-menu-access">
            <i class="icon-proton-logo"></i>
            <i class="icon-reorder"></i>
            </a>
        </nav>
        @include('admin.layouts.header')
        <div class="main-grid">
            @yield('content')
        </div>
        @include('admin.layouts.footer')       
    </section>

<script src="https://kit.fontawesome.com/4fa1165109.js" crossorigin="anonymous"></script>   


</body>
</html>

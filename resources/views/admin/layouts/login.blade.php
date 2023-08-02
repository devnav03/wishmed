<!DOCTYPE html>
<html>
<head>
    <title>Wishmed</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/favicon.png" type="image/png">  
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    
    *{
        font-family: 'Roboto', sans-serif;
    }
    .form-control, .btn{
        box-shadow: unset !important;
    }
    .vmdl{
        position: relative;
        top: 50%;
        transform: translate(0%, -50%);
    }

/********************Login Page Css starts********************/
    .wp-admin{
        height: 100vh;
        background-image: url(../../images/login_back.png);
        background-repeat: no-repeat;
        background-size: cover;
    }
    .wp-admin img{
        object-fit: cover;
    }
    .wp-admin .data{
        position: relative;
        left: 50%;
        top: 50%;
        background-color: #fff;
        padding-top: 30px;
        transform: translate(-50%, -50%);
    }
    .wp-admin .box{
        background: #2e3094;
        padding: 25px;
    }
    .wp-admin .box label{
        color: #fff;
        text-transform: capitalize;
        font-size: 14px;
    }
    .wp-admin .box p, .wp-admin .box a{
        color: #fff;
        font-size: 14px;
    }
    .wp-admin .register{
        background-color: #fff;
        text-transform: uppercase;
        color: #2361AC;
        border: 0px;
        font-size: 14px;
        line-height: 40px;
        font-weight: 500;
        font-size: 17px;
        outline: none;
        padding: 3px 30px;
        -webkit-box-shadow: 5px 5px 18px -11px rgba(226 ,58 ,120 ,1) !important;
        -moz-box-shadow: 5px 5px 18px -11px rgba(226 ,58 ,120 ,1) !important;
        box-shadow: 5px 5px 18px -11px rgba(226 ,58 ,120 ,1) !important;
    }
    .wp-admin .box .form-control{
        border: 0;
        font-size: 14px;
        height: 44px;
        border-radius: 0px;
    }
/********************login page css ends********************/    
.alert.hidden,
.col-md-6.padding0.hidden .alert.alert-danger{
    display: none;
}
</style>
</head>
<body>

    <div class="wp-admin py-5">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-xl-5 col-lg-6 col-md-7 col-sm-9 mx-auto">
                    <div class="data">
                        <img src="{{ url('/') }}/images/logo.png" class="img-fluid mx-auto d-block" alt="" style="width: 190px;">
                        <div class="box mt-4">
                            <div class="row">
                                <div class="col-11 mx-auto">
                                    {!! Form::open(['url' => 'admin/login', 'method' => 'post', 'class' => '']) !!} 
                                        @include('admin.layouts.messages')
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-12 py-2">
                                                <label class="text-capitalize" for="">Email*</label>
                                                <input type="text" name="email" class="form-control" required="true">
                                            </div>
                                            <div class="col-12 py-2">
                                                <label class="text-capitalize" for="">password*</label>
                                                <input type="password" name="password" class="form-control" required="true">
                                            </div>
                                           <!--  <div class="col-12 text-right">
                                                <a href="#" class="text-capitalize">forgot password?</a>
                                            </div> -->
                                            <div class="col-12 mt-4">
                                                <div class="butn">
                                                    <input type="submit" class="register" value="sign in">
                                                    <!-- <div class="btn">sign in</div> -->
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS STARTS -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- SCRIPTS ENDS -->    
  </body>
</html>

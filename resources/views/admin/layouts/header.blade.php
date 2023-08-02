<section class="title-bar">
            <div class="logo">
                <h1><a href="{!! route('dashboard') !!}"><img src="{{ url('/') }}/images/logo.png" alt="" /></a></h1>
            </div> 
            <!-- <div class="full-screen">
                <section class="full-top">
                    <button id="toggle"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>    
                </section>
            </div> -->
            <div class="header-right">
                <div class="profile_details_left">
                    <div class="profile_details">       
                        <ul>
                            <li class="dropdown profile_details_drop">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <div class="profile_img">   
                                        <span class="prfil-img" style="margin-top: 10px;"><i class="fa fa-user" style="font-size: 16px !important;" aria-hidden="true"></i> <span style="color: #000; margin-left: 8px; font-size: 15px;">{{ \Auth::user()->name }}</span></span> 
                                        <div class="clearfix"></div>    
                                    </div>  
                                </a>
                                <ul class="dropdown-menu drp-mnu">
                                    <li> <a href="{!! route('setting.manage-account') !!}"><i class="fa fa-cog"></i> Change Password</a> </li> 
                                     
                                    <li> <a href="{!! route('admin-logout') !!}"><i class="fa fa-sign-out"></i> Logout</a> </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </section>
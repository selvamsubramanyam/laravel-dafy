<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{URL('public/admin/images/dafy_favicon.png')}}" type="image/ico" />

    <title>Dafy</title>

    <!-- Bootstrap -->
    <link href="{{URL('assets/plugins/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{URL('assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">    
    <!-- Custom Theme Style -->
    <link href="{{URL('assets/src/css/custom.css')}}" rel="stylesheet">

    <!-- My Theme Style -->
    <link href="{{URL('assets/css/my_style.css')}}" rel="stylesheet">
    <link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet">

    <link href="{{URL('public/admin/css/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.php" class="site_title sm-hide-logo"> <div class="logo"><!-- <img src="{{URL('public/admin/images/logo.png')}}"
                                    alt="" class="img-responsive"> --> </div></a>
                        <a href="{{Request::route()->getPrefix() == '/admin' ? URL('admin/home') : URL('seller/home')}}" class="site_title md-hide-logo "> <div class="logo"><img src="{{URL('public/admin/images/dafy_logo.png')}}"
                                    alt="" class="img-responsive" height="50" width="50"> </div></a> 
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                   <!--  <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="{{URL('assets/images/img.jpg')}}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2>Don Mathew</h2>
                        </div>
                    </div> -->
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>General</h3>
                            @if(Auth::guard('admin')->check())
                            <ul class="nav side-menu">
                               <!--  <li><a href="index.php"><i class="fa fa-file" aria-hidden="true"></i> Overview</a></li> -->
                            <li><a href="{{URL('admin/home')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                          
                                <li><a href="{{URL('admin/role')}}"><i class="fa fa-users" aria-hidden="true"></i></i> Role Management</a></li>
                           
                            <!-- <li>
                                    <a><i class="fa fa-list"></i>General<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{URL('admin/banner')}}"><i class="fa fa-picture-o" aria-hidden="true"></i>&nbsp;Banners</a></li>
                                        <li><a href="{{URL('admin/brand')}}"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;Brands</a></li>
                                        <li><a href="{{URL('admin/city')}}"><i class="fa fa-location-arrow" aria-hidden="true"></i>&nbsp;Cities</a></li>
                                        <li><a href="{{URL('admin/state')}}"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;States</a></li>
                                        <li><a href="{{URL('admin/unit')}}"><i class="fa fa-balance-scale" aria-hidden="true"></i>&nbsp;Units</a></li>
                                    </ul>
                            </li> -->

                           <!-- <li><a href="{{URL('admin/country')}}"><i class="fa fa-navicon" aria-hidden="true"></i> Business Countrys</a></li> -->

                            <li><a href="{{URL('admin/shop/category')}}"><i class="fa fa-navicon" aria-hidden="true"></i> Shop Category</a></li>

                            <li><a href="{{URL('admin/category')}}"><i class="fa fa-navicon" aria-hidden="true"></i> Business Category</a></li>

                            <li><a href="{{URL('admin/shop')}}"><i class="fa fa-navicon" aria-hidden="true"></i> Business Shops</a></li>
                            
                            <li><a><i class="fa fa-user" aria-hidden="true"></i>User Management<span class="fa fa-chevron-down"></span></a>

                                    <ul class="nav child_menu">
                                    @php $roles = Modules\Users\Entities\Role::whereNotIn('slug' , ['customer','seller','super-admin'])->get();  @endphp
                                        @if($roles->count()>0)
                                            @foreach($roles as $role)
                                             <li><a href="{{URL('admin/additional_user/'.$role->slug)}}">{{ucfirst($role->name)}}</a></li>
                                            @endforeach
                                        @endif
                                       
                                        <li><a href="{{URL('admin/driver')}}">Drivers</a></li>
                                        <li><a href="{{URL('admin/customer')}}">Customer</a></li>
                                        <li><a href="{{URL('admin/seller')}}">Seller</a></li>
                                    </ul>
                            </li>
                         
                            <li><a><i class="fa fa-product-hunt" aria-hidden="true"></i>Product Management<span class="fa fa-chevron-down"></span></a>

                                <ul class="nav child_menu">
                                    <li><a href="{{URL('admin/product?status='.$status="approved")}}">Approved Lists</a></li>
                                    <li><a href="{{URL('admin/product?status='.$status="pending")}}">Pending Lists</a></li>
                                
                                </ul>
                            </li>

                            <li><a><i class="fa fa-shopping-cart" aria-hidden="true"></i></i> Order Management<span class="fa fa-chevron-down"></span></a>

                                     <ul class="nav child_menu">
                                        <li><a href="{{URL('admin/order?status='.$status="all")}}">All Lists</a></li>
                                        <li><a href="{{URL('admin/order?status='.$status="ordered")}}">Ordered</a></li>
                                        <li><a href="{{URL('admin/order?status='.$status="accepted")}}">Accepted</a></li>
                                        <li><a href="{{URL('admin/order?status='.$status="shipped")}}">Shipped</a></li>
                                        <li><a href="{{URL('admin/order?status='.$status="delivered")}}">Delivered</a></li>
                                        <li><a href="{{URL('admin/order?status='.$status="cancelled")}}">Cancelled</a></li>
                                        <li><a href="{{URL('admin/order?status='.$status="rejected")}}">Rejected</a></li>
                                    </ul>
                        </li>
                            

                       
                            <li><a href="{{URL('admin/offer')}}"><i class="fa fa-gift" aria-hidden="true"></i></i> Offer Management</a></li>

                            <li><a href="{{URL('admin/offline_vouchers')}}"><i class="fa fa-gift" aria-hidden="true"></i></i> Redeemed Offline Vouchers</a></li>
                   

                        

                        <li><a href="{{URL('admin/enquiry')}}"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enquiry</a></li>

                        <li><a><i class="fa fa-shopping-basket" aria-hidden="true"></i> Quick Purchase<span class="fa fa-chevron-down"></span></a>

                                    <ul class="nav child_menu">
                                        <li><a href="{{URL('admin/buy_anything')}}">Buy Anything</a></li>
                                        <li><a href="{{URL('admin/del_anything')}}">Deliver Anything</a></li>
                                    </ul>
                        </li>

                        <li><a><i class="fa fa-bar-chart" aria-hidden="true"></i>Report<span class="fa fa-chevron-down"></span></a>

                                <ul class="nav child_menu">
                                    <li><a href="{{URL('admin/vendor_payment_report')}}">Vendor Payment</a></li>
                                    @if(Auth::guard('admin')->check())
                                    <li><a href="{{URL('admin/vendor_commission_report')}}">Vendor Commission</a></li>
                                    @endif
                                </ul>
                        </li>

                        <li>
                                    <a><i class="fa fa-list"></i>General<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{URL('admin/banner')}}"><i class="fa fa-picture-o" aria-hidden="true"></i>&nbsp;Banners</a></li>
                                        <li><a href="{{URL('admin/brand')}}"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;Brands</a></li>
                                        <li><a href="{{URL('admin/city')}}"><i class="fa fa-location-arrow" aria-hidden="true"></i>&nbsp;Cities</a></li>
                                        <li><a href="{{URL('admin/state')}}"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;States</a></li>
                                        <li><a href="{{URL('admin/unit')}}"><i class="fa fa-balance-scale" aria-hidden="true"></i>&nbsp;Units</a></li>
                                    </ul>
                            </li>
 
                            <li><a href="{{URL('admin/notification')}}"><i class="fa fa-bell" aria-hidden="true"></i> Bulk Notifications</a></li>
                          
                        {{--<li><a href="{{URL('admin/product')}}"><i class="fa fa-navicon" aria-hidden="true"></i> Products</a></li> --}}

                        {{--<li><a href="{{URL('admin/event')}}"><i class="fa fa-navicon" aria-hidden="true"></i> Event Management</a></li>--}}

                        {{--<li><a href="{{URL('admin/participants')}}"><i class="fa fa-video-camera" aria-hidden="true"></i> Participants Video</a></li>--}}

                            <li><a href="{{URL('admin/distance/settings')}}"><i class="fa fa-gear" aria-hidden="true"></i> Settings</a></li>

                        {{-- <li><a href="{{URL('admin/settings')}}"><i class="fa fa-gear" aria-hidden="true"></i> App Settings</a></li>--}}
<!-- 
                                <li><a href="{{URL('admin/plan')}}"><i class="fa fa-tasks" aria-hidden="true"></i> Plan Management</a></li> -->
                              <!--   <li><a href="categories.php"><i class="fa fa-th-list" aria-hidden="true"></i> Categories</a></li>
                                <li>
                                    <a><i class="fa fa-list" aria-hidden="true"></i> Sub-Categories<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="sub-categ-elecronics.php">Electronics</a>
                                        </li>
                                        <li><a href="sub-categ-rent.php">Rent</a>
                                        </li>
                                        <li><a href="sub-categ-sales.php">Sales</a>
                                        </li>
                                        <li><a href="sub-categ-services.php">Services</a>
                                        </li>
                                        <li><a href="sub-categ-wedding.php">Wedding</a>
                                        </li>
                                        <li><a href="sub-categ-jobs.php">Jobs</a>
                                        </li>
                                        <li><a href="sub-categ-shops.php">Local Shop</a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a><i class="fa fa-clone"></i>Plans and Sales<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="plans-free.php">Normal User</a></li>
                                        <li><a href="plans-executive.php">Executive Seller</a></li>
                                        <li><a href="plans-premium.php">Premium Seller</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="reported-product.php"><i class="fa fa-table"></i> Reported Products</a>
                                </li>
                                <li>
                                    <a href="blacklist-product.php"><i class="fa fa-stop" aria-hidden="true"></i>
                                         Blacklisted Products</a>
                                </li>
                                <li>
                                    <a href="catalog-product-mngmnt.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> Catalog & Product Management</a>
                                </li> 
                                <li>
                                    <a href="advertising-management.php"><i class="fa fa-file-image-o" aria-hidden="true"></i></i>Advertising Management</a>
                                </li>    

                                <li>
                                    <a><i class="fa fa-wrench" aria-hidden="true"></i> Plan Settings<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="plans-free-edit.php">Normal User</a></li>
                                        <li><a href="plans-executive-edit.php">Executive Seller</a></li>
                                        <li><a href="plans-premium-edit.php">Premium Seller</a></li>
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-cog"></i>Settings <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="settings-plan.php">Plans</a></li>
                                        <li><a href="other-settings.php">Other Settings</a></li>
                                    </ul>
                                </li> -->
                            </ul>
                            @else
                            <ul class="nav side-menu">
                               <!--  <li><a href="index.php"><i class="fa fa-file" aria-hidden="true"></i> Overview</a></li> -->
                            <li><a href="{{URL('seller/home')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                          
                                <li><a href="{{URL('seller/shop/products/'.Auth::guard('seller')->id())}}"><i class="fa fa-product-hunt" aria-hidden="true"></i></i>Product Details</a></li>

                                <li><a><i class="fa fa-shopping-cart" aria-hidden="true"></i></i> Order Management<span class="fa fa-chevron-down"></span></a>

                                    <ul class="nav child_menu">
                                        <li><a href="{{URL('seller/order?status='.$status="ordered")}}">New Orders</a></li>
                                        <li><a href="{{URL('seller/order?status='.$status="	accepted")}}">Approved Orders</a></li>
                                        <li><a href="{{URL('seller/order?status='.$status="rejected")}}">Rejected Orders</a></li>
                                    </ul>
                                </li>

                                
                                <li><a><i class="fa fa-bar-chart" aria-hidden="true"></i>Report<span class="fa fa-chevron-down"></span></a>

                                    <ul class="nav child_menu">
                                        <li><a href="{{URL('seller/vendor_payment_report')}}">Vendor Payment</a></li>

                                        <li><a href="{{URL('seller/vendor_report')}}">Vendor Commision</a></li>
                                    </ul>
                                </li>
                           
                            @endif
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <!-- <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a> -->
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <!-- <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a> -->
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{URL('/admin/logout')}}">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    @if(Auth::guard('admin')->check())
                                    <!-- <img src="{{URL('public/admin/images/logo.png')}}" alt=""> -->Admin
                                    @elseif(Auth::guard('seller')->check())
                                    <!-- <img src="{{URL('public/admin/images/logo.png')}}" alt=""> -->Seller
                                    @else
                                    <!-- <img src="{{URL('public/admin/images/logo.png')}}" alt=""> -->Admin
                                    @endif
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <!-- <li><a href="javascript:;"> Profile</a></li> -->
                                    <li>
                                    @if(Auth::guard('admin')->check())
                                        <a href="{{URL('/admin/changepassword')}}">
                                            <span><i class="fa fa-key pull-right"></i>Change password</span>
                                        </a>
                                    @else
                                        <a href="{{URL('/seller/changepassword')}}">
                                            <span><i class="fa fa-key pull-right"></i>Change password</span>
                                        </a>
                                    @endif
                                    </li> 
                                    <li><a href="{{URL('/admin/logout')}}"><span><i class="fa fa-sign-out pull-right"></i>Log Out</span></a></li>
                                </ul>
                            </li>
                            
                            @if(Auth::guard('admin')->check())
                               <li role="presentation" class="dropdown notification">
                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa fa-bell"></i>
                                        <span class="badge bg-red notycount"></span>
                                    </a>
                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu" >
                                    </ul>
                                </li>
                            @endif
                            
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
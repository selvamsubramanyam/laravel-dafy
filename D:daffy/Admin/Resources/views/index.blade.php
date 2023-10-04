<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="image/favicon.png" type="image/gif" sizes="32x32">
        <title>Dafy</title>
        <link href="{{URL('assets/plugins/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{URL('assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">    
    <!-- Custom Theme Style -->
    <!-- <link href="assets/src/css/custom.css" rel="stylesheet"> -->

    <!-- My Theme Style -->
    <!-- <link href="assets/css/my_style.css" rel="stylesheet">
  -->
        <link rel="stylesheet" href="{{URL('public/admin/css/dev.css')}}">       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body class="login">
        <body class="login">
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="menu-toggler sidebar-toggler"></div><!-- END SIDEBAR TOGGLER BUTTON -->
            <!-- BEGIN LOGO -->
            <div class="logo">
                <a href=""><img alt="" src="{{URL('public/admin/images/dafy_logo.png')}}" width="200" height="100"></a>
                </div><!-- END LOGO -->
                <p class="text-center mb30 ng-binding">Welcome to Dafy. Please sign in to your account</p>
                <!-- BEGIN LOGIN -->
                <div class="content ng-scope" ng-controller="loginCtrl">
                    <!-- BEGIN LOGIN FORM -->
                    <form class="login-form ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="loginForm" novalidate="" action="{{Request::route()->getPrefix() == '/admin' ? URL('admin/login') : URL('seller/login')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group">
                            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                            <label class="control-label visible-ie8 visible-ie9">Username</label> <input autocomplete="off" class="form-control form-control-solid placeholder-no-fix ng-pristine ng-untouched ng-valid-email ng-invalid ng-invalid-required" type="email" required="" name="email" id="login_email" placeholder="Email Address" >
                            <!-- ngIf: errors.email.length -->
                        </div>
                        <div class="form-group">
                            <label class="control-label visible-ie8 visible-ie9">Password</label> <input autocomplete="off" class="form-control form-control-solid placeholder-no-fix ng-pristine ng-untouched ng-invalid ng-invalid-required" placeholder="Password" name="password" required="" type="password" id="login_password" >
                            <!-- ngIf: errors.password.length -->
                        </div>
                        <!-- ngIf: errors.login_attempt.length -->
                        <div class="form-actions">
                            <button  class="btn btn-primary btn-block submit" href="branch-manager-dashboard.php" type="submit"><span>Sign in</span></button>


                        </div>
     
                        <div class="form-actions">                            
                        </div>
                        </form><!-- END LOGIN FORM -->
                        <!-- BEGIN FORGOT PASSWORD FORM -->
                        <form action="index.html" class="forget-form ng-pristine ng-valid" method="post">
                            <div class="form-title">
                                <!-- <span class="form-title">Forget Password ?</span> -->

                                  @if($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif

                              

                                    @if($errors->any())
                                    <div class="has-danger" style="color: red">
                                        <strong class="">{{$errors->first()}}</strong>
                                    </div>
                                @endif
                            </div>                            
                            
                            </form><!-- END FORGOT PASSWORD FORM -->



                                              
                               
                       

                        </div>
                        <div class="copyright text-center">
                            Copyright © Dafy 2021. All rights reserved
                            </div>
                        </body>
                        <script type="text/javascript" src="js/jquery.min.js"></script>
                        <script type="text/javascript" src="js/popper.min.js"></script>
                        <script type="text/javascript" src="js/bootstrap.min.js"></script>
                        <script type="text/javascript" src="js/Chart.min.js"></script>
                        <script type="text/javascript" src="js/DB-custom.js"></script>
                    </body>
                </html>

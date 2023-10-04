@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<style>

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color:  #d2d9dd;
            border-color: #367fa9;
            padding: 1px 10px;
            color: #151313 !important;
        }
        .form-style-5{
            max-width: 700px;
            padding: 10px 20px;
            background: #f4f7f8;
            margin: 10px auto;
            padding: 20px;
            background: #f4f7f8;
            border-radius: 8px;
            font-family: Georgia, "Times New Roman", Times, serif;
        }
        .form-style-5 fieldset{
            border: none;
        }
        .form-style-5 legend {
            font-size: 1.4em;
            margin-bottom: 10px;
        }
        .form-style-5 label {
            display: block;
            margin-bottom: 8px;
        }
        .form-style-5 input[type="text"],
        .form-style-5 input[type="date"],
        .form-style-5 input[type="datetime"],
        .form-style-5 input[type="email"],
        .form-style-5 input[type="password"],
        .form-style-5 input[type="number"],
        .form-style-5 input[type="search"],
        .form-style-5 input[type="time"],
        .form-style-5 input[type="url"],
        .form-style-5 input[type="file"],
        .form-style-5 textarea,
        .form-style-5 select {
            font-family: Georgia, "Times New Roman", Times, serif;
            background: rgba(255,255,255,.1);
            border: none;
            border-radius: 4px;
            font-size: 16px;
            margin: 0;
            outline: 0;
            padding: 7px;
            width: 100%;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            background-color: #e8eeef;
            color:#8a97a0;
            -webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
            box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
            margin-bottom: 30px;

        }
        .form-style-5 input[type="text"]:focus,
        .form-style-5 input[type="date"]:focus,
        .form-style-5 input[type="datetime"]:focus,
        .form-style-5 input[type="email"]:focus,
        .form-style-5 input[type="number"]:focus,
        .form-style-5 input[type="search"]:focus,
        .form-style-5 input[type="time"]:focus,
        .form-style-5 input[type="checkbox"]:focus,
        .form-style-5 input[type="url"]:focus,
        .form-style-5 textarea:focus,
        .form-style-5 select:focus{
            background: #d2d9dd;
        }
        .form-style-5 select{
            -webkit-appearance: menulist-button;
            height:35px;
        }
        .form-style-5 .number {
            background: #1e88e5;
            color: #fff;
            height: 30px;
            width: 30px;
            display: inline-block;
            font-size: 0.8em;
            margin-right: 4px;
            line-height: 30px;
            text-align: center;
            text-shadow: 0 1px 0 rgba(255,255,255,0.2);
            border-radius: 15px 15px 15px 0px;
        }

        .form-style-5 input[type="submit"],
        .form-style-5 input[type="button"],
        .btn1
        {
            position: relative;
            display: block;
            padding: 19px 39px 18px 39px;
            color: #FFF;
            margin: 0 auto;
            background: #1e88e5;
            font-size: 18px;
            text-align: center;
            font-style: normal;
            width: 100%;
            border: 1px solid #1e88e5;
            border-width: 1px 1px 3px;
            margin-bottom: 10px;
        }
        .form-style-5 input[type="submit"]:hover,
        .form-style-5 input[type="button"]:hover
        {
            background: #109177;
        }
        .er{
            color:red;
        }
#exTab1 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

#exTab2 h3 {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

/* remove border radius for the tab */

#exTab1 .nav-pills > li > a {
  border-radius: 0;
}

/* change border radius for the tab , apply corners on top*/

#exTab3 .nav-pills > li > a {
  border-radius: 4px 4px 0 0 ;
}

#exTab3 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}
</style>
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Settings</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if(session()->has('danger'))
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('danger') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                          </div><br />
                    @endif
                </div>

                <div class="x_content">
                    <div class="row">
                    <div id="exTab2" class="container">	
                        <ul class="nav nav-tabs" id="myTab">
                                    <li class="@if(old('tab')) {{ old('tab') == 'general' ? 'active' : '' }} @else {{ 'active' }} @endif ">
                                    <a  href="#1" data-toggle="tab"><strong>General Settings</strong></a>
                                    </li>
                                    <li class="{{ old('tab') == 'appversion' ? 'active' : '' }}">
                                    <a href="#2" data-toggle="tab"><strong>App Settings</strong></a>
                                    </li>
                                    <li class="{{ old('tab') == 'referal' ? 'active' : '' }}">
                                    <a href="#3" data-toggle="tab"><strong>Referral Settings</strong></a>
                                    </li>
                                </ul>

                                    <div class="tab-content ">
                                    <div class="tab-pane @if(old('tab')) {{ old('tab') == 'general' ? 'active' : '' }} @else {{ 'active' }} @endif" id="1">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-12"><br>

                                                        <!--begin::Form-->
                                                        <form method="post" action="{{URL('admin/other/settings')}}"  enctype='multipart/form-data'>

                                                            {{csrf_field()}}
                                                            <input type="hidden" name="tab" value="general">
                                                            <div class="">
                                                                <div class="form-group row form-space">
                                                                    <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                                    Minimum distance :</label>
                                                                    <div class="col-xs-12 col-lg-8 ">
                                                                        <input type="text" class="form-control form-border" data-placement="right" data-toggle="tooltip" title="Minimum distance for listing shops!" name="min_distance" value="{{$min_distance->value ?? ''}}" placeholder="min distance for shop listing" required="required">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="">
                                                                <div class="form-group row form-space">
                                                                    <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                                    Maximum distance:</label>
                                                                    <div class="col-xs-12 col-lg-8 ">
                                                                        <input type="text" class="form-control form-border" data-placement="right" data-toggle="tooltip" title="Maximum distance for delivering products!" name="max_delivery_distance" value="{{$max_delivery_distance->value ?? ''}}" placeholder="Maximum distance for delivering products" required="required">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="">
                                                                <div class="form-group row form-space">
                                                                    <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                                    Delivery Charge Below {{ $delivery_charge_below->max_order_price }} Rs:</label>
                                                                    <div class="col-xs-12 col-lg-8 ">
                                                                        <input type="text" class="form-control form-border" data-placement="right" data-toggle="tooltip" title="Delivery Charge" name="delivery_charge_below" value="{{$delivery_charge_below->price ?? ''}}" placeholder="Delivery Charge" required>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="">
                                                                <div class="form-group row form-space">
                                                                    <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                                    Delivery Charge Between {{ $delivery_charge_between->min_order_price }} Rs - {{ $delivery_charge_between->max_order_price }} Rs:</label>
                                                                    <div class="col-xs-12 col-lg-8 ">
                                                                        <input type="text" class="form-control form-border" data-placement="right" data-toggle="tooltip" title="Delivery Charge" name="delivery_charge_between" value="{{$delivery_charge_between->price ?? ''}}" placeholder="Delivery Charge" required>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="">
                                                                <div class="form-group row form-space">
                                                                    <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                                    Delivery Charge Above {{ $delivery_charge_above->max_order_price }} Rs:</label>
                                                                    <div class="col-xs-12 col-lg-8 ">
                                                                        <input type="text" class="form-control form-border" data-placement="right" data-toggle="tooltip" title="Delivery Charge" name="delivery_charge_above" value="{{$delivery_charge_above->price ?? ''}}" placeholder="Delivery Charge" required>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- <div class="">
                                                                <div class="form-group row form-space">
                                                                    <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                                    Max Delivery Accepted Time:</label>
                                                                    <div class="col-xs-12 col-lg-8 ">
                                                                        <input type="text" class="form-control form-border" data-placement="right" data-toggle="tooltip" title="Maximum Delivery Accepted Time!" name="accepted_delivery_time" value="{{$accepted_delivery_time->value ?? ''}}" placeholder="Maximum Delivery Accepted Time" required="required">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <div class="text-right">
                                                                <!-- <button type="reset" class="btn btn-default btn-sm">Cancel</button> -->
                                                                <button type="Submit" class="btn btn-primary-blue btn-sm">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                        </div>
                                    <div class="tab-pane {{ old('tab') == 'appversion' ? 'active' : '' }}" id="2">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12"><br>
                                        <!--begin::Form-->
                                        <form method="post" action="{{URL('admin/settings/appversion')}}"  enctype='multipart/form-data'>

                                        {{csrf_field()}}
                                        <input type="hidden" name="tab" value="appversion">
                                        <div class="">
                                            <div class="form-group row form-space">
                                                <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                Android Version:</label>
                                                <div class="col-xs-12 col-lg-8 ">
                                                    <input type="text" class="form-control form-border" name="android" placeholder="Android Version" data-placement="right" data-toggle="tooltip" title="Android current version" value="{{$android->value ?? ''}}" required="required">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row form-space">
                                            <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Android Min Version:</label>
                                            <div class="col-xs-12 col-lg-8 ">
                                                <input type="text" class="form-control form-border" name="androidmin"  placeholder="Android minimun Version" data-placement="right" data-toggle="tooltip" title="Android min version" value="{{$android->min_value ?? ''}}" required="required">
                                              
                                            </div>
                                        </div>

                                        <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">IOS Version:</label>
                                            <div class="col-xs-12 col-lg-8">
                                                <input type="text"  class="form-control form-border"  name="ios" placeholder="IOS Version" data-placement="right" data-toggle="tooltip" title="Ios current version" value="{{$ios->value ?? ''}}" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">IOS Min Version:</label>
                                            <div class="col-xs-12 col-lg-8">
                                                <input type="text"  class="form-control form-border"  name="iosmin" placeholder="IOS minimum Version" data-placement="right" data-toggle="tooltip" title="Ios minimum version" value="{{$ios->min_value ?? ''}}" required="required" >
                                            </div>
                                        </div>



                                            <div class="text-right">
                                                <button type="reset" onclick="window.location='{{ URL::previous() }}'" class="btn btn-default btn-sm">Cancel</button>
                                                <button type="Submit" class="btn btn-primary-blue btn-sm">Submit</button>
                                            </div>

                                    </form>

                                    </div>
                                    </div>


                                        </div>
                                <div class="tab-pane {{ old('tab') == 'referal' ? 'active' : '' }}" id="3">
                                <div class="row">
                                        <div class="col-md-6 col-xs-12"><br>
                                        <!--begin::Form-->
                                        <form method="post" action="{{URL('admin/settings/referal')}}"  enctype='multipart/form-data'>

                                        {{csrf_field()}}
                                        <input type="hidden" name="tab" value="referal">
                                        <div class="">
                                            <div class="form-group row form-space">
                                                <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                Referral Earning Registration:</label>
                                                <div class="col-xs-12 col-lg-8 ">
                                                    <input type="text" class="form-control form-border" name="ref_reg" placeholder="Referral Earning Registration" data-placement="right" data-toggle="tooltip" title="Referral Earning when registration" value="{{$referal_reg->value ?? ''}}" required="required">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row form-space">
                                            <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Referral Earning Purchase:</label>
                                            <div class="col-xs-12 col-lg-8 ">
                                                <input type="text" class="form-control form-border" name="ref_purchase"  placeholder="Referral Earning Purchase" data-placement="right" data-toggle="tooltip" title="Referral Earning when purchase" value="{{$referal_purchase->value ?? ''}}" required="required">
                                              
                                            </div>
                                        </div>



                                            <div class="text-right">
                                                <button type="reset" onclick="window.location='{{ URL::previous() }}'" class="btn btn-default btn-sm">Cancel</button>
                                                <button type="Submit" class="btn btn-primary-blue btn-sm">Submit</button>
                                            </div>

                                    </form>

                                    </div>
                                    </div>


                                        </div>
                                    </div>
                    </div>

<hr></hr>
                   
                    </div>

                </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>

    
</div>


</div>
</div>
</div>

</div>
<!-- /page content -->
</div>
@include('admin::layouts.includes.footer')

<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   

});
</script>
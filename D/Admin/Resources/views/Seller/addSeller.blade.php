@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />
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
        .form-style-5 input[type="file"] {
        display: block;
        }
        .er{
            color:red;
        }
    </style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Seller</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('message') }}
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
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/seller/store')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}
                            

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name"  value="{{ old('name') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('name') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Business Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('business_name') is-invalid @enderror" id="business_name" name="business_name" placeholder="Enter business name"  value="{{ old('business_name') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('business_name') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            E-mail*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="email" class="form-control form-border  @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email"  value="{{ old('email') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('email') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Business E-mail*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="email" class="form-control form-border  @error('business_email') is-invalid @enderror" id="business_email" name="business_email" placeholder="Enter business email"  value="{{ old('business_email') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('business_email') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                              

                                <div class="form-group row form-space ">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Image:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type='file' id="imgInp"  accept="image/*" class="form-control form-border @error('profile_image') is-invalid @enderror" name="profile_image"/>
                                        <img id="thump" src="#" alt="your image" height="75px" width="75px"/>
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('profile_image') <span class="er">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div> 

                        

                                <div class="form-group row form-space ">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Business Image:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type='file' id="imgbus"  accept="image/*" class="form-control form-border @error('business_image') is-invalid @enderror" name="business_image"/>
                                        <img id="business_img" src="#" alt="your image" height="75px" width="75px"/>
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('business_image') <span class="er">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div> 

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Mobile Number*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="tel" class="form-control form-border  @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="Enter mobile number"  value="{{ old('mobile') }}" pattern="[0-9]{10}" required="required">
                                            <small>Format: 10 digit valid mobile number</small>
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('mobile') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Wallet Money:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('wallet') is-invalid @enderror" id="wallet" name="wallet" placeholder="Enter wallet money"  value="{{ old('wallet')  }}">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('wallet') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div id="categories" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select categories*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            
                                            <select name="categories[]" id="my-select"  class="form-control select2 @error('categories') is-invalid @enderror" multiple>     
                                                @foreach($categories as $category) 
                                                    <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}}>{{$category->name}}</option>
                                                 @endforeach
                                            </select>

                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('categories') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Latitude*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('latitude') is-invalid @enderror" id="lat" name="latitude" placeholder="Enter latitude"  value="{{ old('latitude') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('latitude') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Longitude*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('longitude') is-invalid @enderror" id="lng" name="longitude" placeholder="Enter longitude"  value="{{ old('longitude') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('longitude') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                {{--<div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Location*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('location') is-invalid @enderror" id="location" name="location" placeholder="Enter location"  value="{{ old('location') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('location') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>--}}


                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Street*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('street') is-invalid @enderror" id="street" name="street" placeholder="Enter street"  value="{{ old('street') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('street') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Area*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('area') is-invalid @enderror" id="area" name="area" placeholder="Enter area"  value="{{ old('area') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('area') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            City*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('city') is-invalid @enderror" id="city" name="city" placeholder="Enter city"  value="{{ old('city') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('city') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                            
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Pincode*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="number" class="form-control form-border  @error('pincode') is-invalid @enderror" id="pincode" name="pincode" placeholder="Enter pincode"  value="{{ old('pincode') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('pincode') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div id="state" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select state:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control @error('state_id') is-invalid @enderror" id="state_id" name="state_id" style="width: 100%;" required>
                                                <option value="">--Select State--</option>
                                                @forelse($states as $state)
                                                    <option value="{{$state->id}}" {{($state->id == old('state_id')) ? 'selected="selected"': ''}}>{{$state->name}}</option>
                                                @empty
                                                    <option value="" disabled>No states available</option>
                                                @endforelse
                                            </select>
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('state_id') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                               
                                
                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1" checked><span>Active</span> 
                                            <input type="radio" id="status-2" name="status" value="0"><span>Inactive</span> 
                                        </div>          
                                    </div>
                                </div>




                                <div class="text-right">
                                    <button type="reset" onclick="window.location='{{ URL::previous() }}'" class="btn btn-default btn-sm">Cancel</button>
                                    <button type="Submit" class="btn btn-primary-blue btn-sm">Submit</button>
                                </div>

                        </div>
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
<!-- <script type="text/javascript">

    $(document).ready(function() {

      $(".btn-success").click(function(){ 
        alert('ss');
          var html = $(".clone").html();
          $(".increment").after(html);
      });

      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".control-group").remove();
      });

    });

</script> -->
    <script>
        $('#my-select').multiSelect();
    </script>
    <script>

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#thump').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#imgInp").change(function() {
  readURL(this);
});

function readBusURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#business_img').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#imgbus").change(function() {
  readBusURL(this);
});


function matchStart (term, text) {
  if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
    return true;
  }
 
  return false;
}
 
$.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
  $("select").select2({
    matcher: oldMatcher(matchStart)
  })
});



    </script>
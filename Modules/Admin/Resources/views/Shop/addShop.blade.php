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
    </style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Shop</h2>
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
                            <form method="post" action="{{URL('admin/shop/store')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}
                                
                                <div class="">
                                    <div id="seller" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Select Seller:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2" id="seller_id" name="seller_id" style="width: 100%;" required>
                                                <option value="" selected>--Select Seller--</option>
                                                @foreach($sellers as $seller)
                                                    <option value="{{$seller->id}}" >{{$seller->business_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="name" placeholder="Shop Name" value="{{old('name')}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space ">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Shop Icon Image*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type='file' id="imgInp" accept="image/*" class="form-control form-border @error('thump_image') is-invalid @enderror" name="thump_image" required/>
                                        <img id="thump" src="#" alt="your image" height="75px" width="75px"/>
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('thump_image') <span class="er">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-md-12" id="preview_div">

                                    </div>
                                </div>

                                <div class="form-group row form-space clone">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Image*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input required type="file" class="form-control" name="pic[]" id="files" placeholder="address" multiple accept="image/*">
                                    </div>
                                </div>

                                <!-- <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Latitude*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="latitude" placeholder="Shop Latitude" value="{{old('latitude')}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Longitude*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="longitude" placeholder="Shop Longitude" value="{{old('longitude')}}" required="required">
                                        </div>
                                    </div>
                                </div> -->

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Location*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="location" placeholder="Shop Location" value="{{old('location')}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Address:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Shop Address" name="address">{{nl2br(old('address'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Pincode:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="pincode" placeholder="Shop Pincode" value="{{old('pincode')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div id="city" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select shop cities:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2" id="city" name="city" style="width: 100%;">
                                                <option value="">--Select City--</option>
                                                @foreach($cities as $city)
                                                    <option value="{{$city->id}}" {{($city->id == old('city')) ? 'selected="selected"': ''}}>{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Contact:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="contact" placeholder="Shop Contact" value="{{old('contact')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Website:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="website" placeholder="Shop Website" value="{{old('website')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Email*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="email" class="form-control form-border" name="email" placeholder="Shop Email" value="{{old('email')}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Password*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="password" class="form-control form-border" name="password" placeholder="Password" value="{{old('password')}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Confirm Password*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="password" class="form-control form-border" name="password_confirmation" value="{{old('password_confirmation')}}" placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Type:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="type-1" name="type" value="0" checked {{( old('type')=='0') ? 'checked': ''}}><span>General</span> 
                                            <input type="radio" id="type-2" name="type" value="1" {{( old('type')=='1') ? 'checked': ''}}><span>Premium</span>
                                        </div>          
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Instore Shop:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="type-1" name="instore" value="0" checked {{( old('instore')=='0') ? 'checked': ''}}><span>No</span> 
                                            <input type="radio" id="type-2" name="instore" value="1" {{( old('instore')=='1') ? 'checked': ''}}><span>Yes</span>
                                        </div>          
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Instore Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Instore Description" name="instore_description">{{nl2br(old('instore_description'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Services:<span style="color: red">&nbsp;&nbsp;[Please add services with comma seperated]</span></label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Available Services" name="services">{{nl2br(old('services'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Shop Description" name="description">{{nl2br(old('description'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div id="isBranch" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Is it main branch?:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="radio" id="main-1" name="main" value="1" checked {{( old('main')=='1') ? 'checked': ''}}><span>Yes</span> 
                                            <input type="radio" id="main-2" name="main" value="0" {{( old('main')=='0') ? 'checked': ''}}><span>No</span><span style="color: red">&nbsp;&nbsp;[Checked <i>'Yes'</i>  If you have not any other branch]</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space" id="branch" style="display: none">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Select Main Branch:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                         <select name="main_shop" class="form-control">
                                          <option value="">Select</option>
                                            @foreach($shops as $shop)
                                             <option value="{{$shop->id}}" {{($shop->id == old('main_shop')) ? 'selected="selected"': ''}}>{{$shop->name}}</option>

                                            @endforeach
                                         </select>
                                            <!-- <span class="form-text text-muted">Please enter your full
                                                name</span> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div id="shop_categories" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select shop categories*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2" multiple="multiple" id="shop_category" name="shop_category[]" data-placeholder="Select categories" style="width: 100%;" required>
                                                @foreach($category_shops as $category_shop)
                                                   <option value="{{$category_shop->id}}">{{$category_shop->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="">
                                    <div id="categories" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select categories*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2" multiple="multiple" id="my-select" name="category[]" data-placeholder="Select categories" style="width: 100%;" required>
                                                <!-- @foreach($categories as $category) -->
                                                   
                                                <!-- @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label ">Category View Type:</label>
                                    <div class="col-xs-12 col-lg-8 view_type">
                                    </div>
                                </div>


                               


                                <!-- <div class="">
                                    <div class="form-group"  id="values">
                                        <label>Possible Values</label>
                                        <span style="color: red">&nbsp;&nbsp;[Press <i>ENTER</i>  Key after adding each value]</span>
                                        <br>
                                        <span id="features_values" class="label label-primary" style="display: none">
                                            
                                        </span>
                                        <input type="text" name="values" id="values" placeholder="Add Possible Values *" class="form-control bootstrap-tagsinput" value="" data-role="tagsinput">

                                    </div>
                                </div> -->

                                


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
<input type="hidden" value="['xsx','xssxs']" id="vi">

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
        $('#seller_id').select2();
    </script>
    <script>

        $("#files").on("change",function(e) {
            $(".imageThumb").remove();
            files = e.target.files ,
                filesLength = files.length ;
            var count = filesLength;
            invalid = "";
            for(i = 0; i < filesLength ; i++) {
                f = files[i];
                fileReader = new FileReader();
                fileReader.file = f;
                fileReader.invalid = i;
                fileReader.onload = (function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.name = e.target.file.name;
                    //Validate the File Height and Width.
                    image.onload = function (e) {
                        // var height = this.height;
                        // var width = this.width;
                        // if (width != 850 && height != 995) {
                        //     alert('Image dimension should be  850 x 995')
                        //     invalid += " '"+e.target.name+"' ";
                        //     $(".imageThumb").remove();
                        // }else{
                            $("<img></img>",{
                                class : "imageThumb",
                                src : e.target.src,
                                title : e.target.name,
                                width : 100,
                                height : 100,
                            }).appendTo("#preview_div");
                        // }
                        if(!--count){
                            if(invalid != ""){
                                alert("Invalid images found : " + invalid);
                                $("#files").val("");
                                $(".imageThumb").remove();
                            }
                        }
                    };
                });
                fileReader.readAsDataURL(f);
            }
        });

        function validation() {
            var cats=$('#my-select').val();
            if(cats=='')
            {
                $.notify("Choose the any category","error");
                setTimeout(function() {
                    test();
                }, 1000)
                return false;
            }
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(100);
                };
                $('#pic').show();
                reader.readAsDataURL(input.files[0]);
            }
        }


        var _URL = window.URL || window.webkitURL;

        $("#file").change(function(e) {

            var image, file;

            if ((file = this.files[0])) {

                image = new Image();

                image.onload = function() {

                    if ((this.width != 262 && this.height != 262)) {
                        alert("Image Dimension should be 262 X 262");
                        $("#file").val('');
                        $('#pic').hide();
                    }
                };

                image.src = _URL.createObjectURL(file);

            }

        });

        $('body').on('change','#main-2',function() {
            $('#branch').show();
        });

        $('body').on('change','#main-1',function() {
            $('#branch').hide();
        });

        $(".btn-success").click(function(){ 
          var html = $(".clone").html();
          $(".increment").after(html);
      });

      $('#seller_id').on('change select click', function (e) {
         e.preventDefault();
        
       //  var base_url="http://localhost/dafy";
        
        
         $('#my-select').html('');
         if( $('#seller_id').val()!='') { 

           var seller_id = $('#seller_id').val();
           
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

             $.ajax({
                url: base_url+'/admin/shop/getSellerCat',
                type: 'post',
                dataType: 'json',
                data:{seller_id:seller_id, _token: '{{csrf_token()}}' },
                success: function(response){
                    var len = 0;
                    if(response['data'] != null){
                    len = response['data'].length;
                    }

                    if(len > 0){
                      var option ='';
                        // Read data and create <option >
                        for(var i=0; i<len; i++){

                            var id = response['data'][i].id;
                            var name = response['data'][i].name;

                            option += "<option value='"+id+"' data-id='"+name+"'>"+name+"</option>"; 

                            
                        }
                       
                        $("#my-select").html(option); 
                    }else{
                        $("#my-select").html('');
                    }
         
           }

        
   
             
         
      });
    }
    else{
      $("#my-select").html('');
    }
    });

    $('#my-select').change(function () {
//         var i =1;
//         var option='';
//         $('#my-select option:selected').each(function() {
//             var option = "<input type="radio" id="status-1" name="status" value="" checked><span>Active</span>";
    
// });
//         var sel = $('#my-select').val();
        
      
//         $('.view_type').append(option);

$( '.view_type' ).empty();

    var radio_name=$("#my-select option:selected").text();
    var a=$("#my-select option:selected").val();
    var option ='';
   
    var a = [];
    a[0] = ['Small list','Big list','Grid']; 


    
    $('#my-select option:selected').each(function() {
    $(' <div class="row"><label class="col-xs-12 col-lg-6 col-form-label">'+$(this).text()+'</label><div class="col-xs-12 col-lg-8" >').appendTo('.view_type');
    $("#sp").append(' ');
    $(".view_type").after(' ');
    for(i=0;i < 3;i++)
    {
       
        $('<input type="radio" class="view form-group" name="'+$(this).val()+'" value="'+i+'" required/>&nbsp;<span>'+a[0][i]+'</span>&nbsp;&nbsp;</div>').appendTo('.view_type');
    }
        

    });
    $( '.view_type' ).append('<br>');
});


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
    </script>
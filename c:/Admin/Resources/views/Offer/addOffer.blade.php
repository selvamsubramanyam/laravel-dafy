@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="{{URL('public/admin/css/bootstrap-multiselect.css')}}" type="text/css"/> -->
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
    </style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Offer</h2>
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
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/offer/store')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}
                                
                                <div class="">
                                    <div id="shops" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select shop*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            
                                            <select name="shops" id="my-select"  class="form-control shop select2 @error('shops') is-invalid @enderror" required>    
                                            <option value="">--Select shop--</option> 
                                               
                                            </select>

                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('shops') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div id="products" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select products:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            
                                            <select name="products[]" id="my-select1"  class="form-control product select2 @error('products') is-invalid @enderror" multiple>     
                                          
                                            </select>

                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('products') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Title*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="title" placeholder="Offer title" value="{{old('title')}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Offer Description" name="description">{{nl2br(old('description'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">About:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="About Offer" name="about">{{nl2br(old('about'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="preview_div">

                                    </div>
                                </div>

                                <div class="form-group row form-space clone">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Image:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type="file" class="form-control" name="pic" id="files" placeholder="address"  accept="image/*">
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Discount Type*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="discount_type-1" name="discount_type" value="1" {{old('discount_type')==1 ? 'checked' : '' }} required><span>Percentage</span> 
                                            <input type="radio" id="discount_type-2" name="discount_type" value="2" {{old('discount_type')==2 ? 'checked' : '' }} required><span>Flat</span> 
                                        </div>          
                                    </div>
                                </div>

                                <div class="sho d-none">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                        Discount value*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('discount_value') is-invalid @enderror" name="discount_value" id="discount_value" placeholder="Discount value"  value="{{ old('discount_value') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('discount_value') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="max" style="display:none">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                        Max Discount value:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" id="max_disc" name="max_discount_value" placeholder="Maximum discount value" value="{{old('max_discount_value')}}">
                                        </div>
                                    </div>
                                </div>


                        {{--        <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                        Min amount price:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="min_amount_price" placeholder="min amount price" value="{{old('min_amount_price')}}">
                                        </div>
                                    </div>
                                </div> --}}

                              

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Valid from*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                     <input type="datetime-local" class="form-control form-border" placeholder="Valid From" name="validfrom" value="{{old('validfrom' , date('Y-m-d'))}}" required="required">

                                        </div>
                                    </div>

                                      <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Valid To*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                     <input type="datetime-local" class="form-control form-border" placeholder="Valid From" name="validto" value="{{old('validto' , date('Y-m-d'))}}" required="required">

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

<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>



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
     
    $(document).ready(function() {
        $('.product').select2();
        $('.shop').select2();
    });
    
        

       
    </script>
     <script type="text/javascript">
     $(function () {
    var val = $('input[name="discount_type"]:checked').val();
    if(val==2)
    {
        $('#max_disc').val('');
        $(".max").hide();
    }else{
        $(".max").show();
    }
});
$(document).ready(function() {
    $("input[name=discount_type]").on( "change", function() {

         var test = $(this).val();
         if(test==2)
         {
            $('#max_disc').val('');
            $(".max").hide();
         }else{
            $(".max").show();
         }
    } );
});

$(document).ready(function(){

  $( ".shop" ).select2({
    ajax: { 
      url: "{{route('offer.shop.search')}}",
      type: "get",
      dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }

  });

});
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
                $.notify("Choose the any shops","error");
                setTimeout(function() {
                    test();
                }, 1000)
                return false;
            }
        }
        function validation() {
            var cats=$('#my-select1').val();
            if(cats=='')
            {
                $.notify("Choose the any shops","error");
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


      $('.shop').on('change select click keypress keyup', function (e) {
          
         e.preventDefault();
        
       //  var base_url="http://localhost/dafy";
        
        
       $('#my-select1').html('');
         if( $('#my-select').val()!='') { 

           var shop_id = $('#my-select').val();
           
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

             $.ajax({ 
                url: base_url+'/admin/offer/getproducts',
                type: 'post',
                dataType: 'json',
                data:{shop_id:shop_id, _token: '{{csrf_token()}}' },
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
                       
                        $("#my-select1").html(option); 
                    }else{
                        $("#my-select1").html('');
                    }
         
           }

        
   
             
         
      });
    }
    else{
      $("#my-select1").html('');
    }
    });
     
    

    </script>
    
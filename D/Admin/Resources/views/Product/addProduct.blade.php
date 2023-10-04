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
        .imageThumb {
        height: 80px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
        width:100px;
        }
        .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
        }
        .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
        width:100px;
        }
        .remove:hover {
        background: white;
        color: black;
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
                    <h2>Add Product</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @error('images.*')
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{ $message  }}</li>
                            </ul>
                          </div><br />
                    @enderror
                </div>
        @if(Auth::guard('admin')->check())
            

                <div class="container">
                    <div class="card bg-light mt-3">
                        <div class="card-header">
                            Import Product List
                        </div>
                        <div class="card-body">
                       
                            <form action="{{ route('product_import') }}" method="POST" enctype="multipart/form-data">
                             @csrf
                            <input type="file" name="product_file" class="form-control" style="width:600px;">
                            <input type="hidden" name="shop_id"  value="{{$shop->id}}">
                            <br>
                            <button class="btn-sm btn-success"> <i class="fa fa-upload"></i> Import Product Data</button> <a class="btn-sm btn-danger" href="{{route('product.untracked',$shop->id)}}" role="button"><i class="fa fa-list"></i> Untracked list</a>
                            </form>
                          
                        </div><br>
                        <div class="card-footer">
                            Or Product Create Individualy
                           
                        </div>
                        <hr>
                    </div>
                </div>
        @endif
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/product/store')}}"  enctype='multipart/form-data' id="myFormId">

                                {{csrf_field()}}
                                <!-- <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Code:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="code" placeholder="Shop Code" required="required">
                                        </div>
                                    </div>
                                </div> -->
                                <input type="hidden" name="shop_id" id="shop_id" value="{{$shop->id}}">
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter product name"  value="{{ old('name') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('name') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Sku:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('sku') is-invalid @enderror" name="sku" id="sku" placeholder="Enter product sku"  value="{{ old('sku') }}">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('sku') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>


                                <div class="form-group row form-space ">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Thump Image*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type='file' id="imgInp" accept="image/*" class="form-control form-border @error('thump_image') is-invalid @enderror" name="thump_image"/>
                                        <img id="thump" src="#" alt="your image" height="75px" width="75px"/>
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('thump_image') <span class="er">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="form-group row form-space increment">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Images:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type="file"  name="images[]" accept="image/*" class="myfrm form-control files">
                                        <div class="input-group-btn"> 
                                            <button class="btn btn-success product_image" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clone hide">
                                    <div class="form-group row form-space clone_img" style="">
                                    <label class="col-xs-12 col-lg-4 col-form-label"></label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="file"  name="images[]" class="myfrm form-control files" accept="image/*"  multiple="multiple">
                                            <div class="input-group-btn"> 
                                                <button class="btn btn-danger rem" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                              

                                <div class="">
                                    <div id="brands" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select brand:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" style="width: 100%;">
                                                <option value="" >--Select Brand--</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}" {{($brand->id == old('brand_id')) ? 'selected="selected"': ''}}>{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('brand_id') <span class="er">{{ $message }}</span> @enderror
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
                                    <div id="isBranch" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Product Type*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <fieldset>
                                                <input type="radio" class="type" id="product_type-1" name="product_type" value="0" required checked onclick="alert('[Single] product set as parent product and have no child products');"><span>&nbsp;Single</span>&nbsp;&nbsp;&nbsp;
                                                <input type="radio" class="type" id="product_type-2" name="product_type" value="1" required {{ old('product_type') == '1' ? 'checked' : '' }} onclick="alert('[Configurable] product set as parent product and this product must have child products');"><span>&nbsp;Configurable</span>&nbsp;&nbsp;&nbsp;
                                                <input type="radio" class="type" id="product_type-3" name="product_type" value="2" required {{ old('product_type') == '2' ? 'checked' : '' }} onclick="alert('[Simple] product have a parent product');"><span>&nbsp;Simple</span> 
                                            </fieldset>
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('product_type') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="">
                                    <div class="form-group row form-space" id="parent_product" style="display: none">
                                        <label class="col-xs-12 col-lg-4 col-form-label">
                                            Select Parent Product:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                         <select name="parent_product" class="form-control @error('parent_product') is-invalid @enderror parent_product" style="width: 100%;">
                                          <option value="">Select</option>
                                        
                                            @forelse($parent_products as $parent)
                                             <option value="{{$parent->id}}" {{($parent->id == old('parent_product')) ? 'selected="selected"': ''}}>{{$parent->sku}}--{{$parent->name}}</option>
                                             @empty
                                                <option value="" disabled>No parent available</option>
                                            @endforelse
                                         </select>
                                            <div class="invalid-feedback active">
                                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('parent_product') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="x"></div>
                                <input type="hidden" name="attrValue" id="hiddenRadioValue" />
                                <input type="hidden" name="checkedlist"/>

                                <!-- @if(Auth::guard('admin')->check())
                                <div class="sho d-none">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Vendor Price*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('vendor_price') is-invalid @enderror" name="vendor_price" id="vendor_price" placeholder="Enter product vendor price"  value="{{ old('vendor_price') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('vendor_price') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <input type="hidden" class="form-control form-border @error('vendor_price') is-invalid @enderror" name="vendor_price" id="vendor_price" placeholder="Enter product vendor price"  value="0" required="required">
                                @endif -->

                                <div class="sho d-none">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Price*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter product price"  value="{{ old('price') }}" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('price') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @if(Auth::guard('admin')->check())
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Commission in percentage:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('commission') is-invalid @enderror" name="commission" id="commission" placeholder="Enter product commission"  value="{{ old('commission') }}" >
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('commission') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Flat Offer:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('offer') is-invalid @enderror" name="offer" id="offer" placeholder="Enter Flat Offer"  value="{{ old('offer') }}" >
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('offer') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                                <div class="sho d-none">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Stock*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="number" class="form-control form-border @error('stock') is-invalid @enderror" name="stock" id="stock"  value="{{ old('stock') }}" placeholder="Enter product stock" required="required">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('stock') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <textarea class="form-control form-border"  rows="3" name="description" id="description">{{nl2br(old('description'))}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Search Keywords:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select class="form-control search-keyword" name="search_keyword[]" multiple="multiple">
                                              </select>
                                        </div>
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

                                <div class="">
                                    <div id="measurement" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select unit of measuremt:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2 @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" style="width: 100%;">
                                                <option value="" >--Select Unit--</option>
                                                @foreach($units as $unit)
                                                    <option value="{{$unit->id}}" {{($unit->id == old('unit_id')) ? 'selected="selected"': ''}}>{{$unit->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('unit_id') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Measurement of unit:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border @error('measurement_unit') is-invalid @enderror" name="measurement_unit" id="measurement_unit" placeholder="Enter product measurement of unit"  value="{{ old('measurement_unit') }}">
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('measurement_unit') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(Auth::guard('admin')->check())
                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1" checked><span>Active</span> 
                                            <input type="radio" id="status-2" name="status" value="0"><span>Inactive</span> 
                                        </div>          
                                    </div>
                                </div>

                                <input type="hidden"  name="approve" value="1"  readonly>
                                @else
                                            <input type="hidden"  name="approve" value="0"  readonly>
                                            <input type="hidden"  name="status" value="0"  readonly>
                                   
                                @endif



                               



                                <div class="text-right">
                                    <button type="reset" onclick="window.location='{{ URL::previous() }}'"  class="btn btn-default btn-sm">Cancel</button>
                                    <button type="Submit" class="btn btn-primary-blue btn-sm" id="myButtonID">Submit</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
    $('#my-select').multiSelect();
</script>
<script type="text/javascript">

    $(document).ready(function() {
        var count=1;
      $(".product_image").click(function(){ 
         
          var lsthmtl = $(".clone").html();
          if(count<=4)
          {
          $(".increment").append(lsthmtl);
          count++;
          }
          
      });
      $("body").on("click",".rem",function(){ 
        if(count>1)
        {
          $(this).parents(".clone_img").remove();
          count--;
        }
      });
    });
</script>
<script>
$(document).ready(function () {
    $('body').on('change','#product_type-3',function() {
                $('#parent_product').show();
                localStorage.setItem('parent_pro', true);
                localStorage.removeItem('visited');
            });

    $('body').on('change','#product_type-1',function() {
                $('#parent_product').hide();
                localStorage.removeItem('parent_pro');
                localStorage.removeItem("response");
                localStorage.removeItem('visited');
            });
    $('body').on('change','#product_type-2',function() {
                $('#parent_product').hide();
                localStorage.removeItem('parent_pro');
                localStorage.removeItem("response");
                localStorage.setItem('visited', true);
            });

            if ($("input[name='product_type']:checked").val()) {
                if (localStorage.getItem('parent_pro')) {
                    $('#parent_product').show();
                }  
            }
               $('body').on('click change','input[name="product_type"]',function() {
                if ($("input[name='product_type']:checked").val()==1) {
                    $('.sho').hide();
                    $('#vendor_price').prop('required',false);
                    $('#price').prop('required',false);
                    $('#stock').prop('required',false);
                    localStorage.setItem('visited', true);
                }else{
                    $('.sho').show();
                    $('#vendor_price').prop('required',true);
                    $('#price').prop('required',true);
                    $('#stock').prop('required',true);
                    localStorage.removeItem('visited');
                }
                });

    
});



$('#myFormId').submit(function(){
    
    var data = $.map($(".attr_type:checked"), function(elem, idx) {
      return $(elem).val()+"&_";
    }).join('');
  
$('#hiddenRadioValue').val(data);
    $("#myButtonID", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
    return true;
});

function matchStart (term, text) {
  if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
    return true;
  }
 
  return false;
}
 
$.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
  $(".select2").select2({
    matcher: oldMatcher(matchStart)
  })
  $(".search-keyword").select2({
    tags: true
});
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
   $(document).ready(function() {
  if (window.File && window.FileList && window.FileReader) {
    $("#files").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          
          $("<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">Remove image</span>" +
            "</span>").insertAfter("#files");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
          
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
        });
        fileReader.readAsDataURL(f);
      }
      console.log(files);
    });
  } else {
    alert("Your browser doesn't support to File API")
  }

});

$('#my-select').change(function () {
    localStorage.removeItem('cat_ids');
    localStorage.setItem('cat_ids',$("#my-select").val());
});

$('.type').change(function () {
   
        $(".x").hide();
        localStorage.removeItem('response');
        localStorage.removeItem('cat_ids');
        localStorage.setItem('cat_ids',$("#my-select").val());
    
    
});


$('.parent_product , #my-select').on('click change select', function (e) {

    e.preventDefault();
    if( $('#my-select').val()!='' && $('.parent_product').val() && $('.type:checked').val()==2) { 
    
    $(".x").show();
    var category_fields = localStorage.getItem('cat_ids');
    var shop_id =  $('#shop_id').val();
    // var base_url="http://localhost/dafy";
   // var base_url="{{url('/')}}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
         url: base_url+'/admin/product/getVarients',
         type: 'post',
         dataType: 'json',
         data:{shop_id:shop_id,category_fields:category_fields, _token: '{{csrf_token()}}' },
         success: function(response){
           $('.x').html(response.html);
           localStorage.setItem("response",response.html);
           }

         
       });

    }

});

$( document ).ready(function() {
    if(localStorage.getItem("response"))
    {
        if( $('#my-select').val()!='' && $('.parent_product').val() ) { 
            $('.x').html(localStorage.getItem("response"));
        }else{
            localStorage.removeItem("response");
        }
    }
    if($('.type:checked').val()!=2)
    {
     localStorage.removeItem("response");
    }
    if (localStorage.getItem('visited')) { // if not site is visited before
        $('.sho').hide(); //show element
    }else { //if not local storage use cookies or just show element in old browsers
        $('.sho').show();
    }
});



</script>
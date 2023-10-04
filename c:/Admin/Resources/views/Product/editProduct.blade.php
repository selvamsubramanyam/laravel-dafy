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
                    <h2>Edit Product</h2>
                    <div class="clearfix"></div>
                </div>

              <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
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


                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/product/update')}}"  enctype='multipart/form-data' id="myFormId">

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
                                <input type="hidden" name="id"  value="{{$product->id}}">
                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <input type="hidden" name="shop_id"  value="{{$shop->id}}">
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Shop Name:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border  @error('shop_name') is-invalid @enderror" id="shop_name" name="shop_name"  value="{{ $shop->name  }}" required="required" disabled>
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('shop_name') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Product Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        @if(Auth::guard('admin')->check())
                                            <input type="text" class="form-control form-border  @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter product name"  value="{{ $product->name ?? old('name') }}" required="required">
                                        @else
                                            @if($product->is_approved ==1)
                                            <input type="text" class="form-control form-border  @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter product name"  value="{{ $product->name }}" required="required" readonly>
                                            @else
                                            <input type="text" class="form-control form-border  @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter product name"  value="{{ $product->name ?? old('name') }}" required="required">
                                            @endif
                                        @endif
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
                                        @if(Auth::guard('admin')->check())
                                            <input type="text" class="form-control form-border @error('sku') is-invalid @enderror" name="sku" id="sku" placeholder="Enter product sku"  value="{{ $product->sku ?? old('sku') }}">
                                        @else
                                            @if($product->is_approved ==1)
                                            <input type="text" class="form-control form-border @error('sku') is-invalid @enderror" name="sku" id="sku" placeholder="Enter product sku"  value="{{ $product->sku }}" readonly>
                                            @else
                                            <input type="text" class="form-control form-border @error('sku') is-invalid @enderror" name="sku" id="sku" placeholder="Enter product sku"  value="{{ $product->sku ?? old('sku') }}">
                                            @endif
                                        @endif
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('sku') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>


                                <div class="form-group row form-space ">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Thump Image*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                    @if(Auth::guard('admin')->check())
                                        <input type='file' id="imgInp" accept="image/*" class="form-control form-border @error('thump_image') is-invalid @enderror" name="thump_image"/>
                                        <img id="thump" src="{{asset('storage/app/'.$product->thump_image)}}" alt="your image" height="75px" width="75px"/>
                                    @else
                                        @if($product->is_approved ==1)
                                        <img id="thump" src="{{asset('storage/app/'.$product->thump_image)}}" alt="your image" height="75px" width="75px"/>
                                        @else
                                        <input type='file' id="imgInp" accept="image/*" class="form-control form-border @error('thump_image') is-invalid @enderror" name="thump_image"/>
                                        <img id="thump" src="{{asset('storage/app/'.$product->thump_image)}}" alt="your image" height="75px" width="75px"/>
                                        @endif
                                    @endif
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('thump_image') <span class="er">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-group row form-space">
                                    <section>
                                    <br>
                                    <br>
                                    <label class="col-xs-12 col-lg-4 col-form-label"><b>Images for {{$product->name}}</b></label>
                                    <div class="row all_images" >
                                        @foreach($product->productImages as $image)
                                            <div style="width: 100px;height: 100px;float: left;position: relative;margin-right: 10px;margin-bottom: 20px;">
                                                <img style="width: 100%; height: 100%; object-fit: cover;" class="margin preview_img img-thumbnail" id="image{{$image->id}}" src="{{asset('storage/app/'.$image->image)}}" >
                                                @if(Auth::guard('admin')->check())
                                                <a href="#" data-id="{{$image->id}}" id="close-btn{{$image->id}}" class="delete-preview-img"><i class="fa fa-times-circle"></i></a>
                                                @else
                                                    @if($product->is_approved ==1)
                                                    @else
                                                    <a href="#" data-id="{{$image->id}}" id="close-btn{{$image->id}}" class="delete-preview-img"><i class="fa fa-times-circle"></i></a>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    <input type="hidden" value="{{$product->productImages->count()}}" id="total_img">
                                    </div>

                                </section>
                                </div>
                                @if(Auth::guard('admin')->check())
                                <div class="form-group row form-space increment">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Images:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type="file"  accept="image/*" name="images[]" class="myfrm form-control files" id="multImg">
                                        <div class="input-group-btn"> 
                                            <button class="btn btn-success product_image" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clone hide">
                                    <div class="form-group row form-space clone_img" style="">
                                    <label class="col-xs-12 col-lg-4 col-form-label"></label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="file"  accept="image/*" name="images[]" class="myfrm form-control files" multiple="multiple">
                                            <div class="input-group-btn"> 
                                                <button class="btn btn-danger rem" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    @if($product->is_approved ==1)
                                    @else
                                     <div class="form-group row form-space increment">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Images:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <input type="file"  accept="image/*" name="images[]" class="myfrm form-control files" id="multImg">
                                        <div class="input-group-btn"> 
                                            <button class="btn btn-success product_image" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clone hide">
                                    <div class="form-group row form-space clone_img" style="">
                                    <label class="col-xs-12 col-lg-4 col-form-label"></label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="file"  accept="image/*" name="images[]" class="myfrm form-control files" multiple="multiple">
                                            <div class="input-group-btn"> 
                                                <button class="btn btn-danger rem" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @endif
                                @endif
                              
                              
                            
                                <div class="">
                                    <div id="categories" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select brand:</label>
                                        <div class="col-xs-12 col-lg-8">
                                        @if(Auth::guard('admin')->check())
                                            <select  class="form-control select2 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" style="width: 100%;">
                                                <option value="" >--Select Brand--</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}" {{($brand->id == $product->brand_id) ? 'selected="selected"': ''}}>{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            @if($product->is_approved ==1)
                                            <select  class="form-control select2 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" style="width: 100%;" disabled>
                                                <option value="" >--Select Brand--</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}" {{($brand->id == $product->brand_id) ? 'selected="selected"': ''}}>{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="form-control form-border " name="brand_id"   value="{{ $product->brand_id}}" readonly>
                                            @else
                                            <select  class="form-control select2 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" style="width: 100%;">
                                                <option value="" >--Select Brand--</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}" {{($brand->id == $product->brand_id) ? 'selected="selected"': ''}}>{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        @endif
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
                                        @if(Auth::guard('admin')->check())
                                            <select name="categories[]" id="my-select"  class="form-control select2 @error('categories') is-invalid @enderror" multiple>     
                                                @forelse($categories as $category) 
                                                 @if(in_array($category->id, $categoryIds))
                                                    <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}} selected="true">{{$category->name}}</option>
                                                 @else
                                                 <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}} >{{$category->name}}</option>
                                                 @endif

                                                 @empty

                                                 @endforelse
                                            </select>
                                        @else
                                            @if($product->is_approved ==1)
                                            <select name="categories[]" id="my-select"  class="form-control select2 @error('categories') is-invalid @enderror" multiple  disabled>     
                                                @forelse($categories as $category) 
                                                 @if(in_array($category->id, $categoryIds))
                                                    <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}} selected="true">{{$category->name}}</option>
                                                 @else
                                                 <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}} >{{$category->name}}</option>
                                                 @endif

                                                 @empty

                                                 @endforelse
                                            </select>
                                                @foreach($categoryIds as $catids)
                                                <input type="hidden" value="{{$catids}}" name="categories[]">

                                                @endforeach

                                            @else
                                            <select name="categories[]" id="my-select"  class="form-control select2 @error('categories') is-invalid @enderror" multiple>     
                                                @forelse($categories as $category) 
                                                 @if(in_array($category->id, $categoryIds))
                                                    <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}} selected="true">{{$category->name}}</option>
                                                 @else
                                                 <option value="{{$category->id}}" {{in_array($category->id, old('categories') ?: []) ? "selected": ""}} >{{$category->name}}</option>
                                                 @endif

                                                 @empty

                                                 @endforelse
                                            </select>
                                          
                                            @endif
                                        @endif
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('categories') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

                             @if($product->parent_id !==0 || $product->parent_id === null)
                             
                                 <!-- @if(Auth::guard('admin')->check())
                                    <div class="">
                                        <div class="form-group row form-space">
                                            <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                                Vendor Price*:</label>
                                            <div class="col-xs-12 col-lg-8 ">
                                          
                                                <input type="text" class="form-control form-border @error('vendor_price') is-invalid @enderror" name="vendor_price" id="vendor_price" placeholder="Enter product vendor price"  value="{{ $product->vendor_price ?? old('vendor_price') }}">
                                         
                                                <div class="invalid-feedback active">
                                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('vendor_price') <span class="er">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @if($product->is_approved ==1)
                                        <input type="hidden" class="form-control form-border @error('vendor_price') is-invalid @enderror" name="vendor_price" id="vendor_price" placeholder="Enter product vendor price"  value="{{ $product->vendor_price ?? old('vendor_price') }}">
                                    @else
                                        <input type="hidden" class="form-control form-border @error('vendor_price') is-invalid @enderror" name="vendor_price" id="vendor_price" placeholder="Enter product vendor price"  value="{{ $product->vendor_price ?? old('vendor_price') }}">
                                     @endif
                                @endif -->

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Price*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        @if(Auth::guard('admin')->check())
                                            <input type="text" class="form-control form-border @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter product price"  value="{{$product->price ??  old('price') }}">
                                        @else
                                            @if($product->is_approved ==1)
                                            <input type="text" class="form-control form-border @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter product price"  value="{{$product->price ??  old('price')}}">
                                            @else
                                            <input type="text" class="form-control form-border @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter product price"  value="{{$product->price ??  old('price') }}">
                                            @endif
                                        @endif    
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('price') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Commission in percentage:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        @if(Auth::guard('admin')->check())
                                            <input type="text" class="form-control form-border @error('commission') is-invalid @enderror" name="commission" id="commission" placeholder="Enter product commission"  value="{{ $product->commission ?? old('commission') }}" >
                                        @else
                                            <input type="text" class="form-control form-border @error('commission') is-invalid @enderror" name="commission" id="commission"   value="{{ $product->commission ?? old('commission') }}" readonly>
                                        @endif
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
                                        @if(Auth::guard('admin')->check())
                                            <input type="text" class="form-control form-border @error('offer') is-invalid @enderror" name="offer" id="offer" placeholder="Enter Flat Offer"  value="{{ $product->offer ?? old('offer') }}" >
                                        @else
                                            <input type="text" class="form-control form-border @error('offer') is-invalid @enderror" name="offer" id="offer"   value="{{ $product->offer ?? old('offer') }}" readonly>
                                        @endif
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('offer') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Stock*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        @if(Auth::guard('admin')->check())
                                            <input type="number" class="form-control form-border @error('stock') is-invalid @enderror" name="stock" id="stock"  value="{{ $product->stock ?? old('stock') }}" placeholder="Enter product stock" >
                                        @else
                                            @if($product->is_approved ==1)
                                            <input type="number" class="form-control form-border @error('stock') is-invalid @enderror" name="stock" id="stock"  value="{{ $product->stock  ?? old('stock') }}" placeholder="Enter product stock" >
                                            @else
                                            <input type="number" class="form-control form-border @error('stock') is-invalid @enderror" name="stock" id="stock"  value="{{ $product->stock ?? old('stock') }}" placeholder="Enter product stock" >
                                            @endif
                                        
                                        @endif
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('stock') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                        @if(Auth::guard('admin')->check())
                                            <textarea class="form-control form-border"  rows="3" name="description" id="description">{{nl2br($product->description ?? old('description'))}}</textarea>
                                        @else

                                            @if($product->is_approved == 1)
                                            <textarea class="form-control form-border"  rows="3" name="description" id="description" readonly>{{nl2br($product->description) }}</textarea>
                                            @else
                                            <textarea class="form-control form-border"  rows="3" name="description" id="description">{{nl2br($product->description ?? old('description'))}}</textarea>
                                            @endif
                                        @endif
                                        </div>
                                    </div>  
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Search Keywords:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select class="form-control search-keyword" name="search_keyword[]" multiple="multiple">
                                                @foreach($keywords as $key)
                                                <option value="{{$key->term}}" selected>{{$key->term}}</option>
                                                @endforeach
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
                                        @if(Auth::guard('admin')->check())
                                            <select  class="form-control select2 @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" style="width: 100%;" readonly>
                                                <option value="" >--Select Unit--</option>
                                                @foreach($units as $unit)
                                                    <option value="{{$unit->id}}" {{($unit->id == $product->unit_id) ? 'selected="selected"': ''}}>{{$unit->name}}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            @if($product->is_approved ==1)
                                            <input type="text" class="form-control form-border " name="unit_id"   value="{{ $product->unit_id}}" readonly>
                                            @else
                                            <select  class="form-control select2 @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" style="width: 100%;" readonly>
                                                <option value="" >--Select Unit--</option>
                                                @foreach($units as $unit)
                                                    <option value="{{$unit->id}}" {{($unit->id == $product->unit_id) ? 'selected="selected"': ''}}>{{$unit->name}}</option>
                                                @endforeach
                                            </select>
                                            
                                            @endif

                                        @endif
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
                                        @if(Auth::guard('admin')->check())
                                            <input type="text" class="form-control form-border @error('measurement_unit') is-invalid @enderror" name="measurement_unit" id="measurement_unit" placeholder="Enter product measurement of unit"  value="{{ $product->measurement_unit ?? old('measurement_unit') }}">
                                        @else
                                            @if($product->is_approved ==1)
                                            <input type="text" class="form-control form-border @error('measurement_unit') is-invalid @enderror" name="measurement_unit" id="measurement_unit" placeholder="Enter product measurement of unit"  value="{{ $product->measurement_unit  }}" readonly>
                                            @else
                                            <input type="text" class="form-control form-border @error('measurement_unit') is-invalid @enderror" name="measurement_unit" id="measurement_unit" placeholder="Enter product measurement of unit"  value="{{ $product->measurement_unit ?? old('measurement_unit') }}">
                                            @endif

                                        @endif
                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('measurement_unit') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                             
                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1" {{ ($product->is_active=="1")? "checked" : "" }}><span>Active</span> 
                                            <input type="radio" id="status-2" name="status" value="0" {{ ($product->is_active=="0")? "checked" : "" }}><span>Inactive</span> 
                                        </div>          
                                    </div>
                                </div>
                             
                              
                                <!-- <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="hidden" name="status" value={{$product->is_active}}>
                                            <input type="radio" id="status-1" name="status1" value="1"  disabled {{ ($product->is_active=="1")? "checked" : "" }}><span>Active</span> 
                                            <input type="radio" id="status-2" name="status1" value="0"  disabled {{ ($product->is_active=="0")? "checked" : "" }}><span>Inactive</span> 
                                        </div>          
                                    </div>
                                </div> -->
                              
                                

                                <input type="hidden"  name="approve" value="{{$product->is_approved}}"  readonly>

                
                                <div class="text-right">
                                    <button type="reset" onclick="window.location='{{ URL::previous() }}'" class="btn btn-default btn-sm">Cancel</button>
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
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $('#my-select').multiSelect();
    
</script>
<script type="text/javascript">
    $(document).ready(function() {
      
        var count=1;
        var total_img= parseInt($('#total_img').val());
       if(total_img >= 5){
        $("#multImg").prop('disabled', true);
       }else{
        $("#multImg").prop('disabled', false);
       }
        if(total_img)
        {
            total_img = 4-total_img;
        }else{
            total_img =4;
        }
      $(".product_image").click(function(){ 
          var lsthmtl = $(".clone").html();
          if(count<=total_img)
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

$('#myFormId').submit(function(){
    $("#myButtonID", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
    return true;
});

$('.delete-preview-img').on('click',function () {

var img_id=$(this).data('id');
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    dataType: 'text',
    type: 'post',
    data:{img_id:img_id,"_token": $('#token').val()},
    url: '{{ route('product.image.delete') }}',
    success: function (res) {
        location.reload();
        // $(".all_images").notify(
        //     res,"success",
        //     { position:"top left" }
        // );

        // $("#image"+img_id).remove();
        // $("#close-btn"+img_id).remove();

    }
});
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




</script>
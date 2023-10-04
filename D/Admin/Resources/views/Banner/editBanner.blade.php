@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Banner</h2>
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
                            <form method="post" action="{{URL('admin/banner/update')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <input type="hidden" name="id" id="id" value="{{$banner->id}}">
                              
                                
                                <div class="">
                                    <div id="shops" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select shop:</label>
                                        <div class="col-xs-12 col-lg-8">
                                       
                                            <select name="shops" id="my-select"  class="form-control shop select2 @error('shops') is-invalid @enderror" >    
                                            <option value="">--Select shop--</option>
                                            @foreach($shops as $shop)
                                                    <option value="{{$shop->id}}" {{($shop->id == $banner->shop_id) ? 'selected="selected"': ''}}>{{$shop->name}}</option>
                                            @endforeach
                                               
                                            </select>

                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('shops') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div id="products" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select product:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            
                                            <select name="product" id="my-select1"  class="form-control product select2 @error('product') is-invalid @enderror">     
                                                <option value="">--Select product--</option>
                                                @foreach($products as $product)
                                                        <option value="{{$product->id}}" {{($product->id == $banner->product_id) ? 'selected="selected"': ''}}>{{$product->name}}</option>
                                                @endforeach
                                            </select>

                                            <div class="invalid-feedback active">
                                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('product') <span class="er">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Title*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="title" placeholder="Banner Title" value="{{$banner->title}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Image*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <img src="{{URL('storage/app/'.$banner->image)}}" width="60" height="60">
                                        <input type="file"  class="form-control form-border"  name="pic" accept="image/*">
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Banner Description" name="description">{{$banner->description}}</textarea>
                                        </div>
                                    </div>
                                   
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Valid from</label>
                                        <div class="col-xs-12 col-lg-8">
                                     <input type="date" class="form-control form-border" placeholder="Valid From" name="validfrom" value="{{$banner->valid_from}}" required="required">

                                        </div>
                                    </div>

                                      <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Valid To</label>
                                        <div class="col-xs-12 col-lg-8">
                                     <input type="date" class="form-control form-border" placeholder="Valid From" name="validto" value="{{$banner->valid_to}}" required="required">

                                        </div>
                                    </div>

                                   
                                </div>


                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                        <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1"
                                            <?php if($banner->is_active == 1) { ?>checked="checked"> <?php } ?>
                                            <span>Active</span> <span></span>
                                            <input type="radio" id="status-2" name="status" value="0" <?php if($banner->is_active == 0) { ?>checked="checked"> <?php } ?><span>Inactive</span> 
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

  <script>
     
     $(document).ready(function() {
         $('.product').select2();
         $('.shop').select2();
     });
     
</script>
<script type="text/javascript">
$(document).ready(function(){

$( ".shop" ).select2({
  ajax: { 
    url: "{{route('banner.shop.search')}}",
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
                 url: base_url+'/admin/banner/getproductList',
                 type: 'post',
                 dataType: 'json',
                 data:{shop_id:shop_id, _token: '{{csrf_token()}}' },
                 success: function(response){
                     var len = 0;
                     if(response['data'] != null){
                     len = response['data'].length;
                     
                     }
 
                     if(len > 0){
                       var option ="<option value=''>--Select Product--</option>";
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
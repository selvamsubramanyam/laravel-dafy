@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Notification Management</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('message') }}
                        </div>
                    @endif
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/notification/store')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Title:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="title" placeholder="Title" required="required">
                                            <!-- <span class="form-text text-muted">Please enter your full
                                                name</span> -->
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Image:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="file"  class="form-control form-border"  name="image" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select a shop:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2 shop" id="my-select" name="shop_id" style="width: 100%;">
                                                <option value="">--Select Shop--</option>
                                                @foreach($shops as $shop)
                                                    <option value="{{$shop->id}}" {{($shop->id == old('shop_id')) ? 'selected="selected"': ''}}>{{$shop->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="">
                                    <div id="product_id" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select a product:</label>
                                        <div class="col-xs-12 col-lg-8">

                                            <select  class="form-control select2 product_id" id="my-select1" name="product_id" style="width: 100%;">
                                            </select>

                                            <div class="invalid-feedback active">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                     <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <textarea type="number"  class="form-control form-border"  name="description" placeholder="Description" required="required"></textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="text-right">
                                    <button type="reset" class="btn btn-default btn-sm">Cancel</button>
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

<script type="text/javascript">
    
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
                      option += "<option value=''>--Select Product--</option>";
                        // Read data and create <option >
                        for(var i=0; i<len; i++){

                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            // option += "<option value='' placeholder='select a product'>";
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
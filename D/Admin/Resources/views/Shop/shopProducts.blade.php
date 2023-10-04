@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 80px;
  height: 30px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;

  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
   border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 23px;
  width: 23px;
  left: 1px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #2ab934;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(55px);
}

/*------ ADDED CSS ---------*/
.slider:after
{
 content:'Off';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 45%;
 font-size: 10px;
 font-family: Verdana, sans-serif;
}

input:checked + .slider:after
{  
  content:'Active';
}

/*--------- END --------*/
</style>
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Product Management</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Product Deleted Succeefully
                        </div>
                    <div class="text-right mb-2">
                        <a href="{{route('shopProduct.export',$id)}}" class="btn  btn-success btn-sm add-prod-btn br-0">
                            <i class="fa fa-download"></i> Export
                        </a>
                        <a href="{{URL('admin/product/add/'.$id)}}" class="btn  btn-primary-blue btn-sm add-prod-btn br-0">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>
                    <input type="hidden" value="{{Auth::guard('admin')->check() ? 1 : 0}}" id="is_admin" name="is_admin">
                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="shop_product_datatable">
                        <input type="hidden" value="{{$id}}" id="sho" name="shop_id">
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >
                                <th data-field="Seller_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Seller</center> 
                                </th>
                                <th data-field="Sku" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Sku</center> 
                                </th>
                                <th data-field="Name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Name</center>
                                </th>
                                <th data-field="Brand_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Brand</center>
                                </th>
                            @if(Auth::guard('admin')->check() )
                                <th data-field="Vendor_price" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Vendor Price</center>
                                </th>
                            @else

                                <th data-field="Unit" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Unit</center>
                                </th>
                            @endif
                                <th data-field="Price" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Price</center>
                                </th>

                                <th data-field="Stock" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Stock</center>
                                </th>

                                <th data-field="Image" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Image</center>
                                </th>

                                <th data-field="Category" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Category</center>
                                </th>


                                <th data-field="Attribute_value" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Attributes</center>
                                </th>

                                <th data-field="Parent_product" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Parent Product</center>
                                </th>

                                
                                
                                <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Status</center>
                                </th>

                                <th data-field="Actions" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center> Actions</center>
                                </th>
                                    
                                </tr>

                            </thead>

                        </table>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <!-- /page content -->
</div>
@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<!-- <script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script> -->
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>

<script>
     $('body').on('click','.status',function(e)
  {

  	e.preventDefault();
  	var prod_id = $(this).val();
	  if( prod_id !='') { 

		 $.ajaxSetup({
			 headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 });

		  $.ajax({ 
			 url: base_url+'/admin/product/changeProductStatus',
			 type: 'post',
			 dataType: 'json',
			 data:{product_id:prod_id,_token: '{{csrf_token()}}'},
			 success: function(data){
                if($.isEmptyObject(data.error)){
                    // alert(data.success);
                    $('#shop_product_datatable').DataTable().ajax.reload(null,false);
                   // location.reload();
                }else{
                    alert(data.error);
                }
            }

    });
 }else{
     alert('product not found');
	location.reload();
 }

});
</script>
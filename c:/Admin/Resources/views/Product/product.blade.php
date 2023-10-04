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
                    <h2>Product {{ucfirst($status)}} List</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Product Deleted Succeefully
                        </div>
                    <div class="text-right mb-2">
                        <a href="{{route('product.status.export',$status)}}" class="btn  btn-success btn-sm add-prod-btn br-0">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div> 
                   
                <input type="hidden" value="{{$status}}" id="prod_status">
                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="product_list_datatable">
                           
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

                                <th data-field="Vendor_price" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Vendor Price</center>
                                </th>

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
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>

<script>
     $('body').on('click','.approve',function(e)
  {
      e.preventDefault();
    var confirmation = confirm("are you sure you want to approve the product?");

    if (confirmation) {
      var prod_id = $(this).val();
     
	  if( prod_id !='') { 

		 $.ajaxSetup({
			 headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 });

		  $.ajax({ 
			 url: base_url+'/admin/product/changeProductApproveStatus',
			 type: 'post',
			 dataType: 'json',
			 data:{product_id:prod_id,_token: '{{csrf_token()}}'},
			 success: function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                   location.reload();
                }else{
                    alert(data.error);
                }
            }

    });
 }else{
     alert('product not found');
	location.reload();
 }
    }
});
</script>
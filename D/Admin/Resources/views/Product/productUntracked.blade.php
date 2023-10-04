@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Product Untracked Management</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Product Deleted Succeefully
                        </div>
                    <div class="text-right mb-2">
                        <a href="{{route('product.untracked.export',$id)}}" class="btn-sm btn-info br-0">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>

                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="product_untracklist_datatable">
                        <input type="hidden" value="{{$id}}" id="sho" name="shop_id">
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >
                               
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

                                <th data-field="Unit_Measurement" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Measurement Unit</center>
                                </th>
                                
                                <th data-field="Measurement_Value" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Measurement Value</center>
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
<script>
  function myFunction() {
      if(!confirm("Are You Sure to delete this"))
      event.preventDefault();
  }
 </script>
@include('admin::layouts.includes.footer')
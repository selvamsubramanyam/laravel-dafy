@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Shop Management</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Shop Deleted Succeefully
                        </div>
                    <div class="text-right mb-2">
                        <a href="{{URL('admin/shop/export')}}" class="btn  btn-success btn-sm add-prod-btn br-0">
                            <i class="fa fa-download"></i> Export
                        </a>
                        <a href="{{URL('admin/shop/add')}}" class="btn  btn-primary-blue btn-sm add-prod-btn br-0">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>

                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="shop_list_datatable">
                           
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >

                                <th data-field="Seller_Name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Seller Business Name</center> 
                                </th>

                                <th data-field="Name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Shop Name</center> 
                                </th>

                                <!-- <th data-field="Name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Products</center> 
                                </th> -->

                                <!-- <th data-field="Name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Website</center> 
                                </th> -->

                                <!-- <th data-field="Image" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Image</center>
                                </th> -->
                                <th data-field="Parent" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Location</center>
                                </th>

                                <!-- <th data-field="Parent" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Address</center>
                                </th> -->
                                
                                <!-- <th data-field="Parent" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Description</center>
                                </th>

                                <th data-field="Parent" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Services</center>
                                </th> -->

                                <!-- <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Attributes</center>
                                </th> -->
                                
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
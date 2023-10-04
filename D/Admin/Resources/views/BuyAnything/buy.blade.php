@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>BuyAnything Management</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            Deleted Succeefully
                        </div>
                  

                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="buy_list_datatable">
                           
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center> User Name</center> 
                                </th>

                                <th data-field="mobile" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Mobile</center> 
                                </th>

                                <th data-field="Image" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Image</center>
                                </th>


                                <th data-field="shop_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Shop Name</center>
                                </th>

                                <th data-field="status" class="m-datatable__cell m-datatable__cell--sort column-title">
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
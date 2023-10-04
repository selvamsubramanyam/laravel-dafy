@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Redeemed Offline Vouchers</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <form class="d-flex">
            {{csrf_field()}}
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-xs-12 col-lg-4 col-form-label">Shop:</label> 
                        <div class="col-xs-12 col-lg-8 ">
                            <select class="form-control" name="shop" id="shop">
                                <option value="">--select--</option>
                                @foreach($shops as $shop)
                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="col-xs-12 col-lg-4 col-form-label">Date From:</label>
                        <div class=" col-lg-8">
                        <input type="datetime-local" class="form-control form-border"  id="orderfrom" placeholder="Order From" name="orderfrom" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-xs-12 col-lg-4 col-form-label">Date To:</label>
                        <div class="col-lg-8">
                            <input type="datetime-local" class="form-control form-border" id="orderto" placeholder="Order To" name="orderto" value="">
                        </div>
                    </div>
                   
                    
                </div><br>
                <div class="row justify-content-end">
                    <div class="text-right">
                
                    <button type="Submit" class="btn btn-primary-blue btn-sm" id="searchvoucher" style="width:120px;border-radius:16px">Search</button>
                    </div>
                </div>
               
                </form> 

                <div class="row justify-content-end">
                    <div class="text-right">
                
                    <!-- <button type="Submit" class="btn btn-primary-blue btn-sm export_voucher" id="export_voucher" style="width:120px;border-radius:16px">Export</button> -->
                    </div>
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Offer Deleted Succeefully
                        </div>
                    <!-- <div class="text-right mb-2">
                        <a href="{{URL('admin/offer/add')}}" class="btn  btn-primary-blue btn-sm add-prod-btn br-0">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div> -->

                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="offline_voucher_datatable">
                           
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Offer Name</center> 
                                </th>

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Shop Name</center> 
                                </th>

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>User Name</center> 
                                </th>

                                <th data-field="Image" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Image</center>
                                </th>

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Date & Time</center> 
                                </th>

                                <!-- <th data-field="latitude" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>description</center>
                                </th> -->

                                <!-- <th data-field="longitude" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Discount Type</center> 
                                </th> -->

                                <!-- <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Discount Value</center> 
                                </th> -->

                                <!-- <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Valid From</center> 
                                </th> -->

                                <!-- <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Valid To</center> 
                                </th> -->
                                
                                <!-- <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Status</center>
                                </th> -->

                                <!-- <th data-field="Actions" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center> Actions</center> -->
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
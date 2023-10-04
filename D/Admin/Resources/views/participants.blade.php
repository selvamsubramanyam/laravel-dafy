@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Participants Video Management</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            Deleted Succeefully
                        </div>
                    <div class="text-right mb-2">
                        <a href="{{URL('admin/participants/add')}}" class="btn  btn-primary-blue btn-sm add-prod-btn br-0">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>

                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="uservideo_list_datatable">
                           
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >
                                    <th data-field="FirstName" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Event Name</center> 
                                </th>


                                 <th data-field="FirstName" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Title</center> 
                                </th>
                                
                                <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center> Video URL</center>
                                </th>
                                
                                <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Thumb Image</center>
                                </th>

                                <!-- <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Description</center>
                                </th> -->
                                
                                <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Votes</center>
                                </th>

                                <th data-field="Company" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>View Count</center>
                                </th>
                                
                                <th data-field="Actions" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center> Actions</center>
                                </th>
                                    
                                </tr>




                            </thead>

                           <!--  <tbody>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        1
                                    </td>
                                    <td><span class="onlineIcon"></span>Don Mathew</td>
                                    <td>
                                        <div class="badge info-bg">Admin</div>
                                    </td>
                                    <td>+91 9885631247 </td>
                                    <td>domathew07@gmail.com</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control form-border field-input">
                                                <option>Active</option>
                                                <option>Inactive</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="last-col">
                                        <a href="sub-admin-mgmnt-edit.php" class=" btn btn-default btn-xs info">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-xs danger dlt-btn">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                        <a href="sub-admin-mngmnt-date&time.php" class=" btn btn-default btn-xs info"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <td class="a-center ">
                                    3
                                </td>
                                <td><span class="offlineIcon"></span>Meera Nair</td>
                                <td>
                                    <div class="badge success-bg">Sub-admin</div>
                                </td>
                                <td>+91 7896451236 </td>
                                <td>nairmeera@gmail.com</td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control form-border field-input">
                                            <option>Active</option>
                                            <option>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="last-col">
                                    <a href="sub-admin-mgmnt-edit.php" class=" btn btn-default btn-xs info">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <button class="danger dlt-btn">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                    <a href="sub-admin-mngmnt-date&time.php" class=" btn btn-default btn-xs info"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                </td>
                                </tr>
                                <td class="a-center ">
                                    3
                                </td>
                                <td><span class="offlineIcon"></span>Nandul Das</td>
                                <td>
                                    <div class="badge success-bg">Sub-admin</div>
                                </td>
                                <td>+91 9980001230 </td>
                                <td>nanduldas@gmail.com</td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control form-border field-input">
                                            <option>Active</option>
                                            <option>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="last-col">
                                    <a href="sub-admin-mgmnt-edit.php" class=" btn btn-default btn-xs info">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <button class="danger dlt-btn">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                    <a href="sub-admin-mngmnt-date&time.php" class=" btn btn-default btn-xs info"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                </td>
                                </tr>
                                <td class="a-center ">
                                    04
                                </td>
                                <td><span class="offlineIcon"></span>Rosemary M</td>
                                <td>
                                    <div class="badge success-bg">Sub-admin</div>
                                </td>
                                <td>+91 8086451236</td>
                                <td>rosemeryrose@gmail.com</td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control form-border field-input">
                                            <option>Active</option>
                                            <option>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="last-col">
                                    <a href="sub-admin-mgmnt-edit.php" class=" btn btn-default btn-xs info">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <button class="danger dlt-btn">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                    <a href="sub-admin-mngmnt-date&time.php" class=" btn btn-default btn-xs info"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                </td>
                                </tr>

                            </tbody> -->
                        </table>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <!-- /page content -->
</div>
@include('admin::layouts.includes.footer')
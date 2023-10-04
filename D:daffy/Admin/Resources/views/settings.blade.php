@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>APP Version Settings</h2>
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
                            <form method="post" action="{{URL('admin/settings/appversion')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                           Android Version:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="android" placeholder="Android Version" required="required"
                                            <?php
                                            if(isset($settings[0]['value'])){?> value="{{$settings[0]['value']}}" <?php }?>>
                                            <!-- <span class="form-text text-muted">Please enter your full
                                                name</span> -->
                                        </div>
                                    </div>

                                     <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                           Android Min Version:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="androidmin" placeholder="Android Version" required="required"
                                            <?php
                                            if(isset($settings[0]['value'])){?> value="{{$settings[0]['min_value']}}" <?php }?>>
                                            <!-- <span class="form-text text-muted">Please enter your full
                                                name</span> -->
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">IOS Version:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="text"  class="form-control form-border"  name="ios" placeholder="IOS Version" required="required" <?php
                                             if(isset($settings[1]['value'])){?> value="{{$settings[1]['value']}}" <?php }?>>
                                        </div>
                                    </div>

                                      <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">IOS Min Version:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="text"  class="form-control form-border"  name="iosmin" placeholder="IOS Version" required="required" <?php
                                             if(isset($settings[1]['value'])){?> value="{{$settings[1]['min_value']}}" <?php }?>>
                                        </div>
                                    </div>

                                    
                                     
                                </div>

                               




                                <!-- <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Address:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <textarea name="" id="" rows="5" class="form-control form-border"
                                                placeholder="address details"></textarea>
                                        </div>
                                    </div>
                                </div> -->



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
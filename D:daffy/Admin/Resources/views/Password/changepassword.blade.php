@include('admin::layouts.includes.header')
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Change Password</h2>
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
                        <div class="col-md-8 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/password/change')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Current Password*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="password" class="form-control form-border" name="password" placeholder="Current password" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            New Password*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="password" class="form-control form-border" name="new-password" placeholder="New password" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Re-enter Password*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="password" class="form-control form-border" name="new-password-confirmation" placeholder="Confirm new password" required="required">
                                        </div>
                                    </div>
                                </div>

                               

                                <div class="text-right">
                                    <button type="reset" onclick="window.location='{{ URL::previous() }}'"  class="btn btn-default btn-sm">Cancel</button>
                                    <button type="Submit" class="btn btn-primary-blue btn-sm">Change</button>
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
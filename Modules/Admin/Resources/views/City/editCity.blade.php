@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit City</h2>
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
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/city/update')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <input type="hidden" name="id" id="id" value="{{$city->id}}">

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="name" placeholder="City Name" value="{{$city->name}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div id="states" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select states:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <select  class="form-control select2" id="state" name="state" data-placeholder="Select State" style="width: 100%;">
                                                @foreach($states as $state)
                                                    <option <?php if($city->state_id == $state->id){?> selected="selected" <?php }?> value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Image*:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <img src="{{URL('storage/app/'.$city->image)}}" width="60" height="60">
                                        <input type="file"  class="form-control form-border"  name="pic" accept="image/*">
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Latitude*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="latitude" placeholder="City Latitude" value="{{$city->latitude}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Longitude*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="longitude" placeholder="City Longitude" value="{{$city->longitude}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1"
                                            <?php if($city->is_active == 1) { ?>checked="checked"> <?php } ?>
                                            <span>Active</span> <span></span>
                                            <input type="radio" id="status-2" name="status" value="0" <?php if($city->is_active == 0) { ?>checked="checked"> <?php } ?><span>Inactive</span> 
                                        </div>          
                                    </div>
                                </div>

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
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
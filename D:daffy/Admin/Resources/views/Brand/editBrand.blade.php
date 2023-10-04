@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Brand</h2>
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
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
                            <form method="post" action="{{URL('admin/brand/update')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <input type="hidden" name="id" id="id" value="{{$brand->id}}">

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="name" placeholder="Brand Name" value="{{$brand->name}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Logo:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <img src="{{URL('storage/app/'.$brand->logo)}}" width="60" height="60">
                                        <input type="file"  class="form-control form-border"  name="logo" accept="image/*">
                                    </div>
                                </div>

                               

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                        <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1"
                                            <?php if($brand->is_active == 1) { ?>checked="checked"> <?php } ?>
                                            <span>Active</span> <span></span>
                                            <input type="radio" id="status-2" name="status" value="0" <?php if($brand->is_active == 0) { ?>checked="checked"> <?php } ?><span>Inactive</span> 
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
@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Services</h2>
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
                            <form method="post" action="{{URL('admin/category/service/store')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}
                                <input type="hidden" name="id" id="id" value="{{$id}}">

                                <?php if($flag == 0) { ?>
                                <div class="">
                                    <div class="form-group"  id="values">
                                        <label>Service Names</label>
                                        <span style="color: red">&nbsp;&nbsp;[Press <i>ENTER</i>  Key after adding each value]</span>
                                        <br>
                                        <span id="features_values" class="label label-primary" style="display: none">
                                            
                                        </span>
                                        <input type="text" name="values" id="values" placeholder="Add Services *" class="form-control bootstrap-tagsinput" value="" data-role="tagsinput">

                                    </div>
                                </div>

                                <?php } else { ?>

                                <div class="">
                                    <div class="form-group"  id="">
                                        <label>Possible Values</label>
                                        <span style="color: red">&nbsp;&nbsp;[Press <i>ENTER</i>  Key after adding each value]</span>
                                        <br>
                                        <span id="features_values" class="label label-primary">
                                        {{$service->service_names}}
                                        </span>
                                        <input type="text" name="edit_values" id="values" values="{{$service->service_names}}" placeholder="Add Possible Values *" class="form-control bootstrap-tagsinput" value="" data-role="tagsinput">

                                    </div>
                                </div>
                                <?php } ?>
                                

                                <div class="text-right">
                                    <!-- <button type="reset" class="btn btn-default btn-sm">Cancel</button> -->
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
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
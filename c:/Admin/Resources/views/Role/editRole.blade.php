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
                    <h2>Edit Role</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('message') }}
                        </div>
                    @endif

                    @if(session()->has('danger'))
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('danger') }}
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
                        <div class="col-md-12 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/role/update')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <input type="hidden" name="id" id="id" value="{{$role->id}}">

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="name" placeholder="Role Name" value="{{$role->name}}" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <textarea class="form-control form-border"  rows="3" name="description" id="description" required>{{nl2br($role->description)}}</textarea>
                                        </div>
                                    </div>  
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                <label >Permissions</label><hr>
                            <div class="row">
                                @foreach($permissions as $perms)
                                    @if($perms->title != 'Dashboard')
                                        <div class="col-md-3 form-group">
                                            <label class="checkbox checkbox-outline-primary">
                                                <input type="checkbox" name="perms[]" value="{{$perms->id}}" {{ in_array($perms->id, $permission) ? 'checked' : '' }} ><span>{{$perms->title}}</span><span class="checkmark"></span>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        
                        </div>

                               

                                <div class="text-right">
                                    <button type="reset" onclick="window.location='{{ URL::previous() }}'"  class="btn btn-default btn-sm">Cancel</button>
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
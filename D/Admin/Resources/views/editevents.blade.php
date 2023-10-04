@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Events Management</h2>
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
                            <form method="post" action="{{URL('admin/events/updateevents')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Events Name:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="title" placeholder="Events Name" required="required" value="{{$events->title}}">
                                            <!-- <span class="form-text text-muted">Please enter your full
                                                name</span> -->
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Video URL:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="text"  class="form-control form-border"  name="videourl" placeholder="Video URL" required="required" value="{{'https://www.youtube.com/watch?v='.$events->video_url}}">
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Thumbnail Image:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <img src="{{URL('storage/app/'.$events->thumbnail_url)}}" width="60" height="60">
                                            <input type="file"  class="form-control form-border"  name="pic" accept="image/*" >
                                        </div>
                                    </div>

                                     
                                </div>

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">From date</label>
                                        <div class="col-xs-12 col-lg-8">
                                     <input type="date" class="form-control form-border" placeholder="Valid From" name="validfrom" required="required" value="{{$events->from_date}}">

                                        </div>
                                    </div>
                                <?php
                                $to=date('Y-m-d\TH:i',strtotime($events->to_date));
                                ?>

                                      <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Publishing date</label>
                                        <div class="col-xs-12 col-lg-8">
                                     <input type="datetime-local" class="form-control form-border" placeholder="Valid To" name="validto" required="required" value="{{$to}}">

                                        </div>
                                    </div>

                                   
                                </div>
                       <input type="hidden" name="eventid" value="{{$events->id}}">
                       <?php $interactive=date('Y-m-d\TH:i',strtotime($events->interactive_date)); ?>
                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Interactive date</label>
                                            <div class="col-xs-12 col-lg-8">
                                                <input type="datetime-local" class="form-control form-border" placeholder="Interactive date" name="interactive" <?php if($interactive != '1970-01-01T00:00') { ?> value="{{$interactive}}" <?php }  else { ?> value="" <?php } ?>>
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Description" name="description" required>{{$events->description}}</textarea>
                                        </div>
                                    </div>
                                   
                                </div>

                                <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                    <div class="col-xs-12 col-lg-8">
                                        <div class="statusBox">
                                            <input type="radio" id="status-1" name="status" value="1"
                                            <?php if($events->is_active == 1) { ?>checked="checked"> <?php } ?>
                                            <span>Active</span> <span></span>
                                            <input type="radio" id="status-2" name="status" value="0" <?php if($events->is_active == 0) { ?>checked="checked"> <?php } ?><span>Inactive</span> 
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
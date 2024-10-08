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
                            <form method="post" action="{{URL('admin/events/updateparticipants')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <div class="">

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Events:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        <select required="required" name="events" class="form-control form-border" >
                                            <option value="">Select</option>
                                            @foreach($event_detail  as $event)
                                            <option value="{{$event->id}}" 
                                            <?php if($events->media_id==$event->id){?> selected="selected" <?php }?>>{{$event->title}}</option>

                                            @endforeach
                                            </select>
                                            <!-- <span class="form-text text-muted">Please enter your full
                                                name</span> -->
                                        </div>
                                    </div>
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Title:</label>
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

                              
                       <input type="hidden" name="eventid" value="{{$events->id}}">

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Description:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control form-border" placeholder="Description" name="description" required>{{$events->description}}</textarea>
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
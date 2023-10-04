@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />
<style>
    ol.progtrckr {
    margin: 0;
    padding: 0;
    list-style-type none;
}

ol.progtrckr li {
    display: inline-block;
    text-align: center;
    line-height: 3.5em;
}

ol.progtrckr[data-progtrckr-steps="2"] li { width: 49%; }
ol.progtrckr[data-progtrckr-steps="3"] li { width: 33%; }
ol.progtrckr[data-progtrckr-steps="4"] li { width: 24%; }
ol.progtrckr[data-progtrckr-steps="5"] li { width: 19%; }
ol.progtrckr[data-progtrckr-steps="6"] li { width: 16%; }
ol.progtrckr[data-progtrckr-steps="7"] li { width: 14%; }
ol.progtrckr[data-progtrckr-steps="8"] li { width: 12%; }
ol.progtrckr[data-progtrckr-steps="9"] li { width: 11%; }

ol.progtrckr li.progtrckr-done {
    color: black;
    border-bottom: 4px solid yellowgreen;
    display: -webkit-inline-box;
}
ol.progtrckr li.progtrckr-todo {
    color: silver; 
    border-bottom: 4px solid silver;
    display: -webkit-inline-box;
}

ol.progtrckr li:after {
    content: "\00a0\00a0";
}
ol.progtrckr li:before {
    position: relative;
    bottom: -2.5em;
    float: left;
    left: 50%;
    line-height: 1em;
}
ol.progtrckr li.progtrckr-done:before {
    content: "\2713";
    color: white;
    background-color: yellowgreen;
    height: 2.2em;
    width: 2.2em;
    line-height: 2.2em;
    border: none;
    border-radius: 2.2em;
}
ol.progtrckr li.progtrckr-todo:before {
    content: "\039F";
    color: silver;
    background-color: white;
    font-size: 2.2em;
    bottom: -1.2em;
}
    </style>
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Order Details</h2>
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

                <div class="container">

                    
                   
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Order Summary</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order No:</label>{{$deliver_anything->order_no}}
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Customer Name:</label>{{$deliver_anything->user->name}}
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Shop Name:</label>{{$deliver_anything->shop_name ?? 'NA'}}
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Mobile:</label>{{$deliver_anything->mobile ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Shop Location:</label>{{$deliver_anything->shop_location ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Shop Latitude:</label>{{$deliver_anything->shop_latitude ?? 'NA'}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Shop Longitude:</label>{{$deliver_anything->shop_longitude ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order Current Status:</label> <kbd>{{$deliver_anything->orderStatus->name ?? 'NA'}} </kbd>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Image:</label> <img src="{{URL('storage/app/'.$deliver_anything->image)}}" width="60" height="60">
                                        </div>
                                    </div>
                                    <br>
                                  
                                    
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Orderd Date:</label>{{($deliver_anything->created_at)->format('d-m-Y h:i A')}}
                                        </div>
                                        <!-- <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Current Status:</label> {{$deliver_anything->status ==0  ? 'InActive' :'Active'}} 
                                        </div> -->
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="col-xs-12 col-lg-12 col-form-label">Notes:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                       
                                        <div class="col-xs-12 col-lg-12 col-form-label">
                                            <textarea class="form-control form-border" name="about" readonly> {{nl2br($deliver_anything->note ?? '')}}</textarea>
                                        </div>
                                    </div><br>

                                    
                               
                                 
                                </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Product Details</b></div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Quantity</th>
                                      
                                        </tr>
                                    </thead>
                                    <tbody><?php
                                    if(!is_null($deliver_anything->buy_items))
                                    {
                
                                        $items =json_decode($deliver_anything->buy_items);

                                        if(count($items)>0)
                                        {?>
                               
                                    @forelse($items as $order_product)
                                        <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>{{$order_product->name ?? 'NA'}}</td>
                                        <td>{{$order_product->quantity ?? 'NA'}}</td>
                                       
                                        </tr>
                                    @empty
                                        <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>NA</td>
                                        <td>NA</td>
                                        <td>NA</td>
                                        <td>NA</td>
                                        </tr>
                                    @endforelse   
                                        <?php } 
                                        
                                        }?>
                                    </tbody>
                                    </table>
                                </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Address</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header"><h5>Delivery Address</h5></div>
                                                <div class="card-body">
                                                    
                                                        @if($del_address)
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Name:</label>{{$del_address->name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Building Name:</label>{{$del_address->build_name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$del_address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$del_address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">LandMark:</label>{{$del_address->landmark ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Latitude:</label>{{$del_address->latitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Longitude:</label>{{$del_address->longitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Mobile:</label>{{$del_address->mobile ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Pincode:</label>{{$del_address->pincode ?? ''}}
                                                        </div>
                                                    </div>
                                                      @endif
                                                </div> 
                                                <div class="card-footer"></div>
                                            </div>
                                        </div>
                                    <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header"><h5>Picup Address</h5></div>
                                                <div class="card-body">
                                                   
                                                        @if($pic_address)
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Name:</label>{{$pic_address->name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Building Name:</label>{{$pic_address->build_name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$pic_address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$pic_address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">LandMark:</label>{{$pic_address->landmark ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Latitude:</label>{{$pic_address->latitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Longitude:</label>{{$pic_address->longitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Mobile:</label>{{$pic_address->mobile ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Pincode:</label>{{$pic_address->pincode ?? ''}}
                                                        </div>
                                                    </div>
                                                        @endif
                                                    
                                                </div> 
                                                <div class="card-footer"></div>
                                            </div>
                                        </div> 
                                    </div>

                                </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Status Summary</b></div>
                                <div class="panel-body">
                                   
                                    <div class="row shop-tracking-status">
    
                                        <div class="col-md-12">
                                            <div class="well">
                                                <form class="d-flex" method="post" action="{{URL('admin/deliveranything/changeOrderStatus')}}"  enctype='multipart/form-data'>
                                                {{csrf_field()}}
                                                <div class="form-horizontal">
                                                    <div class="form-group">
                                                    <div class="row">
                                                        <label for="inputOrderTrackingID" class="col-sm-2 control-label">Order No:{{$deliver_anything->order_no}}</label>
                                                            <div class="col-sm-10">
                                                            <select class="form-control" name="status" id="order_status" required>
                                                                <option value="">--Select Status--</option>
                                                                @forelse($status as $stat)
                                                                    <option value="{{$stat->slug}}" {{($stat->id == $deliver_anything->order_status) ? 'selected="selected"': ''}}>{{$stat->name}}</option>
                                                                @empty
                                                                    <option value="" disabled>No status available</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <label for="inputOrderTrackingID" class="col-sm-2 control-label">Driver Assign</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="driver" id="driver_id" required>
                                                                <option value="">--Select Driver--</option>
                                                                @forelse($drivers as $driver)
                                                                    <option value="{{$driver->id}}" {{($driver->id == $deliver_anything->driver_id) ? 'selected="selected"': ''}}>{{$driver->name}}</option>
                                                                @empty
                                                                    <option value="" disabled>No drivers available</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <label for="inputOrderTrackingID" class="col-sm-2 control-label">Assign Date</label>
                                                        <div class="col-sm-10">
                                                            <input type="datetime-local" class="form-control form-border" placeholder="Assign Date" name="assign_date" <?php if(optional($deliver_anything->deliveryData)->assign_date != null) { ?> value="{{Carbon\Carbon::parse($deliver_anything->deliveryData->assign_date)->format('Y-m-d\TH:i') ?? old('assign_date' , date('Y-m-d'))}}" 
                                                        <?php } else { ?> value="{{old('assign_date' , date('Y-m-d'))}}" <?php } ?> required="required">
                                                        </div>
                                                    </div>

                                                    <input type="hidden" id="order_id" name="order_id" value="{{$deliver_anything->id}}">
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-sm-offset-11 col-sm-10">
                                                        <div class="form-controll">
                                                            <button type="submit" id="OrderStatusID" class="btn btn-success myButtonID">Change</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
                                                </div>
                                           
                                           </div>
   
                                       </div>
   
                                       
                                       
                                      
                                   </div>
                           </div>

                        
            </div>
        </div>
    </div>

</div>


@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
<script>

</script>
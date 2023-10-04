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

.dot {
  height: 15px;
  width: 15px;
  border-radius: 50%;
  display: inline-block;
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
                                            <a href="{{URL('/admin/order/invoice_download').'/'.$order->id}}" class="btn btn-success"><i class="fa fa-download"></i> Invoice</a>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order No:</label>{{$order->order_no}}
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Customer Name:</label>{{$order->user->name}}
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Shop Name:</label>{{$order->shop->name}}
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Payment Method:</label>{{$order->payment_method ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Discount:</label>{{$order->discount ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Discount Percent:</label>{{$order->discount_percent ?? 'NA'}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Tax Amount:</label>{{$order->tax_amount ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Current Status:</label> <kbd>{{$order->orderStatus->name ?? 'NA'}} </kbd>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Delivery Date:</label>
                                            @if($deliver_status != null){{($deliver_status->created_at)->format('d-m-Y h:i A') ?? 'NA'}}
                                            @else
                                            NA
                                            @endif
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Invoice:</label>{{$order->invoice ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Delivery Fee:</label>{{$order->delivery_fee ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Redeemed Point:</label>{{$order->points ?? 'NA'}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Paid Amount:</label>{{$order->amount ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Orderd Date:</label>{{ date('d-m-Y h:i A', strtotime($order->created_at)) }}
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Invoice Date:</label>
                                            @if($order->invoice_date != null)
                                            {{ date('d-m-Y h:i A', strtotime($order->invoice_date)) }}
                                            @else
                                            NA
                                            @endif
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <?php 
                                        if($order->instore == 1) {
                                            $instore = 'Yes';
                                        } else {
                                            $instore = 'No';
                                        }
                                        ?>

                                        <div class="col-sm-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Instore:</label>{{ $instore }}
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="col-xs-12 col-lg-12 col-form-label">Comments:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                       
                                        <div class="col-xs-12 col-lg-12 col-form-label">
                                            <textarea class="form-control form-border" name="about" readonly> {{nl2br($order->comments ?? '')}}</textarea>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="col-xs-12 col-lg-12 col-form-label">Reject Reason:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                       
                                        <div class="col-xs-12 col-lg-12 col-form-label">
                                            <textarea class="form-control form-border" name="about" readonly> {{nl2br($order->reason?? '')}}</textarea>
                                        </div>
                                    </div>
                                 
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
                                        <th scope="col">Price</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Total Discount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($order->orderProducts as $order_product)

                                        @php

                                        $attribute_values = Modules\Product\Entities\ProductAttribute::where(['product_id' => $order_product->product_id])->withTrashed()->get();
                                            
                                        
                                        
                                        @endphp

                                        <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>{{$order_product->productData->name ?? 'NA'}} @if(optional($order_product->productData)->brand != null) - {{ $order_product->productData->brand->name }} @endif 
                                        @foreach($attribute_values as $attribute_value)

                                        @if(strtoupper($attribute_value->attributeData->field_name) == 'COLOR' || strtoupper($attribute_value->attributeData->field_name) == 'COLOUR')
                                        
                                        <p>
                                            {{$attribute_value->attributeData->field_name}} : <span class="dot" style="background-color:{{$attribute_value->attr_value}}"> </span>
                                        </p>
                                        
                                        @else
                                        <p>{{$attribute_value->attributeData->field_name}} : {{$attribute_value->attr_value}}</p>
                                        @endif

                                        @endforeach
                                        </td>
                                        <td>{{$order_product->product_quantity ?? 'NA'}}</td>
                                        <td>{{$order_product->product_price ?? 'NA'}}</td>
                                        <td>{{$order_product->product_discount ?? 'NA'}}</td>
                                        <td>{{$order_product->tot_price ?? 'NA'}}</td>
                                        <td>{{$order_product->tot_discount ?? 'NA'}}</td>
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
                                    </tbody>
                                    </table>
                                </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Address Details</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header"><h5>Home Address</h5></div>
                                                <div class="card-body">
                                                    @foreach($order->orderAddressesView as $address)
                                                        @if($address->type === 0 || $address->type === 2)
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Name:</label>{{$address->name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Building Name:</label>{{$address->build_name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">LandMark:</label>{{$address->landmark ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Latitude:</label>{{$address->latitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Longitude:</label>{{$address->longitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Mobile:</label>{{$address->mobile ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Pincode:</label>{{$address->pincode ?? ''}}
                                                        </div>
                                                    </div>
                                                        @endif
                                                    @endforeach
                                                </div> 
                                                <div class="card-footer"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header"><h5>Office Address</h5></div>
                                                <div class="card-body">
                                                    @foreach($order->orderAddressesView->where('type',1) as $address)
                                                        @if($address->type === 1)
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Name:</label>{{$address->name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Building Name:</label>{{$address->build_name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">LandMark:</label>{{$address->landmark ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Latitude:</label>{{$address->latitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Longitude:</label>{{$address->longitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Mobile:</label>{{$address->mobile ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Pincode:</label>{{$address->pincode ?? ''}}
                                                        </div>
                                                    </div>
                                                        @endif
                                                    @endforeach
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
                                          
                                                <form class="d-flex">
                                                {{csrf_field()}}
                                                <div class="form-horizontal">
                                                    <div class="form-group">
                                                        <label for="inputOrderTrackingID" class="col-sm-2 control-label">Order No:{{$order->order_no}}</label>
                                                            <div class="col-sm-10">
                                                            <select class="form-control" name="status" id="order_status">
                                                                <option value="">--Select Status--</option>
                                                                @forelse($status_val as $stat)
                                                                    <option value="{{$stat->slug}}" {{($stat->id == $order->status_id) ? 'selected="selected"': ''}}>{{$stat->name}}</option>
                                                                @empty
                                                                    <option value="" disabled>No status available</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="order_id" value="{{$order->id}}">
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" id="OrderStatusID" class="btn btn-success myButtonID">Change status</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
                                        
                                                <h4>Your order status:</h4>

                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <span class="prefix">Date created:</span>
                                                        <span class="label label-success">{{($order->created_at)->format('d-m-Y h:i A')}}</span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="prefix">Last update:</span>
                                                        <span class="label label-success">{{($order->updated_at)->format('d-m-Y h:i A')}}</span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="prefix">Current Status:</span>
                                                        @if($order->orderStatus->slug == 'rejected' || $order->orderStatus->slug == 'cancelled')
                                                        <span class="label label-danger">{{$order->orderStatus->name ?? 'NA'}}</span>
                                                        @else
                                                        <span class="label label-success">{{$order->orderStatus->name ?? 'NA'}}</span>
                                                        @endif
                                                    </li>
                                                    {{--   <li class="list-group-item">You can find out latest status of your order with the following link:</li>
                                                   <li class="list-group-item"><a href="//tracking/link/goes/here">//tracking/link/goes/here</a></li>--}}
                                                </ul>
                                                <ol class="progtrckr" data-progtrckr-steps="5">
                                                <li style="display:none"></li><?php echo "<!--"; ?>
                                                    @foreach($order_status as $status)
                                                       
                                                        @if($status->status->slug == 'cancelled')
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Cancelled {{($status->updated_at)->format('d-m-Y h:i A')}}</li><?php echo "<!--"; ?>
                                                            
                                                        @elseif($status->status->slug == 'ordered')
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Ordered {{($status->updated_at)->format('d-m-Y h:i A')}}</li><?php echo "<!--"; ?>
                                                            
                                                        @elseif($status->status->slug == 'accepted')
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Accepted {{($status->updated_at)->format('d-m-Y h:i A')}} </li><?php echo "<!--"; ?>

                                                        @elseif($status->status->slug == 'shipped')
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Shipped {{($status->updated_at)->format('d-m-Y h:i A')}}</li><?php echo "<!--"; ?>
                                                          
                                                        @elseif($status->status->slug == 'delivered')
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Delivered {{($status->created_at)->format('d-m-Y h:i A')}}</li><?php echo "<!--"; ?>
                                                         
                                                        @elseif($status->status->slug == 'rejected')
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Rejected {{($status->updated_at)->format('d-m-Y h:i A')}}</li><?php echo "<!--"; ?>
                                                            
                                                        @endif
                                                    @endforeach
                                                    <!--<li class="progtrckr-todo">Shipped</li> // halfly done -->
                                                </ol>
                                    
                                            </div>
                                           
                                        </div>

                                    </div>

                                    
                                    
                                   
                                </div>
                        </div>

    



            </div>
        </div>
    </div>

</div>
<!-- /page content -->


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reject Reason: <span></span></h4>
            </div>
            <div class="modal-body">
                <form class="form-group" method="post" id="editCommunityForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <textarea class="form-control" name="reject" id="reject" required rows="3"></textarea>
                </form>
                <button class="btn btn-primary dropdown-toggle waves-effect waves-light"  type="submit" form="editCommunityForm">Update Reason</button><br /><br />
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            </div>
        </div>
    </div>
</div>


@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
<script>
    $('body').on('click','#OrderStatusID',function(e)
  {

  	e.preventDefault();
      
  	var status=$("#order_status").val();
  	var order_id=$("#order_id").val();

      $.ajaxSetup({
			 headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 });

	  if( status !='') { 
          if(status == 'rejected')
          {
         
            $('#myModal').modal("show");

          }else{

            $(".myButtonID")
            .html("Please Wait...")
            .attr('disabled', 'disabled');
          
          $.ajax({ 
			 url: base_url+'/admin/order/getOrderStatus',
			 type: 'post',
			 dataType: 'json',
			 data:{order_id:order_id,status:status,_token: '{{csrf_token()}}'},
			 success: function(response){
				 location.reload();
	  
		    }

	      });
        }
 }else{
	location.reload();
 }

});

$('#editCommunityForm').on('submit', function (e) {

e.preventDefault();

var status=$("#order_status").val();
  var order_id=$("#order_id").val();
var reject = $("#reject").val();

$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });

if( status !='') { 
  if(status == 'rejected')
  {
        $.ajax({ 
        url: base_url+'/admin/order/getOrderStatus',
        type: 'post',
        dataType: 'json',
        data:{order_id:order_id,status:status,reject:reject,_token: '{{csrf_token()}}'},
        success: function(response){
            location.reload();

        }

        });
  }
}

});
</script>
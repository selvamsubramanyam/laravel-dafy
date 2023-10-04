
    <!-- top tiles -->
    <div class="row">


        <div class="">
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
                                        <div class="col-md-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order No:</label>{{$order->order_no}}
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Customer Name:</label>{{$order->user->name}}
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Shop Name:</label>{{$order->shop->name}}
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Payment Method:</label>{{$order->payment_method ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Discount:</label>{{$order->discount ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Discount Percent:</label>{{$order->discount_percent ?? 'NA'}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Tax Amount:</label>{{$order->tax_amount ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Current Status:</label> <kbd>{{$order->orderStatus->name ?? 'NA'}} </kbd>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Delivery Date:</label>{{$order->delivery_date ?? 'NA'}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Invoice:</label>{{$order->invoice ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Delivery Fee:</label>{{$order->delivery_fee ?? 'NA'}}
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Amount:</label>{{$order->amount ?? 'NA'}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Orderd Date:</label>{{($order->created_at)->format('d-m-Y h:i A')}}
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
                                        <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>{{$order_product->productData->name ?? 'NA'}}</td>
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

                        <!-- <div class="panel panel-default">
                            <div class="panel-heading"><b>Address Details</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header"><h5>Home Address</h5></div>
                                                <div class="card-body">
                                                    @foreach($order->orderAddressesView->where('type',0) as $address)
                                                        @if($address->type === 0)
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Name:</label>{{$address->name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Building Name:</label>{{$address->build_name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">LandMark:</label>{{$address->landmark ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Latitude:</label>{{$address->latitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Longitude:</label>{{$address->longitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Mobile:</label>{{$address->mobile ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
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
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Name:</label>{{$address->name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Building Name:</label>{{$address->build_name ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Area:</label>{{$address->area ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">LandMark:</label>{{$address->landmark ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Latitude:</label>{{$address->latitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Longitude:</label>{{$address->longitude ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="col-xs-6 col-lg-6 col-form-label">Mobile:</label>{{$address->mobile ?? ''}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-8">
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
                        </div> -->

                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Status Summary</b></div>
                                <div class="panel-body">
                                   
                                    <div class="row shop-tracking-status">
    
                                        <div class="col-md-12">
                                            <div class="well">
                                
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
                                                        <?php echo "-->"; ?><li class="progtrckr-done">Delivered {{($status->updated_at)->format('d-m-Y h:i A')}}</li><?php echo "<!--"; ?>
                                                         
                                                        @else
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
<!-- /page content -->


@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main" onload="load()">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Order Management</h2>
                    <div class="clearfix"></div>
                    
                </div>
               
                                <form class="d-flex">
                                {{csrf_field()}}
                                    <div class="row">
                                        @if($status !== 'all')

                                            @if(Auth::guard('seller')->check() && $status=='accepted')

                                            <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Status:</label> 
                                            <div class="col-xs-12 col-lg-8 ">
                                                <select class="form-control" name="Status" id="status">
                                                    <option value=" "> All</option>
                                                  
                                                    <option value="accepted">Accepted</option>
                                                    <option value="shipped">Shipped</option>
                                                    <option value="delivered">Delivered</option>
                                                    <option value="cancelled">Cancelled</option>
                                                  
                                                </select>
                                            </div>
                                           
                                        </div>

                                            @else
                                                <div class="col-md-4">
                                                    <label class="col-xs-12 col-lg-4 col-form-label"></label> 
                                                    <div class="col-xs-12 col-lg-8 ">
                                                    </div>
                                            
                                                </div>
                                            @endif
                                        @else
                                         <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Status:</label> 
                                            <div class="col-xs-12 col-lg-8 ">
                                                <select class="form-control" name="Status" id="status">
                                                    <option value=" "> All</option>
                                                    <option value="ordered">Ordered</option>
                                                    <option value="accepted">Accepted</option>
                                                    <option value="shipped">Shipped</option>
                                                    <option value="delivered">Delivered</option>
                                                    <option value="cancelled">Cancelled</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>
                                           
                                        </div>
                                        @endif
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order From:</label>
                                            <div class=" col-lg-8">
                                            <input type="date" class="form-control form-border"  id="orderfrom" placeholder="Order From" name="orderfrom" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order To:</label>
                                            <div class="col-lg-8">
                                                <input type="date" class="form-control form-border" id="orderto" placeholder="Order To" name="orderto" value="">
                                            </div>
                                        </div>
                                       
                                        
                                    </div><br>
                                    <div class="row justify-content-end">
                                        <div class="text-right">
                                    
                                        <button type="Submit" class="btn btn-primary-blue btn-sm" id="searchorder" style="width:120px;border-radius:16px">Search</button>
                                        </div>
                                    </div>
                                   
                                    </form> 

                <div class="x_content">
                    <div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Order Deleted Succeefully
                        </div>
                        <div class="text-right">
                            
                            <button class="btn  btn-success btn-sm add-prod-btn br-0 export_order" type="button"><i class="fa fa-download"> Export</i></button>

                            <!-- <a href="{{route('order.export',$status)}}" class="btn  btn-success btn-sm add-prod-btn br-0">
                                <i class="fa fa-download"></i> Export
                            </a> -->
                           
                        </div>
               
                        <input type="hidden" value="{{$status}}" id="status">

                        <input type="hidden" value="{{Auth::guard('admin')->check() ? 1 : 0 }}" id="current_role">

        
                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="order_list_datatable">
                          
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >

                                <th data-field="order_no" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Order No</center> 
                                </th>

                                <th data-field="user_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>User Name</center> 
                                </th>

                                <th data-field="shop_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Shop Name</center>
                                </th>

                                <th data-field="discount" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Discount</center>
                                </th>

                                <th data-field="amount" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Amount</center> 
                                </th>

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Order Date</center> 
                                </th>

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Status</center> 
                                </th>
                                

                             
                                <th data-field="Actions" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center> Actions</center>
                                </th>
                                    
                                </tr>

                            </thead>

                        </table>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <!-- /page content -->
</div>

<!-- reject  -->
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
                    <input type="hidden" value="" name="order_id" id="order_id">
                    <input type="hidden" value="rejected" name="status_id" id="status-id">
                    <textarea class="form-control" name="reject" id="reject" required rows="3"></textarea>
                </form>
                <button class="btn btn-primary dropdown-toggle waves-effect waves-light" type="submit" form="editCommunityForm">Update Reason</button><br /><br />
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            </div>
        </div>
    </div>
</div>


<!-- assign  -->
<div class="modal fade" id="myModalAssign" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order Assign: <span></span></h4>
            </div>
            <div class="modal-body">
                <form class="form-group" method="post" id="editAssignForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                     <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Order No*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        <input type="text" class="form-control" value="" name="order_no" id="assign_order-id" readonly required>
                                          
                                        </div>
                                       
                                    </div>
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Driver Name*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        <select class="form-control"  name="driver_id" id="driver-id" required>
                                          <option value="">--SELECT--</option>
                                          </select>
                                        </div>
                                       
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Assign Date*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                        <input type="datetime-local" id="assign_date" value="" class="form-control" name="assign_date"  required>
                                        </div>
                                       
                                    </div>
                   
                </form>
                <button class="btn btn-primary dropdown-toggle waves-effect waves-light" type="submit" form="editAssignForm">Assign</button><br /><br />
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            </div>
        </div>
    </div>
</div>
<?php  $phpVariable = "<script>document.write(javascriptVariable);</script>"; ?>

@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>

$('body').on('click','.export_order',function(e)
{
    
    var from = $("#orderfrom").val();
    var to = $("#orderto").val();
    var status = $("#status").val();
    // var type = $("#type").val();
    // alert(from);
    // alert(to);
    // alert(status_id);
    window.location.href = base_url+'/admin/export_order_report?from='+from+'&to='+to+'&status='+status;
});

  function autoRefreshPage()
    {
        window.location = window.location.href;
    }
    setInterval('autoRefreshPage()', 60000);

    $('body').on('click','.order_status',function(e)
    {

  	e.preventDefault();
      
      var order_id = $(this).data("id");
      var status_slug = $(this).val();
   
  	
      $.ajaxSetup({
			 headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 }
		 });

	  if( status_slug !='') { 
          if(status_slug == 'rejected')
          {
            $('#order_id').val(order_id)
            $('#myModal').modal("show");

          }else{
            var confirmation = confirm("are you sure you want to approve the order?");

            if (confirmation) {
                $.ajax({ 
                    url: base_url+'/admin/order/getOrderStatus',
                    type: 'post',
                    dataType: 'json',
                    data:{order_id:order_id,status:status_slug,_token: '{{csrf_token()}}'},
                    success: function(response){
                        location.reload();
            
                    }

                });
            }
        }
 }else{
	location.reload();
 }

});

$('body').on('click','.order_assign',function(e)
  {

    e.preventDefault();

    var order_id = $(this).data("id");
    var order_no = $(this).val();

    $.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });
  
    if( order_id !='') { 
        $.ajax({
			    type: "GET",
			    url: base_url+'/admin/order/assignDetails/'+order_id,
			   
			     success: function(result) 
			        {
                        if(result)
                        {
                            result = JSON.parse(result);
                        }
                        
        $('#assign_order-id').val(order_no);
        
        $('#myModalAssign').modal("show");

         $.ajax({
			    type: "GET",
			    url: base_url+'/admin/order/assignlists/',
			   
			     success: function(data) 
			        {
			        	

			        	  var elements = $();

                    	 elements=elements.add('<option value="">Select</option>');
			            $.each(JSON.parse(data), function (key, val) {
                        if(result)
                        {
                            if(result.driver_id == val.id)
			            	var selected = "selected";
			            else
                            var selected = "";
                        }else{
                            var selected = "";
                        }
			         elements = elements.add('<option value="'+val.id+'" '+selected+' >'+val.name+'</option>');

			                 }); 
			                   

                        $("#driver-id").html(elements);
                       if(result)
                       {
                        var today = new Date(result.assigned_date);
                        var date = today.getFullYear()+'-'+adjust(today.getMonth()+1)+'-'+adjust(today.getDate());
                        var time = adjust(today.getHours()) + ":" + adjust(today.getMinutes());
                        var dateTime = `${date}T${time}`;
                         $("#assign_date").val(dateTime);
                       }else{
                        $("#assign_date").val('');
                       }

			         },
	           });
			        

			         },
	           });
   
          
    }else{
	    location.reload();
    }

});

function adjust(v){
if(v>9){
return v.toString();
}else{
return '0'+v.toString();
}
}

$('#editCommunityForm').on('submit', function (e) {

e.preventDefault();

var order_id = $("#order_id").val();
var status_slug = $("#status-id").val();
var reject = $("#reject").val();
$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });

if( status_slug !='') { 
  if(status_slug == 'rejected')
  {
        $.ajax({ 
        url: base_url+'/admin/order/getOrderStatus',
        type: 'post',
        dataType: 'json',
        data:{order_id:order_id,status:status_slug,reject:reject,_token: '{{csrf_token()}}'},
        success: function(response){
            location.reload();

        }

        });
  }
}

});


$('#editAssignForm').on('submit', function (e) {

e.preventDefault();

var order_no = $("#assign_order-id").val();
var driver_id = $("#driver-id").val();
var assign_date = $("#assign_date").val();
$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });

        $.ajax({ 
        url: base_url+'/admin/order/storeDriverOrder',
        type: 'post',
        dataType: 'json',
        data:{order_no:order_no,driver_id:driver_id,assign_date:assign_date,_token: '{{csrf_token()}}'},
        success: function(response){
            if(response.success)
            {
                Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 15000
          })
          $('#myModalAssign').modal("hide");
          location.reload();
            }
          

        }

        });
  
});
</script>
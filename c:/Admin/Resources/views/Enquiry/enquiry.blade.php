@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/dev.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<style>

        .select2-container--default .select2-selection--multiple .select2-selection__choice .shop{
            background-color:  #d2d9dd;
            border-color: #367fa9;
            padding: 1px 100px;
            color: #151313 !important;
        }
        .select2 {
            width:100%!important;
        }
        .form-style-5{
            max-width: 700px;
            padding: 10px 20px;
            background: #f4f7f8;
            margin: 10px auto;
            padding: 20px;
            background: #f4f7f8;
            border-radius: 8px;
            font-family: Georgia, "Times New Roman", Times, serif;
        }
        .form-style-5 fieldset{
            border: none;
        }
        .form-style-5 legend {
            font-size: 1.4em;
            margin-bottom: 10px;
        }
        .form-style-5 label {
            display: block;
            margin-bottom: 8px;
        }
        .form-style-5 input[type="text"],
        .form-style-5 input[type="date"],
        .form-style-5 input[type="datetime"],
        .form-style-5 input[type="email"],
        .form-style-5 input[type="password"],
        .form-style-5 input[type="number"],
        .form-style-5 input[type="search"],
        .form-style-5 input[type="time"],
        .form-style-5 input[type="url"],
        .form-style-5 input[type="file"],
        .form-style-5 textarea,
        .form-style-5 select {
            font-family: Georgia, "Times New Roman", Times, serif;
            background: rgba(255,255,255,.1);
            border: none;
            border-radius: 4px;
            font-size: 16px;
            margin: 0;
            outline: 0;
            padding: 7px;
            width: 100%;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            background-color: #e8eeef;
            color:#8a97a0;
            -webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
            box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
            margin-bottom: 30px;

        }
        .form-style-5 input[type="text"]:focus,
        .form-style-5 input[type="date"]:focus,
        .form-style-5 input[type="datetime"]:focus,
        .form-style-5 input[type="email"]:focus,
        .form-style-5 input[type="number"]:focus,
        .form-style-5 input[type="search"]:focus,
        .form-style-5 input[type="time"]:focus,
        .form-style-5 input[type="checkbox"]:focus,
        .form-style-5 input[type="url"]:focus,
        .form-style-5 textarea:focus,
        .form-style-5 select:focus{
            background: #d2d9dd;
        }
        .form-style-5 select{
            -webkit-appearance: menulist-button;
            height:35px;
        }
        .form-style-5 .number {
            background: #1e88e5;
            color: #fff;
            height: 30px;
            width: 30px;
            display: inline-block;
            font-size: 0.8em;
            margin-right: 4px;
            line-height: 30px;
            text-align: center;
            text-shadow: 0 1px 0 rgba(255,255,255,0.2);
            border-radius: 15px 15px 15px 0px;
        }

        .form-style-5 input[type="submit"],
        .form-style-5 input[type="button"],
        .btn1
        {
            position: relative;
            display: block;
            padding: 19px 39px 18px 39px;
            color: #FFF;
            margin: 0 auto;
            background: #1e88e5;
            font-size: 18px;
            text-align: center;
            font-style: normal;
            width: 100%;
            border: 1px solid #1e88e5;
            border-width: 1px 1px 3px;
            margin-bottom: 10px;
        }
        .form-style-5 input[type="submit"]:hover,
        .form-style-5 input[type="button"]:hover
        {
            background: #109177;
        }
        .er{
            color:red;
        }
        .select2-container--open {
    z-index: 9999999
}
    </style>
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Enquiry List</h2>
                    <div class="clearfix"></div>
                    
                </div>

                <div class="x_content">
<div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Enquiry Deleted Succeefully
                        </div>
            {{--    <div class="text-right mb-2">
                        <a href="{{URL('admin/enquiry/add')}}" class="btn  btn-primary-blue btn-sm add-prod-btn br-0">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>--}}

                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="enquiry_list_datatable">
                           
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >

                                <th data-field="name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>User Name</center> 
                                </th>

                                <th data-field="Shop_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Shop Name</center>
                                </th>

                                <th data-field="Category" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Category</center>
                                </th>

                                <th data-field="Sub category" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Sub category</center> 
                                </th>

                                <th data-field="location" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Location</center> 
                                </th>
                                
                                <th data-field="Mobile" class="m-datatable__cell m-datatable__cell--sort column-title"> 
                                    <center>Mobile</center>
                                </th>

                                <th data-field="product_detail" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Product Detail</center>
                                </th>

                                <th data-field="product_name" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Product Name</center>
                                </th>

                                <th data-field="expected_purchase" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Expected purchase</center>
                                </th>

                                <th data-field="action" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Action</center>
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


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Custom Message: <span></span></h4>
            </div>
            <div class="modal-body">
                <form class="form-group" method="post" id="editNotifyForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  
                    <div id="shops" class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please select shop*:</label>
                                        <div class="col-xs-12 col-lg-8">
                                       
                                            <select name="shops" id="my-select"  class="form-control shop select2 @error('shops') is-invalid @enderror" required>    
                                            <option value="">--Select shop--</option>
                                          
                                               
                                            </select>

                                        
                                        </div>
                                    </div>

                                            
                    <input type="hidden" value="" name="enquiry_id" id="enquiry-id">
                   
                    <textarea class="form-control" name="message" id="message" required rows="3"></textarea>
                </form>
                <button class="btn btn-primary dropdown-toggle waves-effect waves-light" type="submit" form="editNotifyForm">Sent Notification</button><br /><br />
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalnotified" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Notification: <span></span></h4>
            </div>
            <div class="modal-body">
                
                  
                    <div  class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Shop Name:</label>
                                        <div class="col-xs-12 col-lg-8">
                                    <input type="text" class="form-control"  name="shop_name" id="enquiry-shop" readonly>
                                    </div> 
                                    </div>

                                            
                                    <div  class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Message:</label>
                                    <div class="col-xs-12 col-lg-8">
                                    <textarea class="form-control" name="message" id="enquiry-message"  rows="3" readonly></textarea>
                                    </div> 
                                    </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Image loader -->
<div id='loader' style='display: none;'>
  <img src='http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif' width='32px' height='32px'>
</div>
<!-- Image loader -->
@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
     
    $(document).ready(function() {
        
        $(".shop").select2({
    dropdownParent: $("#myModal")
  });
 });

 $('.shop').select2({
     width: '100%'
});
    
</script>
<script>
$('body').on('click','.enquiry_notify',function(e)
  {

  	e.preventDefault();
      
      var shop_id = $(this).data("id");
      var enquiry_id = $(this).val(); 
	
          if(enquiry_id)
          {
          
            $('#myModal').modal("show");
           
           
            $.ajax({ 
            url: "{{route('enquiry.shop.search')}}",
            type: "get",
            dataType: 'json',
            delay: 250,
            success: function(data)
            {
            
                var ex = $();

                ex=ex.add('<option value="">--Select--</option>');
                $.map(data, function (item) {
                    if(item.id == shop_id)
                                        var selected = "selected";
                                    else
                                        var selected = "";
                        ex = ex.add('<option value="'+item.id+'" '+selected+' >'+item.name+'</option>');
                        
                    
                });
                $('#enquiry-id').val(enquiry_id);
                $("#my-select").html(ex);
            }
        
        // cache: true
        })
          }else{
	location.reload();
 }
 });

 $('#editNotifyForm').on('submit', function (e) {

e.preventDefault();

var shop_id=$("#my-select").val();
var enquiry_id=$("#enquiry-id").val();
var message=$("#message").val();

$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });

if( enquiry_id !='') { 
 
        $.ajax({ 
        url: base_url+'/admin/sent/enquiry',
        type: 'post',
        dataType: 'json',
        data:{shop_id:shop_id,enquiry_id:enquiry_id,message:message,_token: '{{csrf_token()}}'},
        beforeSend: function(){
            // Show image container
            $("#loader").show();
        },
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
          $('#myModal').modal("hide");
          location.reload();
            }
           

        },
        complete:function(data){
            // Hide image container
            $("#loader").hide();
        }

        });
  
}

});

$('body').on('click','.notified',function(e)
  {

  	e.preventDefault();
      
     // var shop_id = $(this).data("id");
      var enquiry_id = $(this).val(); 
	
          if(enquiry_id)
          {
            var url = "{{route('enquiry.notified',':id')}}";
            url = url.replace(':id',enquiry_id);
            $.ajax({ 
            url: url,
            type: "get",
            dataType: 'json',
            delay: 250,
            contentType:'application/json',
            success: function(data)
            {
               if(data) {
             
                $('#enquiry-message').val(data.enquiry_result.message);
                
                $('#enquiry-shop').val(data.shop.name);
                $('#modalnotified').modal("show");
               }else{
                   alert('Enquiry not found');
               }
            }
        
        // cache: true
        })
          }else{
	location.reload();
 }
 });


</script>
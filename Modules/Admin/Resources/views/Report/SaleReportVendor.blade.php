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
                    <h2>Vendor Sales Report</h2>
                    <div class="clearfix"></div>
                    
                </div>
                            <div class="container"> 
                                @if(session()->has('message'))
                                <div class="m-alert m-alert--outline alert alert-danger alert-dismissible  show" role="alert" style="color:red; background-color: #fff">
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

               
                                <form class="d-flex" id="reportForm">
                                {{csrf_field()}}
                                    <div class="row">
                                       

                                   
                                        @if(Auth::guard('admin')->check())
                                            <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Select Shop:</label> 
                                            <div class="col-xs-12 col-lg-8 ">
                                            <select name="shops" id="my-select"  class="form-control shop saleshop select2 @error('shops') is-invalid @enderror" required>    
                                            <option value="">--Select shop--</option>
                                            @foreach($shops as $shop)
                                            <option value="{{$shop->id}}">{{$shop->name}}</option>
                                            @endforeach
                                               
                                            </select>

                                                <div class="invalid-feedback active">
                                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('shops') <span class="er">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                           
                                        </div>

                                    @else
                                        <input type="hidden" value="{{$shops->id}}" id="seller_id" class ="saleshop" name="shops">
                                    @endif
                                      
                                        <div class="col-md-4">
                                            <label class="col-xs-12 col-lg-4 col-form-label">Order From:</label>
                                            <div class=" col-lg-8">
                                            <input type="date" class="form-control form-border"  id="orderfrom" placeholder="Order From" name="orderfrom" value="" >
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
                                    
                                        <button type="Submit" class="btn btn-primary-blue btn-sm" id="searchreport" style="width:120px;border-radius:16px">Search</button>
                                        </div>
                                    </div>
                                   
                                    </form> 

                <div class="x_content">
                    <div class="m-alert m-alert--outline alert alert-success alert-dismissible fade" role="alert" style="color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                           Report Deleted Succeefully
                        </div>
                        <div class="text-right">
                        <form method="post" action="{{URL('/admin/vendor_payment/export')}}" >
                                {{csrf_field()}}
                                @if(Auth::guard('admin')->check())
                                    <input type="hidden"  name="shop_id" value ="" id="shop_val">
                                @else
                                    <input type="hidden"  name="shop_id" value ="{{$shops->id}}" id="shop_val">
                                @endif
                                <input type="hidden" name="from_date" value ="" id="from_val">
                                <input type="hidden" name="to_date" value ="" id="to_val">
                                <button type="Submit" class="btn btn-success btn-sm"  style="width:120px;border-radius:16px">Export</button>
                            </a>
                        </form>
                        </div>
               
                      

        
                    <div class="table-responsive table-responsive1">
                        <table class="table table-striped table-bordered jambo_table bulk_action m-datatable__table"  id="salereport_list_datatable">
                          
                            <thead class="m-datatable__head">
                                <tr class="headings m-datatable__row" >

                                <th data-field="sl_no" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Sl.No</center> 
                                </th>

                                <th data-field="date" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Order Date</center> 
                                </th>

                                <th data-field="date" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Delivered Date</center> 
                                </th>

                                <th data-field="order_id" class="m-datatable__cell m-datatable__cell--sort column-title">
                                    <center>Order Id</center>
                                </th>

                                <th data-field="order_detail" class="m-datatable__cell m-datatable__cell--sort column-title">
                                     <center>Order Detail</center>
                                </th>

                                <th data-field="dafy_commission" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Dafy Commission</center> 
                                </th>

                                <th data-field="payment_shop" class="m-datatable__cell m-datatable__cell--sort column-title">
                                   <center>Payment to Shop</center> 
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


<div class="modal" id="single-modal-in-the-page" tabindex="-1" role="dialog">
 <div class="modal-dialog" role="document">
   <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title">Report</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">

  </div>
  <div class="modal-footer">
{{--   <button type="button" class="btn btn-primary">Save changes</button> --}}
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</div>


@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/select2.full.js') }}"></script>
<script src="{{ URL('public/admin/js/advanced-form-element.js') }}"></script>
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>
     
     $('.shop').select2({
     width: '100%'
});
     
</script>
<script> 

$('body').on('click','.salereport',function(e)
{
    // var id = $('.salereport').attr("data-id");
    var id=$(this).attr('data-id');
   console.log(id);
   var modal=$('#single-modal-in-the-page');  
   $.ajax({
	 type:"GET",
	 url: base_url+'/admin/vendor_payment/view_report/'+id,
	
	 success:function(response){ console.log(response);
	   modal.find('.modal-body').html(response.html);
	   modal.modal('show');
	 },
	 error:function(error){
	   // Or handle the errors in your own way
	   console.log(error);
	 }

   });    
});

$('body').on('change click','.shop,#orderfrom,#orderto',function(e)
{

    var shop_id = $('.saleshop').val();
    var order_from = $('#orderfrom').val();
    var order_to = $('#orderto').val();
    $('#shop_val').val(shop_id);
    $('#from_val').val(order_from);
    $('#to_val').val(order_to);
});

// $('body').on('click','#salereport',function(e)
// {
	
//     var shop_id = $('.saleshop').val();
//    var order_from = $('#orderfrom').val();
//    var order_to = $('#orderto').val();

//    $.ajaxSetup({
//      headers: {
//          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//      }
//  });

// if(shop_id !='' && order_from != '' && order_to != '' ) { 
 
//         $.ajax({ 
//         url: base_url+'/admin/vendor_payment/export',
//         type: 'post',
//         dataType: 'json',
//         data:{shop_id:shop_id,order_from:order_from,order_to:order_to,_token: '{{csrf_token()}}'},
       
//         success: function(response){
//             if(response.success)
//             {
//                 Swal.fire({
//             position: 'top-end',
//             icon: 'success',
//             title: response.message,
//             showConfirmButton: false,
//             timer: 15000
//           })
//           $('#myModal').modal("hide");
//           location.reload();
//             }
           

//         },
//         complete:function(data){
//             // Hide image container
//             $("#loader").hide();
//         }

//         });
  
// }

// });

</script>
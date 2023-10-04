var base_url="http://localhost/dafy";
// var base_url="{{url('/')}}";
$(function() 
{

	setInterval(function() {

		$.ajax({
	          
	          type: "GET",
	          cache:true,
	          url: base_url+'/admin/notificationscount',
	         
	          //data:id,
	          success: function(result) 
	          {
	      	  

	           $('.notycount').html(result);

	          },
	           
	               contentType: false,
	               processData: false

	            });

		}, 1000* 60 *  1);


	$('body').on('click','.notification',function(e)
	{


	$.ajax({
	          
	          type: "GET",
	          cache:true,
	          url: base_url+'/admin/notifications',
	         
	          //data:id,
	          success: function(result) 
	          {


	          	 var elements = $();
	            $.each(JSON.parse(result), function (key, val) {

	            if(val.type == 'buy') {
	            	elements = elements.add('<li><span class="message">Order Id <strong><a href='+base_url+'/admin/buy_anything>'+val.number+'</a></strong> <a href='+base_url+'/admin/updatenotify/'+val.Id+'><i class="fa fa-check" aria-hidden="true"></i></a></span></li>');
	            } else if(val.type == 'deliver') {
	            	elements = elements.add('<li><span class="message">Order Id <strong><a href='+base_url+'/admin/del_anything>'+val.number+'</a></strong> <a href='+base_url+'/admin/updatedelivernotify/'+val.Id+'><i class="fa fa-check" aria-hidden="true"></i></a></span></li>');
	            }else {
	            	elements = elements.add('<li><span class="message">Order Id <strong><a href='+base_url+'/admin/order?status=ordered>'+val.number+'</a></strong> <a href='+base_url+'/admin/updateordernotify/'+val.Id+'><i class="fa fa-check" aria-hidden="true"></i></a></span></li>');
	            }
	 			

	          });     

	           $('#menu1').html(elements);

	          },
	           
	               contentType: false,
	               processData: false

	            });


	});


	if($('#brand_list_datatable').length > 0)
	{

		$('#brand_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/brand/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.logo+'" style="width:60px; height:60px;">';
	            }
	        },
	    
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 3,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/brand/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#brand_list_datatable tbody').addClass("m-datatable__body");
	            	$('#brand_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#brand_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#brand_list_datatable td').addClass("m-datatable__cell");
	            	$('#brand_list_datatable_filter input').addClass("form-control m-input");

	            	$('#brand_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#notification_list_datatable').length > 0)
	{

		$('#notification_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/notification/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.notification_category.title;
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		if(full.notification_category.image != null) {
	          			return '<img src="'+base_url+'/storage/app/'+full.notification_category.image+'" style="width:60px; height:60px;">';
	          		} else {
	          			return 'NIL';
	          		}
	              
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          	  if(full.shop_id != null) {
	          	  	return full.get_shop.name;
	          	  } else {
	          	  	return 'NIL';
	          	  }
	              
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          	  if(full.product_id != null) {
	          	  	return full.get_product.name;
	          	  } else {
	          	  	return 'NIL';
	          	  }
	              
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.notification_category.description;
	            }
	        },
	    
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.created_at;
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 6,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
						if(full.is_sent == 1)
	              			return 'Sent';
	              		else if(full.is_view == 2)
	              			return 'Processing';
	              		else
	              			return 'Not sent';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#notification_list_datatable tbody').addClass("m-datatable__body");
	            	$('#notification_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#notification_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#notification_list_datatable td').addClass("m-datatable__cell");
	            	$('#notification_list_datatable input').addClass("form-control m-input");

	            	$('#notification_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#unit_list_datatable').length > 0)
	{

		$('#unit_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/unit/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	     
	    
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 2,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/unit/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#unit_list_datatable tbody').addClass("m-datatable__body");
	            	$('#unit_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#unit_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#unit_list_datatable td').addClass("m-datatable__cell");
	            	$('#unit_list_datatable_filter input').addClass("form-control m-input");

	            	$('#unit_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	

	if($('#banner_list_datatable').length > 0)
	{

		$('#banner_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/banner/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.title;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '350px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.description;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.valid_from+' - '+full.valid_to;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 5,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/banner/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp; <button class="btn btn-xs danger dlt-btn deleteBanner" data-id="'+full.id+'"><i class="fa fa-times" aria-hidden="true"></i></button>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#banner_list_datatable tbody').addClass("m-datatable__body");
	            	$('#banner_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#banner_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#banner_list_datatable td').addClass("m-datatable__cell");
	            	$('#banner_list_datatable_filter input').addClass("form-control m-input");

	            	$('#banner_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}
	
	if($('#country_list_datatable').length > 0)
	{

		$('#country_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/country/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.latitude;
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.longitude;
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 2,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/country/edit/'+full.id+'" class=" btn btn-default btn-xs info"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp; ';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#country_list_datatable tbody').addClass("m-datatable__body");
	            	$('#country_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#country_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#country_list_datatable td').addClass("m-datatable__cell");
	            	$('#country_list_datatable input').addClass("form-control m-input");

	            	$('#country_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#state_list_datatable').length > 0)
	{

		$('#state_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/state/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		//console.log(full);
	              return full.name;
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.latitude;
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.longitude;
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 2,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/state/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp; ';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#state_list_datatable tbody').addClass("m-datatable__body");
	            	$('#state_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#state_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#state_list_datatable td').addClass("m-datatable__cell");
	            	$('#state_list_datatable input').addClass("form-control m-input");

	            	$('#state_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#city_list_datatable').length > 0)
	{

		$('#city_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/city/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.latitude;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.longitude;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.get_state.name;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 6,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/city/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#city_list_datatable tbody').addClass("m-datatable__body");
	            	$('#city_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#city_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#city_list_datatable td').addClass("m-datatable__cell");
	            	$('#city_list_datatable_filter input').addClass("form-control m-input");

	            	$('#city_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#shop_category_list_datatable').length > 0)
	{

		$('#shop_category_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/shop/categories/list',
	        columns: [ 
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		if(full.name != null)
	              		return full.name;
	              	else
	              		return 'NIL';
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		if(full.icon != null)
	              		return '<img src="'+base_url+'/storage/app/'+full.icon+'" style="width:60px; height:60px;">';
	              	else
	              		return 'NIL';
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          	  if(full.order != null)
	              	return full.order;
	              else
	              	return 'NIL';
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '300',
	            	left: '500px',
	                "targets": 4,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/shop/category/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#shop_category_list_datatable tbody').addClass("m-datatable__body");
	            	$('#shop_category_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#shop_category_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#shop_category_list_datatable td').addClass("m-datatable__cell");
	            	$('#shop_category_list_datatable input').addClass("form-control m-input");

	            	$('#shop_category_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#category_list_datatable').length > 0)
	{

		$('#category_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/categories/list',
	        columns: [ 
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		if(full.parent_id == 0)
	          			return 'NIL'
	          		else
	          			return full.parent_name;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		if(full.parent_id == 0)
	          			return 'NIL'
	          		else
	          			return   '<a href="'+base_url+'/admin/category/attributes/'+full.id+'" data='+full.id+' class=" btn btn-default btn-xs info getattrr">View Attributes</a>';
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //   		if(full.is_last_child == 0)
	        //   			return 'NIL'
	        //   		else
	        //   			return   '<a href="'+base_url+'/admin/category/services/'+full.id+'" data='+full.id+' class=" btn btn-default btn-xs info getattrr">View Services</a>';
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '300',
	            	left: '500px',
	                "targets": 5,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/category/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<button class="btn btn-xs danger dlt-btn deleteCategory" data-id="'+full.id+'" title="Delete"><i class="fa fa-times" aria-hidden="true" ></i></button></div>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#category_list_datatable tbody').addClass("m-datatable__body");
	            	$('#category_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#category_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#category_list_datatable td').addClass("m-datatable__cell");
	            	$('#category_list_datatable_filter input').addClass("form-control m-input");

	            	$('#category_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}




	if($('#category_attribute_datatable').length > 0)
	{

		var id=$("#attrid").val();

		$('#category_attribute_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/getAttributes/'+id,
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.field_name;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		return full.field_value;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	          		if(full.control == null) {
	          			return 'NIL';
	          		} else if(full.control == 1) {
	          			return 'checkbox';
	          		} else if(full.control == 2) {
	          			return 'radiobutton';
	          		} else {
	          			return 'selectbox';
	          		}

	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              	if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '300',
	            	left: '500px',
	                "targets": 4,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/category/attribute/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp; <button class="btn btn-xs danger dlt-btn delattr" data-id="'+full.id+'"><i class="fa fa-times" aria-hidden="true"></i></button>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {
	            	$('#category_attribute_datatable tbody').addClass("m-datatable__body");
	            	$('#category_attribute_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#category_attribute_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#category_attribute_datatable td').addClass("m-datatable__cell");
	            	$('#category_attribute_datatable input').addClass("form-control m-input");

	            	$('#category_attribute_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}
	
	if($('#shop_list_datatable').length > 0)
	{

		$('#shop_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/shop/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					  if(full.seller_info)
							  return full.seller_info.business_name || 'NA';
						else
							  return 'NA';
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '100px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '10px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return   '<a href="'+base_url+'/admin/shop/products/'+full.id+'" data='+full.id+' class=" btn btn-default btn-xs info">View</a>';
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '50px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.website;
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '100px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '100px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.location;
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.address;
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '150px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       if(full.description == null)
	        //   			return 'NIL'
	        //   		else
	        //   			return full.description;
	        //     }
	        // },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '200px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //   		if(full.services == null)
	        //   			return 'NIL'
	        //   		else
	        //   			return full.services;
	        //   		// return   '<a href="'+base_url+'/admin/shop/services/'+full.id+'" data='+full.id+' class=" btn btn-default btn-xs info getattrr">View Services</a>';
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '30px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '100px',
	                "targets": 4,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/shop/edit/'+full.id+'/'+full.seller_id+'" class=" btn btn-primary btn-xs info" title="edit shop"><i class="fa fa-edit" aria-hidden="true" ></i></a>&nbsp;<a href="'+base_url+'/admin/shop/products/'+full.id+'" data='+full.id+' class=" btn btn-success btn-xs info" title="product details"><i class="fa fa-product-hunt" aria-hidden="true" ></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#shop_list_datatable tbody').addClass("m-datatable__body");
	            	$('#shop_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#shop_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#shop_list_datatable td').addClass("m-datatable__cell");
	            	$('#shop_list_datatable input').addClass("form-control m-input");

	            	$('#shop_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#shop_product_datatable').length > 0)
	{

		var id=$("#sho").val();
		var is_admin = $("#is_admin").val();

		$('#shop_product_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/shop/getProducts/'+id,
			columns: [
				{ 
					orderable: false,
				   data: "null",
				   width: '50px',
				   autoWidth: false,
				   'text-align': 'center', 
				   render : function(data,type,full) { 
					   if(full.shop)
					   return full.shop.name
					   else
					   return 'NA';
					   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '30px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 return full.sku;
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '70px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 return full.name;
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '70px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						 if(full.brand)
							 return full.brand.name;
						   else
							 return 'NA';
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '40px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						if(is_admin == 1)
						{
						if(full.vendor_price)
							 return full.vendor_price;
						else
							 return 'NIL';
						}else{
							if(full.unit)
							return full.unit.name;
					   		else
							return 'NIL';
						}

				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '40px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						if(full.price)
							 return full.price;
						else
							 return 'NIL'; 
				   }
			   },
			
			   { 
				   orderable: false,
				   data: "null",
				   width: '40px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						 if(full.stock)
							 return full.stock;
						 else
						     return 'NIL';
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '80px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 return '<img src="'+base_url+'/storage/app/'+full.thump_image+'" style="width:60px; height:60px;">';
				   
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '80px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					   let categoryNames= [];
					   $(full.categories).each(function (i, e) {
						 categoryNames.push(e.name);
						 });
					   return categoryNames.join(", ");
					 }, 
				   
				   
			   },

			   { 
				orderable: false,
				data: "null",
				width: '80px',
				autoWidth: false,
				 'text-align': 'center', 
				  render : function(data,type,full) { 
					let attrNames= [];
					
					$(full.attributes).each(function (i, e) {
						
					  attrNames.push(e.pivot.attr_value);
					  });
					  attrNames.join(", ");
					  if (attrNames.length === 0 && full.type != 1) {
						return 'NIL';
					  } else {
							if(is_admin == 1){
							return attrNames+'<br><br><a href="'+base_url+'/admin/product/attribute/edit/'+full.id+'" data="331" class=" btn btn-default btn-xs btn-primary getattrr" style="border-radius:20px;" title="Attribute Edit" >Edit Attributes</a>';
							}else{
								return (full.is_approved==1) ? attrNames : attrNames+'<br><br><a href="'+base_url+'/admin/product/attribute/edit/'+full.id+'" data="331" class=" btn btn-default btn-xs btn-primary getattrr" style="border-radius:20px;" title="Attribute Edit" >Edit Attributes</a>';
							}
					 }
					
				  }, 
				
				
			},

			   { 
				orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				  
				   if(full.parent_product)
				   return full.parent_product.sku
				   else if(full.parent_id === 0)
				   return 'Configurable Type';
				   else
				   return  'Single Type'
				   }
		   		},
			   { 
				   orderable: false,
				   data: "null",
				   width: '20px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 if(full.is_active == 0)
							 return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
						 else
							 return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';
				   }
			   },
			   ],
			   "columnDefs": [
				   {
					   width: '10',
					   left: '100px',
					   "targets": 12,
					   "visible": true,
						
						"render": function (data, type, full) { 
							   return   '<a href="'+base_url+'/admin/product/edit/'+full.id+'/'+id+'" class=" btn btn-primary btn-xs info" title="product edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
					   }
				   }
			   ],
   
			   createdRow: function(row, data, dataIndex) 
			   {
				   setTimeout(function()
				   {
   
					   $('#shop_product_datatable tbody').addClass("m-datatable__body");
					   $('#shop_product_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
					   $('#shop_product_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
					   $('#shop_product_datatable td').addClass("m-datatable__cell");
					   $('#shop_product_datatable input').addClass("form-control m-input");
   
					   $('#shop_product_datatable tr').css('table-layout','fixed');
				   });
			   }
   
		   });
	   }

	   
	if($('#product_list_datatable').length > 0)
	{

		var Status=$("#prod_status").val();

		$('#product_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/product/list?status='+Status,
	    	columns: [
				{ 
					orderable: false,
				   data: "null",
				   width: '50px',
				   autoWidth: false,
				   'text-align': 'center', 
				   render : function(data,type,full) { 
					   if(full.shop)
					   return full.shop.name
					   else
					   return 'NA';
					   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '30px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 return full.sku;
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '70px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 return full.name;
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '70px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						 if(full.brand)
							 return full.brand.name;
						   else
							 return 'NA';
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '40px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						if(full.vendor_price)
							 return full.vendor_price;
						else
							 return 'NIL';

				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '40px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						if(full.price)
							 return full.price;
						else
							 return 'NIL'; 
				   }
			   },
			
			   { 
				   orderable: false,
				   data: "null",
				   width: '40px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
						 if(full.stock)
							 return full.stock;
						 else
						     return 'NIL';
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '80px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					 return '<img src="'+base_url+'/storage/app/'+full.thump_image+'" style="width:60px; height:60px;">';
				   
				   }
			   },
			   { 
				   orderable: false,
				   data: "null",
				   width: '80px',
				   autoWidth: false,
					'text-align': 'center', 
					 render : function(data,type,full) { 
					   let categoryNames= [];
					   $(full.categories).each(function (i, e) {
						 categoryNames.push(e.name);
						 });
					   return categoryNames.join(", ");
					 }, 
				   
				   
			   },

			   { 
				orderable: false,
				data: "null",
				width: '80px',
				autoWidth: false,
				 'text-align': 'center', 
				  render : function(data,type,full) { 
					let attrNames= [];
					
					$(full.attributes).each(function (i, e) {
						
					  attrNames.push(e.pivot.attr_value);
					  });
					  attrNames.join(", ");
					  if (attrNames.length === 0) {
						return 'NIL';
					}else {
						return (full.is_approved == 1) ? attrNames : attrNames+'<br><br><a href="'+base_url+'/admin/product/attribute/edit/'+full.id+'" data="331" class=" btn btn-default btn-xs btn-primary getattrr" style="border-radius:20px;" title="Attribute Edit" >Edit Attributes</a>';
					}
					
				  }, 
				
				
			},

			   { 
				orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				  
				   if(full.parent_product)
				   return full.parent_product.sku
				   else if(full.parent_id === 0)
				   return 'Configurable Type';
				   else
				   return  'Single Type'
				   }
		   		},
			 
			   ],
			   "columnDefs": [
				   {
					   width: '10',
					   left: '100px',
					   "targets": 11,
					   "visible": true,
						
						"render": function (data, type, full) { 
							if(full.is_approved == 0)
							   return   '<div class="row"><a href="'+base_url+'/admin/product/editPending/'+full.id+'/'+full.shop.id+'" class=" btn  btn-xs btn-link" title="product edit"><i class="fa fa-edit" aria-hidden="true"></i></a><button  class="btn btn-default btn-xs btn-link approve"   title="Approve" value="'+full.id+'" data-id="'+full.id+'"><i class="fa fa-check" aria-hidden="true" style="color:green"></i> </button></div>';
							else
							   return '--';
							}
				   }
			   ],
   
			   createdRow: function(row, data, dataIndex) 
			   {
				   setTimeout(function()
				   {
   
					   $('#product_list_datatable tbody').addClass("m-datatable__body");
					   $('#product_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
					   $('#product_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
					   $('#product_list_datatable td').addClass("m-datatable__cell");
					   $('#product_list_datatable input').addClass("form-control m-input");
   
					   $('#product_list_datatable tr').css('table-layout','fixed');
				   });
			   }
   
		   });
	   }


	if($('#product_untracklist_datatable').length > 0)
	{
		
		var id=$("#sho").val();
	
		$('#product_untracklist_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/product/untracklists/'+id,
	        columns: [
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.sku;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					  if(full.brand)
						  return full.brand;
						else
						  return 'NA';
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.vendor_price;
	            }
	        },
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.price;
	            }
	        },
	     
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.stock;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '80px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
				  return full.thump_image;
	            
	            }
			},
		
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '30px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.unit_measurement)
	          			return full.unit_measurement
	          		else
	          			return 'NA';
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '30px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.measurement_value)
	          			return full.measurement_value
	          		else
	          			return 'NA';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '100px',
	                "targets": 9,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/product/deleteUntracked/'+full.id+'" class=" btn btn-default btn-xs danger" onclick="return myFunction();" title="delete product"><i class="fa fa-trash" style="color:red;"></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#product_untracklist_datatable tbody').addClass("m-datatable__body");
	            	$('#product_untracklist_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#product_untracklist_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#product_untracklist_datatable td').addClass("m-datatable__cell");
	            	$('#product_untracklist_datatable input').addClass("form-control m-input");

	            	$('#product_untracklist_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	if($('#variation_list_datatable').length > 0)
	{
		var id=$("#varid").val();

		$('#variation_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/variation/list/'+id,
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '10px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.vin;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '100px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return full.categoryData.name;
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '10px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.price;
	            }
	        },
	        // { 
	        // 	orderable: false,
	        // 	data: "null",
	        // 	width: '50px',
	        // 	autoWidth: false,
	        // 	 'text-align': 'center', 
	        //   	render : function(data,type,full) { 
	        //       return   '<a href="'+base_url+'/admin/product/variation/'+full.id+'" data='+full.id+' class=" btn btn-default btn-xs info getattrr">View</a>';
	        //     }
	        // },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '30px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '50px',
	                "targets": 4,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/variation/edit/'+full.id+'" class=" btn btn-default btn-xs info"><i class="fa fa-edit" aria-hidden="true"></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#variation_list_datatable tbody').addClass("m-datatable__body");
	            	$('#variation_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#variation_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#variation_list_datatable td').addClass("m-datatable__cell");
	            	$('#variation_list_datatable input').addClass("form-control m-input");

	            	$('#variation_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}
	
if($('#events_list_datatable').length > 0)
	{

		$('#events_list_datatable').DataTable({

	        processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/events/list',
	        columns: [
	           	/*{ 
	           		data: "null",
	           		
	           		autoWidth: false,
	              	render : function(data,type,full) {
	                    return full.id;
	              	}
	            },*/
	           
	          	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '150px',
	            	autoWidth: false,
	            	 'text-align': 'center', 
	              	render : function(data,type,full) {
                    
	              return full.title;
	              
	              	}
	                },

	                 { 
	            	orderable: false,
	            	data: "null",
	            	width: '320',
	            	autoWidth: false,
	            	 'text-align': 'center', 
	              	render : function(data,type,full) {
	              		return 'https://www.youtube.com/watch?v='+full.video_url;
	              	}
	            },


	                { 
	            	orderable: false,
	            	data: "null",
	            	width: '150px',
	            	autoWidth: false,
	            	 
	              	render : function(data,type,full) {
                    
	              return '<img src="'+base_url+'/storage/app/'+full.thumbnail_url+'" style="width:60px; height:60px;">';
	              
	              	}
	            },


               
	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '300',
	            	autoWidth: false,
	              	render : function(data,type,full) {
	              		return full.from_date+"-"+full.to_date;
	              	}
	            },

	             { 
	            	orderable: false,
	            	data: "null",
	            	width: '300',
	            	autoWidth: false,
	              	render : function(data,type,full) {
	              		return full.description;
	              	}
	            },


	           ],
	        "columnDefs": [
	            {
	            	 width: '300',
	                "targets": 5,
	                "visible": true,
                   
	                 "render": function (data, type, full) { 
	    

	return   '<a href="'+base_url+'/admin/events/edit/'+full.id+'" class=" btn btn-default btn-xs info"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp; ';

	            
	                }
	            }
	        ],

	        
	     
	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#events_list_datatable tbody').addClass("m-datatable__body");
	            	$('#events_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#events_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#events_list_datatable td').addClass("m-datatable__cell");
	            	$('#events_list_datatable_filter input').addClass("form-control m-input");

	            	$('#events_list_datatable tr').css('table-layout','fixed');
	            });
	        }

	    });

	}

$('body').on('click','.delattr',function() {

	if (!confirm("Do you want to delete?")){
	     return false;
	}

	var id = $(this).attr('data-id');

	$.ajax({
	    type: "GET",
	    url: base_url+'/admin/category/attribute/delete/'+id,
	        //surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
	    success: function(result) 
	    {
            $(".m-alert").removeClass('fade');
	    	$(".m-alert").addClass('show');
	      	location.reload();
	    }
	});
});

$('body').on('click','.deleteBanner',function() {

	if (!confirm("Do you want to delete?")) {
      return false;
    }

	var id = $(this).attr('data-id');

	$.ajax({
	    type: "GET",
	    url: base_url+'/admin/banner/delete/'+id,
	        //surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
	    success: function(result) 
	    {
            $(".m-alert").removeClass('fade');
	    	$(".m-alert").addClass('show');
	      	location.reload();
	    }
	});
});

$('body').on('click','.deleteCity',function() {

	if (!confirm("Do you want to delete?")) {
      return false;
    }

	var id = $(this).attr('data-id');

	$.ajax({
	    type: "GET",
	    url: base_url+'/admin/city/delete/'+id,
	        //surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
	    success: function(result) 
	    {
            $(".m-alert").removeClass('fade');
	    	$(".m-alert").addClass('show');
	      	location.reload();
	    }
	});
});

$('body').on('click','.deleteCountry',function() {

	if (!confirm("Do you want to delete?")) {
      return false;
    }

	var id = $(this).attr('data-id');

	$.ajax({
	    type: "GET",
	    url: base_url+'/admin/country/delete/'+id,
	        //surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
	    success: function(result) 
	    {
            $(".m-alert").removeClass('fade');
	    	$(".m-alert").addClass('show');
	      	location.reload();
	    }
	});
});


$('body').on('click','.deloffer',function() {

	if (!confirm("Do you want to delete?")) {
      return false;
    }

	var id = $(this).attr('data-id');

	$.ajax({
	    type: "GET",
	    url: base_url+'/admin/events/delete/'+id,
	        //surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
	    success: function(result) 
	    {
            $(".m-alert").removeClass('fade');
	    	$(".m-alert").addClass('show');
	      	location.reload();
	    }
	});
});







	



	if($('#uservideo_list_datatable').length > 0)
	{

		$('#uservideo_list_datatable').DataTable({

	        processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/participants/list',
	        columns: [
	           	/*{ 
	           		data: "null",
	           		
	           		autoWidth: false,
	              	render : function(data,type,full) {
	                    return full.id;
	              	}
	            },*/

	            
	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '150px',
	            	autoWidth: false,
	            	 'text-align': 'center', 
	              	render : function(data,type,full) {
                    
	              return full.events.title;
	              
	              	}
	                },
	           
	          	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '150px',
	            	autoWidth: false,
	            	 'text-align': 'center', 
	              	render : function(data,type,full) {
                    
                    if(full.title != null) {
                    	return full.title;
                    } else {
                    	return 'NIL';
                    }
	              
	              
	              	}
	                },

	                 { 
	            	orderable: false,
	            	data: "null",
	            	width: '320',
	            	autoWidth: false,
	            	 'text-align': 'center', 
	              	render : function(data,type,full) {
	              		return 'https://www.youtube.com/watch?v='+full.video_url;
	              	}
	            },


	                { 
	            	orderable: false,
	            	data: "null",
	            	width: '150px',
	            	autoWidth: false,
	            	 
	              	render : function(data,type,full) {
                    
	              return '<img src="'+base_url+'/storage/app/'+full.thumbnail_url+'" style="width:60px; height:60px;">';
	              
	              	}
	            },


	            //  { 
	            // 	orderable: false,
	            // 	data: "null",
	            // 	width: '300',
	            // 	autoWidth: false,
	            //   	render : function(data,type,full) {
	            //   		return full.description;
	            //   	}
	            // },
	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '300',
	            	autoWidth: false,
	              	render : function(data,type,full) {
	              		return full.votes;
	              	}
	            },
	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '300',
	            	autoWidth: false,
	              	render : function(data,type,full) {
	              		return full.view_count;
	              	}
	            },


	           ],
	        "columnDefs": [
	            {
	            	 width: '300',
	                "targets": 6,
	                "visible": true,
                    left: '500px',
	                 "render": function (data, type, full) { 
	    

	return   '<a href="'+base_url+'/admin/participants/edit/'+full.id+'" class=" btn btn-default btn-xs info"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp; <button class="btn btn-xs danger dlt-btn deluservideo" data-id="'+full.id+'"><i class="fa fa-times" aria-hidden="true"></i></button>';

	            
	                }
	            }
	        ],

	        
	     
	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#uservideo_list_datatable tbody').addClass("m-datatable__body");
	            	$('#uservideo_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#uservideo_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#uservideo_list_datatable td').addClass("m-datatable__cell");
	            	$('#uservideo_list_datatable_filter input').addClass("form-control m-input");

	            	$('#uservideo_list_datatable tr').css('table-layout','fixed');
	            });
	        }

	    });

	}





$('body').on('click','.deluservideo',function()
		
		{

if (!confirm("Do you want to delete?")){
      return false;
    }

			var id = $(this).attr('data-id');

			$.ajax({
			    type: "GET",
			    url: base_url+'/admin/participants/delete/'+id,
			        //surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
			    success: function(result) 
			    {
                    $(".m-alert").removeClass('fade');
			    	$(".m-alert").addClass('show');
			      	location.reload();
			    }
			});
		});



	if($('#seller_list_datatable').length > 0)
	{

		$('#seller_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/seller/list',
	        columns: [
			 { 
			 	orderable: false,
		    	data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					
					return full.name || 'NA';
				
					}
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.business_name || 'NA';
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.email || 'NA';
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					return full.business_email || 'NA';
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.mobile || 'NA';
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '80px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
				  return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            
	            }
			},

			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '80px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
				  return '<img src="'+base_url+'/storage/app/'+full.business_image+'" style="width:60px; height:60px;">';
	            
	            }
			},

			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					  if(full.user_addresses.length>0)
					  {
						let cityNames= [];
							$(full.user_addresses).each(function (i, address) {
								if(address.address_type==1)
									cityNames.push(address.city);
							
							});
							return cityNames.join(", ");
					  }else{
						  return 'NA';
					  }
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					if(full.user_addresses.length>0)
					{
					  let stateNames= [];
						  $(full.user_addresses).each(function (i, address) {
							if(address.address_type==1 && address.state)
							  stateNames.push(address.state.name);
						  });
						  return stateNames.join(", ");
					}else{
						return 'NA';
					}
	            }
	        },
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '100px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					let categoryNames= [];
				    $(full.categories).each(function (i, e) {
					  categoryNames.push(e.name);
					  });
					return categoryNames.join(", ");
				  }, 
	            
	            
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '30px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '100px',
	                "targets": 11,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/seller/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#seller_list_datatable tbody').addClass("m-datatable__body");
	            	$('#seller_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#seller_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#seller_list_datatable td').addClass("m-datatable__cell");
	            	$('#seller_list_datatable input').addClass("form-control m-input");

	            	$('#seller_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}


	if($('#customer_list_datatable').length > 0)
	{

		$('#customer_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/customer/list',
	        columns: [
			 { 
			 	orderable: false,
		    	data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					
					return full.name
				
					}
			},
			
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.email;
	            }
			},
		
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.mobile;
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.wallet;
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '80px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
				  return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					  if(full.user_addresses.length>0)
					  {
						let cityNames= [];
							$(full.user_addresses).each(function (i, address) {
								if(address.address_type==0)
									cityNames.push(address.city);
							
							});
							return cityNames.join(", ");
					  }else{
						  return 'NA';
					  }
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '40px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					if(full.user_addresses.length>0)
					{
					  let stateNames= [];
						  $(full.user_addresses).each(function (i, address) {
							if(address.address_type==0)
							{
								if(address.state)
								  stateNames.push(address.state.name);
								
							}
						  });
						  return stateNames.join(", ");
					}else{
						return 'NA';
					}
	            }
	        },

	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '30px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.is_active == 0)
	          			return 'Inactive'
	          		else
	          			return 'Active';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '100px',
	                "targets": 8,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/customer/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#customer_list_datatable tbody').addClass("m-datatable__body");
	            	$('#customer_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#customer_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#customer_list_datatable td').addClass("m-datatable__cell");
	            	$('#customer_list_datatable input').addClass("form-control m-input");

	            	$('#customer_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}


	if($('#role_list_datatable').length > 0)
	{

		$('#role_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/role/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
	        },
	   
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.description;
	            }
	        },
	      
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 2,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/role/edit/'+full.id+'" class=" btn  btn-xs btn-primary" title="edit permission"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#role_list_datatable tbody').addClass("m-datatable__body");
	            	$('#role_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#role_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#role_list_datatable td').addClass("m-datatable__cell");
	            	$('#role_list_datatable_filter input').addClass("form-control m-input");

	            	$('#role_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}



	if($('#offer_list_datatable').length > 0)
	{

		$('#offer_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/offer/list',
	        columns: [
			 { 
			 	orderable: false,
		    	data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					if(full.shop)
						return full.shop.name;
					else
						return 'NA';
				
					}
			},

			{ 
			   orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				       return full.title;
				 }
		   },
		   { 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
				  if(full.image)
						  return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
				   else
				   		  return 'NA'
			}
		},
		{ 
			orderable: false,
			data: "null",
			width: '100px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  return full.description;
			}
		},
	
		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  if(full.discount_type == 1)
				return 'Percentage';
			  else
			    return 'Flat';
			}
		},
		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			     return full.discount_value
			}
		},

		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  return full.valid_from;
			}
		},

		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  return full.valid_to;
			}
		},
		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  if(full.status == 1)
				return 'Active';
			  else
			    return 'InActive';
			}
		},
	      
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '500px',
	                "targets": 9,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<div class="row"><a href="'+base_url+'/admin/offer/edit/'+full.id+'" class=" btn btn-primary btn-xs primary" title="edit"><i class="fa fa-edit" aria-hidden="true" title="offer edit"></i></a>&nbsp;<button class="btn btn-xs danger dlt-btn deleteOffer" data-id="'+full.id+'" title="offer delete"><i class="fa fa-times" aria-hidden="true" ></i></button></div>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#offer_list_datatable tbody').addClass("m-datatable__body");
	            	$('#offer_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#offer_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#offer_list_datatable td').addClass("m-datatable__cell");
	            	$('#offer_list_datatable input').addClass("form-control m-input");

	            	$('#offer_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	$('body').on('click','.deleteCategory',function() {

		if (!confirm("Do you want to delete?")) {
		  return false;
		}
	
		var id = $(this).attr('data-id');
	
		$.ajax({
			type: "GET",
			url: base_url+'/admin/category/delete/'+id,
				//surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
			success: function(result) 
			{
				$(".m-alert").removeClass('fade');
				$(".m-alert").addClass('show');
				  location.reload();
			}
		});
	});

	$('body').on('click','.deleteOffer',function() {

		if (!confirm("Do you want to delete?")) {
		  return false;
		}
	
		var id = $(this).attr('data-id');
	
		$.ajax({
			type: "GET",
			url: base_url+'/admin/offer/delete/'+id,
				//surl: base_url+'/'+region+'/admin/freezoneuser/delete/'+id,
			success: function(result) 
			{
				$(".m-alert").removeClass('fade');
				$(".m-alert").addClass('show');
				  location.reload();
			}
		});
	});

	if($('#offline_voucher_datatable').length > 0)
	{

		$('#offline_voucher_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/offline/vouchers',
	        columns: [
			 { 
			 	orderable: false,
		    	data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					if(full.offer)
						return full.offer.title;
					else
						return 'NA';
				
					}
			},

			{ 
			   orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				    if(full.shop)
						return full.shop.name;
					else
						return 'NA';
				 }
		   },

		   { 
			   orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				    if(full.user)
						return full.user.name;
					else
						return 'NA';
				 }
		   },
		   { 
				orderable: false,
				data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					if(full.offer)
						return '<img src="'+base_url+'/storage/app/'+full.offer.image+'" style="width:60px; height:60px;">';
					else
					   	return 'NA'
				}
			},

			{ 
			   orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				    if(full.created_at != null) {
	              		var date = new Date(full.created_at);
		          		var day = date.getDate();
		          		var month = date.toLocaleString('default', { month: 'short' })
	        			var year = date.getFullYear();
	        			day = day.toString().length > 1 ? day : "0" + day;

	        			var hours = date.getHours();
						var minutes = date.getMinutes();
						var ampm = hours >= 12 ? 'PM' : 'AM';
						
						hours = hours % 12;
						hours = hours ? hours : 12; // the hour '0' should be '12'
						minutes = minutes < 10 ? '0'+minutes : minutes;
						var strTime = hours + ':' + minutes + ' ' + ampm;

	        			return (day+" "+ month +" "+ year +", "+strTime);
	          		}
				}
		   },
			// { 
			// 	orderable: false,
			// 	data: "null",
			// 	width: '100px',
			// 	autoWidth: false,
			// 	 'text-align': 'center', 
			// 	  render : function(data,type,full) { 
			// 	  return full.description;
			// 	}
			// },
	
		// { 
		// 	orderable: false,
		// 	data: "null",
		// 	width: '50px',
		// 	autoWidth: false,
		// 	 'text-align': 'center', 
		// 	  render : function(data,type,full) { 
		// 	  if(full.discount_type == 1)
		// 		return 'Percentage';
		// 	  else
		// 	    return 'Flat';
		// 	}
		// },
		// { 
		// 	orderable: false,
		// 	data: "null",
		// 	width: '50px',
		// 	autoWidth: false,
		// 	 'text-align': 'center', 
		// 	  render : function(data,type,full) { 
		// 	     return full.discount_value
		// 	}
		// },

		// { 
		// 	orderable: false,
		// 	data: "null",
		// 	width: '50px',
		// 	autoWidth: false,
		// 	 'text-align': 'center', 
		// 	  render : function(data,type,full) { 
		// 	  return full.valid_from;
		// 	}
		// },

		// { 
		// 	orderable: false,
		// 	data: "null",
		// 	width: '50px',
		// 	autoWidth: false,
		// 	 'text-align': 'center', 
		// 	  render : function(data,type,full) { 
		// 	  return full.valid_to;
		// 	}
		// },
		// { 
		// 	orderable: false,
		// 	data: "null",
		// 	width: '50px',
		// 	autoWidth: false,
		// 	 'text-align': 'center', 
		// 	  render : function(data,type,full) { 
		// 	  if(full.status == 1)
		// 		return 'Active';
		// 	  else
		// 	    return 'InActive';
		// 	}
		// },
	      
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '500px',
	                "targets": 4,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<div class="row"><a href="'+base_url+'/admin/offer/edit/'+full.id+'" class=" btn btn-primary btn-xs primary" title="edit"><i class="fa fa-edit" aria-hidden="true" title="offer edit"></i></a>&nbsp;<button class="btn btn-xs danger dlt-btn deleteOffer" data-id="'+full.id+'" title="offer delete"><i class="fa fa-times" aria-hidden="true" ></i></button></div>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#offline_voucher_datatable tbody').addClass("m-datatable__body");
	            	$('#offline_voucher_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#offline_voucher_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#offline_voucher_datatable td').addClass("m-datatable__cell");
	            	$('#offline_voucher_datatable input').addClass("form-control m-input");

	            	$('#offline_voucher_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}

	$('body').on('click','#searchvoucher',function(e)
	  {

	  	e.preventDefault();
	  	var fromdate=$("#orderfrom").val();
	  	var todate=$("#orderto").val();
	  	var shop=$("#shop").val();
		$('#offline_voucher_datatable').DataTable().ajax.reload();

		if($('#offline_voucher_datatable').length > 0)
		{

			$('#offline_voucher_datatable').DataTable({
				processing: true,
		        serverSide: true,
		        bDestroy: true,
		        bFilter: false,
		        ajax: base_url+'/admin/offline/searchvoucher?fromdate='+fromdate+'&todate='+todate+'&shop='+shop,
		        columns: [

				{ 
				 	orderable: false,
			    	data: "null",
					width: '50px',
					autoWidth: false,
					'text-align': 'center', 
					render : function(data,type,full) { 
						if(full.offer)
							return full.offer.title;
						else
							return 'NA';
				
					}
				},

				{ 
				   orderable: false,
				   data: "null",
				   width: '50px',
				   autoWidth: false,
				   'text-align': 'center', 
				   render : function(data,type,full) { 
					    if(full.shop)
							return full.shop.name;
						else
							return 'NA';
					 }
			   },

			   { 
				   orderable: false,
				   data: "null",
				   width: '50px',
				   autoWidth: false,
				   'text-align': 'center', 
				   render : function(data,type,full) { 
					    if(full.user)
							return full.user.name;
						else
							return 'NA';
					 }
			   },
			   { 
					orderable: false,
					data: "null",
					width: '50px',
					autoWidth: false,
					'text-align': 'center', 
					render : function(data,type,full) { 
						if(full.offer)
							return '<img src="'+base_url+'/storage/app/'+full.offer.image+'" style="width:60px; height:60px;">';
						else
						   	return 'NA'
					}
				},

				{ 
				   orderable: false,
				   data: "null",
				   width: '50px',
				   autoWidth: false,
				   'text-align': 'center', 
				   render : function(data,type,full) { 
					    if(full.created_at != null) {
		              		var date = new Date(full.created_at);
			          		var day = date.getDate();
			          		var month = date.toLocaleString('default', { month: 'short' })
		        			var year = date.getFullYear();
		        			day = day.toString().length > 1 ? day : "0" + day;

		        			var hours = date.getHours();
							var minutes = date.getMinutes();
							var ampm = hours >= 12 ? 'PM' : 'AM';
							
							hours = hours % 12;
							hours = hours ? hours : 12; // the hour '0' should be '12'
							minutes = minutes < 10 ? '0'+minutes : minutes;
							var strTime = hours + ':' + minutes + ' ' + ampm;

		        			return (day+" "+ month +" "+ year +", "+strTime);
		          		}
					}
			   },
		
		      
		        ],
		        "columnDefs": [
		            {
		            	width: '10',
		            	left: '500px',
		                "targets": 4,
		                "visible": true,
		                 
		                 "render": function (data, type, full) { 
								return   '<a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-default btn-xs info"><i class="fa fa-eye" aria-hidden="true" title="order details" ></i></a>';
		                }
		            }
		        ],

		        createdRow: function(row, data, dataIndex) 
		        {
		            setTimeout(function()
		            {

		            	$('#offline_voucher_datatable tbody').addClass("m-datatable__body");
		            	$('#offline_voucher_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
		            	$('#offline_voucher_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
		            	$('#offline_voucher_datatable td').addClass("m-datatable__cell");
		            	$('#offline_voucher_datatable input').addClass("form-control m-input");

		            	$('#offline_voucher_datatable tr').css('table-layout','fixed');
		            });
		        }

			});
		}
	});



	if($('#order_list_datatable').length > 0)
	{
		var Status=$("#status").val();
		var is_admin=$("#current_role").val();
		
		$('#order_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
			ajax: base_url+'/admin/order/list?status='+Status,
	        columns: [

			{ 
				orderable: false,
				data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
				
					return full.order_no;
				
				   
				}
			},
			 { 
			 	orderable: false,
		    	data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					if(full.user)
						return full.user.name;
					else
						return 'NA';
				
					}
			},
			{ 
				orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				   if(full.shop)
					   return full.shop.name;
				   else
					   return 'NA';
			   
				   }
		   },

			{ 
			   orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				       return full.discount;
				 }
		   },
		 
		{ 
			orderable: false,
			data: "null",
			width: '100px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  return full.amount;
			}
		},
		{ 
			orderable: false,
			data: "null",
			width: '100px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
				const dateTime = full.created_at;
				const parts = dateTime.split(/[- :]/);
				const wanted = `${parts[2]}-${parts[1]}-${parts[0]} ${parts[3]}:${parts[4]}`;
				return wanted;
			}
		},
	
		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  if(full.order_status)
				return full.order_status.name;
			  else
			    return 'NA';
			}
		},
	
	      
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 7,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
						 if(full.order_status)
						 {
							if(full.order_status.slug !='ordered')
							{
								if((full.order_status.slug =='accepted' && full.instore == 0))
								{ 
									
									if(full.drivers.length>0)
									{
										
										return   is_admin ==1 ? '<div class="row"><a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-primary btn-xs info" title="order details"><i class="fa fa-eye" aria-hidden="true" ></i></a>&nbsp;<button  class=" btn btn-success btn-xs info order_assign" title="order assigned" data-id="'+full.id+'" value="'+full.order_no+'"><i class="fa fa-user-plus" aria-hidden="true"></i></button></div>' : '<div class="row"><a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-primary btn-xs info" title="order details"><i class="fa fa-eye" aria-hidden="true" ></i></a></div>';
									}else{
										return   is_admin ==1 ? '<div class="row"><a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-primary btn-xs info" title="order details"><i class="fa fa-eye" aria-hidden="true" ></i></a>&nbsp;<button  class=" btn btn-info btn-xs info order_assign" title="order assign" data-id="'+full.id+'" value="'+full.order_no+'"><i class="fa fa-user-plus" aria-hidden="true"></i></button></div>' : '<div class="row"><a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-primary btn-xs info" title="order details"><i class="fa fa-eye" aria-hidden="true" ></i></a></div>';
									}
								}else{
									return   '<div class="row"><a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-primary btn-xs info" title="order details"><i class="fa fa-eye" aria-hidden="true"  ></i></a></div>';
								}
							}else{
								return '<div class="row"><a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-primary btn-xs info" title="order details"><i class="fa fa-eye" aria-hidden="true"  ></i></a><button class=" btn btn-success btn-xs info order_status" data-id="'+full.id+'" value="accepted" title="approve"><i class="fa fa-check-circle" aria-hidden="true" ></i></button><button class=" btn btn-danger btn-xs info order_status" data-id="'+full.id+'" value="rejected" title="reject"><i class="fa fa-ban" aria-hidden="true" ></i></button></div>';
							}
						 }else{
							return 'NA';
						 }
						}
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#order_list_datatable tbody').addClass("m-datatable__body");
	            	$('#order_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#order_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#order_list_datatable td').addClass("m-datatable__cell");
	            	$('#order_list_datatable input').addClass("form-control m-input");

	            	$('#order_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}


$('body').on('click','#searchorder',function(e)
  {

  	e.preventDefault();
  	var fromdate=$("#orderfrom").val();
  	var todate=$("#orderto").val();
  	var Status=$("#status").val();
	$('#order_list_datatable').DataTable().ajax.reload();

	if($('#order_list_datatable').length > 0)
	{

		$('#order_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        bDestroy: true,
	        bFilter: false,
	        ajax: base_url+'/admin/order/searchorder?fromdate='+fromdate+'&todate='+todate+'&Status='+Status,
	        columns: [

			{ 
				orderable: false,
				data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
				
					return full.order_no;
				
				   
				}
			},
			 { 
			 	orderable: false,
		    	data: "null",
				width: '50px',
				autoWidth: false,
				'text-align': 'center', 
				render : function(data,type,full) { 
					if(full.user)
						return full.user.name;
					else
						return 'NA';
				
					}
			},
			{ 
				orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				   if(full.shop)
					   return full.shop.name;
				   else
					   return 'NA';
			   
				   }
		   },

			{ 
			   orderable: false,
			   data: "null",
			   width: '50px',
			   autoWidth: false,
			   'text-align': 'center', 
			   render : function(data,type,full) { 
				       return full.discount;
				 }
		   },
		 
		{ 
			orderable: false,
			data: "null",
			width: '100px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  return full.amount;
			}
		},
		{ 
			orderable: false,
			data: "null",
			width: '100px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
				const dateTime = full.created_at;
				const parts = dateTime.split(/[- :]/);
				const wanted = `${parts[2]}-${parts[1]}-${parts[0]} ${parts[3]}:${parts[4]}`;
				return wanted;
			}
		},
	
		{ 
			orderable: false,
			data: "null",
			width: '50px',
			autoWidth: false,
			 'text-align': 'center', 
			  render : function(data,type,full) { 
			  if(full.order_status)
				return full.order_status.name;
			  else
			    return 'NA';
			}
		},
	
	      
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '500px',
	                "targets": 7,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/order/view/'+full.id+'" class=" btn btn-default btn-xs info"><i class="fa fa-eye" aria-hidden="true" title="order details" ></i></a>';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#order_list_datatable tbody').addClass("m-datatable__body");
	            	$('#order_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#order_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#order_list_datatable td').addClass("m-datatable__cell");
	            	$('#order_list_datatable input').addClass("form-control m-input");

	            	$('#order_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}
});


if($('#enquiry_list_datatable').length > 0)
	{

		$('#enquiry_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/enquiry/list',
			columns: [
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
					  return full.user ? full.user.name : 'NA';
					}
				},
			 
			
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.shop ? full.shop.name : 'NA';
					}
				},
				
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.category ? full.category.name : 'NA';
					}
				},
				
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.sub_category ? full.sub_category.name : 'NA';
					}
				},
				
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.location || 'NA';
					}
				},
				
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.mobile || 'NA';
					}
				},
				
				{ 
					orderable: false,
					data: "null",
					width: '170px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.product_detail || 'NA';
					}
				},
	
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.product_name || 'NA';
					}
				},
				
				{ 
					orderable: false,
					data: "null",
					width: '70px',
					autoWidth: false,
					 'text-align': 'center', 
					  render : function(data,type,full) { 
						return full.expected_purchase || 'NA';
					}
				},
	
	      
	        ],
	        "columnDefs": [
	            {
	            	width: '10',
	            	left: '500px',
	                "targets": 9,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 

						var shop_id = full.shop ? full.shop.id: '';
						 if(full.status==0)
						 {
							return   '<button class=" btn btn-success btn-xs info enquiry_notify" data-id="'+shop_id+'" value="'+full.id+'" title="sent notification" data-toggle="modal" data-target="#modal"><i class="fa fa-bell" aria-hidden="true"></i>Notify</button>';
						 }else{
							return   '<button class=" btn btn-primary btn-xs info notified"  value="'+full.id+'" title="already notified"><i class="fa fa-check" aria-hidden="true"></i>Notified</button>';
						 }
					}
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#enquiry_list_datatable tbody').addClass("m-datatable__body");
	            	$('#enquiry_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#enquiry_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#enquiry_list_datatable td').addClass("m-datatable__cell");
	            	$('#enquiry_list_datatable input').addClass("form-control m-input");

	            	$('#enquiry_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}



	if($('#buy_list_datatable').length > 0)
	{

		$('#buy_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/buy/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.mobile;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.shop_name || 'NA';
	            }
	        },
	    
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.order_status != null)
	          			return full.order_status.name;
	          		else
	          			return 'NA';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 5,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/buy/assign/'+full.id+'" class=" btn btn-primary btn-xs info" title="assign"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#buy_list_datatable tbody').addClass("m-datatable__body");
	            	$('#buy_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#buy_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#buy_list_datatable td').addClass("m-datatable__cell");
	            	$('#buy_list_datatable_filter input').addClass("form-control m-input");

	            	$('#buy_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}


	if($('#deliver_list_datatable').length > 0)
	{

		$('#deliver_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/deliver_anything/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.mobile;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.shop_name || 'NA';
	            }
	        },
	    
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '70px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              if(full.order_status != null)
	          			return full.order_status.name;
	          		else
	          			return 'NA';
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 5,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/deliver/assign/'+full.id+'" class=" btn btn-primary btn-xs info" title="assign"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#deliver_list_datatable tbody').addClass("m-datatable__body");
	            	$('#deliver_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#deliver_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#deliver_list_datatable td').addClass("m-datatable__cell");
	            	$('#deliver_list_datatable_filter input').addClass("form-control m-input");

	            	$('#deliver_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}



	
	if($('#subadmin_list_datatable').length > 0)
	{
		var role_slug = $('#role_slug').val();

		$('#subadmin_list_datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: base_url+'/admin/additonaluser_anything/list/'+role_slug,
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.email;
	            }
	        },
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.mobile;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
			},
			
		
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 4,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/additonaluser/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="assign"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
	        ],

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#subadmin_list_datatable tbody').addClass("m-datatable__body");
	            	$('#subadmin_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#subadmin_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#subadmin_list_datatable td').addClass("m-datatable__cell");
	            	$('#subadmin_list_datatable_filter input').addClass("form-control m-input");

	            	$('#subadmin_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}



	if($('#driver_list_datatable').length > 0)
	{

		$('#driver_list_datatable').DataTable({
			processing: true,
			serverSide: true,
			stateSave: true,
	        ajax: base_url+'/admin/driver/list',
	        columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.name;
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.email;
	            }
	        },
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.mobile;
	            }
			},
			
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.alt_mobile;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return '<img src="'+base_url+'/storage/app/'+full.image+'" style="width:60px; height:60px;">';
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.city ?? 'NA';
	            }
	        },
		
	        ],
	        "columnDefs": [
	            {
	            	width: '50',
	            	left: '500px',
	                "targets": 6,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '<a href="'+base_url+'/admin/driver/edit/'+full.id+'" class=" btn btn-primary btn-xs info" title="assign"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;';
	                }
	            }
			],
			
				
			"fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
			},
			

	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#driver_list_datatable tbody').addClass("m-datatable__body");
	            	$('#driver_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#driver_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#driver_list_datatable td').addClass("m-datatable__cell");
	            	$('#driver_list_datatable_filter input').addClass("form-control m-input");

	            	$('#driver_list_datatable tr').css('table-layout','fixed');
	            });
	        }

		});
	}


	

	
$('body').on('submit','#reportForm',function(e)
{

	e.preventDefault();
	var fromdate=$("#orderfrom").val();
	var todate=$("#orderto").val();
	var shop=$(".saleshop").val();

   $('#salereport_list_datatable').DataTable().ajax.reload();

  if($('#salereport_list_datatable').length > 0)
  {
	
	  $('#salereport_list_datatable').DataTable({
			processing: true,
			serverSide: true,
			bDestroy: true,
			bFilter: false,
			stateSave: true,
		  ajax: base_url+'/admin/vendor_payment/searchshop_report?fromdate='+fromdate+'&todate='+todate+'&shop='+shop,
		  columns: [
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.id;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.created_at;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.delivery_date;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '350px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	              return full.order_no;
	            }
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	             // return '<a href="'+base_url+'/admin/vendor_payment/view/'+full.id+'" class=" btn btn-default btn-xs info getattrr">View Details</a>';
				  return '<button class=" btn btn-primary btn-xs info salereport"  data-id="'+full.id+'" title="order details">view details</button>';
				}
	        },
	        { 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					let total= 0;
					$(full.order_products).each(function (i,products) {
					  if(products.product)
					  { 
						//	total.push(products.product.name);
						
						  	total += (products.tot_price) *( products.product.commission)/100;
					  }
					});
					return 'Rs.'+total;
	            }
			},
			{ 
	        	orderable: false,
	        	data: "null",
	        	width: '50px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
					let total= 0;
					$(full.order_products).each(function (i,products) {
					  if(products.product)
					  { 
						//	total.push(products.product.name);
						
						  	total += (products.tot_price) *( products.product.commission)/100;
					  }
					});
					return 'Rs.'+(full.grand_total - total);
	            }
	        },
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 7,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							return   '--';
	                }
	            }
			],
			
			"fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            },
		  createdRow: function(row, data, dataIndex) 
		  {
			  setTimeout(function()
			  {

				  $('#salereport_list_datatable tbody').addClass("m-datatable__body");
				  $('#salereport_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
				  $('#salereport_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
				  $('#salereport_list_datatable td').addClass("m-datatable__cell");
				  $('#salereport_list_datatable input').addClass("form-control m-input");

				  $('#salereport_list_datatable tr').css('table-layout','fixed');
			  });
		  }

	  });
  }
});




  


	
if($('#salereport_list_datatable').length > 0)
{
  
	$('#salereport_list_datatable').DataTable({
		  processing: true,
		  serverSide: true,
		
		ajax: base_url+'/admin/vendor_payment_report/list',
		columns: [
		  { 
			  orderable: false,
			  data: "null",
			  width: '150px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.id;
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '150px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.delivery_date;
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.order_no;
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '150px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
			   // return '<a href="'+base_url+'/admin/vendor_payment/view/'+full.id+'" class=" btn btn-default btn-xs info getattrr">View Details</a>';
				return '<button class=" btn btn-primary btn-xs info salereport"  data-id="'+full.id+'" title="order details">view details</button>';
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  let total= 0;
				  $(full.order_products).each(function (i,products) {
					if(products.product)
					{ 
					  //	total.push(products.product.name);
					 
							total += (products.tot_price) *( products.product.commission)/100;
					}
				  });
				  return 'Rs.'+total;
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  let total= 0;
				  $(full.order_products).each(function (i,products) {
					if(products.product)
					{ 
					  //	total.push(products.product.name);
					  
							total += (products.tot_price) *( products.product.commission)/100;
					}
				  });
				  return 'Rs.'+(full.grand_total - total);
			  }
		  },
		  ],
		  "columnDefs": [
			  {
				  width: '100',
				  left: '500px',
				  "targets": 6,
				  "visible": true,
				   
				   "render": function (data, type, full) { 
						  return   '--';
				  }
			  }
		  ],
		  
		  "fnRowCallback" : function(nRow, aData, iDisplayIndex){
			  $("td:first", nRow).html(iDisplayIndex +1);
			 return nRow;
		  },
		createdRow: function(row, data, dataIndex) 
		{
			setTimeout(function()
			{

				$('#salereport_list_datatable tbody').addClass("m-datatable__body");
				$('#salereport_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
				$('#salereport_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
				$('#salereport_list_datatable td').addClass("m-datatable__cell");
				$('#salereport_list_datatable input').addClass("form-control m-input");

				$('#salereport_list_datatable tr').css('table-layout','fixed');
			});
		}

	});
}

if($('#commission_report_list_datatable').length > 0)
{

	$('#commission_report_list_datatable').DataTable({
		  processing: true,
		  serverSide: true,
			
		ajax: base_url+'/admin/vendor_commission_report/list',
		columns: [
		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.seller_data.name;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
					var date = new Date(full.created_at);
	          		var day = date.getDate();
	          		var month = date.toLocaleString('default', { month: 'short' })
        			var year = date.getFullYear();
        			day = day.toString().length > 1 ? day : "0" + day;

        			return (day+" "+ month +" "+ year);
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 

					if(full.order_data.delivery_date != null) {
						var date = new Date(full.order_data.delivery_date);
		          		var day = date.getDate();
		          		var month = date.toLocaleString('default', { month: 'short' })
	        			var year = date.getFullYear();
	        			day = day.toString().length > 1 ? day : "0" + day;

	        			return (day+" "+ month +" "+ year);
					} else {
						return 'NIL';
					}
					
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.order_id;
			  }
		  },

		  { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	             // return '<a href="'+base_url+'/admin/vendor_payment/view/'+full.id+'" class=" btn btn-default btn-xs info getattrr">View Details</a>';
				  return '<button class=" btn btn-primary btn-xs info salereport"  data-id="'+full.order_id+'" title="order details">view details</button>';
				}
	        },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.amount;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.commission;
			  }
		  },

		  ],
		  "columnDefs": [
			  {
				  width: '100',
				  left: '500px',
				  "targets": 7,
				  "visible": true,
				   
				   "render": function (data, type, full) { 
						
						if(full.is_paid == 0)
							return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
						else 
							return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';

						// if(is_paid == 0)
						// 	return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
						// else 
						// 	return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';
				  }
			  }
		  ],
		  
		 //  "fnRowCallback" : function(nRow, aData, iDisplayIndex){
		 //  	$("td:first", nRow).html(iDisplayIndex +1);
			// return nRow;
		 //  },
		createdRow: function(row, data, dataIndex) 
		{
			setTimeout(function()
			{

				$('#commission_report_list_datatable tbody').addClass("m-datatable__body");
				$('#commission_report_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
				$('#commission_report_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
				$('#commission_report_list_datatable td').addClass("m-datatable__cell");
				$('#commission_report_list_datatable input').addClass("form-control m-input");

				$('#commission_report_list_datatable tr').css('table-layout','fixed');
			});
		}

	});
}

});


$('body').on('submit','#adminCommissionreportForm',function(e)
{

	e.preventDefault();
	var fromdate=$("#orderfrom").val();
	var todate=$("#orderto").val();
	var shop=$(".saleshop").val();

   $('#commission_report_list_datatable').DataTable().ajax.reload();

  if($('#commission_report_list_datatable').length > 0)
  {
	
	  $('#commission_report_list_datatable').DataTable({
			processing: true,
			serverSide: true,
			bDestroy: true,
			bFilter: false,
			stateSave: true,
		  ajax: base_url+'/admin/vendor_commission/searchshop_report?fromdate='+fromdate+'&todate='+todate+'&shop='+shop,
		  columns: [
		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.seller_data.name;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
					var date = new Date(full.created_at);
	          		var day = date.getDate();
	          		var month = date.toLocaleString('default', { month: 'short' })
        			var year = date.getFullYear();
        			day = day.toString().length > 1 ? day : "0" + day;

        			return (day+" "+ month +" "+ year);
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 

					if(full.order_data.delivery_date != null) {
						var date = new Date(full.order_data.delivery_date);
		          		var day = date.getDate();
		          		var month = date.toLocaleString('default', { month: 'short' })
	        			var year = date.getFullYear();
	        			day = day.toString().length > 1 ? day : "0" + day;

	        			return (day+" "+ month +" "+ year);
					} else {
						return 'NIL';
					}
					
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.order_id;
			  }
		  },

		  { 
	        	orderable: false,
	        	data: "null",
	        	width: '150px',
	        	autoWidth: false,
	        	 'text-align': 'center', 
	          	render : function(data,type,full) { 
	             // return '<a href="'+base_url+'/admin/vendor_payment/view/'+full.id+'" class=" btn btn-default btn-xs info getattrr">View Details</a>';
				  return '<button class=" btn btn-primary btn-xs info salereport"  data-id="'+full.order_id+'" title="order details">view details</button>';
				}
	        },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.amount;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.commission;
			  }
		  },

		  ],
		  "columnDefs": [
			  {
				  width: '100',
				  left: '500px',
				  "targets": 7,
				  "visible": true,
				   
				   "render": function (data, type, full) { 
						
						if(full.is_paid == 0)
							return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
						else 
							return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';

						// if(is_paid == 0)
						// 	return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
						// else 
						// 	return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';
				  }
			  }
		  ],
			
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
   //              $("td:first", nRow).html(iDisplayIndex +1);
   //             return nRow;
   //          },
		  createdRow: function(row, data, dataIndex) 
		  {
			  setTimeout(function()
			  {

				  $('#commission_report_list_datatable tbody').addClass("m-datatable__body");
				  $('#commission_report_list_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
				  $('#commission_report_list_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
				  $('#commission_report_list_datatable td').addClass("m-datatable__cell");
				  $('#commission_report_list_datatable input').addClass("form-control m-input");

				  $('#commission_report_list_datatable tr').css('table-layout','fixed');
			  });
		  }

	  });
  }
});


if($('#vendor_commission_report_datatable').length > 0)
{
	$('#vendor_commission_report_datatable').DataTable({
		  processing: true,
		  serverSide: true,
		
		// ajax: 'http://dev3.webcastle.in/dafy/seller/vendor_commission_report/list',
		ajax: 'https://dafy.in/seller/vendor_commission_report/list',
		columns: [
		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
					var date = new Date(full.created_at);
	          		var day = date.getDate();
	          		var month = date.toLocaleString('default', { month: 'short' })
        			var year = date.getFullYear();
        			day = day.toString().length > 1 ? day : "0" + day;

        			return (day+" "+ month +" "+ year);
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 

					if(full.order_date.delivery_date != null) {
						var date = new Date(full.created_at);
		          		var day = date.getDate();
		          		var month = date.toLocaleString('default', { month: 'short' })
	        			var year = date.getFullYear();
	        			day = day.toString().length > 1 ? day : "0" + day;

	        			return (day+" "+ month +" "+ year);
					} else {
						return 'NIL';
					}
					
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.order_data.order_no;
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.total;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.amount;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.commission;
			  }
		  },

		  ],
		  "columnDefs": [
			  {
				  width: '100',
				  left: '500px',
				  "targets": 6,
				  "visible": true,
				   
				   "render": function (data, type, full) { 
						
						if(full.is_paid == 0)
							return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
						else 
							return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';
				  }
			  }
		  ],
		  
		 //  "fnRowCallback" : function(nRow, aData, iDisplayIndex){
		 //  	$("td:first", nRow).html(iDisplayIndex +1);
			// return nRow;
		 //  },
		createdRow: function(row, data, dataIndex) 
		{
			setTimeout(function()
			{

				$('#vendor_commission_report_datatable tbody').addClass("m-datatable__body");
				$('#vendor_commission_report_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
				$('#vendor_commission_report_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
				$('#vendor_commission_report_datatable td').addClass("m-datatable__cell");
				$('#vendor_commission_report_datatable input').addClass("form-control m-input");

				$('#vendor_commission_report_datatable tr').css('table-layout','fixed');
			});
		}

	});
}



$('body').on('submit','#commissionReport',function(e)
{
	e.preventDefault();
	var fromdate=$("#orderfrom").val();
	var todate=$("#orderto").val();
	var shop=$(".saleshop").val();

   $('#vendor_commission_report_datatable').DataTable().ajax.reload();

  if($('#vendor_commission_report_datatable').length > 0)
  {
	
	  $('#vendor_commission_report_datatable').DataTable({
			processing: true,
			serverSide: true,
			bDestroy: true,
			bFilter: false,
			stateSave: true,
		  // ajax: 'http://dev3.webcastle.in/dafy/seller/search_shop_commission_report?fromdate='+fromdate+'&todate='+todate+'&shop='+shop,
		  ajax: 'https://dafy.in/seller/search_shop_commission_report?fromdate='+fromdate+'&todate='+todate+'&shop='+shop,
		  columns: [
	        { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
					var date = new Date(full.created_at);
	          		var day = date.getDate();
	          		var month = date.toLocaleString('default', { month: 'short' })
        			var year = date.getFullYear();
        			day = day.toString().length > 1 ? day : "0" + day;

        			return (day+" "+ month +" "+ year);
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 

					if(full.order_date.delivery_date != null) {
						var date = new Date(full.created_at);
		          		var day = date.getDate();
		          		var month = date.toLocaleString('default', { month: 'short' })
	        			var year = date.getFullYear();
	        			day = day.toString().length > 1 ? day : "0" + day;

	        			return (day+" "+ month +" "+ year);
					} else {
						return 'NIL';
					}
					
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '350px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				return full.order_data.order_no;
			  }
		  },
		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.total;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.amount;
			  }
		  },

		  { 
			  orderable: false,
			  data: "null",
			  width: '50px',
			  autoWidth: false,
			   'text-align': 'center', 
				render : function(data,type,full) { 
				  return 'Rs.'+full.commission;
			  }
		  },
	        ],
	        "columnDefs": [
	            {
	            	width: '100',
	            	left: '500px',
	                "targets": 6,
	                "visible": true,
	                 
	                 "render": function (data, type, full) { 
							if(full.is_paid == 0)
								return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'" value="'+full.id+'"><div class="slider round"></div></label>'
							else 
								return '<label class="switch"><input type="checkbox" class="status" id="togBtn" data-id="'+full.id+'"  value="'+full.id+'" checked><div class="slider round"></div></label>';
	                }
	            }
			],
			
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
   //              $("td:first", nRow).html(iDisplayIndex +1);
   //             return nRow;
   //          },
		  createdRow: function(row, data, dataIndex) 
		  {
			  setTimeout(function()
			  {

				  $('#vendor_commission_report_datatable tbody').addClass("m-datatable__body");
				  $('#vendor_commission_report_datatable tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
				  $('#vendor_commission_report_datatable tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
				  $('#vendor_commission_report_datatable td').addClass("m-datatable__cell");
				  $('#vendor_commission_report_datatable input').addClass("form-control m-input");

				  $('#vendor_commission_report_datatable tr').css('table-layout','fixed');
			  });
		  }

	  });
  }
});

  $('body').on('click','.export_voucher',function(e)
  {
    window.location.href = base_url+'/admin/offline/export_voucher';
  });
$(function() 
{


	if($('#category_table').length > 0)
	{
		$('#category_table').DataTable({
	        processing: true,
	        serverSide: true,
	        pageLength: 50,
	        ajax: base_url+'/admin/categories/list',
	        columns: [
	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '300',
	            	autoWidth: false,
	              	render : function(data,type,full) {
	              		return full.title;
	              	}
	            },
	            { 
	            	orderable: false,
	            	data: "null",
	            	width: '300',
	            	autoWidth: false,
	              	render : function(data,type,full) {
	              		if(full.parent_details != null)
	              			return full.parent_details.title;
	              		else
	              			return '---';
	              	}
	            }
	        ],
	        "columnDefs": [
	            {
	            	 width: '300',
	                "targets": 2,
	                "visible": true,
	                 "render": function (data, type, full) { 
	 				return '<button class="success dlt-btn" title="Edit details"> <a href="'+base_url+'/admin/categories/edit/'+full.id+'"><i class="fa fa-pencil" aria-hidden="true"></i></a> </button> &nbsp;&nbsp; <button class="danger dlt-btn deleteCategory" title="Delete details" data-id="'+full.id+'"><i class="fa fa-times" aria-hidden="true"></i></button> ';
	                
	                }
	            }
	         ],
	        createdRow: function(row, data, dataIndex) 
	        {
	            setTimeout(function()
	            {

	            	$('#category_table tbody').addClass("m-datatable__body");
	            	$('#category_table tbody tr:odd').addClass("m-datatable__row m-datatable__row--odd");
	            	$('#category_table tbody tr:even').addClass("m-datatable__row m-datatable__row--even");
	            	$('#category_table td').addClass("m-datatable__cell");
	            	$('#category_table_filter input').addClass("form-control m-input");

	            	$('#category_table tr').css('table-layout','fixed');
	            });
	        }
	    });

	    $('body').on('click','.deleteCategory',function()
		{
			var id = $(this).attr('data-id');
			$.ajax({
			    type: "GET",
			    url: base_url+'/admin/categories/delete/'+id,
			    success: function(result) 
			    {
			      	location.reload();
			    }
			});
		});
	}

});
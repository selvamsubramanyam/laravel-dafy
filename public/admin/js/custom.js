$(document).ready(function () {

    var field;
    $(".edit-btn").click(function () {

        $("#text-edit").val($(this).html());

        field = $(this);

    });
    $(".dlt-btn").click(function () {

        $(this).parent().parent().remove();

    });

    $("#save-input").click(function () {
        console.log($("#text-edit").val());
        // alert("click");
        field.html($("#text-edit").val());
    });
});



// Table Collapse

$('#tbl-toggle').click(function () {
    
    if ($(this).hasClass('padding-none')) {
        $(this).removeClass('padding-none');
        $(this).addClass('pad-10');
    }
  });



  
/*Add row event*/
$(document).on('click', '.rowfy-addrow', function(){
    let rowfyable = $(this).closest('table');
    let lastRow = $('tbody tr:last', rowfyable).clone();
    $('input', lastRow).val('');
    $('tbody', rowfyable).append(lastRow);
    $(this).removeClass('rowfy-addrow btn-success').addClass('rowfy-deleterow btn-danger').text('-');
  });
  
  /*Delete row event*/
  $(document).on('click', '.rowfy-deleterow', function(){
    $(this).closest('tr').remove();
  });
  
  /*Initialize all rowfy tables*/
  $('.rowfy').each(function(){
    $('tbody', this).find('tr').each(function(){
      $(this).append('<td><button class="btn btn-sm '
        + ($(this).is(":last-child") ?
          'rowfy-addrow btn-success">+' :
          'rowfy-deleterow btn-danger">-') 
        +'</button></td>');
    });
  });
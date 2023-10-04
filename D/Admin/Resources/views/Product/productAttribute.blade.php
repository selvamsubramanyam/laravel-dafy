<?php
$checked_values = Session::get('check_radio[]');

?>

<div class="">
    <div class="form-group row form-space">
    <label class="col-xs-12 col-lg-4 col-form-label">Attribute Types*:</label>
    <input type="hidden" name="checkedlist"/>

                   
                <div class="col-xs-12 col-lg-8 ">
                    @foreach($cate as $key=>$value)
                    <div class="row">
                        <label class="col-xs-12 col-lg-4 col-form-label">{{ucfirst($key)}}</label>
                        @foreach($value as $val)
                        
                            <input type="radio" class="attr_type"  data-waschecked="true" name="attr_{{$key}}" value="{{$val}}"><span>&nbsp;{{strtoupper($val)}}</span>&nbsp;&nbsp;
                        
                        @endforeach
                    </div>
                    @endforeach
                </div>
                        
    </div>

</div>
<script>
$(function(){
    $('.attr_type').click(function(){
        var $radio = $(this);
        
        // if this was previously checked
        if ($radio.data('waschecked') == true)
        {
            $radio.prop('checked', false);
            $radio.data('waschecked', false);
        }
        else
            $radio.data('waschecked', true);
        
        // remove was checked from other radios
        $radio.siblings('.attr_type').data('waschecked', false);
    });
});



$(".attr_type").on('change',function(event){
      event.preventDefault();
      var checked_radio = []; 
      let check = $("input[name=checkedlist]").val();
    //  var base_url="{{url('/')}}";
    $(".attr_type:checked").each(function(){
    checked_radio.push($(this).val());
    });

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
      $.ajax({
        url: base_url+'/admin/product/setRadioSession',
        type:"POST",
        data:{
           checked_values: checked_radio,
          _token: "{{ csrf_token() }}",
        },
        success:function(response){
          console.log(response);
         
        },
       });
  });
</script>
@include('admin::layouts.includes.header')
<link href="{{URL('public/admin/css/taginput.css')}}" rel="stylesheet" type="text/css" />
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add {{$product_attribute->name}} Attributes</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('message') }}
                        </div>
                    @endif
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/product/updateattribute')}}"  enctype='multipart/form-data' id="myFormId">

                                {{csrf_field()}}
          
                                <input type="hidden" name="id" id="id" value="{{$product_attribute->id}}">
                                <input type="hidden" name="attrValue" id="hiddenRadioValue" />
                                <input type="hidden" name="attrName" id="hiddenRadioName" />

                                @foreach($product_attribute->categories as $cat)
                                    <input type="hidden" name="cat_id[]" value="{{$cat->id}}"/>
                                @endforeach
                                <div class="">
                                    <div class="form-group row form-space">
                                    <label class="col-xs-12 col-lg-4 col-form-label">Attribute Types*:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            @foreach($field_val as $key=>$field)
                                            <div class="row">
                                      
                                            <label class="col-xs-12 col-lg-4 col-form-label">{{ucfirst($key)}}</label>
                                            @foreach($field as $val)
                                              <?php $attr_key = $key.'->'.$val; ?>
                                              <input type="radio" class="attr_type" data-id="{{$key.'->'.$val}}" data-waschecked="true" name="attr_{{$key}}" value="{{$val}}" {{ (is_array($attribute_names)) && in_array($val, $attribute_values)  && in_array($attr_key, $attribute_names) ? 'checked' : '' }}><span>&nbsp;{{strtoupper($val)}}</span>&nbsp;&nbsp;
                                              @endforeach
                                            </div>
                                            @endforeach
                                        </div>
                        
                                     </div>

                                </div>


                                <div class="text-right">
                                    <button type="reset" class="btn btn-default btn-sm" onclick="window.history.back()">Back</button>
                                    <button type="Submit" class="btn btn-primary-blue btn-sm" id="myButtonID">Submit</button>
                                </div>

                        </div>
                    </div>

                </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>

    
</div>


</div>
</div>
</div>

</div>
<!-- /page content -->
</div>

@include('admin::layouts.includes.footer')
<script src="{{ URL('public/admin/js/taginput.js') }}"></script>

<script>
$('#myFormId').submit(function(){
    var data = $.map($(".attr_type:checked"), function(elem, idx) {
      return $(elem).val()+"&_";
    }).join('');
    var dataList = $(".attr_type:checked").map(function() {
    return $(this).data("id");
}).get().join(",");
$('#hiddenRadioName').val(dataList);
$('#hiddenRadioValue').val(data);
    $("#myButtonID", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
    return true;
});

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
        $radio.siblings('.type').data('waschecked', false);
    });
});
</script>
@include('admin::layouts.includes.header')
<link rel="stylesheet" href="{{ URL('public/admin/assets/vendor_components/select2/dist/css/select2.min.css')}}">
<link href="{{URL('public/admin/css/animate.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/scroll.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/style.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/master_style.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL('public/admin/css/bootstrap-extend.css')}}" rel="stylesheet" type="text/css" />


<style>

        .disabled {
            color: darkgrey;
            background-color: grey;
        }
        /*category model*/
        input[type="file"] {

            display:block;
        }
        .imageThumb {
            max-height: 75px;
            border: 2px solid;
            margin: 10px 10px 0 0;
            padding: 1px;
        }
        .cat-img{
            height: 100%;
            float: left;
        }


        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color:  #d2d9dd;
            border-color: #367fa9;
            padding: 1px 10px;
            color: #151313 !important;
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
        .form-style-5 input[type="checkbox"]:focus,
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
            background: #652d90;
            color: #ffdd15;
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
            color: #ffdd15;
            margin: 0 auto;
            background: #652d90;
            font-size: 18px;
            text-align: center;
            font-style: normal;
            width: 100%;
            border: 1px solid #652d90;
            border-width: 1px 1px 3px;
            margin-bottom: 10px;
        }
        .form-style-5 input[type="submit"]:hover,
        .form-style-5 input[type="button"]:hover
        {
            background: #109177;
        }
        .cat-img{
            height: 100%;
            float: left;
        }
    </style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count section-gap">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Category Management</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="container"> 
                    @if(session()->has('message'))
                       <div class="m-alert m-alert--outline alert alert-success alert-dismissible  show" role="alert" style="    color: #1bbf23; background-color: #fff">
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

                    @if(session()->has('error_message'))
                    <div class="alert alert-danger">
                        <ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                            {{ session()->get('error_message') }}
                        </ul>
                    </div><br />
                    @endif
                </div>

                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">

                            <!--begin::Form-->
                            <form method="post" action="{{URL('admin/category/addcategory')}}"  enctype='multipart/form-data'>

                                {{csrf_field()}}

                                <div class="">
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Category Name:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="name" placeholder="Category Name" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Image:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="file"  class="form-control form-border"  name="pic" accept="image/*" required="required">
                                        </div>
                                    </div>

                                     
                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Parent Info:</label>
                                            <div class="col-xs-12 col-lg-8">
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">- -Choose Parent- -</button>
                                                <input type="text" name="parent_id" id="parent" value="0" hidden> 
                                            </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label"></label>
                                            <div class="col-xs-12 col-lg-8">
                                                <input type="text" name="parent_name" id="parent_name" value="" placeholder="--Parent name--" readonly>
                                            </div>
                                    </div>

                                    <div id="fieldset_spec" class="form-group row form-space" style="display: none">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Please specify about child category:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="checkbox"  name="last_child" checked readonly> {{--id="last_child"--}}
                                            <label for="last_child">Last Child Category </label>
                                        {{--    <input type="checkbox" id="not_a_last_child" name="not_a_last_child" checked >
                                            <label for="not_a_last_child">Not Last Child Category? </label>  --}}
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Position:</label>
                                        <div class="col-xs-12 col-lg-8 ">
                                            <input type="text" class="form-control form-border" name="order" placeholder="Category Position">
                                        </div>
                                    </div>

                                    <div class="form-group row form-space">
                                        <label class="col-xs-12 col-lg-4 col-form-label">Status:</label>
                                            <div class="col-xs-12 col-lg-8">
                                            <div class="statusBox">
                                                <input type="radio" id="status-1" name="status" value="1" checked><span>Active</span> 
                                                <input type="radio" id="status-2" name="status" value="0"><span>Inactive</span> 
                                            </div>          
                                            </div>
                                    </div>

                                    <!-- <div id="attributes" class="form-group row form-space" style="display: none">
                                        <label class="col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label">
                                            Attribute Name:</label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input type="text" class="form-control form-border" name="attr[]" placeholder="Attribute Value">
                                            <button type="button" class="btn btn-info btn-sm addAttr">+</button>
                                        </div>
                                    </div>
                                    <div class="form-group row form-space result">
                                        
                                    </div> -->
                                    
                                </div>

                                <div class="text-right">
                                    <button type="reset" onclick="window.location='{{ URL::previous() }}'" class="btn btn-default btn-sm">Cancel</button>
                                    <button type="Submit" class="btn btn-primary-blue btn-sm">Submit</button>
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

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h7 class="modal-title" id="largeModalLabel">Select Category</h7><em></em>
                </div>
                <div class="modal-body">
                    <button id="addCatChild" type="button" class="btn btn-primary add" style="display:none;">
                        ADD HERE
                    </button>
                    <div class="content-bottom demo-x">
                        <div class="scrol-bottom">
                            <div class="div_content" id="cat_1">
                                <div class="content1 content-3d">
                                    <div class="viewport">
                                        <ul class="cat">
                                            <li>
                                                <button type="button" class="btn btn-primary btn-xs selectParent">
                                                    ADD HERE
                                                </button>
                                                <input type="text" class="chosenParentName" name="chosenParentName1" id="chosenParentName1" value="" hidden>
                                                <input type="text" class="chosenParent" name="chosenParent1" id="chosenParent1" value="0" hidden>
                                            </li>
                                            @foreach($categories as $cat)
                                                <li>
                                                    <a href="#" id="{{ $cat->id }}" class="icon-list-main icon-list block rel lheight16 tdnone clr">
                                                        <span class="inlblk">{{ $cat->name }}</span>
                                                        <span class="target abs hidden">&nbsp;</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
<script src="{{URL('public/admin/js/jquery.mCustomScrollbar.concat.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">

    $(document).ready(function () {
            //category selection scroll fn start
            var catDivs = [1];
            var parent = [];
            var in_use = false;
            $(document).on('click','.cat li a', function(){
                if(!in_use)
                {
                    par = $.trim($('.inlblk', this).html());
                    mainDiv = $(this).closest('.div_content').attr('id');
                    divNo = parseInt(mainDiv.split("_").pop());
                    parent[divNo - 1] = par;

                    NextDivNo = divNo + 1;
                    cat = $(this).attr('id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type:'get',
                        url:'{{ route('category.getSubCategory') }}',
                        data:{category:cat},
                        success:function(res){

                            i = catDivs.length - 1;
                            while(i >= 0){
                                if(catDivs[i] >= NextDivNo){
                                    $('#cat_'+ catDivs[i]).remove();
                                    catDivs.splice( $.inArray(catDivs[i], catDivs), 1 );
                                }
                                i--;
                            }

                            j = parent.length - 1;
                            while(j > (divNo-1)){
                                parent.splice(j, 1);
                                j--;
                            }

                            parentString = '';
                            for(key in parent){
                                parentString += '>>' + parent[key];
                            }

                            $('#'+ mainDiv +' .cat li a').removeClass('click_active');
                            $('#'+ cat).addClass('click_active');
                            $('#'+ mainDiv +' .cat li a span.pull-right').remove();
                            if(res.sub_categories.length > 0){
                                $('#'+ cat).append('<span class="pull-right"><i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></span>');
                                var opt = '';
                                for(key in res.sub_categories){
                                    opt += '<li>\n' +
                                        '      <a href="#" id="'+ res.sub_categories[key].id +'" class="icon-list-main icon-list block rel lheight16 tdnone clr">\n' +
                                        '          <span class="inlblk">\n'
                                        + res.sub_categories[key].name +
                                        '          </span>\n' +
                                        '          <span class="target abs hidden">&nbsp;</span>\n' +
                                        '      </a>\n' +
                                        '  </li>\n';
                                }
                                newDiv = '<div class="div_content" id="cat_'+ NextDivNo +'">\n' +
                                    '  <div class="content1 content-3d">\n' +
                                    '      <div class="viewport">\n' +
                                    '          <ul class="cat">\n' +
                                    '              <li>\n' +
                                    '                  <button type="button" class="btn btn-primary btn-xs selectParent">\n' +
                                    '                      ADD HERE\n' +
                                    '                  </button>\n' +
                                    '                  <input type="text" class="chosenParentName" name="chosenParentName'+ NextDivNo +'" id="chosenParentName'+ NextDivNo +'" value="'+ parentString +'" hidden>\n' +
                                    '                  <input type="text" class="chosenParent" name="chosenParent'+ NextDivNo +'" id="chosenParent'+ NextDivNo +'" value="'+ cat +'" hidden>\n' +
                                    '              </li>\n'
                                    + opt +

                                    '          </ul>\n' +
                                    '      </div>\n' +
                                    '  </div>\n' +
                                    '</div>';
                                $('div.scrol-bottom').append(newDiv);
                                makeScroll();
                                catDivs.push(NextDivNo);
                                $('#addCatChild').hide();
                            }else{
                                $('#addCatChild').html('Add Under "' + par + '"');
                                $('#addCatChild').after('' +
                                    '<input type="text" class="chosenParentName" name="chosenParentName'+ NextDivNo +'" id="chosenParentName'+ NextDivNo +'" value="'+ parentString +'" hidden>\n' +
                                    '<input type="text" class="chosenParent" name="chosenParent'+ NextDivNo +'" id="chosenParent'+ NextDivNo +'" value="'+ cat +'" hidden>');
                                $('#addCatChild').show();
                            }
                            in_use=false;
                        }
                    });
                }


            });

        // $('body').on('click','.addAttr',function() {
        //     selectHTML = "<br><label class='col-xs-12 col-lg-4 col-sm-5 col-xs-12 col-form-label'></label><div class='col-xs-12 col-lg-8'><input type='text' class='form-control form-border' name='attr[]' placeholder='Attribute Value'></div>";
        //     $(".result").append(selectHTML);
        // });
    
        $('body').on('click','.selectParent',function() {
            cid = $(this).closest('ul.cat').find('.chosenParent').val();
            cname = $(this).closest('ul.cat').find('.chosenParentName').val();
            $('#parent').val(cid);
            $('#parent_name').val(cname);
            $('#myModal').modal('toggle');
            
            var parent_name = $('#parent_name').val();
            
            if(parent_name !='')
            {
                $('#fieldset_spec').show();
                // $('#hasSpecs').prop('checked', true);
            } else {
                $('#fieldset_spec').hide();
                $('#last_child').prop('checked', false);
                // $('#hasSpecs').prop('checked', false);
                $('#not_a_last_child').prop('checked', false);
                // $('#specification_titles').val('');
                // $('#specification_titles').tagsinput('removeAll');
                // $("#spec").hide();
            }

         });

        $('body').on('change','#last_child',function() {

            if (document.getElementById('last_child').checked)
            {
                $("#not_a_last_child").prop("checked",false);
                // $('#attributes').show();
                // $('#hasSpecDiv').show();
                // $('#hasSpecs').prop('checked', true);

                // if($("#hasSpecs").prop("checked")) {
                //     $("#spec").show();
                // } else {
                //     $("#spec").hide();
                // }
            }
            else
            {
                $("#last_child").prop("checked",false);
                $("#not_a_last_child").prop("checked",true);
                // $('#attributes').hide();
                // $("#spec").hide();
                // $('#hasSpecDiv').hide();
            }

        });

        $('body').on('change','#not_a_last_child',function() {
            
            if (document.getElementById('not_a_last_child').checked)
            {
                $("#last_child").prop("checked",false);
                // $('#attributes').hide();
            }
            else
            {
                $("#not_a_last_child").prop("checked",false);
                $("#last_child").prop("checked",true);
                // $('#attributes').show();
            }

        });


        $('body').on('click','#addCatChild',function() {
            cid = $(this).closest('.modal-body').find('.chosenParent').first().val();
            cname = $(this).closest('.modal-body').find('.chosenParentName').first().val();
            
            $('#parent').val(cid);
            $('#parent_name').val(cname);
            $('#myModal').modal('toggle');

            var parent_name = $('#parent_name').val();
            
            if(parent_name !='')
            {
                $('#fieldset_spec').show();
            }
        });
    });

        //category selection scroll fn start

        function makeScroll() {
            $.mCustomScrollbar.defaults.scrollButtons.enable = true; //enable scrolling buttons by default
            $.mCustomScrollbar.defaults.axis = "yx"; //enable 2 axis scrollbars by default

            $("#content-3d").mCustomScrollbar({theme: "rounded-dark"});

            $(".content-3d").mCustomScrollbar({theme: "rounded-dark"});

            $("#content-3dd").mCustomScrollbar({theme: "rounded-dark"});

            $("#content-3dt").mCustomScrollbar({theme: "3d-thick"});

            $("#content-3dtd").mCustomScrollbar({theme: "rounded-dark"});

            $(".all-themes-switch a").click(function (e) {
                e.preventDefault();
                var $this = $(this),
                    rel = $this.attr("rel"),
                    el = $(".content");
                switch (rel) {
                    case "toggle-content":
                        el.toggleClass("expanded-content");
                        break;
                }
            });

            $.mCustomScrollbar.defaults.theme = "dark-thin"; //set "light-2" as the default theme

            $(".demo-y").mCustomScrollbar();

            $(".demo-x").mCustomScrollbar({
                axis: "x",
                advanced: {
                    autoExpandHorizontalScroll: true
                }
            });

            $(".demo-yx").mCustomScrollbar({
                axis: "yx"
            });

            $(".scrollTo a").click(function (e) {
                e.preventDefault();
                var $this = $(this),
                    rel = $this.attr("rel"),
                    el = rel === "content-y" ? ".demo-y" : rel === "content-x" ? ".demo-x" : ".demo-yx",
                    data = $this.data("scroll-to"),
                    href = $this.attr("href").split(/#(.+)/)[1],
                    to = data ? $(el).find(".mCSB_container").find(data) : el === ".demo-yx" ? eval("(" + href + ")") : href,
                    output = $("#info > p code"),
                    outputTXTdata = el === ".demo-yx" ? data : "'" + data + "'",
                    outputTXThref = el === ".demo-yx" ? href : "'" + href + "'",
                    outputTXT = data ? "$('" + el + "').find('.mCSB_container').find(" + outputTXTdata + ")" : outputTXThref;
                $(el).mCustomScrollbar("scrollTo", to);
                output.text("$('" + el + "').mCustomScrollbar('scrollTo'," + outputTXT + ");");
            });

            /*
            get snap amount programmatically or just set it directly (e.g. "273")
            in this example, the snap amount is list item's (li) outer-width (width+margins)
            */
            var amount = Math.max.apply(Math, $("#content-1 li").map(function () {
                return $(this).outerWidth(true);
            }).get());

            $("#content-1").mCustomScrollbar({
                axis: "x",
                theme: "inset",
                advanced: {
                    autoExpandHorizontalScroll: true
                },
                scrollButtons: {
                    enable: true,
                    scrollType: "stepped"
                },
                keyboard: {scrollType: "stepped"},
                snapAmount: amount,
                mouseWheel: {scrollAmount: amount}
            });
        }
        (function ($) {
            $(window).on("load", function () {
                makeScroll();
            });
        })(jQuery);


        //category selection scroll fn end

        /*$(".modal-body #add_here").click(function()
        {
            var parent_name=$('#parent_name').val();
            if(parent_name !='')
            {
                $('#fieldset_spec').show();
            }
        });*/
</script>
<script>
        $('body').on('click','#not_a_last_child',function() {
        // $("#not_a_last_child").on("click", function (e) {
            var check2 = document.getElementById("not_a_last_child").checked;
            if(check2 == false) {
                e.preventDefault();
                return false;
            }
        });

        $('body').on('click','#last_child',function() {
        // $("#last_child").on("click", function (e) {
            var check1 = document.getElementById("last_child").checked;
            if(check1 == false) {
                e.preventDefault();
                return false;
            }
        });

        // $("#hasSpecs").on("click", function (e) {
        //     var check3 = document.getElementById("hasSpecs").checked;
        //     if(check3 == false) {
        //         e.preventDefault();
        //         return false;
        //     }
        // });

    </script>

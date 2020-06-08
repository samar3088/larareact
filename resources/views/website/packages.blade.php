@extends('layouts.frontend.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
<link href="{{ asset('/frontend/css/custom11.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/commonfiles.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/searchpage-list.css') }}" rel="stylesheet">
        <style>
            table#employee-grid thead, #employee-grid_filter label::before, #employee-grid_filter 
            {
               display: none;
            }
            table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd 
            {
               background-color: transparent !important;
            }
            #packages_include_div
            {
                display:none;
            }
            table.dataTable.no-footer
            {
                border: 0;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover
            {
                color: #fff !important;
                border: 1px solid #d52a33;
                background: linear-gradient(to bottom, #941e24 0%, #8e1d23 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #941e24), color-stop(100%, #8e1d23));
                background: -webkit-linear-gradient(top, #941e24 0%, #8e1d23 100%);
                background: -moz-linear-gradient(top, #941e24 0%, #8e1d23 100%);
                background: -ms-linear-gradient(top, #941e24 0%, #8e1d23 100%);
                background: -o-linear-gradient(top, #941e24 0%, #8e1d23 100%);
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active
            {
                color: #d52a33;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover
            {
                background: #d52a33;
            }
         </style>

@endpush

@section('content')

<!-- Main content comes here -->

<div class="mt-90">

    <div class="m-hide" id="" align="center" style="margin-left: 5%;">
        <a href="#" title="Plan Your Event">
            <img name="0" src="{{ Storage::url($page_items->file) }}" alt="Plan Your Event">
        </a>
    </div>

    <div class="wrapper" style="padding: 0px !important;">
        <div class="col-lg-3 col-md-12 col-sm-12">
            @include('website._additional')
        </div>
    </div>
    <div class="wrapper">
        <div style="margin-top: 10px;"></div>
        <div class="directory-structure clearfix">
            <div id="app-vendors-directory-result-list" class="directory-structure-content">
                <div class="right-panel-search">
                    <div class="col-lg-10 col-md-10 col-sm-12 taketop-50">
                        <div id="right_searchresult">
                            <div class="found-status" style="margin-bottom:5px;">
                                <h1 style="color: #d93f47 !important;">{{ $page_items->heading }}</h1>
                            </div>
                            <div class="found-status" style="margin-bottom:5px;font-size: 12px">
                                <div id="cityDIVS2">
                                    <div class="collapseWrapper22" style="height: auto;">
                                        <p class="collapse22">
                                            {!! $page_items->content !!}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- start flip card -->
                            <div class='containerss'>
                                <div class='flip_box'>
                                    <div class='front'>
                                        <div class="row imgalin">
                                            <div class="col-md-4">
                                                <h3 class="title" id="package_name">{!! $package_first->package_name !!}</h3>
                                                <p class="border"></p>
                                                <img id="package_image" src="{{ Storage::url($package_first->package_image) }}" alt="{!! $package_first->package_name !!}" title="{!! $package_first->package_name !!}" draggable="false" class="imges">
                                                <p class="bolds" id=""><b>&#x2217;</b> Starts from &#8377; <b class="min_package_amount">{{  $package_first->packagedetails->min('price') }}</b> /- </p>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="alignitms">
                                                    <p id="package_description">{!! $package_first->description !!}</p>
                                                </div>
                                            </div>
                                            <div class='r_wrap booksnw'>
                                                <div class='b_round'></div>
                                                <div class='s_round'>
                                                    <div class='s_arrow'>Book Now</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='back algns'>
                                        <div class="row imgalins">
                                            <div class="col-md-5">
                                                <h3 class="title" id="package_name_book">Book {!! $package_first->package_name !!}</h3>
                                                <p class="border"></p>
                                                <div class="form-holder">
                                                    <fieldset>
                                                        <legend>Event Date</legend>
                                                        <i class="fa fa-calendar icon mycustomicons" onclick="clickdateselection()"></i>
                                                        <input class="form-control input-field event_date datepicker" type="text" id="demos" placeholder="dd/mm/yy" onchange="specialEvent();" name="event_date" autocomplete="off" required="" readonly>
                                                    </fieldset>
                                                    <p class="evedateserror packages_error"></p>
                                                </div>
                                                <div class="form-holder">
                                                    <fieldset>
                                                        <legend>No. Of Days Event</legend>
                                                        <select class="required form-control style_box" name="no_of_days" id="no_of_days" onchange="updatepackagetotal();">
                                                            <option value="1" selected="selected">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                        </select>
                                                    </fieldset>
                                                    <p class="evedayserror packages_error"></p>
                                                </div>
                                                <div class="form-holder">
                                                    <fieldset>
                                                        <legend>No Of Guest Expected</legend>
                                                        <select class="required form-control style_box" id="no_of_guest" name="no_of_guest_exp" required="" onchange="changepackagedetails();">
                                                            <option value="" selected="selected">Select</option>
                                                            @foreach($package_first->packagedetails as $info) 
                                                                <option value="{{ $info->id }}">{{ $info->no_of_pax }}</option>
                                                            @endforeach
                                                        </select>
                                                    </fieldset>
                                                    <p class="noofguestserror packages_error"></p>
                                                </div>
                                                <div class="form-holder">
                                                    <fieldset>
                                                        <legend>Per Day Package Amount</legend>
                                                        <i class="fa fa-rupee icon mycustomicon"></i>
                                                        <input type="text" class="form-control package_price" id="package_price" name="package_price" placeholder="" required="" readonly="" style="text-align: left;padding: 7px 20px;">
                                                    </fieldset>
                                                    <p class="evepriceserror packages_error"></p>
                                                </div>
                                                <div class='r_wrap backsbn'>
                                                    <div class='b_round'></div>
                                                    <div class='s_round'>
                                                        <div class='s_arrow'>
                                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-7">
                                                <div class="alignitms" id="default_packge_text">
                                                    <h3>Packages Includes</h3>
                                                    <p class="whiteborder"></p>
                                                    <p class="textcnts">
                                                        Input your details
                                                    </p>
                                                </div>
                                                <div class="alignitms" id="packages_include_div">
                                                    <h3>Packages Includes</h3>
                                                    <p class="whiteborder"></p>
                                                    <ul id="package_include">
                                                        {{-- @if ($package_include != "")
                                                            @foreach(explode(',', $package_include) as $info) 
                                                                <li>{{$info}}</li>
                                                            @endforeach
                                                        @endif --}}
                                                    </ul>
                                                    <p class="bolds">Total Amount<b id="total_package_amount"> &#8377;  {{  $package_first->packagedetails->min('price') }} /-  </b> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ends flip card -->

                            <!-- start button actions -->
                            <div class="row text-center threebtns" style="margin-top: 2%;">
                                <div class="col-md-4">
                                    <div class="btntxt equips" id="add_more_equipments">
                                        <p class="span">Add More Equipments</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="btntxt addtocarts package_booking_type" id="package_book_cart">
                                        <p class="span">Add to Cart</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="btntxt booknows package_booking_type" id="package_book_now">
                                        <p class="span">Book Now</p>
                                    </div>
                                </div>
                            </div>
                            <!-- ends button actions -->

                            <div class="sort-by" itemscope="" itemtype="#" id="first_show" style="display: none;">
                                @include('website._search')
                                    <!-- Logs Tale Here -->
                                    
                                        <table id="employee-grid" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Sl No</th>
                                            </tr>
                                            </thead>
                                        </table>
                        
                                    <!--./ Logs Table Here -->

                            </div>

                            <div class="clear"></div>
                            <br/><br/>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-12" style="padding-right:4px;padding-left: 0;">
                        <div class="r-add2 addeleme">
                            <div class="clearfix"></div>

                            <div class="imagerghts">
                                <img class="rghtimg" src="{{ asset('/frontend/images/side-banner.png') }}" style="z-index:-1;height:440px;">
                            </div>
                            <div class="cartsrghts" style="display:none;z-index: -1 !important;">
                                <div id="searchformpacksdata" class="gift" style="margin-top: 35px; width: 16.5em; top: 80px;">
                                    <form class="form" autocomplete="off" id="search-form-side" action="#" method="post">
                                        <div id="userDetails" class="cb packs">
                                            <div class="tab-search">
                                                <h3 style="font-size: 16px;line-height: 1.3;color: #fff;margin-top: 5px;margin-bottom: 20px;border-bottom: 2px solid;">Your Cart</h3>
                                                
                                                    <!-- cart added -->
                                                    <div class="cart-body page_cart_items" id="page_cart_items">

                                                        @php
                                                            $counter = 0;
                                                            $cart_total = 0;
                                                        @endphp    
                                                        @foreach ($cart_items as $item)

                                                            @foreach ($item as $key => $value)                                            

                                                                {{-- <div class="col-md-12 minitotal-prod manual_added_cart">
                                                                    <div class="col-md-5 minicart-img">
                                                                        <p>{{ $value['theme_name'] }}</p>
                                                                    </div>
                                                                    <div class="col-md-5 minicart-data">
                                                                        <p class="mc-name">{{ $value['particular'] }}</p>

                                                                        @if ($value['trans_type'] == 'package')
                                                                            <p class="ng-binding">{{ $value['partidays'] }} X  ₹ {{ $value['item_price'] }}</p>
                                                                        @else
                                                                            <p class="ng-binding">{{ $value['partidays'] }} X  ₹ {{ $value['net_price'] }}</p>
                                                                        @endif
                                                                        
                                                                    </div>
                                                                    <div class="col-md-1 minicart-close">
                                                                        <p class="endclose" data-remove="remove-generalcart-{{ $value['trans_type'] }}-{{$key}}" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p>
                                                                    </div>
                                                                </div> --}}
                                                                @php
                                                                    $cart_total = $cart_total + $value['net_price'];
                                                                @endphp
                                                                <div class="col-md-12 minitotal-prod manual_added_cart">                                                    
                                                                    <div class="col-md-7 minicart-data">
                                                                       <p class="mc-name">{{ ++$counter }}) {{ $value['theme_name'] }}</p>
                                                                       <p class="ng-binding">1 day x per unit X  ₹ {{ $value['item_price'] }}</p>
                                                                    </div>
                                                                    <div class="col-md-3 minicart-img">
                                                                       <p class="amountvalue"> ₹ {{ $value['net_price'] }}</p>                                                    
                                                                    </div>
                                                                    <div class="col-md-1 minicart-close">                                                    
                                                                        <p class="endclose" data-remove="remove-generalcart-{{ $value['trans_type'] }}-{{$key}}" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p>
                                                                    </div>                                                   
                                                                 </div>

                                                            @endforeach

                                                        @endforeach

                                                    </div>
                                                    <!-- cart added ends-->
                                                    <!--  starts total amount -->
                                                    <hr class="manualhr">
                                                    <div class="col-md-12 totalvals"> 
                                                        <div class="col-md-6">
                                                        <p class="sino">Total Amount</p>
                                                        </div>
                                                        
                                                        <div class="col-md-6 minicart-data">
                                                        <p class="totalamount"> ₹ {{ $cart_total }}</p>
                                                        </div>
                                                    </div>
                                                    <!-- ends total amount -->

                                                <div class="form-group">
                                                    <div class="help-block error" id="QuoteReq_quote_req_cityy_em_" style="display:none"></div>
                                                </div>
                                                <div class="form-group " style="">
                                                    <div class="form-mob">
                                                        <button class="mt-15 proceed-checkout-btn" type="button" id="callBtnform" data-typecart="cart_general" onclick="setbookingtype(this)">Proceed to Checkout</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div id="searchformequipsdata" class="gift" style="margin-top: 35px; width: 17%; top: 70px; position: fixed;display: none;">
                                    <form class="form" autocomplete="off" id="" action="#" method="post">
                                        <div id="userDetails" class="cb packs">
                                            <div class="tab-search">
                                                <h3 style="font-size: 16px;line-height: 1.3;color: #fff;margin-top: 5px;margin-bottom: 20px;border-bottom: 2px solid;">Your Cart</h3>

                                                <!-- cart added -->
                                                <div class="cart-body page_cart_items" id="page_cart_items">

                                                    @php
                                                        $counter = 0;
                                                        $cart_total = 0;
                                                    @endphp   
                                                    @foreach ($cart_items as $item)

                                                        @foreach ($item as $key => $value)                                            

                                                            {{-- <div class="col-md-12 minitotal-prod manual_added_cart">
                                                                <div class="col-md-5 minicart-img">
                                                                    <p>{{ $value['theme_name'] }}</p>
                                                                </div>
                                                                <div class="col-md-5 minicart-data">
                                                                    <p class="mc-name">{{ $value['particular'] }}</p>
                                                                    
                                                                    @if ($value['trans_type'] == 'package')
                                                                        <p class="ng-binding">{{ $value['partidays'] }} X  ₹ {{ $value['item_price'] }}</p>
                                                                    @else
                                                                        <p class="ng-binding">{{ $value['partidays'] }} X  ₹ {{ $value['net_price'] }}</p>
                                                                    @endif

                                                                </div>
                                                                <div class="col-md-1 minicart-close">
                                                                    <p class="endclose" data-remove="remove-generalcart-{{ $value['trans_type'] }}-{{$key}}" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p>
                                                                </div>
                                                            </div> --}}

                                                            @php
                                                                $cart_total = $cart_total + $value['net_price'];
                                                            @endphp

                                                            <div class="col-md-12 minitotal-prod manual_added_cart">                                                    
                                                                <div class="col-md-7 minicart-data">
                                                                   <p class="mc-name">{{ ++$counter }}) {{ $value['theme_name'] }}</p>
                                                                   <p class="ng-binding">1 day x per unit X  ₹ {{ $value['item_price'] }}</p>
                                                                </div>
                                                                <div class="col-md-3 minicart-img">
                                                                   <p class="amountvalue"> ₹ {{ $value['net_price'] }}</p>                                                    
                                                                </div>
                                                                <div class="col-md-1 minicart-close">                                                    
                                                                    <p class="endclose" data-remove="remove-generalcart-{{ $value['trans_type'] }}-{{$key}}" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p>
                                                                </div>                                                   
                                                             </div>

                                                        @endforeach

                                                    @endforeach

                                                </div>
                                                <!-- cart added ends-->
                                                <!--  starts total amount -->
                                                <hr class="manualhr">
                                                <div class="col-md-12 totalvals"> 
                                                    <div class="col-md-6">
                                                    <p class="sino">Total Amount</p>
                                                    </div>
                                                    
                                                    <div class="col-md-6 minicart-data">
                                                    <p class="totalamount"> ₹ {{ $cart_total }}</p>
                                                    </div>
                                                </div>
                                                <!-- ends total amount -->

                                                <div class="form-group">
                                                    <div class="help-block error" id="QuoteReq_quote_req_cityy_em_" style="display:none"></div>
                                                </div>
                                                <div class="form-group " style="">
                                                    <div class="form-mob">
                                                        <button class="mt-15 proceed-checkout-btn" type="button" data-typecart="cart_general" onclick="setbookingtype(this)">Proceed to Checkout</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="app-vendors-directory-filters" class="directory-structure-aside aside">
                <div class="left-panel-searchs">
                    <div class="left-panel">
                        <h2 class="left-head leftFitler" style="cursor: pointer; margin-top: -15px; margin-bottom: 0px;">
                            <span class="c-filter" id="clrFilter" rel="nofollow" data-url="/search" href="javascript:void(0);"style="margin-top: -30px;">Clear <i class="fa fa-filter"></i></span>
                            <span class="exp-f"><i class="fa fa-plus"></i></span>
                        </h2>
                        <div class="panel-group expand-fd" id="accordion" style="margin-top: 12px;">
                            <div class="panel panel-default ">
                                <div class="panel-heading panel-head-1">
                                    <h4 class="panel-title panel_item">
                                        <a role="button"  aria-controls="">Select your Event Packages</a>
                                    </h4>
                                </div>
                                <div class="panel-heading panel-head-2">
                                    <input id="item_event_packages" class="searchinput" type="text" placeholder="Search By Event Packages">
                                </div>
                                <div id="style-3" class="panel-collapse collapse in default_height_package scrollbar" style="display:block;">
                                    <div id="" class="panel-body ">
                                        <ul class="venueTypes item_venue_type " id="listvalss">
                                            @foreach ($packages as $package)

                                                <li class="facet__item">
                                                    <input style="display: none;" rel="nofollow" onclick="changepackage(this)" class="leftchkliLoc1 common-sprite check packagelist" id="{{ $package->id }}" type="radio" name="radio" value="{{ $package->id }}">
                                                    <label style="margin-left: 0px!important" class=""  for="{{ $package->id }}">{{ $package->package_name }}</label>
                                                </li>

                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- starts 2nd for equip -->

                    <div class="left-panel eventtypessequips" style="display: none;">
                        <div class="panel-group expand-fd" id="accordion" style="margin-top: 12px;">
                            <div class="panel panel-default ">
                                <div class="panel-heading panel-head-1">
                                    <h4 class="panel-title panel_item">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href=" " aria-expanded="true" aria-controls="collapseOne">Select your Event Equipments</a>
                                    </h4>
                                </div>
                                <div class="panel-heading panel-head-2">
                                    <input id="item_event_equipments" class="searchinput" type="text" placeholder="Search By Event Equipments">
                                </div>
                                <div id="style-3" class="panel-collapse collapse in default_height scrollbar" style="display:block;">
                                    <div id="" class="panel-body ">
                                        <ul class="venueTypes item_venue_type" id="liztz">

                                            @foreach ($equipments as $equipment)

                                                <li class="facet__item">
                                                    <input style="display: none;" rel="nofollow" onclick="" class="leftchkliLoc1 common-sprite check equipmentlist" id="{{ $equipment->id }}" type="checkbox" name="checkbox[]" value="{{ $equipment->id }}">
                                                    <label style="margin-left: 0px!important" class="" onclick="" for="{{ $equipment->id }}"> {{ $equipment->theme_name }}</label>
                                                </li>

                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ends 2nd for equip -->
                    <div class="r-add"></div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- /.Main content ends here -->

    <!-- home footer links -->
    @include('layouts.frontend.partial.footer_common')
    <!-- /.home footer Ends-->

@endsection

@push('js')

    <script src="{{ asset('/frontend/js/custom.js') }}"></script>
    <script src="{{ asset('/frontend/js/cart.js') }}"></script>
    <script>
        $(document).ready(function() {
           //loadTables();

           @if ($package_theme_id != '' && $package_detail_id != '')
           var package_theme_id = "{{ $package_theme_id }}";
           var package_detail_id = "{{ $package_detail_id }}";
           var package_partidays = "{{ $package_partidays }}";
            @else
            var package_theme_id = '';
            var package_detail_id = '';
            var package_partidays = '';
            @endif

            @if ($package_event_date != '')
            var package_event_date = "{{ $package_event_date }}";
            @else
            var package_event_date = '';
            @endif

            if(package_theme_id == '' || package_theme_id == undefined || isNaN(package_theme_id))
            {
                $('#listvalss li:first .packagelist').prop("checked", true);
            }
            else
            {
                $('#no_of_days option:selected').removeAttr('selected');
                $('#no_of_guest option:selected').removeAttr('selected');

                $('#listvalss #'+package_theme_id).prop("checked", true);
                var item = $('#listvalss #'+package_theme_id);
                changepackage(item);

                if(package_event_date != '')
                {
                    $('#demos').val(package_event_date);
                }

                $('#demos').val(package_event_date);
                $("#no_of_days option[value="+package_partidays+"]").attr("selected",true).change();

                setTimeout(function(){ $("#no_of_guest option[value="+package_detail_id+"]").attr("selected",true).change(); }, 1000);
                setTimeout(function(){ changepackagedetails(); }, 1000);
            }
            
            //$('#liztz li:first .equipmentlist').prop("checked", true);
            //dataTable.search(searchterm).draw();
            //fillpackagedetails();
            loadTables();

            var min_package_amount = moneyFormatIndia($('.min_package_amount').text());
            $('.min_package_amount').text(min_package_amount);
        });
  
        $(document).on('change', "input[class*='equipmentlist']", function()
        {   
           $("#employee-grid").dataTable().fnDestroy();
           //dataTable.search(searchterm).draw();
           loadTables();
        });

        $(document).on('click', "input[class^='packagelist']", function()
        {   
            /* fillpackagedetails();//not in use */
        });
  
        function loadTables() 
        {
           var filter_id = [];
           var cityid = 1; 
           var searchtext = $('#myInputTextFields').val(); 
  
           $('.equipmentlist:checked').each(function(){
              filter_id.push($(this).val());
           });

           if(searchtext != '')
            {
               filter_id = [];
            }
  
            //console.log(filter_id);
  
           dataTable = $('#employee-grid').DataTable({
                  "processing": true,
                  "serverSide": true,
                  "info": false,
                  "bLengthChange": false,
                  "pageLength": 50,
                  "pagingType": "full",
                  "deferRender":true,
                 "initComplete": function() 
                 {
                    //$('.gallery a').lightbox();
                 },
                 "language": 
                 {
                    "processing": " ",
                    search: '', searchPlaceholder: "Report title, keywords or company name <i class='la la-search'></i>",
                    "paginate": 
                    {
                        "previous": "« Previous",
                        "next": "Next »"
                    }
                 },
                 "drawCallback": function( settings ) {
                    showfooter();
                },
                 "ajax":
                 {
                    url: "{{ route('website.packages') }}",
                    "data": function ( d ) 
                    {
                          d.filter_id = filter_id;
                          d.cityid = cityid;
                          d.searchtext = searchtext;
                          //d.cityid = $('#cityid').val();
                          // etc
                    },
                    type: "post",  // method  , by default get
                    error: function()
                    {  // error handling
                       $(".employee-grid-error").html("");
                       $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="1">No data found in the server</th></tr></tbody>');
                       $("#employee-grid_processing").css("display","none");
                    }
                 },
                 columns:
                 [
                    {
                       data: 'action',
                       name: 'action',
                    },
                 ]
           });
        }

        general_cart_ui();
        function showfooter()
        {
        console.log('Data tables has been redrawn');
        }
        </script>

@endpush

@extends('layouts.mobile.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
<link href="{{ asset('/frontend/mobile/css/cart.css') }}" rel="stylesheet">
<style>
    table#employee-grid thead, #employee-grid_filter label::before, #employee-grid_filter 
    {
       display: none;
    }
    table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd 
    {
       background-color: transparent !important;
    }
    table.dataTable.display tbody tr.even>.sorting_1, table.dataTable.order-column.stripe tbody tr.even>.sorting_1,
    table.dataTable.display tbody tr.odd>.sorting_1, table.dataTable.order-column.stripe tbody tr.odd>.sorting_1
    {
        background-color: transparent !important;
        padding: 0 !important;
    }
    #packages_include_div
    {
        display:none;
    }
</style>
@endpush

@section('content')

<div class="sidenav-black-overlay"></div>

<!-- Side Nav Wrapper-->

<div class="suha-sidenav-wrapper" id="sidenavWrapper">
    <!-- Sidenav Profile-->
    <div class="left-panel eventtypess" style="">
        <div class="bangalore">
            <h2 class="left-head leftFitler" style="cursor: pointer; margin-top: -4px; margin-bottom: 35px;"> 
                <span class="c-filter" id="clrFilter" rel="nofollow" data-url="/search" href="javascript:void(0);">Clear <i class="fa fa-filter"></i></span>
            </h2>
        </div>
        <div class="panel-group" id="accordion" style="margin-top: 12px;">
            <div class="panel panel-default ">
                <div class="panel-heading panel-head-1">
                    <h4 class="panel-title panel_item">
                        <a role="button" aria-controls="">Select your Event Equipments</a>
                    </h4>
                </div>
                <div class="panel-heading panel-head-2">
                    <input id="item_event_equipmentss" class="searchinput" type="text" placeholder="Search By Event Equipments">
                </div>
                <div id="style-3" class="panel-collapse collapse in default_height scrollbar" style="display:block;">
                    <div id="" class="panel-body ">
                        <ul class="venueTypes item_venue_type " id="listvalss">
                            
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
    <!-- Go Back Button-->
    <div class="go-home-btn" id="goHomeBtn"><i class="lni-arrow-left"></i></div>
 </div>

<!-- /. Ends Side Nav Wrapper-->

<!-- Page Wrapper Comes Here--->
<div class="">

    <!-- starts page content -->
    <div class="section equips">
        <div class="container">
            <div class="row">
                <div class="col s12 found-status">
                    <h1 class="title" id="package_name">{!! $package_first->package_name !!}</h1>
                </div>
            </div>
        </div>
        <!-- start packages form --> 
        <div class="row imgalins">
            <div class="formalign">
                <div class="form-holder">
                    <fieldset>
                        <legend>Event Date</legend>
                        <i class="fa fa-calendar icon mycustomicons" onclick="clickdateselection()"></i>
                    <input class="form-control input-field event_date datepicker" type="text" id="demos" placeholder="dd/mm/yy" onchange="specialEvent();" name="event_date" autocomplete="off" required="" value="{{  $package_event_date  }}">
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
           <div class="arrowbtn">
                <a href="{{ route('website.packageslist') }}">
                    <div class="s_arrow s_arrow_rotate">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </div>
                </a>
           </div>
        </div>
        <div class="row text-center threebtns" style="margin-top: 2%; display: block;">
           <div class="col-md-4 equipspart">
              <div class="btntxt equips addmoreequip"  id="add_more_equipments_mobile">
                    <p class="span">Add More Equipments</p>
              </div>
           </div>
           <div class="col-md-4 bookingpart">
                <div class="btntxt booknows package_booking_type_mobile" id="package_book_now_mobile">
                    <p class="span">Book Now</p>
                </div>
           </div>
        </div>
        <div style="clear: both"></div>
        <!-- end packages form -->

        <div class="equipcontents">
           <div class="container">
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
        </div>
     </div>    

    <!-- /.ends page content -->

        <div style="clear: both;"></div>

</div>    
<!-- /. Ends Page Wrapper-->

@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/counter.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
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
            //changepackage(item);

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
        $('.min_package_amount').text(min_package_amount)
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

       $('.leftchkliLoc1:checked').each(function(){
          filter_id.push($(this).val());
       });

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
                url:"/packages/"+{{ $packages->id }},
                "data": function ( d ) 
                {
                      d.filter_id = filter_id;
                      d.cityid = cityid;
                      //d.cityid = $('#cityid').val();
                      // etc
                },
                type: "get",  // method  , by default get
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
    general_cart_ui_mobile();
    function showfooter()
    {
    console.log('Data tables has been redrawn');
    }
</script>

@endpush

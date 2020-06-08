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
    .minicart-close {
       margin-top: 1%;
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
                            
                            @foreach ($theme_names as $theme)

                                <li class="facet__item">
                                    <input style="display: none;" rel="nofollow" onclick="" class="leftchkliLoc1 common-sprite check" id="{{ $theme->id }}" type="checkbox" name="checkbox[]" value="{{ $theme->id }}">
                                    <label style="margin-left: 0px!important" class=" " onclick=" " for="{{ $theme->id }}"> {{ $theme->theme_name }}</label>
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
        <div class="section equips otherstatus">
            <div class="container">
                <div class="row">
                   <div style="width:100%;">
                       <div class="found-status">
                           <h1>{{ $page_items->heading }}</h1>
                       </div>
                    </div>
                    <div class="col s12 found-status"> 
                        <h2></h2>
                        <p class="collapse22">
                            {{ $page_items->content }}
                        </p> 
                        <p class="collapse_add_content">
                            {{ $page_items->subheading }}
                       </p> 
                    </div>
                </div>

                @include('website._search') 

                <!-- Logs Tale Here -->

                    <!-- for loader -->
                        <div style="height:20px;">
                            <div class="add_content_loader"></div>
                        </div>
                        <div style="clear:both;"></div>
                    <!-- for loader ends -->

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
        <!-- /.ends page content -->
        <div style="clear: both;"></div>

        <!-- Offers that you cant resist-->
        @include('website._offers')
        <!-- /.Offers that you cant resist Ends-->

</div>    
<!-- /. Ends Page Wrapper-->

@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/counter.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
<script src="{{ asset('/frontend/js/cart.js') }}"></script>
<script>

    $('.datastest').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        autoplayTimeout: 15000,
        autoplayHoverPause: true,
        items: 1,
        dots: true
    });
    $('.flash-sale-card').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        items: 1
    });

    $(document).ready(function() {
       //loadTables();

       //$('.panel-body ul.item_venue_type li:first .leftchkliLoc1').prop("checked", true);
       //dataTable.search(searchterm).draw();
       loadTables();
    });

    $(document).on('change', "input[class^='leftchkliLoc1']", function()
    {   
       $("#employee-grid").dataTable().fnDestroy();
       //dataTable.search(searchterm).draw();
       loadTables();
    });

    function loadTables() 
    {
        var filter_id = [];
        var cityid = 1; 
        var searchtext = $('#myInputTextFields').val(); 
       
        $('.leftchkliLoc1:checked').each(function(){
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
                url: "{{ route('website.equipments') }}",
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

    general_cart_ui_mobile();
    function showfooter()
    {
    console.log('Data tables has been redrawn');
    }
</script>

@endpush

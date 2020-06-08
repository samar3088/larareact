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
            .minicart-close {
               margin-top: 1%;
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

<div class="mt-125">
    <div class="m-hide row full-width" id="" align="center">
       <div class="col-md-2">
       </div>
       <div class="col-md-8" style="margin-left:5%;">
          <img name="0" src="{{ Storage::url($page_items->file) }}" alt="Plan Your Event">
       </div>
       <div class="col-md-2">
       </div>
    </div>
    <div class="wrapper">
       <div style="margin-top: 10px;"></div>
       <div class="directory-structure clearfix">
          <div id="app-vendors-directory-result-list" class="directory-structure-content">
             <div class="right-panel-search">
                <div class="col-lg-10 col-md-10 col-sm-12" style="margin-bottom: 550px;">
                   <div id="right_searchresult">
                      <div class="found-status" style="margin-bottom:15px;">
                         <h1>{{ $page_items->heading }}</h1>
                      </div>
                      <div class="found-status" style="margin-bottom:25px;font-size: 12px">
                         <div id="cityDIVS2">
                            <div class="collapseWrapper22" style="height: 52px;">
                               <p class="collapse22">
                                    {{ $page_items->content }}
                               </p>
                            </div>
                         </div>
                      </div>
                      <!-- for search -->  
                      @include('website._search')
                      <div class="sort-by" itemscope="" itemtype="">
                         
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
                      <!-- sort by ends -->
                      <div class="clear"></div>
                      <br/><br/>
                   </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12" style="padding-right:4px;padding-left: 0;">
                   <div class="r-add2">
                      <div class="clearfix"></div>
                      <div id="searchform" class="gift" style="margin-top: 35px; width: 17%; position: fixed; top: 100px;">
                         <form class="form" autocomplete="off" id="search-form-side" action="#" method="post">
                            <div class="alert alert-block alert-danger" id="search-form-side_es_" style="display: none; opacity: 1;">
                            </div>
                            <div id="userDetails" class="cb">
                               <div class="tab-search">
                                  <h3 style="font-size: 16px;line-height: 1.3;color: #fff;margin-top: 5px;margin-bottom: 20px;border-bottom: 2px solid;">
                                     Your Cart
                                  </h3>
                                  <!-- event date -->
                                  <div class="form-holder date_cart_ap">
                                     <fieldset>
                                        <legend>Event Date</legend>
                                        <i class="fa fa-calendar icon mycustomicons"></i>
                                        <input class="form-control input-field event_date datepicker" type="text" id="demos" placeholder="dd/mm/yy" onchange="specialEvent();" name="event_date" autocomplete="off" required="" readonly>
                                     </fieldset>
                                     <p class="evedateserror"></p>
                                  </div>
                                  <!-- event date ends -->
                                  <!-- cart added -->
                                  <div class="cart-body page_cart_items" id="page_cart_items">
                                    @php
                                          $counter = 0;
                                    @endphp     
                                    @foreach ($cart_items as $item)

                                          @foreach ($item as $key => $value)        

                                                <div class="col-md-12 minitotal-prod manual_added_cart wdngscart">
                                                   <div class="col-md-12 minicart-data">
                                                      <p class="mc-name mc-names">{{ ++$counter }}) {{ $value['particular'] }}</p>
                                                   </div>
                                                   <div class="col-md-12">
                                                      <div class="col-md-3 minicart-img">
                                                         @if ($value['item_qty'] > 0)
                                                            <p class="chosevalue"> Qty: {{ $value['item_qty'] }}</p>
                                                         @else
                                                            <p class="chosevalue"> Sqft: {{ $value['item_width'] }} x {{ $value['item_height'] }} </p>
                                                         @endif                                                      
                                                      </div>
                                                      <div class="col-md-3 minicart-img">
                                                         @if ($value['partidays'] > 0)
                                                            <p class="chosevalue"> Days: {{ $value['partidays'] }}</p>
                                                         @endif
                                                      </div>
                                                      <div class="col-md-1 minicart-close">
                                                         <p class="endclose" data-remove="remove-weddingcart-items-{{$key}}" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p>
                                                      </div>
                                                   </div>
                                                </div>

                                          @endforeach

                                    @endforeach                                    
                                     
                                  </div>
                                  <!-- cart added ends-->
                                  <div class="form-group">
                                     <div class="help-block error" id="QuoteReq_quote_req_cityy_em_" style="display:none"></div>
                                  </div>
                                  <div class="form-group " style="" >
                                     <div class="form-mob">
                                        <button class="mt-15" type="button" id="callBtnform" data-typecart="cart_wedding" onclick="setbookingtype(this)">Proceed to Checkout</button>
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
          <div id="app-vendors-directory-filters" class="directory-structure-aside aside">
             <div class="left-panel-search">
                <div class="left-panel datareprs">
                    <div class="bangalore">
                        @include('website._additional')
                        <h2 class="left-head leftFitler" style="cursor: pointer; margin-top: -44px; margin-bottom: 55px;">
                            <span class="c-filter" id="clrFilter" rel="nofollow">Clear <i class="fa fa-filter"></i></span>
                            <span class="exp-f"><i class="fa fa-plus"></i></span>
                        </h2>
                   </div>
                   <div class="panel-group expand-fd" id="accordion" style="margin-top: 12px;">
                      <div class="panel panel-default ">
                         <div class="panel-heading panel-head-1">
                            <h4 class="panel-title panel_item">
                               <a>Theme Name</a>
                            </h4>
                         </div>
                         <div class="panel-heading panel-head-2">
                            <!--<input id="item_venue_type" class="searchinput" type="text" placeholder="Search By Venue Type" onkeyup="myFunction()">-->
                            <input id="item_venue_type" class="searchinput" type="text" placeholder="Search By Venue Type">
                         </div>
                         <div id="style-3" class="panel-collapse collapse in default_height scrollbar" style="display:block;">
                            <div id="" class="panel-body ">
                               <ul class="venueTypes item_venue_type">

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
       var dataTable; var clickcounter = 0;  
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
                  url: "{{ route('website.weddings') }}",
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
      wedding_cart_ui();
      function showfooter()
      {
         console.log('Data tables has been redrawn');
      }
      </script>

@endpush

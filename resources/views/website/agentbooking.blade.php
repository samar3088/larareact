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
        <link href="{{ asset('/frontend/css/agentbooking.css') }}" rel="stylesheet">

@endpush

@section('content')

<!-- Main content comes here -->

<div class="mt-125">
    <div class="m-hide" id="vvv" align="center" style="margin-left: 5%;">
    </div>
    <div class="wrapper">
      <div style="margin-top: 10px;"></div>
      <div class="directory-structure clearfix">
         <div id="app-vendors-directory-result-list" class="">
            <div class="right-panel-search">
               <div class="col-lg-9 col-md-9 col-sm-12" style="margin-bottom: 350px;">
                  <div id="right_searchresult">
                     <div class="found-status" style="margin-bottom:15px;">
                        <h1> Tosshead Agent Booking Page </h1>
                     </div>
                     <div class="found-status" style="margin-bottom:25px;font-size: 12px">
                        <div id="cityDIVS2">
                           <div class="collapseWrapper22" style="height: 52px;">
                                <p class="collapse22">This is has been done to enble tosshead employees to enter any custom items.</p>
                                <hr>
                           </div>

                           <!-- Page Form Starts Here-->


                           <div class="col-md-12 col-xs-12  pull-left" style="">
                                <div class="col-md-4 col-xs-12  pull-left"></div>
                                <div class="col-md-4 col-xs-12  pull-left" style="margin-top:15px;margin-bottom: 20px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4 text-right" style="padding: 0;margin-top: 2%;font-weight: 600;">
                                                <p>Venue: </p>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" id="city" name="city" placeholder="Enter Venue" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12  pull-left"></div>
                            </div>

                            <!--Transportation Cost start-->
                            <div class="col-md-6 col-xs-6  pull-left" style="">
                                <div class="col-md-6  col-xs-12  pull-left blac_filed">Transportation</div>
                                <div class="col-md-6  col-xs-12  pull-right padd0">
                                    <input  class="form-control  style_box transport_cost numberOnly" id="transport_cost" placeholder="Transportation Cost" name="transport_cost" onkeyup="finalCalculation();" autocomplete="off" required>
                                </div>
                            </div>
                            <!--Transportation Cost End-->


                            <!--Crew Transportation Cost start-->
                            <div class="col-md-6 col-xs-12  pull-left" style="">
                                <div class="col-md-6  col-xs-12  pull-left blac_filed">Crew Transportation</div>
                                <div class="col-md-6  col-xs-12  pull-right padd0">
                                    <input  class="form-control style_box crew_cost numberOnly" id="crew_cost" placeholder="Crew Transportation Cost" name="crew_cost" onkeyup="finalCalculation();" autocomplete="off" required>
                                </div>
                            </div>
                            <!--Crew Transportation Cost End-->
            
                            <!--GST Block start-->
                                <div class="col-md-2 col-xs-12  pull-left" style="margin-top:15px;">
                                    <div class="col-md-12  col-xs-12  pull-right blac_filed"><input type="checkbox" value="yes" id="add_gst" name="add_gst" class="add_gst">&nbsp;&nbsp;&nbsp;Add GST</div>
                                </div>
                            <!--GST Block End-->
            
                            <!--Manual Discount Block start-->
                                <div class="col-md-3 col-xs-12  pull-left" style="margin-top:15px;">
                                    <div class="col-md-6  col-xs-12  pull-left blac_filed">Discount</div>
                                    <div class="col-md-6  col-xs-12  pull-right padd0">
                                        <input  class="form-control style_box manual_discount numberOnly" id="manual_discount" placeholder="Discount" name="manual_discount" onkeyup="finalCalculation();" autocomplete="off" required>
                                    </div>
                                </div>
                            <!--Manual Discount Block End-->

                            <!--Crew Transportation Cost start-->
                                <div class="col-md-5 col-xs-12  pull-left" style="margin-top:15px;">
                                    <div class="col-md-6  col-xs-12  pull-left blac_filed">Select Item Type</div>
                                    <div class="col-md-6  col-xs-12  pull-right padd0">
                                        <select id="test2" class="form-control style_box">
                                            <option value="">--- please select ---</option>
                                            <option value="Qty">Qty</option>
                                            <option value="Sq.ft">Sq.ft</option>
                                        </select>
                                    </div>
                                </div>
                            <!--Crew Transportation Cost End-->      
            
                            <!--Add Button start-->
                                <div class="col-md-2 col-xs-12  pull-left" style="margin-top:15px;">
                                    <div class="col-md-12  col-xs-12" style="padding: 0px;">
                                        <button class="btn btn-warning btn-lg btn-block db" onclick="addfields();" style="margin-top:10px;">Add Items</button>
                                    </div>    
                                </div>
                            <!--Add Button End--> 
        
                            <!--List of Custom items added comes-->
                                <div class="col-md-12 col-xs-12  pull-left" id="useradded" style="margin-top:15px;">
                                </div>
                            <!--List of Custom items added Ends-->     
            
                            <!--Add Notes start-->
                                <div class="col-md-12 col-xs-12 pull-left" style="margin-top:15px;">
                                    <textarea class="form-control style_box manual_notes_added" id="manual_notes_added" rows="10" cols="10" placeholder="Add Manual Notes to be added for the invoice and quotes. If left blank defalt notes will be visible. Any special chars are not allowed" name="manual_notes_added" autocomplete="off"></textarea>
                                </div>
                            <!--Add Notes End-->

                           <!-- /.Page Form Ends Here-->
                           
                        </div>
                     </div>
                     <div class="clear"></div>
                     <br/><br/>
                  </div>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-12" style="padding-right:4px;padding-left: 0;">
                  <div class="r-add2">
                     <div class="clearfix"></div>
                     <div id="searchform" class="gift" style="width: 25em; position: fixed; top: 100px;margin-top: 10px;">
                        <form class="form" autocomplete="off" id="search-form-side" action="" method="post">
                           <div id="userDetails" class="cb">
                              <div class="tab-search">
                                 <h3 style="font-size: 16px;line-height: 1.3;color: #fff;margin-top: 5px;margin-bottom: 20px;border-bottom: 2px solid;">Your Cart</h3>
                                 <!-- event date starts -->
                                 <div class="form-holder date_cart_ap">
                                    <fieldset>
                                       <legend>Event Date</legend>
                                       <i class="fa fa-calendar icon mycustomicons" onclick="clickdateselection()"></i>
                                       <input class="form-control input-field event_date datepicker" type="text" id="demos" placeholder="dd/mm/yy" onchange="specialEvent();" name="event_date" autocomplete="off" required="">
                                    </fieldset>
                                    <p class="evedateserror"></p>
                                 </div>
                                 <!-- event date ends -->
                                 <!-- cart added -->
                                 <div id="emptycart">You have not added any items to cart</div>

                                 <div class="cart-body mycart" id="mycart">                                 

                                 </div>
                                 <!-- cart added ends-->
                                 <!--  starts total amount -->
                                 <hr class="manualhr">
                                 <div class="col-md-12 totalvals"> 
                                    <div class="col-md-6">
                                       <p class="sino">Total Amount</p>
                                    </div>
                                    
                                    <div class="col-md-6 minicart-data">
                                       <p class="totalamount">₹ <span id="finaltotal"></span></p>
                                    </div>
                                </div>
                                <!-- ends total amount -->
                                 <div class="form-group">
                                    <div class="help-block error" id="QuoteReq_quote_req_cityy_em_" style="display:none"></div>
                                 </div>
                                 <div class="form-group " style="" >
                                    <div class="form-mob">
                                       {{-- <button class="mt-15" type="button" id="callBtnform" data-typecart="cart_general" onclick="setbookingtype(this)">Proceed to Checkout</button> --}}
                                       {{-- <button class="mt-15" data-toggle="modal" onclick="return send_quotes();" style="margin-top:10px">Send Quote</button> --}}
                                       <a class="mt-15 btn btn-primary" data-toggle="modal"  onclick="send_quotes();"  style="margin-top:10px">Send Quote</a>
                                    </div>
                                 </div>
                              </div>
                           </div>

                            <input type="hidden" name="itemCount" value="0" id="itemCount">
                            <input type="hidden" name="total_price" value="" id="total_price">
                            <input class="hidden discount_percent" value="" name="discount_percent" id="discount_percent" readonly>
                            <input class="hidden discount_amnt" value="" name="discount_amnt" id="discount_amnt" readonly>
                            <input type="hidden" name="cityid" value="1" id="cityid">

                            <hr>

                            <div class="form-group mx-sm-3 mb-2">
                                <label for="ordertosave" class="sr-only">Mobile No</label>
                                <input type="text" class="form-control" id="ordertosave" placeholder="Order to Save" min="10" maxlength="10">
                                <span id="ordertosave_err"></span>
                            </div>
                            <button type="button" name="nametosave_btn" id="nametosave_btn" class="btn btn-primary mb-2" onclick="savetempcart()">Save Cart</button>                           

                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- modal content kept here -->

<!-- /.modal content ends here -->

<!-- /.Main content ends here -->

    <!-- home footer links -->
    @include('layouts.frontend.partial.footer_common')
    <!-- /.home footer Ends-->

    @if (Request::path() == 'agentbooking')
        @include('layouts.frontend.partial.modal_agent')
    @endif

@endsection

@push('js')

        <script src="{{ asset('/frontend/js/custom.js') }}"></script>
        <script src="{{ asset('/frontend/js/cart.js') }}"></script>
        <script src="{{ asset('/frontend/js/agentbooking.js') }}"></script>
        <script>
                $( "#mycart" ).sortable();

                $(function () 
                {
                    $("#useradded").accordion({ header: "> div > div.content" }).sortable({ axis: "y",handle: "div.content",update: function (event, ui) { getValues(); } }); 
                });

                function getValues() 
                { 
                    var values = []; 
                    
                    $('#useradded > .group').each(function (index) { values.push($(this).data("itemsort")); });
                    console.log(values);
                    
                    var el = $('#mycart');
                    var map = {};
                    
                    $('#mycart div[class*="manuallyadded"]').each(function() { 
                        var el = $(this);
                        map[el.data('itemsort')] = el;
                        
                        //console.log(map[el.attr('class')]);
                    });
                    
                    var values = values.reverse(); 
                    
                    for (var i = 0, l = values.length; i < l; i ++) {
                        
                        //console.log(map[values[i]]);
                        if (map[values[i]]) {
                            el.prepend(map[values[i]]);
                        }
                    }
                }
         </script>

@endpush

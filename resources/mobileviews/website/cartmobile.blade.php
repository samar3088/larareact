@extends('layouts.mobile.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
<link href="{{ asset('/frontend/mobile/css/date.css') }}" rel="stylesheet">
<style>

</style>
@endpush

@section('content')

<!-- Page Wrapper Comes Here--->
<div class="">
        <!-- starts page content -->
        <div class="section carts">
            <div class="">
               <!-- item 1 -->
                <div class="container text-center">
                    <div class="row mt-20 mb-0">
                        <div class="col s12 p-0">
                            <!-- start cart page -->
                            <div id="searchform" class="gift" style=" top: 10px;">
                                <form class="form" autocomplete="off" id="search-form-side" action="" method="post">
                                    <div id="userDetails" class="cb">
                                        <div class="tab-search">
                                            <h3 style="font-size: 16px;line-height: 1.3;color: #fff;margin-top: 5px;margin-bottom: 20px;border-bottom: 2px solid;">Your Cart</h3>
                                            <!-- event date starts -->
                                            <div class="form-holder date_cart_ap">
                                                <fieldset>
                                                    <legend>Event Date</legend>
                                                    <i class="fa fa-calendar icon mycustomicons"></i>
                                                    <input class="form-control input-field event_date datepicker" type="text" id="demos" placeholder="dd/mm/yy" onchange="specialEvent();" name="event_date" autocomplete="off" required="" value="{{ $event_date }}">
                                                </fieldset>
                                                <p class="evedateserror"></p>
                                            </div>
                                            <!-- event date ends -->
                                            <!-- cart items listed -->
                                            <div class="cart-body page_cart_items" id="page_cart_items">
                                            </div>
                                            <!-- ends cart items listed -->
                                            <!-- starts total amount -->
                                            <hr class="manualhr">
                                            <div class="col s12 totalvals">
                                                <div class="col s6">
                                                    <p class="sino"> Total Amount </p>
                                                </div>
                                                <div class="col s6 minicart-data">
                                                    <p class="totalamount"> ₹ 0.00</p>
                                                </div>
                                            </div>
                                            <!-- ends total amount -->
                                            <div class="form-group">
                                                <div class="help-block error" id="QuoteReq_quote_req_cityy_em_" style="display:none"></div>
                                            </div>
                                            <div class="form-group " style="">
                                                <div class="form-mob">
                                                    <a href="#">
                                                        @if (Request::is('mobilecart/wedding'))
                                                            <button class="checkoutproceed mt-15" type="button" id="callBtnform" data-typecart="cart_wedding" onclick="setbookingtype(this)">Proceed to Checkout</button>
                                                        @elseif (Request::is('mobilecart/general'))
                                                            <button class="checkoutproceed mt-15" type="button" id="callBtnform" data-typecart="cart_general" onclick="setbookingtype(this)">Proceed to Checkout</button>
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- ends cart page -->
                        </div>
                    </div>
                </div>
                <!-- item 1 ends -->
            </div>
        </div>
        <!-- /.ends page content -->
        <div style="clear: both;"></div>


</div>    
<!-- /. Ends Page Wrapper-->

@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/date.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
<script src="{{ asset('/frontend/js/cart.js') }}"></script>

    @if (Request::is('mobilecart/wedding'))
        <script>wedding_cart_ui_mobile();</script>
    @elseif (Request::is('mobilecart/general'))
        <script>general_cart_ui_mobile();</script>
    @endif

@endpush

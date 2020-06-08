@extends('layouts.mobile.app')
@section('title','Tosshead Checkout Page')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
<link href="{{ asset('/frontend/mobile/css/checkout.css') }}" rel="stylesheet">
<style>
    .page-content-wrapper 
    {
        margin-bottom:0px;
    }
</style>
@endpush

@section('content')

<!-- Page Wrapper Comes Here--->

        <!-- starts page content -->
        <div class="section carts">
            <div class="">
               <!-- starts cart form page -->
                <div class="">
                    <div class="containersform">
                        <div class="leftbox">
                            <nav class="navside">
                                <a id="profile-s" class="active"><i class="fa fa-user"></i></a>
                                <a id="email-s"><i class="fa fa-envelope-o"></i></a>
                                <a id="phone-s"><i class="fa fa-phone"></i></a>
                                <a id="instruction-s"><i class="fa fa-tasks"></i></a>
                                <a id="otp-s" style="display: none;"><i class="fa fa-credit-card"></i></a>
                            </nav>
                        </div>
                        <div class="rightbox">
                            <div class="profile">
                                <div class="personaltext">
                                    <form method="post" id="single_checkout_form" class="" enctype="multipart/form-data">
                                        <h1 class="titletext"> Input Your Details </h1>                                
                                        <h2>Name to be mentioned on Quotation / Invoice</h2>
                                        <input type="text" name="cus_name" id="cus_name" class="required form-control" style="" required="required">
                                        <h2>Email ID</h2>
                                        <input type="email" name="cus_email" id="cus_email" placeholder="Eg: xxxx@xxxx.com" class="required form-control" style="" required="required">
                                        <h2>Contact Number</h2>
                                        <input type="number" name="cus_mobile" id="cus_mobile" placeholder="91xxxxxxxxxx" class="required form-control" maxlength="10" style="" required="required" onkeyup="changeToUpperCases(this);" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57|| event.key === 'Backspace'" pattern="\d{10}" min="0" max="9999999999" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                        
                                        <h2>Event Date</h2>
                                        <input class="form-control input-field event_date datepicker" type="text" id="demos" placeholder="dd/mm/yy" onchange="specialEvent();" name="event_date" autocomplete="off" required="" value="{{ $event_date }}">
        
                                        {{-- @if ($booking_type == 'cart_single')
                                            @guest
                                                @if ($trans_type != 'wedding')
                                                    <h2>Enter Discount Coupon</h2>
                                                    <input type="text" name="promocode" id="promocode" placeholder="Enter discount coupon" class="required form-control" style="" required="required" value="{{ $promocode }}">
                                                    <div><span class="btn btn-primary" id="apply_coupon" onclick="applycoupon()">Apply Coupon</span><span id="apply_coupon_result"></span></div><br/>
                                                @endif
                                                <br/>
                                            @endguest
                                        @elseif ($booking_type == 'cart_general')
                                            <h2>Enter Discount Coupon</h2>
                                            <input type="text" name="promocode" id="promocode" placeholder="Enter discount coupon" class="required form-control" style="" required="required" value="{{ $promocode }}">
                                            <div><span class="btn btn-primary" id="apply_coupon" onclick="applycoupon()">Apply Coupon</span><span id="apply_coupon_result"></span></div><br/>
                                        @else
                                        @endif --}}
                                        
                                        <h2>Special Instructions (If any)</h2>
                                        <textarea type="text" name="cus_description" id="cus_description" class="required form-control" style="" placeholder=""></textarea>
                                        
                                        <div class="mark_terms">
                                            <input type="checkbox" id="terms_and_conditions" style="display:inline" class="terms">
                                            <a class="term" data-toggle="modal" data-target="#exampleModalScrollable" style="padding:5px;color:#000 !important;"> Terms and Conditions</a>
                                         </div>
                                         
                                        <div class="row">
                                            <div class="col-md-2 text-center"></div>
                                            <div class="col-md-6 btnstop">
                                                <button id="btnSendotp" class="btn btn-primary" value="Proceed to Checkout" name="btnSendotp" style="margin-bottom: 1em;">Get OTP</button>
                                            </div>
                                        </div> 
                                    </form>
                                </div>
                                <div class="otptext" style="display: none;">
                                    <h1 class="titletext">Verify with  OTP</h1>
                                    <h2>We have sent a 4 digit OTP to your mobile number. Please enter the same to get Quotation/Invoice</h2>  
                                    <div class="row">
                                        <div class="col-md-2 text-center"></div>
                                        <div class="col-md-6 btnquote">
                                            <input class="required form-control" name="entered_otp" id="entered_otp" style="">
                                            <div class="col-md-12"> 
                                                <a class="resnet" id="resendotp" href="">Resend OTP</a>
                                            </div>
                                            
                                            @if ($booking_type == 'cart_single')
                                                <button id="btnSendquote" class="btn btn-primary btnSendquote" value="Proceed to Checkout" name="btnSendquote" style="margin-bottom: 1em;">Get Quotation</button>
                                            @else
                                                <button id="btnSendquoteCart" class="btn btn-primary btnSendquote" value="Proceed to Checkout" name="btnSendquoteCart" style="margin-bottom: 1em;">Get Quotation</button>
                                            @endif
                                        
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 errorshowingresult" style="padding-left: 0;">  
                                            <div class="" id="form_result1"></div>
                                    </div>
                                </div>
                                    <div class="col-md-12 errorshowingresult">  
                                            <div class="" id="form_result"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
               <!-- ends cart form page -->
            </div>
        </div>
        <!-- /. Ends starts page content -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="text-center scon_hd myheading modal-title">Terms 
                            <span class="evnt_clr"> &amp; Conditions</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>This Terms of Use agreement was last updated: October 12th 2018.</p>
                        <p>Tosshead Events India Pvt Ltd ("Tosshead"), primarily operates controls and manages the Services (as defined below) provided by it from its corporate office at Bangalore.</p>
                        <p><b>1. Acceptance of Terms</b></p>
                        <p>PLEASE READ THE TERMS OF USE THOROUGHLY AND CAREFULLY. The terms and conditions set forth below ("Terms of Use") and the Privacy Policy (as defined below) constitute a legally-binding agreement between Tosshead operating from its Bangalore Corporate Office. These Terms of Use contain provisions that define your limits, legal rights and obligations with respect to your use of and participation in (i) the Tosshead website, forums, various email functions and Internet links, and all content and Tosshead services available through the domain and sub-domains of Tosshead located at www.tosshead.com (collectively referred to herein as the "Website"), and (ii) the online transactions between those users of the Website who are offering services (each, a "Service Professional") and those users of the Website who are obtaining services (each, a "Service User") through the Website (such services, collectively, the "Services"). The Terms of Use described below incorporate the Privacy Policy and apply to all users of the Website, including users who are also contributors of video content, information, private and public messages, advertisements, and other materials or Services on the Website.</p>
                        <p>The Website is owned and operated by Tosshead Events India Pvt Ltd You acknowledge that the Website serves as a venue for the online distribution and publication of user submitted information between Tosshead and Service Users, and, by using, visiting, registering for, and/or otherwise participating in this Website, including the Services presented, promoted, and displayed on the Website, and by clicking on "I have read and agree to the terms of use," you hereby certify that: (1) you are either a Service Professional or a prospective Service User, (2) you have the authority to enter into these Terms of Use, (3) you authorize the transfer of payment for Services requested through the use of the Website, and (4) you agree to be bound by all terms and conditions of these Terms of Use and any other documents incorporated by reference herein. If you do not so agree to the foregoing, you should not click to affirm your acceptance thereof, in which case you are prohibited from accessing or using the Website. If you do not agree to any of the provisions set forth in the Terms of Use, kindly discontinue viewing or participating in this Website immediately.</p>
                        <p>YOU SPECIFICALLY AGREE THAT BY USING THE WEBSITE, YOU ARE AT LEAST 18 YEARS OF AGE AND YOU ARE COMPETENT UNDER LAW TO ENTER INTO A LEGALLY BINDING AND ENFORCEABLE CONTRACT.</p>
                        <p>All references to "you" or "your," as applicable, mean the person that accesses, uses, and/or participates in the Website in any manner. If you use the Website (as defined below) on behalf of a business, you represent and warrant that you have the authority to bind that business and your acceptance of the Terms of Use will be deemed an acceptance by that business and "you" and "your" herein shall refer to that business.</p>
                        <p><b>2. MODIFICATIONS TO TERMS OF USE AND/OR PRIVACY POLICY</b></p>
                        <p>Tosshead reserves the right, in its sole discretion, to change, modify, or otherwise amend the Terms of Use, and any other documents incorporated by reference herein for complying with legal and regulatory framework and for other legitimate business purposes, at any time, and Tosshead will post the amended Terms of Use at the domain of www.tosshead.com/terms. It is your responsibility to review the Terms of Use for any changes and you are encouraged to check the Terms of Use frequently. Your use of the Website following any amendment of the Terms of Use will signify your assent to and acceptance of any revised Terms of Use. If you do not agree to abide by these or any future Terms of Use, please do not use or access the Website</p>
                        <p><b>3. PRIVACY POLICY</b></p>
                        <p>The information you provide is very secure and will never be shared to third party for any reason.</p>
                        <p></p>
                        <p><b>4. LICENSE TO ACCESS</b></p>
                        <p>Tosshead hereby grants you a non-exclusive, revocable license to use the Website as set forth in the Terms of Use; provided, however, that (i) you will not copy, distribute, or make derivative works of the Website in any medium without Tosshead's prior written consent; (ii) you will not alter or modify any part of the Website other than as may be reasonably necessary to use the Website for its intended purposes; and (iii) you will otherwise act in accordance with the terms and conditions of the Terms of Use and in accordance with all applicable laws.</p>
                        <p><b>5. ELIGIBILITY CRITERIA</b></p>
                        <p>Use of the Website is available only to individuals who are at least 18 years old and can form legally binding contracts under applicable law. You represent, acknowledge and agree that you are at least 18 years of age, and that: (a) all registration information that you submit is truthful and accurate, (b) you will maintain the accuracy of such information, and (c) your use of the Website and Services offered through this Website do not violate any applicable law or regulation. Your Account (defined below) may be terminated without warning if we at our discretion, believe that you are under the age of 18 or that you are not complying with any applicable laws, rules or regulations.</p>
                        <p>You need not register with Tosshead to simply visit and view the Website, but to book the services you will have to provide the OTP. To receive OTP, you must submit your name, mobile number and email address.  </p>
                        <p>You are solely responsible for safeguarding your Tosshead OTP. You shall be solely responsible for all activity that occurs and you shall notify Tosshead immediately of any breach of security or any unauthorized use of your OTP. Similarly, you shall never use another's OTP without Tosshead’s permission. You agree that you will not misrepresent yourself or represent yourself as another user of the Website and/or the Services offered through the Website.</p>
                        <p>You hereby expressly acknowledge and agree that you yourself and not Tosshead will be liable for your losses, damages etc. (whether direct or indirect) caused by an unauthorized use of your OTP. Notwithstanding the foregoing, you may be liable for the losses of Tosshead or others due to such unauthorized use.</p>
                        <p><b>6. ADDITIONAL POLICIES</b></p>
                        <p>Your access to, use of, an Tosshead’s participation in the Website is subject to the Terms of Use and all applicable Tosshead regulations, guidelines and additional policies that Tosshead may set forth from time to time, including without limitation, a copyright policy and any other restrictions or limitations that Tosshead publishes on the Website (the "Additional Policies"). You hereby agree to comply with the Additional Policies and your obligations thereunder at all times. You hereby acknowledge and agree that if you fail to adhere to any of the terms and conditions of this Agreement or documents referenced herein, including the Policies, membership eligibility criteria or Additional Policies, Tosshead, in its sole discretion, may terminate your booking at any time without prior notice to you as well as initiate appropriate legal proceedings, if necessary.</p>
                        <p><b>7. SUGGESTIONS.</b></p>
                        <p>If you send or transmit any communications, comments, questions, suggestions, or related materials to Tosshead, whether by letter, email, telephone, or otherwise (collectively, "Suggestions"), suggesting or recommending changes to the Website, including, without limitation, new features or functionality relating thereto, all such Suggestions are, and will be treated as, non-confidential and non-proprietary. You hereby assign all right, title, and interest in, and Tosshead is free to use, without any attribution or compensation to you, any ideas, know-how, concepts, techniques, or other intellectual property and proprietary rights contained in the Suggestions, whether or not patentable, for any purpose whatsoever, including but not limited to, developing, manufacturing, having manufactured, licensing, marketing, and selling, directly or indirectly, products and services using such Suggestions. You understand and agree that Tosshead is not obligated to use, display, reproduce, or distribute any such ideas, know-how, concepts, or techniques contained in the Suggestions, and you have no right to compel such use, display, reproduction, or distribution or seek recognition if the Suggestions are in fact implemented.</p>
                        <p><b>8. MODIFICATION OR CESSATION OF WEBSITE</b></p>
                        <p>Tosshead reserves the right at any time and from time to time to modify or discontinue, temporarily or permanently, the Website (or any part thereof) with or without notice and in its sole discretion. You agree that Tosshead shall not be liable to you or to any third party for any modification, suspension or discontinuance of Tosshead services.</p>
                        <p><b>9. Intellectual Property Rights</b></p>
                        <p><b>9a. TOSSHEAD OWNS OR HOLDS THE LICENSES TO ALL DATA AND MARKS ON THE WEBSITE</b></p>
                        <p>The content on the Website, including without limitation, the text, software, scripts, graphics, photos, sounds, music, videos, interactive features and the like ("Data") and the trademarks, service marks and logos contained therein ("Marks"), are owned by Tosshead. Other trademarks, names and logos on this Website are the property of their respective owners.</p>
                        <p>Data on the Website is provided to you AS IS for your information and personal use only and may not be used, copied, reproduced, distributed, transmitted, broadcast, displayed, sold, licensed, or otherwise exploited for any other purposes whatsoever without the prior written consent of the respective owners. Tosshead reserves all rights not expressly granted in and to the Website and the Data. You agree not to use, copy, or distribute, any of the Data other than as expressly permitted herein, including any use, copying, or distribution of Submitted Content obtained through the Website for any commercial purposes. If you download or print a copy of the Data for personal use, you must retain all copyright and other proprietary notices contained thereon. You agree not to circumvent, disable or otherwise interfere with security features of the Website or features that prevent or restrict use or copying of any Data or enforce limitations on use of the Website or the Data therein.</p>
                        <p><b>9b. TOSSHEAD 'S LICENSE TO YOU FOR THE USE OF DATA AND MARKS</b></p>
                        <p>The Website contains Tosshead's Data and Marks, which are, or may become, protected by copyright, trademark, patent, trade secret and other laws, and Tosshead owns and retains all rights in the Tosshead Data and Marks. Subject to these Terms of Use, Tosshead hereby grants you a limited, revocable, nontransferable, nonsublicensable license to reproduce and display the Tosshead Data (excluding any software source code) solely for your personal use in connection with accessing and participating in the Website.</p>
                        <p>The Website may also contain Data of other users or licensors, which you shall not copy, modify, translate, publish, broadcast, transmit, distribute, perform, display, or sell.</p>
                        <p><b>10. TAXES</b></p>
                        <p>You understand that we are acting solely as an intermediary for the collection of rents and fees from a Service User who choose to enter into an Agreement for Service. Because state and local tax laws vary significantly by locality, you understand and agree that you are solely responsible for determining your own tax reporting requirements in consultation with tax advisors, and that we cannot and do not offer tax advice to either hosts or guests. Further, you understand that Tosshead shall not be responsible or liable in any manner in relation to tax liability of a Service User.</p>
                        <p><b>11. REFUND POLICY</b></p>
                        <p>Tosshead will refund the entire amount if the services is canceled before 48 hours before the booking date. If canceled later refund cannot be processed.</p>
                        <p>© 2018 Tosshead Events Pvt Ltd. All Rights Reserved</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ends modal content --> 

<!-- /. Ends Page Wrapper-->

@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/ui.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
<script src="{{ asset('/frontend/js/cart.js') }}"></script>

@endpush

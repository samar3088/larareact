@extends('layouts.mobile.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
@endpush

@section('content')

<!-- Page Wrapper Comes Here--->
<div class="">
    
    <!-- starts page content -->

    <div class="section packages">
        <div class="container">
            <div class="col s12 found-status">
                <h1>Frequently Asked Questions</h1>
                <p></p>
            </div>
            <div class="bs-example">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i> What is TOSSHEAD? </button>                                  
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Choosing TossHead for managing your events - heads you win, tails you win! TOSSHEAD is India’s first online event equipment solutions platform that allows you to choose variety of event packages and also choose Do It Yourself mode of events. </a></p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> Why TOSSHEAD? </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Expertise of over 10 years of managing over 7000 plus events; hassle free login, convenient packages, professional management, unmatched prices, on-time-no-complaints execution, on-time delivery of booked equipment. </a></p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><i class="fa fa-plus"></i> Currently in how many cities TOSSHEAD is available? </button>                     
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Currently we are live in Bangalore. Shortly we will be expanding to other cities in India.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading4">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse4"><i class="fa fa-plus"></i> Can I place order & pay online?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Yes you can place order online and also make the payment online.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading5">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse5"><i class="fa fa-plus"></i>What’s on offer on TossHead?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>You can choose variety of packages and also mix and match the equipment that suits your events with do-it-yourself option</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading6">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse6"><i class="fa fa-plus"></i>What services do you offer?</button>
                            </h2>
                        </div>
                        <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>We provide end-to-end service for your event </p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading7">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse7"><i class="fa fa-plus"></i> Who handles my event?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>No one will handle your event; equipment will come with skilled operator. If you need an event co-ordinator you can book with us as well.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading8">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse8"><i class="fa fa-plus"></i> Do you charge for various services?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse8" class="collapse" aria-labelledby="heading8" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Multiple service options are available and will be charged accordingly.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading9">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse9"><i class="fa fa-plus"></i> What if I want to mange my own event?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse9" class="collapse" aria-labelledby="heading9" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Yes you can manage your event on your own; equipment is ours, management is yours</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading10">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse10"><i class="fa fa-plus"></i>  Why should I hire an event manager?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse10" class="collapse" aria-labelledby="heading10" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>An event manager brings expertise, skill and professionalism who will manage start to finish of the event.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading11">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse11"><i class="fa fa-plus"></i>  Will the booked equipment reach on time? </button>                     
                            </h2>
                        </div>
                        <div id="collapse11" class="collapse" aria-labelledby="heading11" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Yes the equipment will reach on time, our executive will call you 1 day prior to your event to ensure your delivery.If the equipment dosent reach your place then the entire money will be refunded.No refund will be entertained once the equipments reach your place.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading12">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse12"><i class="fa fa-plus"></i> Any geographical limits for booking & delivery of equipment?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse12" class="collapse" aria-labelledby="heading12" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>You can book the equipment within city limits; the same will be decided internally based on the specific location chosen by you.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading13">
                            <h2 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse13"><i class="fa fa-plus"></i> General tips and advice   </button>                     
                            </h2>
                        </div>
                        <div id="collapse13" class="collapse" aria-labelledby="heading13" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Finalise the dates, book the venue and get the quotation from us; then our executive will give call you for better understanding and complete event solutions will be provided by TOSSHEAD.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading14">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse14"><i class="fa fa-plus"></i> How can you guarantee lowest rates?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse14" class="collapse" aria-labelledby="heading14" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>TOSSHEAD is completely online based with no middle-men, so the prices are lowest compared to any local vendors.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading15">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse15"><i class="fa fa-plus"></i> Will you take the responsibility of the venue?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse15" class="collapse" aria-labelledby="heading15" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>No, we will take responsibility of the equipment only which are booked by you with TOSSHEAD.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading16">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse16"><i class="fa fa-plus"></i> How early can i book?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse16" class="collapse" aria-labelledby="heading16" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>24 hrs prior to the event or you can book once your venue and dates are finalised.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading17">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse17"><i class="fa fa-plus"></i> Will you work with vendors we select or only those you recommend?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse17" class="collapse" aria-labelledby="heading17" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>We will work only with recommended by us</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading18">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse18"><i class="fa fa-plus"></i> How do you ensure the quality of equipment?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse18" class="collapse" aria-labelledby="heading18" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Quality of the equipment is assured; we have experienced team who do quality checks regularly.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading19">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse19"><i class="fa fa-plus"></i> Do I have to buy any license? </button>                     
                            </h2>
                        </div>
                        <div id="collapse19" class="collapse" aria-labelledby="heading19" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Yes, based on the venue you need to buy license for playing copyrighted music.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading20">
                            <h2 class="mb-0">
                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse20"><i class="fa fa-plus"></i> Do I get invoice for the booked equipments?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse20" class="collapse" aria-labelledby="heading20" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>Yes, you get GST invoice accordingly.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading21">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse21"><i class="fa fa-plus"></i> Does the cost shown in website include GST?  </button>                     
                            </h2>
                        </div>
                        <div id="collapse21" class="collapse" aria-labelledby="heading21" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>No, price shown in website is base price (exclusive of GST, the quote & invoice after adding necessary GST will include).</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading22">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse22"><i class="fa fa-plus"></i> Do you charge separately for Event Management?   </button>                     
                            </h2>
                        </div>
                        <div id="collapse22" class="collapse" aria-labelledby="heading22" data-parent="#accordionExample">
                            <div class="card-body animated zoomIn">
                                <p>NO</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading23">
                        <h2 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse23"><i class="fa fa-plus"></i> How many cities is Tosshead available in? Management?  </button>                     
                        </h2>
                        </div>
                        <div id="collapse23" class="collapse" aria-labelledby="heading23" data-parent="#accordionExample">
                        <div class="card-body animated zoomIn">
                            <p>
                                We are currently providing services only in Bangalore,Chennai and Hyderabad; soon we will be available in more cities.
                            </p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading24">
                        <h2 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse24"><i class="fa fa-plus"></i> How can I contact you? Management?   </button>                     
                        </h2>
                        </div>
                        <div id="collapse24" class="collapse" aria-labelledby="heading24" data-parent="#accordionExample">
                        <div class="card-body animated zoomIn">
                            <p>
                                When you place an order you will get quotation to your email id with contact details Or our contact details are mentioned in the website you can reach us accordingly.
                            </p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading25">
                        <h2 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse25"><i class="fa fa-plus"></i> How secure is the information I provide in Tosshead?   </button>                     
                        </h2>
                        </div>
                        <div id="collapse25" class="collapse" aria-labelledby="heading25" data-parent="#accordionExample">
                        <div class="card-body animated zoomIn">
                            <p>
                                The information you provide is very secure and will never be shared for any reason.
                            </p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading26">
                        <h2 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse26"><i class="fa fa-plus"></i> 
                            What happens if an Event is cancelled?
                            </button>                     
                        </h2>
                        </div>
                        <div id="collapse26" class="collapse" aria-labelledby="heading26" data-parent="#accordionExample">
                        <div class="card-body animated zoomIn">
                            <p>
                                We will refund the entire amount if cancelled 48 hours prior to the event. Non-refundable if cancelled later.
                            </p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading27">
                        <h2 class="mb-0">
                            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse27"><i class="fa fa-plus"></i> 
                            Do I need to have login credentials with Tosshead to place order?
                            </button>                     
                        </h2>
                        </div>
                        <div id="collapse27" class="collapse" aria-labelledby="heading27" data-parent="#accordionExample">
                        <div class="card-body animated zoomIn">
                            <p>
                                No, it’s open to anybody for placing orders.
                            </p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
           <!-- ends faq -->
        </div>
    </div>

    <!-- /.ends page content -->

    <div style="clear: both;"></div>

</div>    
<!-- /. Ends Page Wrapper-->

@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/bootstrap.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
@endpush

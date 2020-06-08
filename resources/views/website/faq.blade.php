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
 .animated{-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-fill-mode:both;animation-fill-mode:both}.animated.infinite{-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite}.animated.hinge{-webkit-animation-duration:2s;animation-duration:2s
}@-webkit-keyframes zoomIn{0%{opacity:0;-webkit-transform:scale3d(.3,.3,.3);transform:scale3d(.3,.3,.3)}50%{opacity:1}}@keyframes zoomIn{0%{opacity:0;-webkit-transform:scale3d(.3,.3,.3);transform:scale3d(.3,.3,.3)}50%{opacity:1}}.zoomIn{-webkit-animation-name:zoomIn;animation-name:zoomIn}
@-webkit-keyframes zoomOut{0%{opacity:1}50%{opacity:0;-webkit-transform:scale3d(.3,.3,.3);transform:scale3d(.3,.3,.3)}100%{opacity:0}}@keyframes zoomOut{0%{opacity:1}50%{opacity:0;-webkit-transform:scale3d(.3,.3,.3);transform:scale3d(.3,.3,.3)}100%{opacity:0}}.zoomOut{-webkit-animation-name:zoomOut;animation-name:zoomOut}

#accordion .panel-title i.glyphicon
{
    -moz-transition: -moz-transform 0.5s ease-in-out;
    -o-transition: -o-transform 0.5s ease-in-out;
    -webkit-transition: -webkit-transform 0.5s ease-in-out;
    transition: transform 0.5s ease-in-out;
    font-size: 19px !important;
    font-weight: 600 !important;
    margin-right: 3px;
}
.rotate-icon
{
    -webkit-transform: rotate(-225deg);
    -moz-transform: rotate(-225deg);
    transform: rotate(-225deg);
}
.panel
{
    border: 0px;
    border-bottom: 1px solid #30bb64;
}
.panel-group .panel
{
    border-radius: 0px;
    border-bottom: 5px solid #d52a3382;
}
.panel-heading
{
    border-radius: 0px;
    color: white;
}
.panel-custom>.panel-heading
{
    background-color: #d52a33;
}
panel-collapse .collapse.in{
    border-bottom:0;
}
.faqs h1 {
    font-size: 24px;
    text-align: center;
    color: #d52a33;
    margin-bottom: 20px;
}
</style>

@endpush

@section('content')

<!-- Main content comes here -->

    <div class="mt-125">  
        <div class="wrapper">
            <!-- starts faq -->
            <div class="container">
                <div class="row faqs">
                    <h1>Frequently Asked Questions</h1>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        
                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="glyphicon glyphicon-plus"></i> What is TOSSHEAD?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body animated zoomOut">
                                    Choosing TossHead for managing your events - heads you win, tails you win! TOSSHEAD is India’s first online event equipment solutions platform that allows you to choose variety of event packages and also choose Do It Yourself mode of events.
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="glyphicon glyphicon-plus"></i> Why TOSSHEAD?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body animated zoomOut">
                                    Expertise of over 10 years of managing over 7000 plus events; hassle free login, convenient packages, professional management, 
                                    unmatched prices, on-time-no-complaints execution, on-time delivery of booked equipment.                      
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                                                     
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">                            
                                        <i class="glyphicon glyphicon-plus"></i> Currently in how many cities TOSSHEAD is available?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Currently we are live in Bangalore. Shortly we will be expanding to other cities in India.
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                   
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapsefour">                            
                                        <i class="glyphicon glyphicon-plus"></i> Can I place order & pay online?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Yes you can place order online and also make the payment online.
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">           
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefive" aria-expanded="false" aria-controls="collapsefive">                            
                                        <i class="glyphicon glyphicon-plus"></i> What’s on offer on TossHead?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapsefive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    You can choose variety of packages and also mix and match the equipment that suits your events with do-it-yourself option
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">             
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsesix" aria-expanded="false" aria-controls="collapsesix">                            
                                        <i class="glyphicon glyphicon-plus"></i> What services do you offer?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapsesix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    We provide end-to-end service for your event 
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseseven" aria-expanded="false" aria-controls="collapseseven">                            
                                        <i class="glyphicon glyphicon-plus"></i> Who handles my event?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapseseven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    No one will handle your event; equipment will come with skilled operator. If you need an event co-ordinator you can book with us as well.
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseeight" aria-expanded="false" aria-controls="collapseeight">                            
                                        <i class="glyphicon glyphicon-plus"></i> Do you charge for various services?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapseeight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Multiple service options are available and will be charged accordingly
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">             
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsenine" aria-expanded="false" aria-controls="collapsenine">                            
                                        <i class="glyphicon glyphicon-plus"></i> What if I want to mange my own event?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapsenine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Yes you can manage your event on your own; equipment is ours, management is yours.
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">               
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseten" aria-expanded="false" aria-controls="collapseten">                            
                                        <i class="glyphicon glyphicon-plus"></i> Why should I hire an event manager?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapseten" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    An event manager brings expertise, skill and professionalism who will manage start to finish of the event                             
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse11" aria-expanded="false" aria-controls="collapse11">                            
                                        <i class="glyphicon glyphicon-plus"></i> Will the booked equipment reach on time?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse11" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Yes the equipment will reach on time, our executive will call you 1 day prior to your event to ensure your delivery.If the equipment dosent reach your place then the entire money will be refunded.No refund will be entertained once the equipments reach your place. 
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse12" aria-expanded="false" aria-controls="collapse12">                            
                                        <i class="glyphicon glyphicon-plus"></i> Any geographical limits for booking & delivery of equipment?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse12" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    You can book the equipment within city limits; the same will be decided internally based on the specific location chosen by you.                          
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse13" aria-expanded="false" aria-controls="collapse13">                            
                                        <i class="glyphicon glyphicon-plus"></i> General tips and advice
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse13" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Finalise the dates, book the venue and get the quotation from us; then our executive will give call you for better understanding and complete event solutions will be provided by TOSSHEAD.                       
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">               
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse14" aria-expanded="false" aria-controls="collapse14">                            
                                        <i class="glyphicon glyphicon-plus"></i> How can you guarantee lowest rates?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse14" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    TOSSHEAD is completely online based with no middle-men, so the prices are lowest compared to any local vendors.                       
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">            
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse15" aria-expanded="false" aria-controls="collapse15">                            
                                        <i class="glyphicon glyphicon-plus"></i> Will you take the responsibility of the venue?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse15" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    No, we will take responsibility of the equipment only which are booked by you with TOSSHEAD.                          
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">             
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse16" aria-expanded="false" aria-controls="collapse16">                            
                                        <i class="glyphicon glyphicon-plus"></i> How early can i book?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse16" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    24 hrs prior to the event or you can book once your venue and dates are finalised.                    
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse17" aria-expanded="false" aria-controls="collapse17">                            
                                        <i class="glyphicon glyphicon-plus"></i> Will you work with vendors we select or only those you recommend?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    We will work only with recommended by us
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">               
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse18" aria-expanded="false" aria-controls="collapse18">                            
                                        How do you ensure the quality of equipment?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse18" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    <i class="glyphicon glyphicon-plus"></i> Quality of the equipment is assured; we have experienced team who do quality checks regularly.                        
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">          
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse19" aria-expanded="false" aria-controls="collapse19">                            
                                            <i class="glyphicon glyphicon-plus"></i> Do I have to buy any license?
                                        </a>                        
                                </h4>
                            </div>
                            <div id="collapse19" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Yes, based on the venue you need to buy license for playing copyrighted music.                        
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                 
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse20" aria-expanded="false" aria-controls="collapse20">                            
                                        <i class="glyphicon glyphicon-plus"></i> Do I get invoice for the booked equipments?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse20" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    Yes, you get GST invoice accordingly. 
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">            
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse21" aria-expanded="false" aria-controls="collapse21">                            
                                        <i class="glyphicon glyphicon-plus"></i> Does the cost shown in website include GST?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse21" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    No, price shown in website is base price (exclusive of GST, the quote & invoice after adding necessary GST will include ).                    
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse22" aria-expanded="false" aria-controls="collapse22">                            
                                        <i class="glyphicon glyphicon-plus"></i> Do you charge separately for Event Management?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse22" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    NO                    
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse23" aria-expanded="false" aria-controls="collapse23">                            
                                        <i class="glyphicon glyphicon-plus"></i> How many cities is Tosshead available in? Management?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse23" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    We are currently providing services only in Bangalore,Chennai and Hyderabad; soon we will be available in more cities.                    
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                 
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse24" aria-expanded="false" aria-controls="collapse24">                            
                                        <i class="glyphicon glyphicon-plus"></i> How can I contact you? Management?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse24" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    When you place an order you will get quotation to your email id with contact details 
                                    Or our contact details are mentioned in the website you can reach us accordingly.                     
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse25" aria-expanded="false" aria-controls="collapse25">                            
                                        <i class="glyphicon glyphicon-plus"></i> How secure is the information I provide in Tosshead?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse25" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    The information you provide is very secure and will never be shared for any reason.                   
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">              
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse26" aria-expanded="false" aria-controls="collapse26">                            
                                        <i class="glyphicon glyphicon-plus"></i> What happens if an Event is cancelled?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse26" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    We will refund the entire amount if cancelled 48 hours prior to the event. Non-refundable if cancelled later.                     
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-custom">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">                
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse27" aria-expanded="false" aria-controls="collapse27">                            
                                        <i class="glyphicon glyphicon-plus"></i> Do I need to have login credentials with Tosshead to place order?
                                    </a>                        
                                </h4>
                            </div>
                            <div id="collapse27" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body animated zoomOut">
                                    No, it’s open to anybody for placing orders.                      
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- ends faq -->
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

            $(function() {

                function toggleChevron(e) 
                {
                    $(e.target) .prev('.panel-heading').find("i").toggleClass('rotate-icon');
                        $('.panel-body.animated').toggleClass('zoomIn zoomOut');
                }
                $('#accordion').on('hide.bs.collapse', toggleChevron);
                $('#accordion').on('show.bs.collapse', toggleChevron);
            });

    </script>

@endpush

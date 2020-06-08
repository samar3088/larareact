@extends('layouts.mobile.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
@endpush

@section('content')
<div class="page-content-wrapper homepages">
    <!-- page slider comes here -->
        <!-- Hero Slides-->
        <div class="hero-slides owl-carousel">
            <!-- Single Hero Slide-->
            <div class="single-hero-slide">
                <div class="slide-img">
                    <img src="{{ asset('/frontend/mobile/images/events/1.png') }}" alt=""/>
                </div>
            </div>
            <!-- /.Ends single Hero Slide-->
            <!-- Single Hero Slide-->
            <div class="single-hero-slide">
                <div class="slide-img">
                    <img src="{{ asset('/frontend/mobile/images/events/2.png') }}" alt=""/>
                </div>
            </div>
            <!-- /.Ends single Hero Slide-->
            <!-- Single Hero Slide-->
            <div class="single-hero-slide">
                <div class="slide-img">
                    <img src="{{ asset('/frontend/mobile/images/events/3.png') }}" alt=""/>
                </div>
            </div>
            <!-- /.Ends single Hero Slide-->
            <!-- Single Hero Slide-->
            <div class="single-hero-slide">
                <div class="slide-img">
                    <img src="{{ asset('/frontend/mobile/images/events/4.png') }}" alt=""/>
                </div>
            </div>
            <!-- /.Ends single Hero Slide-->
            <!-- Single Hero Slide-->
            <div class="single-hero-slide">
                <div class="slide-img">
                    <img src="{{ asset('/frontend/mobile/images/events/5.png') }}" alt=""/>
                </div>
            </div>
            <!-- /.Ends single Hero Slide-->
        </div>
    <!-- /.page slider Ends-->

    <!--  Catagories-->
    <div class="product-catagories-wrapper pt-3">
        <div class="container">
            <div class="section-heading frontpage_heading_top">
            <h6 class="ml-1">We have 20,625 great event essentials you deserve </h6>
            </div>
            <div class="product-catagory-wrap">
                <div class="row">
                    <div class="col-6">
                        <div class="card mb-3 catagory-card">
                            <div class="card-body">
                                {{-- <a href="{{ route('website.weddings') }}">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/wedding.png') }}" alt=""/></center>
                                    <span>Wedding Planning</span>
                                </a> --}}

                                <div class="firstlist">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/wedding.png') }}" alt="" class="mnimg"/></center>
                                    <span class="button">Wedding Planning</span>
                                </div>
                                <span class="loader"><center><img src="{{ asset('/frontend/mobile/images/loading.gif') }}" alt="" class="loadimages1"/></center></span>

                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card mb-3 catagory-card">
                            <div class="card-body">
                                {{-- <a href="{{ route('website.birthdays') }}">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/birthday.png') }}" alt=""/></center>
                                    <span>Birthday Decorations</span>
                                </a> --}}

                                <div class="secondlist">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/birthday.png') }}" alt="" class="mnimg"/></center>
                                    <span class="button">Birthday Decorations</span>
                                </div>
                                <span class="loader"><center><img src="{{ asset('/frontend/mobile/images/loading.gif') }}" alt="" class="loadimages2"/></center></span>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card mb-3 catagory-card">
                            <div class="card-body">
                                {{-- <a href="{{ route('website.equipments') }}">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/equipments.png') }}" alt=""/></center>
                                    <span>Event Equipments</span>
                                </a> --}}

                                <div class="thirdlist">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/equipments.png') }}" alt="" class="mnimg"/></center>
                                    <span class="button">Event Equipments</span>
                                </div>
                                <span class="loader"><center><img src="{{ asset('/frontend/mobile/images/loading.gif') }}" alt="" class="loadimages3"/></center></span>

                            </div>
                        </div>
                    </div> 
                    <div class="col-6">
                        <div class="card mb-3 catagory-card">
                            <div class="card-body">
                                {{-- <a href="{{ route('website.packageslist') }}">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/packages.png') }}" alt=""/></center>
                                    <span>Event Packages</span>
                                </a> --}}

                                <div class="fourthlist">
                                    <center><img src="{{ asset('/frontend/mobile/images/icons/packages.png') }}" alt="" class="mnimg"/></center>
                                    <span class="button">Event Packages</span>
                                </div>
                                <span class="loader"><center><img src="{{ asset('/frontend/mobile/images/loading.gif') }}" alt="" class="loadimages4"/></center></span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Catagories Ends-->
 
    <!-- start how it works -->

    <section class="howitworks">
        <div class="container">
            <div class="flash-sale-wrapper">
                <div class="col-12 headtitle">
                    <h3 class="btn btn-primary btn-sm p" href="#">Bookings Made Easy</h3>
                    <div class="linedivider"></div>
                    <div class="smalldivider"></div>
                </div>
            </div>
            <div class="row">
                <div class="s12">
                    <!-- starts icons -->
                    <div class="services-2 w-100 d-flex">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <img src="{{ asset('/frontend/mobile/images/select-mobile-01.png') }}" alt=""/>
                        </div>
                        <div class="text pl-4">
                            <h4>Select Event Category</h4>
                            <p> Select category ranging from Equipment rental, Wedding essential services, Birthday decorations to Event packages  </p>
                        </div>
                    </div>
                    <div class="services-2 w-100 d-flex">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <img src="{{ asset('/frontend/mobile/images/click-01.png') }}" alt=""/>
                        </div>
                        <div class="text pl-4">
                            <h4>Confirm Your Booking</h4>
                            <p>All it takes is just a 3 click to confirm your choice of Event</p>
                        </div>
                    </div>
                    <div class="services-2 w-100 d-flex">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <img src="{{ asset('/frontend/mobile/images/make-payment-01.png') }}" alt=""/>
                        </div>
                        <div class="text pl-4">
                            <h4>Make  Payment</h4>
                            <p> Online payment process via Razorpay secured platform</p>
                        </div>
                    </div>
                    <!-- ends icons -->
                </div>
            </div>
        </div>
    </section>

    <!-- /.start how it works Ends-->

    <!-- start about us -->

    <div class="section about-home bg-second">
        <div class="container">
            <div class="section-head">
                <h4>ABOUT US</h4>
                <div class="underline"></div>
            </div>
            <div class="content">
                <p>We are India's largest Online Event Equipment Booking & Execution Platform, we provide anything and everything from <strong> Sound System, Lighting, Stage Setup, Led Wall, DJ, Anchors, Dance Troupe, Magician, Karaoke Singer, Tattoo Artist, Mehandi Artist, Face Painting and the endless list goes on. </strong></p>
            </div>
        </div>
    </div>

    <!-- /.start about us Ends-->

    <!-- starts proud member of -->

    <section class="proudmember">
        <div class="container">
           <div class="flash-sale-wrapper">
              <div class="col-12 headtitle">
                 <h3 class="btn btn-primary btn-sm p" href="#">We are proud member of </h3>
                 <div class="linedivider"> </div>
                 <div class="smalldivider"></div>
              </div>
              <div class="row">
                 <div class="col s6">
                    <img src="{{ asset('/frontend/mobile/images/partner1.png') }}" alt=""/>
                 </div>
                 <div class="col s6">
                    <img src="{{ asset('/frontend/mobile/images/partner2.png') }}" alt=""/>
                 </div>
              </div>
           </div>
        </div>
     </section>

    <!-- /.starts proud member of Ends-->

    <!-- start services --> 

    <div class="flash-sale-wrapper">
        <div class="col-12 headtitle">
           <h3 class="btn btn-primary btn-sm p" href="#">Our Commitment </h3>
           <div class="linedivider"></div>
           <div class="smalldivider"></div>
        </div>
    </div>
    <div class="section services">
        <div class="container">
            <div class="row">
                <div class="col s6">
                    <center>
                        <img src="{{ asset('/frontend/mobile/images/icons/quality.png') }}" alt=""/>
                    </center>
                    <h5>Quality</h5>
                    <p>All our customer requirements with regard to events will go through strict quality check from Event Essentials to hiring of vendors.</p>
                </div>
                <div class="col s6">
                    <center>
                        <img src="{{ asset('/frontend/mobile/images/icons/service.png') }}" alt=""/>
                    </center>
                    <h5>Great Customer Service</h5>
                    <p>Our support team available 24 / 7 so that you need not worry about hand holding your event any given point in time.  </p>
                </div>
            </div>
            <div class="row mb">
                <div class="col s6">
                    <center>
                        <img src="{{ asset('/frontend/mobile/images/icons/discount.png') }}" alt=""/>
                    </center>
                    <h5>Lowest Price</h5>
                    <p>All Event Equipments rental are lowest price without compromising the quality and execution .  </p>
                </div>
                <div class="col s6">
                    <center>
                        <img src="{{ asset('/frontend/mobile/images/icons/maintain.png') }}" alt=""/>
                    </center>
                    <h5>Booking & Execution</h5>
                    <p>It's just not hiring Equipment for rental we execute Events as well, so you can sit back and relax </p>
                </div>
            </div>
        </div>
    </div>

    <!-- /. end services-->
    
    <!-- Offers that you cant resist-->
    @include('website._offers')
    <!-- /.Offers that you cant resist Ends-->

</div>
@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
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

/* added on may - 25 */
$(function () {
    $(".firstlist").click(function () { 
            // $(".loadimages1").show(); 
                window.setTimeout(function(){  
                window.location.href = "{{ route('website.weddings') }}";
                }, 1000); 
                return false;
    });
    $(".secondlist").click(function () { 
                //$(".loadimages2").show(); 
                window.setTimeout(function(){  
                window.location.href = "{{ route('website.birthdays') }}";
                }, 1000); 
                return false;
    });
    $(".thirdlist").click(function () { 
                //$(".loadimages3").show(); 
                window.setTimeout(function(){  
                window.location.href = "{{ route('website.equipments') }}";
                }, 1000); 
                return false;
    });
    $(".fourthlist").click(function () { 
                //$(".loadimages4").show(); 
                window.setTimeout(function(){  
                window.location.href = "{{ route('website.packageslist') }}";
                }, 1000); 
                return false;
    });
});

var cart_counter = {{ $cart_items_count }};
$('#total_cart_items').text(cart_counter);
</script>
@endpush

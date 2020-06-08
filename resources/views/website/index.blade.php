@extends('layouts.frontend.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
<link href="{{ asset('/frontend/css/styleslider.css') }}" rel="stylesheet">
<link href="{{ asset('/frontend/css/custom-home.css') }}" rel="stylesheet">
 @endpush

@section('content')

    <!-- page slider comes here -->
    <div class="fga">
        <div class="bnimages">
            <div id="sliderFrame">
                <div id="slider">
                    <img src="{{ asset('/frontend/images/homeslider/1.png') }}" />
                    <img src="{{ asset('/frontend/images/homeslider/2.png') }}" />
                    <img src="{{ asset('/frontend/images/homeslider/3.png') }}" />
                    <img src="{{ asset('/frontend/images/homeslider/4.png') }}" />
                    <img src="{{ asset('/frontend/images/homeslider/5.png') }}" />
                </div>
            </div>
        </div>
        <div class="pad-btmb"></div>
    </div>
    <!-- /.page slider Ends-->

    <!-- starts drodown 2 -->

    <div class = "second-form">
        <form role="form" action="#" method="post">
            <div class="col-md-1"></div>
            <div class="form-group col-md-4">
                <div class="drop-down">
                    <select name="options">
                        <option id="imgsiz" class="0" value="0" style="background-image:url('{{ asset('/frontend/images/map.png') }}');">Bangalore</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-4">
                <div class="drop-down1">
                    <select name="options1" >
                        <option class="select_cats" id="imgsiz1" class="0" value="0" style="background-image:url(' {{ asset('/frontend/images/category.png') }} ');background-size: 20px 20px;"> &nbsp;&nbsp;&nbsp;Select Category</option>
                        <option id="imgsiz1" class="chennai1" value="{{ route('website.equipments') }}">Event Equipments Rental</option>
                        <option id="imgsiz1" class="wedding" value="{{ route('website.weddings') }}">Wedding Planning Services</option>
                        <option id="imgsiz1" class="Hyderabad1" value="{{ route('website.birthdays') }}">Birthday Decorations</option>
                        <option id="imgsiz1" class="bangalore1" value="{{ route('website.packages') }}">Event Packages</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- /.ends dropdown 2-->

    <div style="clear:both;"></div>

    <section class="bg-chrismas frontconts">
        <div class="container text-center">
            <div class="btn-trans-hover page-header" style="border:none; margin-bottom:0px;margin-top:0px">
                <div class="page-header" style="margin-top: 1px;border-bottom:0px;padding-bottom: 4px;"></div>
                <p class="contenttext"> More than <span> 20,620 + </span> Event Equipments to choose from </p>
                <div class="page-header" style="border-bottom: 1px solid #C0C0C0;margin-top: 13px;color:#d52a33;margin-bottom: 10px;">
                    <h3 style="color:#000;margin-left: 5%;">India's Largest Online Event Equipment Booking & Execution  Platform </h3>
                </div>
            </div>

            <!-- starts addding tosshead images -->
            <div class="row imagcls">
                <div class="col-md-2 nwinc">
                    <img src="{{ asset('/frontend/images/icons/packages.png') }}" class="incrs1">
                    <p class="css-174j2l pr5">Event Packages</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('/frontend/images/icons/equipments.png') }}" class="reduce1">
                    <p class="css-174j2l pr5">Event Equipments</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('/frontend/images/icons/birthday.png') }}">
                    <p class="css-174j2l pr5">Birthday Decorations</p>
                </div>
                <div class="col-md-2 nwinc">
                    <img src="{{ asset('/frontend/images/icons/wedding.png') }}" class="wdng">
                    <p class="css-174j2l pr5">Wedding Planning</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('/frontend/images/icons/corporate.png') }}">
                    <p class="css-174j2l pr5">Corporate Events</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('/frontend/images/icons/banquet.png') }}">
                    <p class="css-174j2l pr5">Banquet Halls</p>
                </div>
            </div>
            <!-- ends adding tosshead images -->
        </div>
    </section> 
    
    <!-- starts client testimonail -->
    <section class="bg-chrismas testimo mt-e3">
            <div class="full-width">
                      
                      <!-- start new testimonial --> 

                        <div class="demohow"> 

                            <!-- starts how it works -->
                                <div class="container">
                                    <div class="row justify-content-center pb-5 mb-3" id="about"> 
                                    <div class="col-md-12 heading-section text-center ftco-animate fadeInUp ftco-animated page-header">
                                        <!--
                                        <h2>Why coaching work?</h2>
                                        <span class="subheading">Other Services</span>
                                    -->
                                    
                                        <div class="heading">Bookings <span>Made Easy</span></div>
                                        <div class="stline"></div>
                                   
                                    </div>
                                    </div>
                                    <div class="row d-flex no-gutters">
                                    <div class="col-md-6 d-flex">
                                    <div class="img img-video fix_img d-flex align-self-stretch align-items-center justify-content-center justify-content-md-end mb-4 mb-sm-0" style="background-image:url({{ asset('/frontend/images/how.png') }});">
                                    
                                    </div>
                                    </div>
                                    <div class="col-md-6 pl-md-5 py-md-5">
                                    <div class="services-2 w-100 d-flex">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                    <img src="https://www.tosshead.com/imagestosshead/select-mobile-01.svg">
                                    </div>
                                    <div class="text pl-4">
                                    <h4>Select Event Category</h4>
                                    <p> Select category ranging from Equipment rental, Wedding essential services, Birthday decorations to Event packages  </p>
                                    </div>
                                    </div>
                                    <div class="services-2 w-100 d-flex">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                    <img src="https://www.tosshead.com/imagestosshead/click-01.svg">
                                    </div>
                                    <div class="text pl-4">
                                    <h4>Confirm Your Booking</h4>
                                    <p>All it takes is just a 3 click to confirm your choice of Event</p>
                                    </div>
                                    </div>
                                    <div class="services-2 w-100 d-flex">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                    <img src="https://www.tosshead.com/imagestosshead/make-payment-01.svg">
                                    </div>
                                    <div class="text pl-4">
                                    <h4>Make  Payment</h4>
                                    <p> Online payment process via Razorpay secured platform</p>
                                    </div>
                                    </div>
                                    
                                    </div>
                                    </div>
                                </div> 
                            <!-- ends how it works -->
                        </div> 
    
                      <!-- ends new testimonial --> 
            </div>
             
        </section>

    <!-- ends client testimonail -->
    
    <!-- icons -->
    <section class="bg-chrismas" style="padding-bottom:0px;">
        <div class="container text-center rm-benefit__container">
            <div class="btn-trans-hover page-header" style="border:none; margin-bottom:0px;margin-top:40px">
                <div class="page-header" style="border-bottom: 1px solid #C0C0C0;margin-top: 13px;">
                    <h3 class="tohed">Here is why we have been rated the best Event Platform </h3>
                </div>
            </div>
            <!-- icons from rento -->
            <div class="row rowicons">
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="rm-benefit__block rm-margin__right">
                        <img src="{{ asset('/frontend/images/icons/quality.png') }}" alt="">
                        <h3>Quality</h3>
                        <p>Quality matters to you and us ! That&#39;s why all our customer requirements with regard to events will go through strict quality check from Event Essentials to hiring of vendors.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="rm-benefit__block rm-margin__left">
                        <img src="{{ asset('/frontend/images/icons/location.png') }}" alt="">
                        <h3>Location</h3>
                        <p>Our serve  all over Bangalore, its just that you need not worry if your event is held far off.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="rm-benefit__block rm-margin__right">
                        <img src="{{ asset('/frontend/images/icons/maintain.png') }}" alt="">
                        <h3>Booking & Execution</h3>
                        <p>It&#39;s just not hiring Equipment for rental we execute Events as well, so you can sit back and relax</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6 visible-xs">
                    <div class="rm-benefit__block rm-mob__readmore rm-margin__left">
                        <div class="rm-more__data">
                            <a href="#" class=""><img src="{{ asset('/frontend/images/arrow_more.png') }} " alt="">Read More</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row rowicons">
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="rm-benefit__block">
                        <img src="{{ asset('/frontend/images/icons/cancel.png') }}" alt="">
                        <h3>Cancel anytime</h3>
                        <p>You can cancel your booking 24 hours before your event date, 100 % money will be refunded. No questions asked.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="rm-benefit__block">
                        <img src="{{ asset('/frontend/images/icons/discount.png') }}" alt="">
                        <h3>Lowest Price</h3>
                        <p>All Event Equipments rental are lowest price without compromising the quality and execution . </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="rm-benefit__block">
                        <img src="{{ asset('/frontend/images/icons/service.png') }}" alt="">
                        <h3>Great Customer Service</h3>
                        <p>Our support team available 24 / 7 so that you need not worry about hand holding your event any given point in time. </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- icons ends --> 
    
    <!-- start new partner logos -->
    <section class="brands">
        <div class="container card">
            <div class="row">
                <div class="col-sm-12  col-md-6">
                    <div class="flex flex-col justify-center">
                        <div class="heading">Business <span>Partners</span></div>
                        <div class="centerline"></div>
                        <div class="description mt-30"> We execute events for customers of India(s) best brands  </div>
                    </div>
                </div>
                <div class="col-sm-12  col-md-6">
                    <div class="clientslogos">
                        <div class="flex justify-end">
                            <div class="circle"><img src="{{ asset('/frontend/images/partner/1.png') }}"  alt=""></div>
                            <div class="circle"><img src="{{ asset('/frontend/images/partner/2.png') }}"  alt=""></div>
                            <div class="circle"><img src="{{ asset('/frontend/images/partner/3.png') }}"  alt=""></div>
                        </div>
                        <div class="flex justify-end">
                            <div class="circle"><img src="{{ asset('/frontend/images/partner/4.png') }}"  alt=""></div>
                            <div class="circle"><img src="{{ asset('/frontend/images/partner/5.png') }}"  alt=""></div>
                            <div class="circle"><img src="{{ asset('/frontend/images/partner/6.png') }}"  alt=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-purple desktop-only"></div>
    </section>
    <!-- ends new partner logos -->

    <!-- starts client logos-->
    <section class="client_brands">
        <div class="container card">
            <div class="page-header" style="margin-top: 13px;color:#d52a33;margin-bottom: 40px;border:0px;">
                <div class="heading">Our Clients <span>Love Us</span></div>
                <div class="stline"></div>
            </div>
            <div class="w12 clientslogos">
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-1.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-2.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-3.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-4.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-5.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-6.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-7.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-8.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-9.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-10.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-11.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-12.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-13.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-14.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-15.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-16.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-17.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-18.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-19.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-20.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-21.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-22.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-23.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-24.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-25.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-26.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-27.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-28.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-29.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-30.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-31.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-32.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-33.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-34.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-35.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-36.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-37.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-38.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-39.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-40.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-41.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-42.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-43.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-44.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-45.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-46.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-47.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-48.jpg') }}" alt=""></div>
                </div>
                <div class="flex justify-end mb-20">
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-49.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-50.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-51.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-52.jpg') }}" alt=""></div>
                    <div class="circle"><img src="{{ asset('/frontend/images/clients/clients-53.jpg') }}" alt=""></div>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <p class="powered"> Powered by G Events Unlimited </p>
            </div>
        </div>
        <div class="bg-purple desktop-only"></div>
    </section>
    <!-- ends client logos -->

    <!-- start about us new -->
    <section id="about" class="about">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-12">
                    <div class="page-header">
                        <div class="heading">About Us <span>We are Unique</span></div>
                    </div>
                    <div class="stline"></div>
                </div>
                <div class="col-lg-5 d-flex align-items-center justify-content-center about-img imgcorner" style="padding: 0;">
                    <img src="{{ asset('/frontend/images/about.png') }} " class="img-fluid aos-init aos-animate" alt="" data-aos="zoom-in">
                </div>
                <div class="col-lg-6 pt-5 pt-lg-0">
                    <p data-aos="fade-up" data-aos-delay="100" class="aos-init">
                        We are India largest Online Event Equipment Booking & Execution Platform, we provide anything and everything from
                        <strong>
                            Sound System, Lighting, Stage Setup, Led Wall, DJ, Anchors, Dance Troupe, Magician, Karaoke Singer, Tattoo Artist, Mehandi Artist,
                            Face Painting and the endless list goes on.
                        </strong>
                    </p>
                    <p id="about"> Why we are unique </p>
                    <p class="line"></p>
                    <ul class="order">
                        <li> We are in a process of transforming unorganized event industry to  organized</li>
                        <li> We are first to get Event mangement services on a digital platform which increase transparency in pricing and the quality of product served</li>
                        <li> We created one platform for all type of events - Corporate Event, Wedding, Birthday or any kind of social events  </li>
                        <li> We are 100 % technology driven booking platform</li>
                    </ul>
                    <div class="row partnes">
                        <div class="col-md-12 proud">
                            <p> We are proud member of </p>
                        </div>
                        <div class="col-md-6 aos-init" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('/frontend/images/startupindia.png') }}">
                            <h4></h4>
                            <p></p>
                        </div>
                        <div class="col-md-6 aos-init" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('/frontend/images/indiagovt.png') }}">
                            <h4></h4>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end about us new -->
    
    <!-- starts 2nd layer new carousel -->

    <section class="bg-chrismas cardsbg" style="background: #fff;margin-top: 1%;margin-bottom:8%;padding-top:1%;padding-bottom:1%;">
        <div class="container text-center productscars">
            <div id="wrapper">
                <div class="header">
                    <div class="shell">
                        <div class="page-header" style="padding-top: 3%;">
                            <h3 style="font-family: 'Anton', sans-serif;color:#fff;font-size: 32px;"> Exclusive offers only for you </h3>
                        </div>
                        <div class="slider-holder">
                            <span class="left"></span>
                            <span class="right"></span>
                            <!-- flexslider -->
                            <div class="big-slider">
                                <ul class="slides">
                                    @foreach ($homesliders as $homeslider)
                                    <li>
                                        <img src="{{Storage::url($homeslider->file) }}" alt="" class="alignleft"/>
                                        <div class="slide-cnt">
                                            <h2>{!! nl2br(e($homeslider->heading)) !!}</h2>
                                            <div class="textalign" style="">
                                                <p>{{ $homeslider->subheading }}</p>
                                                <a href="{{ $homeslider->buttonlink }}" class="red-btn"> {!! $homeslider->buttontext !!} </a>
                                                <div class="notetext">
                                                    <p>{{ $homeslider->subtext }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <ul class="flex-direction-nav"><li><a class="prev" href="#">Previous</a></li><li><a class="next" href="#">Next</a></li></ul>
                            <!-- end of flexslider -->
                        </div>
                        <!-- end of slider -->
                    </div>
                    <!-- end of shell -->
                </div>
                <!-- end of header -->
            </div>
            <!-- end of wrapper -->
        </div>
        <!--  ends slide portion -->
    </section> 
    
    <!-- ends 2nd layer new carousel -->


    <!-- news links -->
    @include('layouts.frontend.partial.news')
    <!-- /.news links Ends-->

    <!-- home footer links -->
    @include('layouts.frontend.partial.footer_home')
    <!-- /.home footer Ends-->

@endsection

@push('js')
<script src="{{ asset('/frontend/js/custom-home.js') }}"></script>
@endpush

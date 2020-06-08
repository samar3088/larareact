<footer>
    <div class="row" style="width:95%;margin:auto;margin-top:5px;">
        <h4 style="text-align: center; color: #d52a33; font-weight: bold;">The offers that you can't resist </h4>
        <div class="line line-gray r-hide" style="border-bottom: 1px solid #d52a33 !important;"></div>

        @foreach ($homesliders as $homeslider)

        <div class="col-sm-3">
            <div class="card card-pop">
                <div class="card-body">
                    <span class="iw pull-right " style="float:right;">
                        <a target="_blank" href="{{ $homeslider->buttonlink }}" title="{!! nl2br(e($homeslider->heading)) !!}">
                            <img width="100%" style="float:right;" src="{{Storage::url($homeslider->file) }}">
                        </a>
                    </span>
                    <a style="color:#d52a33" target="_blank" href="{{ $homeslider->buttonlink }}" title="{!! nl2br(e($homeslider->heading)) !!}">
                        <span class="card-title vtitle mBtm-10">{!! nl2br(e($homeslider->heading)) !!}</span>
                    </a>
                    <div class="card-text blog-excerpt mBtm-5">{{ $homeslider->subheading }}</div>
                    <span class="blog-date"> {!! $homeslider->buttontext !!} </span>
                </div>
            </div>
        </div>

        @endforeach        
    </div>

    <div class="footer-bg">
        <div class="container footer-cont">
            <div class="container" style="padding:0;"></div>
            <div class="r-show">
                <div class="container" style="padding:0;" align="center">
                    <ul class="list-unstyled footer-gray">
                        <li>
                            <a href="https://bit.ly/2UKzyNk" style="margin-right: 16px;">
                                <img style="width: 98px;height: 28px;" src="{{ asset('/frontend/images/appstore.webp') }}" alt="Partner app link" border="0" align="middle">
                            </a>
                            <a href="https://bit.ly/2UKzyNk">
                                <img src="{{ asset('/frontend/images/playstores.webp') }}" alt="Partner app link" border="0" align="middle">
                            </a>
                        </li>
                        <i style="font-size:10px;">Download Tosshead Partner App</i>
                    </ul>
                    <h5 style="font-size:10px;color: #b4b4b4;margin-bottom: 26px;">© 2020 Tosshead</h5>
                </div>
            </div>
            <div class="r-hide r-hide-tab">
                <h4 style="text-align: center; color: #d52a33; font-weight: bold;">WE ARE UNIQUE</h4>
                <div class="line line-gray r-hide" style="border-bottom: 1px solid #d52a33 !important;"></div>

                <!-- starts footer 1 -->
                <div class="row">
                    <div class="col-lg-3 footer-keys abt-links" style="margin-bottom: 5px;">
                        <h4 style="color: #d52a33 !important;;">Important Links</h4>
                        <ul class="list-unstyled footer-gray">
                            <li><a class="color_services" href="/">Home</a></li>
                            <li><a class="color_services" target="_blank" href=""> Contact Us</a></li>
                            <li><a class="color_services" target="_blank" href="">About Us</a></li>
                            <li><a class="color_services" target="_blank" href="">Terms of Use </a></li>
                            <li><a class="color_services" target="_blank" href="#">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 footer-keys abt-links">
                        <h4 style="color: #d52a33 !important;;">Event Planning Services</h4>
                        <ul class="list-unstyled footer-gray">
                            <li><a class="color_services" target="_blank" href="">Event Equipment Rental</a></li>
                            <li><a class="color_services" target="_blank" href="">Wedding Planning Services </a></li>
                            <li><a class="color_services" target="_blank" href=""> Birthday Decorations </a></li>
                            <li style="background-color: #e5e6e8; font-weight: bold; width: 109px; border-radius: 4px;padding-left: 5px;"><a class="color_services" target="_blank" href="">Event Packages</a></li>
                            <li><a class="color_services" target="_blank" href="">Banquet Hall</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 footer-keys abt-links">
                        <h4 style="color: #d52a33 !important;">Popular Links</h4>
                        <ul class="list-unstyled footer-gray">
                            <li><a class="color_services" target="_blank" href="">Birthday Party Package</a></li>
                            <li><a class="color_services" target="_blank" href="">Corporate Conference Package</a></li>
                            <li><a class="color_services" target="_blank" href="">Dandiya Raas Package</a></li>
                            <li><a class="color_services" target="_blank" href="">DJ Party Package</a></li>
                            <li><a class="color_services" target="_blank" href="">Wedding Entertainment </a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 footer-keys abt-links">
                        <h4 style="color: #d52a33 !important;;">Important Links</h4>
                        <ul class="list-unstyled footer-gray">
                            <li><a class="color_services" target="_blank" href="">A - Z Event Essentials </a></li>
                            <li><a class="color_services" target="_blank" href=""> Party Entertainment </a></li>
                            <li><a class="color_services" target="_blank" href="">Award Ceremony </a></li>
                            <li><a class="color_services" target="_blank" href="">Customised Event Planners</a></li>
                            <li><a class="color_services" target="_blank" href="">Event Decor Essentials</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 footer-keys mt20">
                        <ul class="list-unstyled footer-gray applogo">
                            <li>
                                <a target="_blank" rel="nofollow" href="https://shorturl.at/HNZ16">
                                    <button class=""><img style="width: 150px;" src="{{ asset('/frontend/images/playstore1.jpg') }}" alt="Partner app link" border="0" align="middle">
                                        <span style="font-weight: bold; font-size: 20px; color: #333;"></span>
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a rel="nofollow" target="_blank" href="https://shorturl.at/efFS1">
                                    <button class=""><img style="" src="{{ asset('/frontend/images/appstorelogo.png') }}" alt="Partner app link" border="0" align="middle">
                                        <span style="font-weight: bold; font-size: 20px; color: #333;"></span>
                                    </button>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- ends footer 1  -->

                <div class="line line-gray r-hide" style="border-bottom: 1px solid #d52a33 !important;"></div>

                <div class="row pds-10">
                     <h2 class="ftco-footer">
                         <a href="{{ route('website.equipments') }}" alt="Event Equipment and Essential" title="Event Equipment and Essential" class="eee">Event Equipment and Essential</a>
                    </h2>
                    <ul class="list-unstyled1 equips">
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="DJ Machine" title="DJ Machine">DJ Machine</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="LED Wall" title="LED Wall">LED Wall</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Sound System" title="Sound System">Sound System</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Karaoke Machine" title="Karaoke Machine">Karaoke Machine</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Stage Setup" title="Stage Setup">Stage Setup</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Lightings" title="Lightings">Lightings</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Parcans" title="Parcans">Parcans</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Generator" title="Generator">Generator</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Stalls" title="Stalls">Stalls</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Canopy" title="Canopy">Canopy</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Mic" title="Mic">Mic</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Cordless Mic" title="Cordless Mic">Cordless Mic</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Short through Projector" title="Short through Projector">Short through Projector</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Long through Projector" title="Long through Projector">Long through Projector</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Spot Lights" title="Spot Lights">Spot Lights</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Truss" title="Truss">Truss</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Anchor" title="Anchor">Anchor</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="DJ" title="DJ">DJ</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Photographer" title="Photographer">Photographer</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Videographer" title="Videographer">Videographer</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Dancers" title="Dancers">Dancers</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Smoke Machine" title="Smoke Machine">Smoke Machine</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Laser" title="Laser">Laser</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Hostess" title="Hostess">Hostess</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Promoters" title="Promoters">Promoters</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="TV" title="TV">TV</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Red Carpet" title="Red Carpet">Red Carpet</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Digital Audio Mixer" title="Digital Audio Mixer">Digital Audio Mixer</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Speakers" title="Speakers">Speakers</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Banquet Chairs with Cloth" title="Banquet Chairs with Cloth">Banquet Chairs with Cloth</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Digital Switcher" title="Digital Switcher">Digital Switcher</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Balloon Decoration" title="Balloon Decoration">Balloon Decoration</a></li>
                        <li><a href="{{ route('website.equipments') }}" class="py-2 d-block" alt="Games" title="Games">Games</a></li>
                        <li><a href="{{ route('website.food') }}" class="py-2 d-block" alt="Food" title="Food">Food</a></li>
                        <li><a href="{{ route('website.banquets') }}" class="py-2 d-block" alt=">Banquet Halls" title=">Banquet Halls">Banquet Halls</a></li>
                        <li><a href="https://zyppys.com/" target="_blank" class="py-2 d-block lastset" alt="Cabs" title="Cabs">Cabs and many more</a></li>
                    </ul>
                </div>
            </div>
            {{--  <-- r hide ends -->  --}}
        </div>

        <div class="container" style="height: 55px; background-color: #d52a33; width: 100%;">
            <div style="margin-top: 10px;">
                <div class="col-md-4" style="margin-top: 5px;">
                    <span class="r-hide r-hide-tab" style="line-height:20px; float:left; color:#FFF; font-size: 17px; font-weight: bold;">© 2020 <a style="color:#FFF;" href="#"> Tosshead </a> | All right reserved</span><br><br>
                </div>
                <div class="r-hide r-hide-tab col-md-6" style="float: right;">
                    <div class="col-md-6" style="margin-top: 5px;">
                        <span style="font-size: 16px !important; font-weight: bold; float: right; color: #fff;">FOLLOW US ON SOCIAL MEDIA</span>
                    </div>
                    <span class="social-box" style="display: flex; margin-right: 20px;">
                        <a title="Tosshead Facebook" target="_blank" href="https://www.facebook.com/tossheadevents"><i class="social fa fa-facebook-square fa-3x social-fb"></i></a>
                        <a title="Tosshead Twitter" target="_blank" href="https://twitter.com/tossheadevents"><i class="social fa fa-twitter-square fa-3x social-tw"></i></a>
                        <a title="Tosshead LinkedIn" target="_blank" href="https://www.linkedin.com/company/tosshead-events-india-pvt-ltd/"><i class="social fa fa-linkedin-square fa-3x social-li"></i></a>
                        <a title="Tosshead Instagram" target="_blank" href="https://www.instagram.com/tosshead/"><i class="social fa fa-instagram  fa-3x social-li"></i></a>
                    </span>
                </div>
                <div class="catr-show text-center">© 2020 <a href="#"   style="color:#FFF;">Tosshead</a> | All right reserved</div>
                <div class="catr-show text-center col-md-4">
                    <div class="">
                        <span class="fa fa-phone font-white" aria-hidden="true"></span>
                        <a href="tel:+918470804805" class="font-white">+91-8470804805</a>
                    </div>
                </div>
                <div class="social-box catr-show" style="text-align:center; float:none !important; margin-top:20px;">
                    <a title="Tosshead Facebook" target="_blank" href="https://www.facebook.com/tossheadevents"><i class="social fa fa-facebook-square fa-3x social-fb"></i></a>
                    <a title="Tosshead Twitter" target="_blank" href="https://twitter.com/tossheadevents"><i class="social fa fa-twitter-square fa-3x social-tw"></i></a>
                    <a title="Tosshead LinkedIn" target="_blank" href="https://www.linkedin.com/company/tosshead-events-india-pvt-ltd/"><i class="social fa fa-linkedin-square fa-3x social-li"></i></a>
                    <a title="Tosshead Instagram" target="_blank" href="https://www.instagram.com/tosshead/"><i class="social fa fa-instagram  fa-3x social-li"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

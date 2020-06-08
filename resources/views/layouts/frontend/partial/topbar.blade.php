<header id="top">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div id="header-main" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <nav id="nav1" class="navbar navbar-default navbar-fixed-top" style="display: block;">
                <div id="navbar1" class="container-fluid black-bg black_bg_text">
                    <div class="container" style="height:35px;">
                        <div class="col-lg-2 col-md-2 col-sm-2 pad-none headupper_row"></div>
                        <div class="col-lg-9 col-md-9 col-sm-9 pad-none headupper_row">
                            <!-- ticker starts-->
                                    <div class="ticker-container">
                                        <div class="ticker-caption">
                                            <p>Hot offers</p>
                                        </div>
                                        <ul>
                                            @forelse ($coupon_texts as $coupon_text)
                                                <div>
                                                    <li><span>{!! $coupon_text['description'] !!}</span></li>
                                                </div>
                                            @empty
                                                <div>
                                                    <li><span>Get Rs.250 /- off for Rs.5000 /- & above use code TH250 &ndash; Limited Period Offer</span></li>
                                                </div>
                                                <div>
                                                    <li><span>Get Rs.500 /- off for Rs.10,000 /- & above use code TH500 &ndash; Limited Period Offer</span></li>
                                                </div>
                                            @endforelse
                                        </ul>
                                    </div>
                            <!-- ticker ends -->
                        </div>
                    </div>
                </div>
            </nav>
            <nav id="nav2" class="navbar navbar-default navbar-fixed-top navTop">
                <div class="container" style="margin-top: 10px;">
                    <div class="navbar-header page-scroll col-lg-3 pad-none" style="width:20%;">
                        <a class="navbar-brand" href="{{ route('homepage') }}">
                            <img class=" lazyloaded" alt="VenueLook" data-src="{{ asset('/frontend/images/logo.png') }}" src="{{ asset('/frontend/images/logo.png') }}">
                        </a>
                    </div>
                    <div id="suggest_right">
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1 col-lg-3">
                            <ul class="nav navbar-nav navbar-right pad-top-8 nav_mar_new" style="padding-top: 5px !important;">
                                <li class="hidden">
                                    <a rel="nofollow" href="#page-top"></a>
                                </li>
                                <li class="page-scroll">
                                    <div class="complete">
                                        <a href="{{ route('homepage') }}" title="Tosshead Homepage">Home</a>
                                    </div>
                                </li>
                                <li class="page-scroll">
                                    <div class="complete">
                                        <a href="{{ route('homepage') }}#about" title="About Tosshead">About Us</a>
                                    </div>
                                </li>
                                <li class="page-scroll">
                                    <div class="complete">
                                        <a href="{{ route('website.terms') }}" title="Terms Of Use">Terms Of Use</a>
                                    </div>
                                </li>
                                <li class="page-scroll">
                                    <div class="complete">
                                        <a href="{{ route('website.faq') }}" title="Events">FAQ</a>
                                    </div>
                                </li>
                                <li class="page-scroll">
                                    <div class="complete">
                                        <a target="_blank" href="mailto:support@tosshead.com" title="Tosshead Events">support@tosshead.com</a>
                                    </div>
                                </li>
                                <li class="page-scroll">
                                    <div class="complete">
                                        <a href="tel:+918448444942" class="">+91-8448444942</a>
                                    </div>
                                </li>
                                @if (Request::path() == 'birthdays')
                                    <li class="page-scroll">
                                        <button id="myBtn" class="btn btn-danger red-btn suggest_new" data-toggle="modal" data-target="#myModal" >Upload Image & Get Quote</button>
                                    </li>
                                @else
                                    <li class="page-scroll">
                                        <button id="myBtn" class="btn btn-danger red-btn suggest_new" data-toggle="modal" data-target="#myModal">Enquire Now</button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<!-- start footer --> 
<div class="footer">
    <div class="container">
        <div class="about-us-foot">
            <img src="{{ asset('/frontend/mobile/images/logo-white.png') }}" alt=""/>
            <p>India's Largest Online Event Equipment Booking & Execution Platform </p>
        </div>
        <div class="social-media">
            <a href="https://www.facebook.com/tossheadevents"><i class="fa fa-facebook"></i></a>
            <a href="https://twitter.com/tossheadevents"><i class="fa fa-twitter"></i></a> 
            <a href="https://www.linkedin.com/company/tosshead-events-india-pvt-ltd"><i class="fa fa-linkedin"></i></a> 
            <a href="https://www.instagram.com/tosshead/"><i class="fa fa-instagram"></i></a> 
        </div>
        <div class="copyright">
            <span>© 2020 All Right Reserved</span>
        </div>
    </div>
</div>
<!-- /.footer Ends-->
<!-- Footer Nav-->
@if (Request::path() == '/')
<div class="footer-nav-area home-page" id="footerNav">
    <div class="suha-footer-nav h-100">
        <ul class="h-100 d-flex align-items-center justify-content-between">
            <li class="active homefooter">
                <a href="{{ route('homepage') }}" class="incrs">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </a>
            </li>
            <li class="thirdfooter">
                <a href="{{ route('website.faq') }}" class="incrs2">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
            </li>
            <li class="cartfooter cartssfoort"> 
                @if (Request::path() == 'weddings')
                    <a href="{{ route('mobilecart', 'wedding') }}" class="incrs3">
                        <div class="countersup"><span id="total_cart_items">0</span></div>
                        <div class="datacart">
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        </div>
                    </a>                        
                @else
                    <a href="{{ route('mobilecart', 'general') }}" class="incrs3">
                        <div class="countersup"><span id="total_cart_items">0</span></div>
                        <div class="datacart">
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        </div>
                    </a>                         
                @endif                   
            </li>
            <li class="mailfooter">
                <a href="mailto:support@tosshead.com" style="">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                </a>
            </li>
            <li class="phonefooter">
                <a href="tel:+918448444942" style="" class="incrs3">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
@elseif (Request::path() == 'checkout' || Request::is('mobilecart/*')  || Request::is('payment/*'))
<div class="footer-nav-area pagefooter withcart" id="footerNav">
    <div class="suha-footer-nav h-100">
       <ul class="h-100 d-flex align-items-center justify-content-between">
            <li class="homefooter">
                <a href="{{ route('homepage') }}" class="incrs">
                <i class="fa fa-home" aria-hidden="true"></i>
                </a>
            </li>
            <li class="secondfooter">
                <a href="mailto:support@tosshead.com" style="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                </a>
            </li>
            <li class="phonefooter">
                <a href="tel:+918448444942" style="" class="incrs3">
                <i class="fa fa-phone" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
@else
<div class="footer-nav-area pagefooter withcart" id="footerNav">
    <div class="suha-footer-nav h-100">
        <ul class="h-100 d-flex align-items-center justify-content-between">
            <li class=" homefooter">
                <a href="{{ route('homepage') }}" class="incrs">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </a>
            </li>
            <li class="secondfooter">
                <a href="mailto:support@tosshead.com" style="" class="incrs2">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                </a>
            </li>
            <li class="cartfooter cartssfoort">
                @if (Request::path() == 'weddings')
                    <a href="{{ route('mobilecart', 'wedding') }}" class="incrs3">
                        <div class="countersup"><span id="total_cart_items">0</span></div>
                        <div class="datacart">
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        </div>
                    </a>                        
                @else
                    <a href="{{ route('mobilecart', 'general') }}" class="incrs3">
                        <div class="countersup"><span id="total_cart_items">0</span></div>
                        <div class="datacart">
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        </div>
                    </a>                         
                @endif
            </li>
            <li class="phonefooter">
                <a href="tel:+918448444942" style="" class="incrs3">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
@endif
<!-- /.Footer Nav Ends-->
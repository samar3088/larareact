@if (Request::path() == '/')
<div class="header-area" id="headerArea">
   <div class="container h-100 d-flex align-items-center justify-content-between">
      <!-- Logo Wrapper-->
      <div class="logo-wrapper" id="home-page">
         <a href="{{ route('homepage') }}">
            <img src="{{ asset('/frontend/mobile/images/logo.png') }}" alt=""/>
         </a>
      </div>
   </div>
</div>
@elseif (Request::path() == 'faq' || Request::path() == 'packages' || Request::path() == 'checkout' || Request::is('mobilecart/*') || Request::is('payment/*'))
<div class="header-area" id="headerArea">
   <div class="container h-100 d-flex align-items-center justify-content-between">
      <a href="{{ url()->previous() }}">
         <div id="qaBack" class="QuestionHeader__backDiv--2TbSZ">
            <span data-icon=""></span>
         </div>
      </a>
      <!-- Logo Wrapper-->
      <div class="logo-wrapper"><a href="{{ route('homepage') }}"><img src="{{ asset('/frontend/mobile/images/logo.png') }}" alt=""/></a></div>
      <!-- Search Form-->
      <div class="flex-wrap" id="">
      </div>
   </div>
</div>

@else
<!-- Header Area-->
<div class="header-area" id="headerArea">
   <div class="container h-100 d-flex align-items-center justify-content-between">
      <a href="{{ url()->previous() }}">
         <div id="qaBack" class="QuestionHeader__backDiv--2TbSZ">
            <span data-icon=""></span>
         </div>
      </a>
      <div class="logo-wrapper"><a href="{{ route('homepage') }}"><img src="{{ asset('/frontend/mobile/images/logo.png') }}" alt=""/></a></div>
      <!-- Search Form-->
      <!-- Navbar Toggler-->
      <div class="suha-navbar-toggler d-flex justify-content-between flex-wrap" id="suhaNavbarToggler"> 
         <i class="fa fa-filter" aria-hidden="true"></i>
         <p class="toppos-filter">Filter</p>
      </div>
   </div>
</div>
@endif
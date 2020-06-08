<ul class="breadcrumb" itemscope="" itemtype="https://schema.org/BreadcrumbList">
   @if (Request::path() != 'packages')
    <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
       <a href="{{ route('website.packages') }}/" itemtype="https://schema.org/Thing" itemprop="item" title="Event Packages" href="">
          <img style="width:36px;margin-top: -5px;" itemprop="image" src="{{ asset('/frontend/images/packs.png') }}">
          <div class="headtext">Packages</div>
          <span itemprop="name" style="display: none;">Packages</span>
       </a>
       <meta itemprop="position" content="1">
    </li>
    @endif
    @if (Request::path() != 'equipments')
    <li itemprop="itemListElement" itemscope="" itemtype=" ">
       <a href="{{ route('website.equipments') }}" itemtype="https://schema.org/Thing" itemprop="item" title="Equipments" href="">
          <img style="width:36px;margin-top: -5px;" itemprop="image" src="{{ asset('/frontend/images/equipments.png') }}">
          <div class="headtext movele-5">Equipments</div>
          <span itemprop="name" style="display: none;">Equipments</span>
       </a>
       <meta itemprop="position" content="2">
    </li>
    @endif
    @if (Request::path() != 'weddings')
    <li itemprop="itemListElement" itemscope="" itemtype=" ">
       <a href="{{ route('website.weddings') }}" itemtype="https://schema.org/Thing" itemprop="item" title="Wedding" href="">
          <img style="width:36px;margin-top: -5px;" itemprop="image" src="{{ asset('/frontend/images/wedding.png') }}">
          <div class="headtext movele-11">Wedding</div>
          <span itemprop="name" style="display: none;">Wedding</span>
       </a>
       <meta itemprop="position" content="3">
    </li>
    @endif
    @if (Request::path() != 'birthdays')
    <li itemprop="itemListElement" itemscope="" itemtype=" ">
      <a href="{{ route('website.birthdays') }}" itemtype="https://schema.org/Thing" itemprop="item" title="Birthday" href="">
         <img style="width:36px;margin-top: -5px;" itemprop="image" src="{{ asset('/frontend/images/birthday.png') }}">
         <div class="headtext movele-11">Birthday</div>
         <span itemprop="name" style="display: none;">Birthday</span>
      </a>
      <meta itemprop="position" content="3">
   </li>
    @endif
 </ul>

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

    <div class="section packages mainpack">
        <div class="container">
           <div class="row">
               <div class="col s12 found-status">
                  <h1>{{ $page_items->heading }}</h1>
                 <p class="collapse22">
                     {{ $page_items->content }}
                 </p>
               </div>
            </div>
            @foreach ($packages as $package)
            
               <!-- item 1 -->
               <div class="flash-sale-card">
                  <div class="card card-pop">
                     <div class="card-body">
                        <div class="card-img">
                        <center><img src="{{ Storage::url($package->package_image) }}"></center>
                        </div>
                        <div class="card-contents pckdts">
                        <h2><a href="packages/{{ $package->id }}">{{ $package->package_name }}</a></h2>
                           <p class="desccontents">{{ $package->description }}</p>
                           <div style="clear: both;"></div>
                           <!-- start button row -->
                           <a class="" rel="nofollow" style="text-decoration:none" href="packages/{{ $package->id }}">
                               <div class="row final">
                                  <div class="col s12">
                                     <!-- starts enquire now -->
                                     <div class="col s12">
                                        <div id="book-inquiry" class="marg">
                                           Book Now
                                        </div>
                                     </div>
                                     <!-- ends enquire now -->
                                  </div>
                               </div>
                           </a>
                           <!-- ends button row -->
                        </div>
                     </div>
                  </div>
               </div>
               <!-- item 1 ends -->

            @endforeach

         </div>
      </div>

   <!-- /.ends page content -->
   <div style="clear: both;"></div>

</div>    
<!-- /. Ends Page Wrapper-->

@endsection

@push('js')
<script src="{{ asset('/frontend/mobile/js/active.js') }}"></script>
<script src="{{ asset('/frontend/mobile/js/common.js') }}"></script>
<script src="{{ asset('/frontend/js/cart.js') }}"></script>
@endpush

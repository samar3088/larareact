
@include('layouts.mobile.partial.header')
<body>
    <div class="cover-spin "> 
        <img src="{{ asset('/frontend/mobile/images/icons.png') }}" alt=""/>
     </div>
     <!-- Header Area-->
     <!-- topbar-->
     @include('layouts.mobile.partial.topbar')
     <!-- /.topbar Ends-->
    <div class="pagecontent page-content-wrapper">

        @yield('content')

        <!-- news links -->
            @include('layouts.mobile.partial.footer')
        <!-- /.news links Ends-->

    </div>

    @stack('js')
</body>

</html>


@include('layouts.frontend.partial.header')
    <body>
        <div class="pagecontent">

            <!-- topbar-->
            @include('layouts.frontend.partial.topbar')
            <!-- /.topbar Ends-->

            <!-- All data to be coming Here -->

                <div class="content">
                    @yield('content')
                </div>

            <!-- /.All data to be coming Here ends -->

            <a href="#" id="scroll-top" title="Back to Top" style="display: none;"><span class="fa fa-arrow-up fa-2x"></span></a>

            <!-- Modal -->
                @if (Request::path() == 'birthdays')
                    @include('layouts.frontend.partial.modal_bday')
                @else
                    @include('layouts.frontend.partial.modal_home')
                @endif
            <!-- /.Modal Ends-->
        </div>

    </body>

</html>

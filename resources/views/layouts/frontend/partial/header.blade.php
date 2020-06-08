<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anton|Lobster&display=swap" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700"  type="text/css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />

        <!-- Theme style -->
        <link href="{{ asset('/frontend/css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/jquery-ui.css') }}" rel="stylesheet">

        <link href="{{ asset('/frontend/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/ticker-responsive.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/main.css') }}" rel="stylesheet">

        <link href="{{ asset('/frontend/css/jquerysctop.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/js-image-slider.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/flexslider.css') }}" rel="stylesheet">

        <link href="{{ asset('/frontend/css/owl.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/owltheme.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/owltransition.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/slidertestimonial.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/lity.css') }}"  rel="stylesheet">

        @stack('css')
        {{-- <script src="{{ asset('/frontend/js/jquery.min.js') }}"></script> --}}
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="{{ asset('/frontend/js/bootstrap.js') }}"></script>
        <script src="{{ asset('/frontend/js/jquery-ui.js') }}"></script>

        <script src="{{ asset('/frontend/js/ticker.js') }}"></script>
        <script src="{{ asset('/frontend/js/js-image-slider.js') }}"></script>
        <script src="{{ asset('/frontend/js/carousel.min.js') }}"></script>
        <script src="{{ asset('/frontend/js/jquery.flexslider-min.js') }}"></script>
        <script src="{{ asset('/frontend/js/functions.js') }}"></script>
        <script src="{{ asset('/frontend/js/bootstrap-modal.js') }}"></script>
        <script src="{{ asset('/frontend/js/tilt.jquery.min.js') }}"></script>
        <script src="{{ asset('/frontend/js/modalform.js') }}"></script>
        <script src="{{ asset('/frontend/js/lity.js') }}"></script>
        
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

        <script>

            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });

            jQuery.browser = {};
            (function ()
            {
             jQuery.browser.msie = false;
             jQuery.browser.version = 0;
             if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                 jQuery.browser.msie = true;
                 jQuery.browser.version = RegExp.$1;
            }
             })();
        </script>

        <!-- page script -->
        @stack('js')

        <!--[if lt IE 9]>
        <![endif]-->

    </head>

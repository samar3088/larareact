<!DOCTYPE html>
<html lang="en">
   <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,  shrink-to-fit=no">
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Title-->
        <title>@yield('title')</title>
        <!-- Favicon-->
        <link rel="icon" href="{{ asset('/frontend/images/favicon.png') }}">
        <!-- Theme style -->
        <link href="{{ asset('/frontend/mobile/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/mobile/css/custom.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
        <link href="{{ asset('/frontend/mobile/css/jquery-ui.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/frontend/css/lity.css') }}">    
        @stack('css')

        <script rel="prefetch" as="script" src="{{ asset('/frontend/mobile/js/jquery.min.js') }}"></script>
        <script rel="prefetch" as="script" src="{{ asset('/frontend/js/jquery-ui.js') }}"></script>
        {{-- <script rel="prefetch" as="script" src="{{ asset('/frontend/mobile/js/details.js') }}"></script> --}}
        <script rel="prefetch" as="script" src="{{ asset('/frontend/mobile/js/owl_carousel.js') }}"></script>
        <script rel="prefetch" as="script" src="{{ asset('/frontend/mobile/js/custom.js') }}"></script>
        
        <script rel="prefetch" as="script" src="{{ asset('/frontend/js/lity.js') }}"></script>
        <script rel="prefetch" as="script" type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script rel="prefetch" as="script" src="{{ asset('/frontend/js/dataTables.pageLoadMore.min') }}"></script>
        <script>
                $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        </script>
        
        <style type="text/css">
            .flash-sale-wrapper .owl-carousel .owl-stage-outer {
                box-shadow: 2px 2px #888;
            }
        </style>
    </head>
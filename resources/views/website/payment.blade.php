@extends('layouts.frontend.app')
@section('title','Tosshead Homepage')
{{--  @section('pageheading','Dashboard')
@section('cardheading','Tosshead Banquet Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')   --}}

@push('css')
<link href="{{ asset('/frontend/css/custom11.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/commonfiles.css') }}" rel="stylesheet">
        <link href="{{ asset('/frontend/css/searchpage-list.css') }}" rel="stylesheet">
        <style>
            ol li 
            {
                list-style-type: decimal;
                list-style-position: inside;
                margin-bottom:2px;
            }
            th, td {
                word-wrap: break-word !important;
            }
         </style>

@endpush

@section('content')

<!-- Main content comes here -->

<div class="mt-125">

    <div class="container">
        {!! $message !!}
    </div>   

 </div>

<!-- /.Main content ends here -->

    <!-- home footer links -->
    @include('layouts.frontend.partial.footer_common')
    <!-- /.home footer Ends-->

@endsection

@push('js')

   <script src="{{ asset('/frontend/js/custom.js') }}"></script>
   <script src="{{ asset('/frontend/js/cart.js') }}"></script>
   <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    var options = {
        "key": "rzp_live_F8pFCFdSpIlulH",
        "amount": "{{$amountpaypable}}", // 2000 paise = INR 20
        "name": "Tosshead",
        "description": "Equipment Booking With Tosshead",
        "image": "https://tosshead.com/imagestosshead/logo.svg",
        "handler": function (response){
            console.log(response.razorpay_payment_id);
            console.log(response);
            sendmails(response.razorpay_payment_id);
        },
        "prefill": {
            "contact": "{{$cus_mobile}}",
            "email": "{{$cus_email}}"
        },
        "notes": {
            "quotations": "{{$url}}"
        },
        "theme": {
            "color": "#F37254"
        }
    };
    
    var rzp1 = new Razorpay(options);
    $(document).on('click', "#rzp-button1", function(event) {
        rzp1.open();
        event.preventDefault();
    });
    
    function sendmails(value) 
    {
        var payfor = options['notes']['quotations'];
        var paymentreference = value;
        $('.error').hide();

        $.ajax
        ({
            url:'/updatepayment',
            type: "post",
            dataType:"json",
            data: { "payfor": payfor, "paymentreference": paymentreference },
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
                
                if(status == 1)
                {
                    alert('Your Payment towards Event Booking is successful.Thank you');
                    window.location.href = "https://www.tosshead.com/payment/{{$urls}}"; 
                }
                else
                {
                    alert('Sorry the payment cant be made. Please refersh anr try again');
                    window.location.href = "https://www.tosshead.com/payment/{{$urls}}"; 
                }
            },
            error: function(a,b)
            {
                console.log('error in no ajax');
            }
        });                  
    }
    </script>
   

@endpush



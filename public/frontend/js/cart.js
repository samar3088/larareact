function additemstosinglecarts(elementitem)
{
    var particular = ''; var item_price = ''; var item_qty = ''; var net_price = ''; var item_height = ''; var item_width = ''; var pack_desc = ''; var partidays = ''; var trans_type = '';
    var item_type = elementitem.getAttribute("data-itemtype");
    var item_details = elementitem.getAttribute("data-itemdetails");
    var item_id = elementitem.getAttribute("data-id");
    var booking_type = 'cart_single';

    //console.log(item_id);

    var splitted_item_details = item_details.split("^");
    var splitted_item_id = item_id.split("-");
    trans_type = splitted_item_id['0'];

    if(item_type == 'Q')
    {
        particular =  splitted_item_details['3'];
		item_price = isNaN(splitted_item_details['5']) ? '1' : splitted_item_details['5'];
		item_qty = $('#'+item_id).find('input[name=quantity]').val();
		pack_desc = splitted_item_details['8'];
		partidays = $('#'+item_id).find('input[name=partidays]').val();
		if(pack_desc == undefined) {
		    pack_desc = '';
		}
		item_height = '';
        item_width = '';
		net_price = parseInt(item_price*item_qty*partidays);
    }
    else if(item_type == 'S')
    {
        particular =  splitted_item_details['3'];
		item_price = isNaN(splitted_item_details['5']) ? '1' : splitted_item_details['5'];
		item_qty = '';
		pack_desc = splitted_item_details['8'];
		partidays = $('#'+item_id).find('input[name=partidays]').val();
		if(pack_desc == undefined) {
		    pack_desc = '';
        }
        item_height = $('#'+item_id).find('input[name=height]').val();
		item_width = $('#'+item_id).find('input[name=width]').val();
		net_price = parseInt(item_price*item_height*item_width*partidays);
    }

    $.ajax
    ({
        url:'/additemstosinglecart',
        type: "post",
        contentType: "application/json",
        cache:false,
        processData: false,
        dataType:"json",
        data:JSON.stringify({"particular":particular,"item_price":item_price,"item_qty":item_qty,"pack_desc":pack_desc,"partidays":partidays,"item_height":item_height,"item_width":item_width,"net_price":net_price,"trans_type":trans_type,"booking_type":booking_type}),
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;
            var status = response['status'];

            //console.log('status' + status);

            if(status == 1)
            {
                location = '/checkout';
            }
            else
            {
                alert('Error in order booking. Please refersh and try again');
            }
        },
        error: function(a,b)
        {
            alert('Error in order booking. Please refersh and try again');
        }
    });
}

function additemstocartsmobile(elementitem)
{
    var particular = ''; var item_price = ''; var item_qty = ''; var net_price = ''; var item_height = ''; var item_width = '';
    var pack_desc = ''; var partidays = ''; var trans_type = ''; var theme_name = ''; var added_item_type = '';
    var item_type = elementitem.getAttribute("data-itemtype");
    var item_details = elementitem.getAttribute("data-itemdetails");
    var item_id = elementitem.getAttribute("data-id");
    var booking_type = elementitem.getAttribute("data-bookingtype");

    //console.log(item_id);

    var splitted_item_details = item_details.split("^");
    var splitted_item_id = item_id.split("-");
    trans_type = splitted_item_id['0'];

    if(item_type == 'Q')
    {
        particular =  splitted_item_details['3'];
		item_price = isNaN(splitted_item_details['5']) ? '1' : splitted_item_details['5'];
		item_qty = $('#'+item_id).find('input[name=quantity]').val();
		pack_desc = splitted_item_details['8'];
		partidays = $('#'+item_id).find('input[name=partidays]').val();
		if(pack_desc == undefined) {
		    pack_desc = '';
		}
		item_height = '';
        item_width = '';
        net_price = parseInt(item_price*item_qty*partidays);
        theme_name = splitted_item_details['9'];
        added_item_type = 'cart_entry'; //can also be manual_entry when we will implement manaual entry
    }
    else if(item_type == 'S')
    {
        particular =  splitted_item_details['3'];
		item_price = isNaN(splitted_item_details['5']) ? '1' : splitted_item_details['5'];
		item_qty = '';
		pack_desc = splitted_item_details['8'];
		partidays = $('#'+item_id).find('input[name=partidays]').val();
		if(pack_desc == undefined) {
		    pack_desc = '';
        }
        item_height = $('#'+item_id).find('input[name=height]').val();
		item_width = $('#'+item_id).find('input[name=width]').val();
        net_price = parseInt(item_price*item_height*item_width*partidays);
        theme_name = splitted_item_details['9'];
        added_item_type = 'cart_entry'; //can also be manual_entry when we will implement manaual entry
    }

    $.ajax
    ({
        url:'/additemstocart',
        type: "post",
        contentType: "application/json",
        cache:false,
        processData: false,
        dataType:"json",
        data:JSON.stringify({"particular":particular,"item_price":item_price,"item_qty":item_qty,"pack_desc":pack_desc,"partidays":partidays,"item_height":item_height,"item_width":item_width,"net_price":net_price,"trans_type":trans_type,"booking_type":booking_type,"theme_name":theme_name,"added_item_type":added_item_type}),
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;
            var status = response['status'];
            var cart_items = response['cart_items'];
            var counter = 0;

            if(status == 1)
            {
                $("#result-"+item_id).text('Item added to cart').show().delay(1000).fadeOut();

                if(booking_type == 'cart_wedding')
                {
                    var cart_total_items = cart_items.items;
                    counter = cart_total_items.length;
                }
                else if(booking_type == 'cart_general')
                {
                    //console.log('cart_general');
                    $.each(cart_items, function( key, value ) {
                        $.each(value, function( item_key, item_value ) {
                            counter++;
                        });
                    });
                }
            }
            else
            {
                $("#result-"+item_id).text('Sorry item not added. Try Again').show().delay(1000).fadeOut();
            }
            $('#total_cart_items').text(counter);
        },
        error: function(a,b)
        {
            $("#result-"+item_id).text('Sorry item not added. Try Again').show().delay(1000).fadeOut();
        }
    });
}

$(document).on('click', "#btnSendotp", function(event) {
    event.preventDefault();

    var name = encodeURIComponent($('#cus_name').val());
    var email = encodeURIComponent($('#cus_email').val());
    var mobile = encodeURIComponent($('#cus_mobile').val());
    var event_date = encodeURIComponent($('#demos').val());
    var description = encodeURIComponent($('#cus_description').val());

    var error = '';

    if(name.trim() == '' || name == undefined)
    {
        error = "Please enter name";
        $('#cus_name').focus();
        //$("#cus_name").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(email.trim() == '' || email == undefined)
    {
        error = "Please enter email";
        $('#cus_email').focus();
        //$("#cus_email").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(mobile.trim() == '' || mobile == undefined)
    {
        error = "Please enter mobile";
        $('#cus_mobile').focus();
        //$("#cus_mobile").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(event_date.trim() == '' || event_date == undefined)
    {
        error = "Please enter event date";
        $('#event_date').focus();
        //$("#event_date").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if (!$('input#terms_and_conditions').is(':checked')) 
    {
        error = "Please agree to terms and conditions";
        $('#terms_and_conditions').focus();
        //$("#terms_and_conditions").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else
    {
        var checkoutForm = document.getElementById('single_checkout_form');

        $.ajax
        ({
            url:'/getotp',
            type: "post",
            dataType:"json",
            /* cache : false,
            processData: false, */
            data: { "name": name, "email": email, "mobile": mobile,"description": description ,"event_date": event_date },
            //data: new FormData(checkoutForm),
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
                var opt_input = response['opt_input'];
    
                //console.log('status' + status);
    
                if(status == 1)
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Otp has been sent to the mobile.</span></div>');
                    $('#btnSendotp').hide();
                    $('.otptext').fadeIn(3000).show();
                    $('#otp-s').fadeIn(3000).show();

                    $('html, body').animate({ scrollTop: $(".otptext").offset().top - 120 }, 2000);
                    console.log('opt_input: ' + opt_input);
                }
                else
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Sorry the otp can not be sent now. Please refersh and try</span></div>');
                }
            },
            error: function(a,b)
            {
                $('#form_result').html('<div class="alert alert-danger"><span>Sorry the otp can not be sent now. Please refersh and try</span></div>');
            }
        });
    }
});

$(document).on('click', "#resendotp", function(event) {
    event.preventDefault();
    var name = encodeURIComponent($('#cus_name').val());
    var email = encodeURIComponent($('#cus_email').val());
    var mobile = encodeURIComponent($('#cus_mobile').val());
    var event_date = encodeURIComponent($('#demos').val());
    var description = encodeURIComponent($('#cus_description').val());

    var error = '';
    //$('.personaltext input').removeClass("itemfocus");

    if(name.trim() == '' || name == undefined)
    {
        error = "Please enter name";
        $('#cus_name').focus();
        //$("#cus_name").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(email.trim() == '' || email == undefined)
    {
        error = "Please enter email";
        $('#cus_email').focus();
        //$("#cus_email").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(mobile.trim() == '' || mobile == undefined)
    {
        error = "Please enter mobile";
        $('#cus_mobile').focus();
        //$("#cus_mobile").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(event_date.trim() == '' || event_date == undefined)
    {
        error = "Please enter event date";
        $('#event_date').focus();
        //$("#event_date").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if (!$('input#terms_and_conditions').is(':checked')) 
    {
        error = "Please agree to terms and conditions";
        $('#terms_and_conditions').focus();
        //$("#terms_and_conditions").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else
    {
        var checkoutForm = document.getElementById('single_checkout_form');

        $.ajax
        ({
            url:'/resentotp',
            type: "post",
            dataType:"json",
            /* cache : false,
            processData: false, */
            //data: new FormData(checkoutForm),
            data: { "name": name, "email": email, "mobile": mobile,"description": description ,"event_date": event_date },
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
    
                //console.log('status' + status);
    
                if(status == 1)
                {
                    $('#form_result1').html('<div class="alert alert-danger"><span>Otp has been sent to the mobile.</span></div>');
                }
                else
                {
                    $('#form_result1').html('<div class="alert alert-danger"><span>Sorry the otp can not be sent now. Please refersh and try</span></div>');
                    $('.btnSendquote').attr("disabled",false);
                    $(".resnet").removeClass("disabled");
                }
            },
            error: function(a,b)
            {
                $('#form_result').html('<div class="alert alert-danger"><span>Sorry the otp can not be sent now. Please refersh and try</span></div>');
            }
        });
    }
});

function applycoupon()
{
    var promocode = encodeURIComponent($('#promocode').val());

    var error = '';

    if(promocode.trim() == '' || promocode == undefined)
    {
        error = "Please enter coupon code";
        $('#promocode').focus();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
	    return false;
    }
    else
    {
            $.ajax
            ({
                url:'/applypromocode',
                type: "post",
                dataType:"json",
                data: { "promocode": promocode },
                success: function(ajaxresponse,status)
                {
                    var response = ajaxresponse;
                    var status = response['status'];
        
                    if(status == 1)
                    {
                        $('#form_result').html('<div class="alert alert-danger"><span>Promocode applied.</span></div>');
                    }
                    else if(status == 2)
                    {
                        $('#form_result').html('<div class="alert alert-danger"><span>Amount not matches the minimal value required.</span></div>');
                    }
                    else if(status == 3)
                    {
                        $('#form_result').html('<div class="alert alert-danger"><span>Incorrect Promocode.</span></div>');
                    }
                },
                error: function(a,b)
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Amount not matches the minimal value required.</span></div>');
                }
            });
    }
}

$(document).on('click', "input.plus,input.minus", function(event) {
    event.preventDefault();
    var item_id = ''; var price = ''; var item_type_split = ''; var item_type = ''; var partidays = ''; var item_qty = ''; var item_height = ''; var item_width = ''; var amount_total = 0;
    item_id = this.getAttribute("data-mainid");
    price = this.getAttribute("data-price");
    item_type_split = item_id.split("-");
    item_type = item_type_split['1'];

    if(item_type == 'Q')
    {
        partidays = $('#'+item_id).find('input[name=partidays]').val();
        item_qty = $('#'+item_id).find('input[name=quantity]').val();
        amount_total = Math.round(partidays * item_qty * price);
    }
    else if(item_type == 'S')
    {
        partidays = $('#'+item_id).find('input[name=partidays]').val();
        item_height = $('#'+item_id).find('input[name=height]').val();
        item_width = $('#'+item_id).find('input[name=width]').val();
        amount_total = Math.round(partidays * item_height * item_width * price);
    }

    amount_total = moneyFormatIndia(amount_total);    
    $('#'+item_id).find('span.totamonnt').text(amount_total);
    //console.log(amount_total);
});

function changepackagedetails() 
{
    var id = $( "#no_of_guest option:selected" ).val();
    //console.log(id);
    if(id == '') { return false; }
    $.ajax
    ({
        url:'/changepackagedetail',
        type: "post",
        dataType:"json",
        data: { "id": id },
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;  
            var package_price = response['package_price'];
            var package_include = response['package_include'];
            var no_of_days = $( "#no_of_days option:selected" ).val();
            $('#package_price').val(package_price);

            $('#package_include li').remove();
            $('#default_packge_text').hide();
            $('#packages_include_div').show();
            package_include = package_include.split(",");            

            $.each(package_include, function( index, value ) {
                //alert( index + ": " + value );
                //console.log(value);
                $('#package_include').append('<li>'+value+'</li>');
            });

            if(!isNaN(no_of_days))
            {
                var total_package_amount = Math.round(package_price*no_of_days);
                total_package_amount = moneyFormatIndia(total_package_amount);
                $('#total_package_amount').text('₹ '+total_package_amount+'/-');
            }
        },
        error: function(a,b)
        {
            console.log('error in package fetch');
        }
    });

    //finalCalculation();
}

function updatepackagetotal() 
{
    var no_of_days = $( "#no_of_days option:selected" ).val();
    var package_price = $('#package_price').val();

    if(!isNaN(no_of_days) && !isNaN(package_price))
    {
        var total_package_amount = Math.round(package_price*no_of_days);
        total_package_amount = moneyFormatIndia(total_package_amount);
        $('#total_package_amount').text('₹ '+total_package_amount+'/-');
    }

    //finalCalculation();
}

$(document).on('click', ".package_booking_type_mobile", function(event) {
    event.preventDefault();

    var btn_id = $(this).attr('id');
    
    var event_date = encodeURIComponent($('#demos').val());
    var no_of_days = encodeURIComponent($( "#no_of_days option:selected" ).val());
    var package_detail_id = encodeURIComponent($( "#no_of_guest option:selected" ).val());
    var package_price = encodeURIComponent($('#package_price').val());

    var error = '';
    $('.packages_error').text('');

    if(event_date.trim() == '' || event_date == undefined)
    {
        error = "Please enter event date";
        $('#demos').focus();
        $('.evedateserror').text(error);
	    return false;
    }
    else if(no_of_days.trim() == '' || no_of_days == undefined)
    {
        error = "Please enter no of days";
        $('#no_of_days').focus();
        $('.evedayserror').text(error);
	    return false;
    }
    else if(package_detail_id.trim() == '' || package_detail_id == undefined)
    {
        error = "Please enter no of guest";
        $('#no_of_guest').focus();
        $('.noofguestserror').text(error);
	    return false;
    }
    else if(package_price.trim() == '' || package_price == undefined)
    {
        error = "Please enter package price";
        $('#package_price').focus();
        $('.evepriceserror').text(error);
	    return false;
    }
    else
    {
        var trans_type = 'package';
        var passData = 'switchtype=quotesuccessown&event_date='+event_date+'&no_of_days='+no_of_days+'&package_detail_id='+package_detail_id+'&package_price='+package_price+'&dummy='+Math.floor(Math.random()*100032680100);
        var url = '';

        //console.log(btn_id);
        //return false;

        if(btn_id == 'package_book_now_mobile')
        {
            url = '/additemstosinglecartpackage';
        }
        else if(btn_id == 'package_book_cart')
        {
            url = '/additemstocartpackage';
        }

        $.ajax
        ({
            /* url:'/resentotp',
            type: "post",
            dataType:"json",
            data: { "name": name, "email": email, "mobile": mobile,"description": description ,"event_date": event_date}, */

            url:url,type: "post",data: passData, cache: false,dataType: "json",async: false,
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
    
                if(status == 1)
                {
                    if(btn_id == 'package_book_now_mobile')
                    {
                        location = '/checkout';
                    }
                    else if(btn_id == 'package_book_cart')
                    {
                        console.log('items added to cart');
                        $('.imagerghts').fadeOut(2000).hide();
                        $('.cartsrghts').fadeIn(3000).show();
                    }
                }
                else
                {
                    alert('Error in order booking. Please refersh and try again');
                }
            },
            error: function(a,b)
            {
                alert('Error in order booking. Please refersh and try again');
            }
        });
    }
});

$(document).on('click', "#add_more_equipments_mobile", function(event) {
    event.preventDefault();

    var btn_id = $(this).attr('id');
    
    var event_date = encodeURIComponent($('#demos').val());
    var no_of_days = encodeURIComponent($( "#no_of_days option:selected" ).val());
    var package_detail_id = encodeURIComponent($( "#no_of_guest option:selected" ).val());
    var package_price = encodeURIComponent($('#package_price').val());

    var error = '';
    $('.packages_error').text('');

    if(event_date.trim() == '' || event_date == undefined)
    {
        error = "Please enter event date";
        $('#demos').focus();
        $('.evedateserror').text(error);
	    return false;
    }
    else if(no_of_days.trim() == '' || no_of_days == undefined)
    {
        error = "Please enter no of days";
        $('#no_of_days').focus();
        $('.evedayserror').text(error);
	    return false;
    }
    else if(package_detail_id.trim() == '' || package_detail_id == undefined)
    {
        error = "Please enter no of guest";
        $('#no_of_guest').focus();
        $('.noofguestserror').text(error);
	    return false;
    }
    else if(package_price.trim() == '' || package_price == undefined)
    {
        error = "Please enter package price";
        $('#package_price').focus();
        $('.evepriceserror').text(error);
	    return false;
    }
    else
    {
        //console.log('add_more_equipments clicked');	
        var trans_type = 'package';
        var passData = 'switchtype=quotesuccessown&event_date='+event_date+'&no_of_days='+no_of_days+'&package_detail_id='+package_detail_id+'&package_price='+package_price+'&dummy='+Math.floor(Math.random()*100032680100);
        url = '/additemstocartpackage';

        $.ajax
        ({
            url:url,type: "post",data: passData, cache: false,dataType: "json",async: false,
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
    
                if(status == 1)
                {
                    $("#suhaNavbarToggler").click();
                    $(".equipcontents").fadeIn(2000).show();
                }
                else
                {
                    alert('Add package to book additional items');
                }
            },
            error: function(a,b)
            {
                alert('Error in order booking. Please refersh and try again');
            }
        });
    }
});

function showcartpackage()
{
    $(".addeleme").addClass("intro-fix");
    $('.threebtns').fadeOut(2000).hide();
    $('.imagerghts').fadeOut(2000).hide();
    $('#searchformpacksdata').fadeOut(2000).hide();
    $('.cartsrghts').fadeIn(3000).show();
    $('#paging_button').fadeIn(3000).show();
    $('.eventtypessequips').fadeIn(3000).show();
    $('.eventtypess').fadeIn(3000).show();
    $('#searchformequipsdata').fadeIn(3000).show();
    $('#first_show').fadeIn(3000).show();
    $('html, body').animate({
        scrollTop: $("#first_show").offset().top - 100
    }, 2000);
}

function changeToUpperCases(t) {
    var eleVal = document.getElementById(t.id);
    eleVal.value= eleVal.value.toUpperCase();
}

/*---------------button click for single booking-----------------*/

$(document).on('click', "#btnSendquote", function(event) {
    event.preventDefault();
    var entered_otp = encodeURIComponent($('#entered_otp').val());
    
    var allowtrans= allowcheckout();
    if(allowtrans == true)
    {
        $.ajax
        ({
            url:'/checkotp',
            type: "post",
            dataType:"json",
            data: { "entered_otp": entered_otp },
            beforeSend: function()
            {
                $('.btnSendquote').attr("disabled",true);
                $(".resnet").addClass("disabled");
            },
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];

                if(status == 1)
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Thank you for details. Updating details please wait.</span></div>');
                    completesinglecarts();
                }
                else
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Entered OTP does not matches. Please try again.</span></div>');
                    $('.btnSendquote').attr("disabled",false);
                    $(".resnet").removeClass("disabled");
                }
            },
            error: function(a,b)
            {
                $('.btnSendquote').attr("disabled",false);
                $(".resnet").removeClass("disabled");
                $('#form_result').html('<div class="alert alert-danger"><span>Entered OTP does not matches. Please try again.</span></div>');
            }
        });
    }
});

function completesinglecarts()
{
    var allowtrans= allowcheckout();
    if(allowtrans == true)
    {
        var checkoutForm = document.getElementById('single_checkout_form');

        $.ajax
        ({
            url:'/completesinglecart',
            type: "post",
            dataType:"json",
            cache : false,
            processData: false,
            contentType: false,
            data: new FormData(checkoutForm),
            //data: { "name": name, "email": email, "mobile": mobile,"description": description ,"event_date": event_date },
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
                var trans_type = response['trans_type'];
                var quotelink = response['quotelink'];

                if(status == 1)
                {
                    location = quotelink;
                }
                else
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Error in order booking. Please refersh and try again</span></div>');
                    //console.log('in else part');
                    $('.btnSendquote').attr("disabled",false);
                    $(".resnet").removeClass("disabled");
                }             
            },
            error: function(a,b)
            {
                $('#form_result').html('<div class="alert alert-danger"><span>Sorry the otp can not be sent now. Please refersh and try</span></div>');
                $('.btnSendquote').attr("disabled",false);
                $(".resnet").removeClass("disabled");
            }
        });
    }
}
/*---------------button click for single booking ends-----------------*/

/*---------------button click for cart booking ends-----------------*/

$(document).on('click', "#btnSendquoteCart", function(event) {
    event.preventDefault();
    var entered_otp = encodeURIComponent($('#entered_otp').val());
    var allowtrans= allowcheckout();
    if(allowtrans == true)
    {
        $.ajax
        ({
            url:'/checkotp',
            type: "post",
            dataType:"json",
            data: { "entered_otp": entered_otp },
            beforeSend: function()
            {
                $('.btnSendquote').attr("disabled",true);
                $(".resnet").addClass("disabled");
            },
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];

                if(status == 1)
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Thank you for details. Updating details please wait.</span></div>');
                    completecarts();
                }
                else
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Entered OTP does not matches. Please try again.</span></div>');
                    $('.btnSendquote').attr("disabled",false);
                    $(".resnet").removeClass("disabled");
                }
            },
            error: function(a,b)
            {
                $('.btnSendquote').attr("disabled",false);
                $(".resnet").removeClass("disabled");
                $('#form_result').html('<div class="alert alert-danger"><span>Entered OTP does not matches. Please try again.</span></div>');
            }
        });
    }
});

function completecarts()
{
    var allowtrans= allowcheckout();
    if(allowtrans == true)
    {
        var checkoutForm = document.getElementById('single_checkout_form');

        $.ajax
        ({
            url:'/completecart',
            type: "post",
            dataType:"json",
            cache : false,
            processData: false,
            contentType: false,
            data: new FormData(checkoutForm),
            //data: { "name": name, "email": email, "mobile": mobile,"description": description ,"event_date": event_date },
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
                var trans_type = response['trans_type'];
                var quotelink = response['quotelink'];

                if(status == 1)
                {
                    location = quotelink;
                }
                else
                {
                    $('#form_result').html('<div class="alert alert-danger"><span>Error in order booking. Please refersh and try again</span></div>');
                    $('.btnSendquote').attr("disabled",false);
                    $(".resnet").removeClass("disabled");
                }             
            },
            error: function(a,b)
            {
                $('#form_result').html('<div class="alert alert-danger"><span>Sorry the otp can not be sent now. Please refersh and try</span></div>');
                $('.btnSendquote').attr("disabled",false);
                $(".resnet").removeClass("disabled");
            }
        });
    }
}
/*---------------button click for cart booking ends-----------------*/

function setbookingtype(elementitem)
{
    var event_date = $('#demos').val();
    var cart_count = $('div.manual_added_cart').length;
    var booking_type = elementitem.getAttribute("data-typecart");

    if(cart_count > 0 && event_date != '' && event_date != undefined)
    {
            $.ajax
            ({
                url:'/addeventdate',
                type: "post",
                dataType:"json",
                data: { "event_date": event_date,"booking_type": booking_type },
                success: function(ajaxresponse,status)
                {
                    var response = ajaxresponse;
                    var status = response['status'];

                    if(status == 1)
                    {
                        location = '/checkout';
                    }
                    else if(status == 2)
                    {
                        console.log('No Items In Cart');
                    }
                },
                error: function(a,b)
                {
                    console.log('No Items In Cart');                
                }
            });        
    }
    else if(cart_count == 0)
    {
        alert('Please add some items in cart to proceed');
    } 
    else if(event_date == '' || event_date == undefined)
    {
        alert('Please select event date');
        $('#demos').focus();
    }   
}

/*============================written for updating cart in UI comes here ====================*/

function wedding_cart_ui_mobile()
{
        var cart_type = 'weddingcart.items';
        $.ajax
        ({
            url:"/cart/"+cart_type,
            dataType:"json",
            cache: false,
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;

                var status = response['status'];
                var cart_items = response['cart_items'];

                if(status == '1')
                {
                    var counter = 0;
                    $('.page_cart_items .manual_added_cart').remove();
                    $.each(cart_items, function( key, value ) {
                        ++counter;    
                        if(value.item_qty != '' && !(isNaN(value.item_qty)))
                        {
                            var cart_item_to_add = '<div class="col s12 minitotal-prod  manual_added_cart wdngs_cart"><div class="col s1 countercart"><p class="sino"> '+counter+')  </p></div><div class="col s6 minicart-data"><p class="mc-name move-left">'+value.particular+'</p></div> <div class="col s2 mt-2"> <p class="ng-binding">'+value.item_qty+'</p> </div><div class="col s2 mt-2 partcol"> <p class="ng-binding"> '+value.partidays+'</p> </div><div class="col s2 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcartmobile(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                        }
                        else
                        {
                            var cart_item_to_add = '<div class="col s12 minitotal-prod  manual_added_cart wdngs_cart"><div class="col s1 countercart"><p class="sino"> '+counter+')  </p></div><div class="col s6 minicart-data"><p class="mc-name move-left">'+value.particular+'</p></div><div class="col s2 mt-2"> <p class="ng-binding">'+value.item_width+' x '+value.item_height+'</p> </div><div class="col s2 mt-2 partcol"> <p class="ng-binding"> '+value.partidays+'</p> </div><div class="col s2 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcartmobile(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                        }
                        
                        $('.page_cart_items').append(cart_item_to_add);
                    });

                    var cart_length = $('.page_cart_items .manual_added_cart').length;
                    if(cart_length == 0)
                    {
                        $('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                    }

                    console.log(cart_items.length);
                    $('#total_cart_items').text(counter);

                }
                else if(status == '2')
                {
                    $('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                }
            },
            error: function(a,b)
            {
                console.log('No Items In Cart Data');
            }
        });
}

function general_cart_ui_mobile()
{
    var cart_type = 'generalcart';
    $.ajax
    ({
        url:"/cart/"+cart_type,
        dataType:"json",
        cache: false,
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;

            var status = response['status'];
            var cart_items = response['cart_items'];
            var cart_total = 0;

            if(status == '1')
            {
                var counter = 0;
                $('.page_cart_items .manual_added_cart').remove();
                $.each(cart_items, function( key, value ) {
                    $.each(value, function( item_key, item_value ) {
                        ++counter;    
                        cart_total = Math.round(+cart_total + +item_value.net_price);

                        var item_price_comma = moneyFormatIndia(item_value.item_price);
                        var net_price_comma = moneyFormatIndia(item_value.net_price);

                        var cart_item_to_add = '<div class="col s12 minitotal-prod  manual_added_cart"><div class="col s1"><p class="sino"> '+counter+')  </p></div><div class="col s6 minicart-data"><p class="mc-name">'+item_value.particular+'</p><p class="ng-binding">1 day x per unit  ₹ '+item_price_comma+'</p></div><div class="col s3 minicart-data"><p class="amountvalue"> ₹ '+net_price_comma+'</p></div><div class="col s2 minicart-close"><p class="endclose" data-remove="remove-generalcart-'+item_value.trans_type+'-'+item_key+'" onclick="removefromcartmobile(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                        $('.page_cart_items').append(cart_item_to_add);
                    });
                });

                var cart_length = $('.page_cart_items .manual_added_cart').length;
                if(cart_length == 0)
                {
                    $('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                    $('.totalvals').hide();
                    $('.totalamount').text('');
                }
                else
                {
                    cart_total = moneyFormatIndia(cart_total);
                    $('.totalvals').show();
                    $('.totalamount').text('₹ '+cart_total);
                }

                $('#total_cart_items').text(counter);
            }
            else if(status == '2')
            {
                $('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                $('.totalvals').hide();
                $('.totalamount').text('');             
            }
        },
        error: function(a,b)
        {
            //$("#form_result").show().text('Please refresh and try again');
            console.log('No Items In Cart');
        }
    });
}

function removefromcartmobile(elementitem)
{
    var remove_item = elementitem.getAttribute("data-remove");
    var splitted_remove_item = remove_item.split("-");
    var action_type = splitted_remove_item['0'];
    var cart_type = splitted_remove_item['1'];
    var item_type = splitted_remove_item['2'];
    var item_id = splitted_remove_item['3'];

        $.ajax
        ({
            url:'/removecartitem',
            type: "post",
            dataType:"json",
            data: { "item_id": item_id,"item_type": item_type ,"cart_type": cart_type},
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];

                if(status == 1)
                {
                    var cart_length = $('.page_cart_items .manual_added_cart').length;

                    if(cart_length == 1)
                    {
                        location = '/samar/public/';
                        //$('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                    }
                    else
                    {
                        if(cart_type == 'weddingcart')
                        {
                            wedding_cart_ui_mobile();
                        }
                        else if(cart_type == 'generalcart')
                        {
                            general_cart_ui_mobile();
                        }
                    }
                    //$('#form_result').html('<div class="alert alert-danger"><span>Promocode applied.</span></div>');
                }
                else if(status == 2)
                {
                    location = '/samar/public/';
                    console.log('No Items In Cart');
                    //$('#form_result').html('<div class="alert alert-danger"><span>Amount not matches the minimal value required.</span></div>');
                }
            },
            error: function(a,b)
            {
                console.log('No Items In Cart');                
                //$('#form_result').html('<div class="alert alert-danger"><span>Amount not matches the minimal value required.</span></div>');
            }
        });
}
/*============================written for updating cart in UI ends here ====================*/

var dates = ['12/24/2020', '12/25/2020', '12/31/2020','01/01/2021'];

function DisableDates(date) 
{
    var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
    
    for (var i = 0; i < dates.length; i++) 
	{
    	if (new Date(dates[i]).toString() == date.toString()) 
    	{  
			if(new Date(dates[i]).toString() == new Date($('.event_date').val()))
			{
				alert(1); 
			}
			else
			{
				return [true,'highlight'];
			}
		}
	}
	return [true, ''];
	
    //return [dates.indexOf(string) == -1];
}

$(function() {
    $("#demos").datepicker({
        numberOfMonths: 1, 
		minDate: 0, 
		dateFormat: 'dd/mm/yy',
        beforeShowDay: DisableDates,
    });
});

/*var dates = ['06/02/2020','12/24/2020', '12/25/2020', '12/31/2020','01/01/2021'];
$(document).ready(function () {
$("#demos").datepicker({
		numberOfMonths: 1, 
		minDate: 1, 
		dateFormat: 'dd/mm/yy', 
		beforeShowDay: highlightDays 
	}); 
}); 

function highlightDays(date) 
{ 
	for (var i = 0; i < dates.length; i++) 
	{
    	if (new Date(dates[i]).toString() == date.toString()) 
    	{  
			if(new Date(dates[i]).toString() == new Date($('.event_date').val()))
			{
				alert(1); 
			}
			else
			{
				return [true,'highlight'];
			}
		}
	}
	return [true, ''];
}
*/

function specialEvent(){ 
	
	var date = $('.event_date').val();
	if(date == '24/12/2020' || date == '25/12/2020' || date == '31/12/2020' || date == '01/01/2020')
	{
		$('a[href="#ex9"]').click();
		$('#demos').datepicker('setDate', null);
	} 
}

function moneyFormatIndia(num)
{
    num = String(num);
    return num.toString().split('.')[0].length > 3 ? num.toString().substring(0,num.toString().split('.')[0].length-3).replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + num.toString().substring(num.toString().split('.')[0].length-3): num.toString();
}

// Get the input field
/* var input = document.getElementById("myInputTextFields");

input.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.which == 13 || event.keyCode == 13) 
    {
        var searchterm = $('#myInputTextFields').val();
        $("#employee-grid").dataTable().fnDestroy();
        loadTables();
        dataTable.search(searchterm).draw();
    }
}); */

$(document).on('keyup', "#myInputTextFields", function(event) {
    $("#employee-grid").dataTable().fnDestroy();
    loadTables();
    dataTable.search($(this).val()).draw();
 }); 

 $(document).on('click', "#filterlisteditems", function(event) {
    
    var serachterms = $('#myInputTextFields').val();
    if(serachterms != '' && serachterms.length > 1)
    {
        $("#employee-grid").dataTable().fnDestroy();
        loadTables();
        dataTable.search($(this).val()).draw();
    }
    else
    {
        alert('Please enter text to serach');
    }
 }); 

function allowcheckout()
{
    var name = encodeURIComponent($('#cus_name').val());
    var email = encodeURIComponent($('#cus_email').val());
    var mobile = encodeURIComponent($('#cus_mobile').val());
    var event_date = encodeURIComponent($('#demos').val());
    var description = encodeURIComponent($('#cus_description').val());

    var error = '';
    //$('.personaltext input').removeClass("itemfocus");

    if(name.trim() == '' || name == undefined)
    {
        error = "Please enter name";
        $('#cus_name').focus();
        //$("#cus_name").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(email.trim() == '' || email == undefined)
    {
        error = "Please enter email";
        $('#cus_email').focus();
        //$("#cus_email").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(mobile.trim() == '' || mobile == undefined)
    {
        error = "Please enter mobile";
        $('#cus_mobile').focus();
        //$("#cus_mobile").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if(event_date.trim() == '' || event_date == undefined)
    {
        error = "Please enter event date";
        $('#event_date').focus();
        //$("#event_date").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else if (!$('input#terms_and_conditions').is(':checked')) 
    {
        error = "Please agree to terms and conditions";
        $('#terms_and_conditions').focus();
        //$("#terms_and_conditions").addClass("itemfocus");
        $('#form_result').show();
        $('#form_result').html('<div class="alert alert-danger"><span>'+error+'.</span></div>');
        someFunction();
        $('.btnSendquote').attr("disabled",false);
        $(".resnet").removeClass("disabled");
        return false;
    }
    else
    {
        return true;
    }
}

function someFunction() {
    setTimeout(function() {
        $('#form_result').fadeOut('slow');
    }, 5000);
}
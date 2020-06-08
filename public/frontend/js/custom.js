$(document).ready(function() {
    
    $(document).on("keyup", "#item_venue_type", function(ev) {
        var value = $(this).val().toLowerCase();
        $(".item_venue_type .facet__item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $(document).on("keyup", "#item_amenities_type", function(ev) {
        var value = $(this).val().toLowerCase();
        $(".item_amenities_type .facet__item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('.panel-collapse').on('show.bs.collapse', function() {
        $(this).siblings('.panel-heading').addClass('active');
    });

    $('.panel-collapse').on('hide.bs.collapse', function() {
        $(this).siblings('.panel-heading').removeClass('active');
    });
});

$(document).ready(function() {
    $("#myNav").on('shown.bs.modal', function() {
        alert("900");
        $(this).find('#searchBoxinput').focus();
    });
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 250) {
        $('#scroll-top').fadeIn();
    } else {
        $('#scroll-top').fadeOut();
    }
});

$(window).scroll(function() {
    
    if ($(this).scrollTop() > 180) {
        $('#nav2').addClass('navTop2');
        $('#nav2').removeClass('navTop');
        document.getElementById("nav1").style.display = "block";
        $('#search-top-change').addClass('search-top');
    } 
    else {
        document.getElementById("nav1").style.display = "block";
        $('#nav2').removeClass('navTop2');
        $('#nav2').addClass('navTop');
        $('#search-top-change').removeClass('search-top');
    }
});

function openNav() {
    document.getElementById("myNav").style.width = "100%";
}

function closeSearch() {
    document.getElementById("myNav").style.width = "0%";
    document.getElementById("wrapSearch").style.display = "block";
}

$(document).ready(function() {
    $("#item_event_equipmentss").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listvalss li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$(document).ready(function() {
    $("#item_venue_types").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listvals li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

function sortList() {
    document.getElementById("sortDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
var windw = this;
$.fn.followTo = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                /*top: pos*/
                top: pos - 100
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 10,
            });
        }
    });
};


$.fn.followTo1 = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                top: pos
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 100,
            });
        }
    });
};

$.fn.followTo2 = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                /*top: pos*/
                top: pos - 100
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 100,
            });
        }
    });
};

$.fn.followTo3 = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                top: pos
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 100,
            });
        }
    });
};

$.fn.followTo4 = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                top: pos - 100
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 170,
            });
        }
    });
};

$.fn.followTo5 = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                top: pos - 40
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 170,
            });
        }
    });
};

$.fn.followTo6 = function(pos) {
    var $this = this,
        $window = $(windw);

    $window.scroll(function(e) {
        if ($window.scrollTop() > pos) {
            $this.css({
                position: 'absolute',
                top: pos
            });
        } else {
            $this.css({
                position: 'fixed',
                width: 205,
                top: 170,
            });
        }
    });
};

/*$('#searchform').followTo(1650);*/
$('#searchform').followTo(150);          
$('#searchforms').followTo2(750);
$('#searchformwedding').followTo3(650);
$('#searchformbday').followTo3(620);
$('#searchformpacks').followTo4(200);
$('#searchformequis').followTo5(50);
$('#searchformpacksdata').followTo6(100);
$('.eventtypess').followTo1(50);

jQuery(document).ready(function($) {
    $('#scroll-top').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});

/* $(function() {
    $("#demos").datepicker({
        dateFormat: "dd / mm / yy",
        minDate: new Date(),
        changeMonth: true,
        changeYear: true
    });
});

var dateToday = new Date();
var dates = $("#demos").datepicker({
    dateFormat: "dd / mm / yy",
    minDate: new Date(),
    changeMonth: true,
    changeYear: true
}); */

/* for packages */
$(document).ready(function() {
    var s_round = '.s_round';
    $(s_round).hover(function() {
        $('.b_round').toggleClass('b_round_hover');
        return false;
    });

    $(s_round).click(function() {
        $('.flip_box').toggleClass('flipped');
        $(this).addClass('s_round_click');
        $('.s_arrow').toggleClass('s_arrow_rotate');
        $('.b_round').toggleClass('b_round_back_hover');
        return false;
    });

    $('.booksnw .s_round').click(function() {
        $('.threebtns').fadeIn(3000).show();
        $('.col-md-8 .alignitms').fadeOut(2000).hide();
    });

    $('.backsbn .s_round').click(function() {
        $('.threebtns').fadeOut(2000).hide();
        $('.col-md-8 .alignitms').fadeIn(2000).show();
    });

    $(s_round).on('transitionend', function() {
        $(this).removeClass('s_round_click');
        $(this).addClass('s_round_back');
        return false;
    });

    $('.cartsrghts').click(function() {
        $('.imagerghts').fadeOut(2000).hide();
        $('.cartsrghts').fadeIn(3000).show();
    });
});

$(document).ready(function() {
    $("#item_event_packages").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listvalss li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$(document).ready(function() {
    $("#item_event_equipments").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#liztz li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

function wcqib_refresh_quantity_increments() {
    jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function(a, b) {
       var c = jQuery(b);
       c.addClass("buttons_added"), c.children().first().before('<input type="button" value="-" class="minus" />'), c.children().last().after('<input type="button" value="+" class="plus" />')
    })
 }

 String.prototype.getDecimals || (String.prototype.getDecimals = function() {
    var a = this,
       b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
 }), jQuery(document).ready(function() {
    wcqib_refresh_quantity_increments()
 }), jQuery(document).on("updated_wc_div", function() {
    wcqib_refresh_quantity_increments()
 }), jQuery(document).on("click", ".plus, .minus", function() {
    var a = jQuery(this).closest(".quantity").find(".qty"),
       b = parseFloat(a.val()),
       c = parseFloat(a.attr("max")),
       d = parseFloat(a.attr("min")),
       e = a.attr("step");
    b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
 });

/* for packages ends */

/*============================common js for packages , euipments,  weddings and marraiges starts here ====================*/

$(document).on('click', "li.itemdesc_link", function()
{
    var i = $(this).attr('id');
    var j = parseInt(i, 10);

    if ($("#mapshow_list_" + j).is(':visible')) {

        $("#mapshow_list_" + j).slideToggle("slow");
        $("#featured-list" + j).removeClass("listing-top-collapse");
        $("#ul-" + j).removeClass("opacity-list");
        $("#text-map-" + j).removeClass("list-border");
    } 
    else 
    {
        $("#amenities_list_" + j).hide(); //amenities remove
        $("#featured-list" + j).removeClass("listing-top-collapse"); //amenities remove
        $("#text-amenity-" + j).removeClass("list-border"); //amenities remove
        $("#bestfor_list_" + j).hide(); //bestfor remove
        $("#featured-list" + j).removeClass("listing-top-collapse"); //bestfor remove
        $("#text-bestfor-" + j).removeClass("list-border"); //bestfor remove
        $("#photo_list_" + j).hide(); //photos remove
        $("#featured-list" + j).removeClass("listing-top-collapse"); //photos remove
        $("#text-photos-" + j).removeClass("list-border"); //photos remove
        $("#rating_list_" + j).hide(); //rating remove
        $("#featured-list" + j).removeClass("listing-top-collapse"); //rating remove
        $("#text-rating-" + j).removeClass("list-border"); //rating remove

        $("#mapshow_list_" + j).slideToggle("slow");
        $("#featured-list" + j).addClass("listing-top-collapse");
        $("#ul-" + j).addClass("opacity-list");
        $("#text-map-" + j).addClass("list-border");
    }
});

$(document).on('click', "li.itemimage_link", function()
{              
    var i = $(this).attr('id');
    var j=parseInt(i,10);
    
    if($("#photo_list_"+j).is(':visible')){
      
      $("#photo_list_"+j).slideToggle("slow");
      $("#featured-list"+j).removeClass("listing-top-collapse");
      $("#ul-"+j).removeClass("opacity-list");
      $("#text-photos-"+j).removeClass("list-border");
    } 
    else 
    {
      $("#amenities_list_"+j).hide(); //amenities remove
      $("#featured-list"+j).removeClass("listing-top-collapse");//amenities remove
      $("#text-amenity-"+j).removeClass("list-border");//amenities remove
      $("#bestfor_list_"+j).hide();//bestfor remove
      $("#featured-list"+j).removeClass("listing-top-collapse");//bestfor remove
      $("#text-bestfor-"+j).removeClass("list-border");//bestfor remove
      $("#featured-list"+j).removeClass("listing-top-collapse");//map remove
      $("#text-map-"+j).removeClass("list-border");//map remove
      $("#mapshow_list_"+j).hide();//map remove
      $("#rating_list_"+j).hide();//rating remove
      $("#featured-list"+j).removeClass("listing-top-collapse");//rating remove
      $("#text-rating-"+j).removeClass("list-border");//rating remove
      
      $("#photo_list_"+j).slideToggle("slow");
      $("#featured-list"+j).addClass("listing-top-collapse");
      $("#ul-"+j).addClass("opacity-list");
      $("#text-photos-"+j).addClass("list-border");
    }
});

/* $(document).on('click', '#clrFilter', function() {
    $(':checkbox').each(function () {
        $(this).removeAttr('checked');
          $("li label").removeClass("select");
    });
    $(':radio').each(function () {
        $(this).removeAttr('checked');
          $("li label").removeClass("select");
    });

    $("#employee-grid").dataTable().fnDestroy();
    loadTables();
}); */

function rmvtextfunc() {
    $("#item_event_equipmentss").val('').focus(); 
    $("#item_venue_type").val('').focus(); 
    $("#item_venue_types").val('').focus(); 
    $("#item_event_packages").val('').focus();
    $("#item_event_equipments").val('').focus(); 
}

$(document).ready(function(){
    $("#clrFilter").click(function(){
         $(':checkbox').each(function () {
                $(this).removeAttr('checked');
                $("li label").removeClass("select");
        });
        $(':radio').each(function () {
                $(this).removeAttr('checked');
                $("li label").removeClass("select");
        });  
        $(".facet__item").show();
        rmvtextfunc();
        $("#employee-grid").dataTable().fnDestroy();
        loadTables(); 
   });  
}); 

/*---------------button click for cart booking-----------------*/

function additemstocarts(elementitem)
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

            //console.log('status' + status);

            if(status == 1)
            {
                $("#result-"+item_id).text('Item added to cart').show().delay(1000).fadeOut();

                if(booking_type == 'cart_wedding')
                {
                    wedding_cart_ui();
                }
                else if(booking_type == 'cart_general')
                {
                    general_cart_ui();
                }
            }
            else
            {
                $("#result-"+item_id).text('Sorry item not added. Try Again').show().delay(1000).fadeOut();
            }
        },
        error: function(a,b)
        {
            $("#result-"+item_id).text('Sorry item not added. Try Again').show().delay(1000).fadeOut();
        }
    });
}

/*---------------button click for cart booking ends-----------------*/
/*============================common js for packages , euipments,  weddings and marraiges ends here ====================*/

$(document).on('click','#employee-grid_paginate', function() {
    $('html, body').animate({ scrollTop: 0 }, '10000');
});
/*========================================written js for packages comes here =================================*/

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

function changepackage(elementitem) 
{
    var id = $(elementitem).prop('id');
    
    $.ajax
    ({
        url:'/changepackage',
        type: "post",
        dataType:"json",
        data: { "id": id },
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;  
            //console.log(response);

            var package_name = response['package_name'];
            var min_price = response['min_price'];
            var package_include = response['package_include'];
            var package_description = response['package_description'];
            var packagedetails = response['packagedetails'];
            var package_image = response['package_image'];

            //console.log(package_include);
            min_price = moneyFormatIndia(min_price);

            $('.min_package_amount').text(min_price);
            $('#package_name').text(package_name);
            $('#package_name_book').text('Book '+package_name);            
            $('#package_image').attr("alt", package_name);
            $('#package_image').attr("title", package_name);
            $('#package_image').attr("src", package_image);
            $('#package_description').text(package_description);

            var package_include = package_include.split(",");
            $('#package_include li').remove();

            $.each(package_include, function( key, value ) {
                $("#package_include").append('<li>'+value+'</li>');
            });

            $('#no_of_guest option:not(:first)').remove();
            $.each( packagedetails, function( key, value ) {
                $('#no_of_guest').append( '<option value="'+value.id+'">'+value.no_of_pax+'</option>' );
            }); 
            //$(this).attr("src", "images/card-front.jpg");

        },
        error: function(a,b)
        {
            console.log('error in package fetch');
        }
    });
}

$(document).on('click', ".package_booking_type", function(event) {
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

        console.log(btn_id);
        //return false;

        if(btn_id == 'package_book_now')
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
                    if(btn_id == 'package_book_now')
                    {
                        location = '/checkout';
                    }
                    else if(btn_id == 'package_book_cart')
                    {
                        console.log('items added to cart');
                        $('.imagerghts').fadeOut(2000).hide();
                        $('.cartsrghts').fadeIn(3000).show();
                        general_cart_ui();
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

$(document).on('click', "#add_more_equipments", function(event) {
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
        console.log('add_more_equipments clicked');	
        var trans_type = 'package';
        var passData = 'switchtype=quotesuccessown&event_date='+event_date+'&no_of_days='+no_of_days+'&package_detail_id='+package_detail_id+'&package_price='+package_price+'&dummy='+Math.floor(Math.random()*100032680100);
        url = '/additemstocartpackage';
        $('.s_arrow_rotate').hide();
        $.ajax
        ({
            url:url,type: "post",data: passData, cache: false,dataType: "json",async: false,
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var status = response['status'];
    
                if(status == 1)
                {
                    showcartpackage();
                    general_cart_ui();
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

/*============================written js for packages ends here ====================*/

/*============================written for updating cart in UI comes here ====================*/

function wedding_cart_ui()
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
                    //$('#page_cart_items .manual_added_cart').remove();
                    $('.page_cart_items .manual_added_cart').remove();
                    $.each(cart_items, function( key, value ) {
                        //console.log( value.particular );
                        ++counter;    
                        
                        //var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart"><div class="col-md-10 minicart-data"><p class="mc-name ng-binding">'+counter+') '+value.particular+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                        //$('#page_cart_items').append(cart_item_to_add);

                        if(value.item_qty != '' && !(isNaN(value.item_qty)))
                        {
                            //var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart"><div class="col-md-7 minicart-data"><p class="mc-name mc-names">'+counter+') '+value.particular+'</p></div><div class="col-md-3 minicart-img"><p class="chosevalue"> Qty: '+value.item_qty+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                            var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart wdngscart"><div class="col-md-12 minicart-data"><p class="mc-name mc-names">'+counter+') '+value.particular+'</p></div><div class="col-md-12"><div class="col-md-3 minicart-img"><p class="chosevalue"> Qty: '+value.item_qty+'</p></div><div class="col-md-3 minicart-img"><p class="chosevalue"> Days: '+value.partidays+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div></div>';
                        }
                        else
                        {
                            //var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart"><div class="col-md-7 minicart-data"><p class="mc-name mc-names">'+counter+') '+value.particular+'</p></div><div class="col-md-3 minicart-img"><p class="chosevalue"> Sqft: '+value.item_width+' x '+value.item_height+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                            var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart othercart"><div class="col-md-12 minicart-data"><p class="mc-name mc-names">'+counter+') '+value.particular+'</p></div><div class="col-md-12"><div class="col-md-3 minicart-img"><p class="chosevalue"> Sqft: '+value.item_width+' x '+value.item_height+'</p></div><div class="col-md-3 minicart-img"><p class="chosevalue"> Days: '+value.partidays+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-weddingcart-items-'+key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div></div>';
                        }
                        
                        $('.page_cart_items').append(cart_item_to_add);
                    });

                    var cart_length = $('.page_cart_items .manual_added_cart').length;
                    if(cart_length == 0)
                    {
                        $('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                    }

                }
                else if(status == '2')
                {
                    $('.page_cart_items').append('<p class="manual_added_cart">No items in cart</p>');
                }
            },
            error: function(a,b)
            {
                //$("#form_result").show().text('Please refresh and try again');
                console.log('No Items In Cart Data');
            }
        });
}

function general_cart_ui()
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
                //$('#page_cart_items .manual_added_cart').remove();
                $('.page_cart_items .manual_added_cart').remove();
                $.each(cart_items, function( key, value ) {
                    //console.log( key );
                    //console.log( value );
                    $.each(value, function( item_key, item_value ) {
                        ++counter;    
                        /* var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart"><div class="col-md-5 minicart-img"><p>'+item_value.particular+'</p></div><div class="col-md-5 minicart-data"><p class="mc-name">'+item_value.particular+'</p><p class="ng-binding">'+item_value.partidays+' X  ₹ '+item_value.net_price+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-generalcart-'+item_value.trans_type+'-'+item_key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                        $('#page_cart_items').append(cart_item_to_add); */

                        cart_total = Math.round(+cart_total + +item_value.net_price);

                        var item_price_comma = moneyFormatIndia(item_value.item_price);
                        var net_price_comma = moneyFormatIndia(item_value.net_price);

                        //var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart"><div class="col-md-7 minicart-data"><p class="mc-name">'+counter+') '+item_value.particular+'</p><p class="ng-binding">1 day x per unit X  ₹ '+item_price_comma+'</p></div><div class="col-md-3 minicart-img"><p class="amountvalue"> ₹ '+net_price_comma+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-generalcart-'+item_value.trans_type+'-'+item_key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
                        var cart_item_to_add = '<div class="col-md-12 minitotal-prod manual_added_cart equipdata"><div class="col-md-7 minicart-data"><p class="mc-name">'+counter+') '+item_value.theme_name+'</p></div><div class="col-md-3 minicart-img"><p class="amountvalue"> ₹ '+net_price_comma+'</p></div><div class="col-md-1 minicart-close"><p class="endclose" data-remove="remove-generalcart-'+item_value.trans_type+'-'+item_key+'" onclick="removefromcart(this)"><i class="fa fa-times" aria-hidden="true"></i></p></div></div>';
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

function removefromcart(elementitem)
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
                    if(cart_type == 'weddingcart')
                    {
                        wedding_cart_ui();
                    }
                    else if(cart_type == 'generalcart')
                    {
                        general_cart_ui();
                    }
                    //$('#form_result').html('<div class="alert alert-danger"><span>Promocode applied.</span></div>');
                }
                else if(status == 2)
                {
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

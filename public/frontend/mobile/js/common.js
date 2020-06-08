(function($) {
    'use strict';
    $(document).ready(function() {

        //check device type
        function deviceType() {
            var mobile = false;
            //touch on IOS and Android
            var isAndroid = /(android)/i.test(navigator.userAgent);
            var isMobile = /(mobile)/i.test(navigator.userAgent);
            if (navigator.userAgent.match(/(iPod|iPhone|iPad)/) || isAndroid || isMobile) {
                mobile = true;
            } else {
                mobile = false;
            }
            return mobile;
        }


        //desktop hover
        function itemHover() {
            $("#content").hover(
                function() {
                    $(this).addClass("transition");
                },
                function() {
                    $(this).removeClass("transition");
                }
            );
        }

        //mobile touch
        function itemTouch() {
            $("#content").on("touchstart", function() {
                $(this).addClass("transition");

                //if you need working links comment this
                return false;
            });

        }

        function itemInit() {
            var mobile = deviceType();
            //check device type
            if (mobile == true) {
                //if mobile
                itemTouch();
            } else {
                //if desktop
                itemHover();
            }
        }

        //call function
        itemInit();

    });
}(jQuery));


/*$(window).load(function() {
    setTimeout(function() {
        $('.cover-spin').fadeOut('fast');
    }, 8000);
});
*/
 

/* $(document).on('click', '.addmoreequip', function(event) {
    event.preventDefault();
    //$(".booknows").click();
    $("#suhaNavbarToggler").click();
    $(".equipcontents").fadeIn(2000).show();
});
 */
 
 /*============================common js for packages , euipments,  weddings and marraiges starts here ====================*/

$(document).on('click', "li.itemdesc_link", function()
{
    var i = $(this).attr('id');
    var j = parseInt(i, 10);

    if ($("#mapshow_list_" + j).is(':visible')) 
    {
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
    
    if($("#photo_list_"+j).is(':visible'))
    {  
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

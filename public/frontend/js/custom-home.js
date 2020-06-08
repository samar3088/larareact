$(window).scroll(function() 
{
    if ($(this).scrollTop() > 180) 
    {
        $('#nav2').addClass('navTop2');
        $('#nav2').removeClass('navTop');
        document.getElementById("nav1").style.display = "block";        
        $('#search-top-change').addClass('search-top');
    } 
    else 
    {
        document.getElementById("nav1").style.display = "block";
        $('#nav2').removeClass('navTop2');
        $('#nav2').addClass('navTop');
        $('#search-top-change').removeClass('search-top');
    }
});

jQuery().ready(function() 
{  
    /* Custom select design */    
    jQuery('.drop-down').append('<div class="button"></div>');    
    jQuery('.drop-down').append('<ul class="select-list"></ul>');    
    
    jQuery('.drop-down select option').each(function() 
    {  
        var bg = jQuery(this).css('background-image');    
        jQuery('.select-list').append('<li class="clsAnchor"><span value="' + jQuery(this).val() + '" class="' + jQuery(this).attr('class') + '" id="' + jQuery(this).attr('id') +'" style=background-image:' + bg + '>' + jQuery(this).text() + '</span></li>');   
    });    
    
    jQuery('.drop-down .button').html('<span id="newimgs" class="imgsnw" style=background-image:' + jQuery('.drop-down select').find(':selected').css('background-image') + '>' + jQuery('.drop-down select').find(':selected').text() + '</span>' + '<a href="javascript:void(0);" class="select-list-link"><i class="fa fa-angle-down" aria-hidden="true"></i></a>');   
    jQuery('.drop-down ul li').each(function() 
    {   
        if (jQuery(this).find('span').text() == jQuery('.drop-down select').find(':selected').text()) 
        {  
            jQuery(this).addClass('active');       
        }      
    });     
    
    jQuery('.drop-down .select-list span').on('click', function()
    {          
        var dd_text = jQuery(this).text();  
        var dd_img = jQuery(this).css('background-image'); 
        var dd_val = jQuery(this).attr('value');   
        jQuery('.drop-down .button').html('<span id="newimgs" class="imgsnw" style=background-image:' + dd_img + '>' + dd_text + '</span>' + '<a href="javascript:void(0);" class="select-list-link"><i class="fa fa-angle-down" aria-hidden="true"></i></a>');      
        jQuery('.drop-down .select-list span').parent().removeClass('active');    
        jQuery(this).parent().addClass('active');     
        $('.drop-down select[name=options]').val( dd_val ); 
        $('.drop-down .select-list li').slideUp();     
    });       
                                            
    /* End */   
    
    jQuery('.col-md-4').on('click',' .drop-down .button', function()
    {      
        jQuery('.drop-down ul li').slideToggle();  
    }); 
});

jQuery(function($) {
    $('select').on('change', function() {
        var url = $(this).val();
        if (url) {
            window.location = url;
        }
        return false;
    });
});

$(document).ready(function () {
    $("#myBtn").click(function(){
        $('#myModal').modal('show');
    });
});

$(window).scroll(function() 
{
    if ($(this).scrollTop() > 150) 
    {
        $('#scroll-top').fadeIn();
    } 
    else 
    {
        $('#scroll-top').fadeOut();
    }
});
            
jQuery(document).ready(function($){ 
    $('#scroll-top').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 500);
        return false;
    });
});

$(document).ready(function()
{ 
    $("#testimonial-slider").owlCarousel({
        items:1,
        pagination: true,
        slideSpeed: 60000, 
        paginationSpeed:90000,
        rewindSpeed: 30000,
        singleItem:true,
        transitionStyle:"fadeUp",
        autoPlay:true,
        stopOnHover: true,
        autoplayTimeout: 9500
    });
});

jQuery().ready(function() 
{  
    /* Custom select design */    
    jQuery('.drop-down1').append('<div class="button" id="homepageoption"></div>');    
    jQuery('.drop-down1').append('<div class="manualhomelist"><ul class="select-list1"></ul></div>');    
    
    jQuery('.drop-down1 select option').each(function() {  
        var bg = jQuery(this).css('background-image');    
        jQuery('.select-list1').append('<li class="clsAnchor"><a href="' + jQuery(this).val() + '"><span value="' + jQuery(this).val() + '" class="' + jQuery(this).attr('class') + '" id="' + jQuery(this).attr('id') +'" style=background-image:' + bg + '>' + jQuery(this).text() + '</span></a></li>');   
    });    
    
    jQuery('.drop-down1 .button').html('<span id="newimgs1" style=background-image:' + jQuery('.drop-down1 select').find(':selected').css('background-image') + '>' + jQuery('.drop-down1 select').find(':selected').text() + '</span>' + '<a href="javascript:void(0);" class="select-list-link1"><i class="fa fa-angle-down" aria-hidden="true"></i></a>');   
    jQuery('.drop-down1 ul li').each(function() {   
        if (jQuery(this).find('span').text() == jQuery('.drop-down1 select').find(':selected').text()) {  
            jQuery(this).addClass('active');       
        }      
    });     
    
    jQuery('.drop-down1 .select-list1 span').on('click', function()
    {          
        var dd_text = jQuery(this).text();  
        var dd_img = jQuery(this).css('background-image'); 
        var dd_val = jQuery(this).attr('value');   
        jQuery('.drop-down1 .button').html('<span id="newimgs1" style=background-image:' + dd_img + '>' + dd_text + '</span>' + '<a href="javascript:void(0);" class="select-list-link1"><i class="fa fa-angle-down" aria-hidden="true"></i></a>');      
        jQuery('.drop-down1 .select-list1 span').parent().removeClass('active');    
        jQuery(this).parent().addClass('active');     
        $('.drop-down1 select[name=options1]').val(dd_val); 
        $('.drop-down1 .select-list1 li').slideUp();     
    });       
                                            

    jQuery('.col-md-4').on('click',' .drop-down1 .button', function()
    {
        jQuery('.drop-down1 ul li').slideToggle();  
    }); 

    
    jQuery(".drop-down1").hover(function(){
        jQuery('.drop-down1 .select-list1 li').slideToggle("slow");
        }, function(){
            jQuery('.drop-down1 .select-list1 li').slideToggle("slow");
    }); 

});
 
$(document).on("click", function(event){
    var $trigger = $(".drop-down1");
    if($trigger !== event.target && !$trigger.has(event.target).length){
        $(".select-list1 li").slideUp("slow");
    }            
});

/* $(document).on('mouseenter', "#homepageoption", function(event) {				
    $('.select-list1').click();
    console.log('my data');
}); */

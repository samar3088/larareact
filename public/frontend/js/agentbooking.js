var dataTable; var clickcounter = 0;
$('#cc_mails_div').hide();
$('#bcc_mails_div').hide();

$(document).on('click', "#show_cc", function(event) {
    $("#cc_mails_div").toggle();
    
    if ($('#cc_mails_div').is(":hidden"))
    {
        $('#cc_mails').val('');
    }
});

$(document).on('click', "#show_bcc", function(event) {    
    $("#bcc_mails_div").toggle();
    
    if ($('#bcc_mails_div').is(":hidden"))
    {
        $('#bcc_mails').val('');
    }
});

/*Code to add items to cart*/

var counter = 0;

function changedata(value) {
    counter++;
    var value = value; 
    var res = value.split("^");
    //console.log(res);
    
    var checkid = res[1]+''+res[2];
    
    var spancls = res[7];
    var pack_desc = res[8];
    
    if(res[4] == 'Q') 
    {
        var itemscount = $('#additem-'+checkid).val();
        var itemsdays = $('#additem_partidays-'+checkid).val();

        var cost = Math.round(itemscount*res[5]*itemsdays);
        var cart = '<div id="cartitems-'+counter+'" class="generalitem col-md-12 col-xs-12 padd0 '+counter+'-added-'+checkid+'"><p class="pull-left cr_cs savebrtabs">'+res[6]+'</p><p class="pull-right" onclick="removeitem(\''+counter+'-added-'+checkid+'\')"><strong style="cursor: pointer;">X</strong></p><div class="col-xs-12 col-md-4 padd0 textleft  pull-left"><span class="qt_style">Qty</span><br><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><input type="button" value="-" class="minuses" onclick="itemcals(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="1" min="1" max="" name="quantity" value="'+itemscount+'" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcals(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div></div><div class="col-xs-12 col-md-4 padd0 textleft  pull-right"><span class="qt_style">Day(s)</span><br><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><input type="button" value="-" class="minuses" onclick="itemcals_days(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="1" min="1" max="" name="partidays" value="'+itemsdays+'" title="Days" class="input-text qty partidays added_days_data text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcals_days(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><p class="pull-right" style="padding-top:10px;">₹&nbsp;<span class="calamount">'+cost+'</span>.00</p><input type="hidden" name="item_price" value="'+res[5]+'"><input type="hidden" name="particular" value="'+res[6]+'"><input type="hidden" name="pack_desc" value="'+pack_desc+'"></div>';
        $("#mycart").append(cart);
        $('.quantitycls').val(1);
    }
    else if(res[4] == 'S') 
    {
        var itemscount1 = $('#addsq1-'+checkid).val();
        var itemscount2 = $('#addsq2-'+checkid).val();
        var itemsdays = $('#additem_partidays-'+checkid).val();
        
        //console.log(itemscount1+''+itemscount2);
        
        var cost = Math.round(itemscount1*itemscount2*res[5]*itemsdays);
        if(res[3] == 'LED Wall') 
        {
            var cart = '<div id="cartitems-'+counter+'" class="specialitem col-md-12 col-xs-12 padd0 '+counter+'-added-'+checkid+'"><p class="pull-left cr_cs savebrtabs">'+res[6]+'</p><p class="pull-right" onclick="removeitem(\''+counter+'-added-'+checkid+'\')"><strong style="cursor: pointer;">X</strong></p><div class="col-xs-12 col-md-12 padd0"><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Sq.ft</span><br><input type="button" value="-" class="minuses" onclick="itemcalculate_led(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="2" min="2" max="" name="quantity" value="'+itemscount1+'" title="Sq.ft" class="input-text qty text qty1" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculate_led(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Sq.ft</span><br><input type="button" value="-" class="minuses" onclick="itemcalculates_led(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="2" min="2" max="" name="quantity" value="'+itemscount2+'" title="Sq.ft" class="input-text qty text qty2" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculates_led(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><div class="quantity buttons_added pull-right" style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Day(s)</span><br><input type="button" value="-" class="minuses" onclick="itemcalculates_led_days(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="1" min="1" max="" name="partidays" value="'+itemsdays+'" title="Days" class="input-text qty partidays added_days_data text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculates_led_days(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><input type="hidden" name="item_price" value="'+res[5]+'"><input type="hidden" name="particular" value="'+res[6]+'"></div><p class="pull-left" style="padding-top:10px;">₹&nbsp;<span class="calamount">'+cost+'</span>.00</p></div>';
            $('.LEDWall').val(2);        
            $("#mycart").append(cart);    
        }
        else 
        {
            var cart = '<div id="cartitems-'+counter+'" class="specialitem col-md-12 col-xs-12 padd0 '+counter+'-added-'+checkid+'"><p class="pull-left cr_cs savebrtabs">'+res[6]+'</p><p class="pull-right" onclick="removeitem(\''+counter+'-added-'+checkid+'\')"><strong style="cursor: pointer;">X</strong></p><div class="col-xs-12 col-md-12 padd0"><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Sq.ft</span><br> <input type="button" value="-" class="minuses" onclick="itemcalculate_sqft(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="4" min="4" max="" name="quantity" value="'+itemscount1+'" title="Sq.ft" class="input-text qty text qty1" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculate_sqft(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Sq.ft</span><br><input type="button" value="-" class="minuses" onclick="itemcalculates_sqft(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="4" min="4" max="" name="quantity" value="'+itemscount2+'" title="Sq.ft" class="input-text qty text qty2" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculates_sqft(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><div class="quantity buttons_added pull-right" style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Day(s)</span><br> <input type="button" value="-" class="minuses" onclick="itemcalculates_sqft_days(\''+counter+'-added-minus-'+checkid+'-'+res[5]+'\')"><input type="number" step="1" min="1" max="" name="partidays" value="'+itemsdays+'" title="Days" class="input-text qty partidays added_days_data text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculates_sqft_days(\''+counter+'-added-plus-'+checkid+'-'+res[5]+'\')"></div><input type="hidden" name="item_price" value="'+res[5]+'"><input type="hidden" name="particular" value="'+res[6]+'"></div><p class="pull-left" style="padding-top:10px;">₹&nbsp;<span class="calamount">'+cost+'</span>.00</p></div>';
            $('.sqftcls').val(4);
            $("#mycart").append(cart);
        }
    }
    
    $('.'+spancls).text('Item Added to Cart').fadeIn().css({"margin-left": "10px", "font-weight": "400", "color": "#d4603b"}).delay(1000).fadeOut();
    finalCalculation();
    emptyaddremove();
}

function addfieldstocart(value) 
{
    var particularname = $('#'+value +' #particularname').val();
    var partiquantity = $('#'+value +' #partiquantity').val();
    var partiamount = $('#'+value +' #partiamount').val();
    var partidays = $('#'+value +' #partidays').val();

    if(particularname == '') {
        alert('Please enter particular name');
        $('#'+value +' #particularname').focus();
        return false;
    }
    else if(partiquantity == '') {
        alert('Please enter particular quantity');
        $('#'+value +' #partiquantity').focus();
        return false;
    }
    else if(partiamount == '') {
        alert('Please enter particular amount');
        $('#'+value +' #partiamount').focus();
        return false;
    }
    else if(partidays == '') {
        alert('Please enter particular days');
        $('#'+value +' #partidays').focus();
        return false;
    }
    else 
    {
        counter++;

        /*----------------------items added ----------------------------*/
        var d = new Date();
        var checkid = d.getTime();
        var cost = Math.round(partiquantity*partiamount*partidays);
        
        var cart = '<div id="cartitems-'+counter+'" data-itemsort="'+value+'" class="generalitems '+value+' col-md-12 col-xs-12 padd0 '+counter+'-added-'+checkid+'"><p class="pull-left cr_cs savebrtabs">'+particularname+'</p><p class="pull-right" onclick="removeitems(\''+counter+'-added-'+checkid+'-'+value+'\')"><strong style="cursor: pointer;">X</strong></p><div class="col-xs-12 col-md-12 padd0"><div class="col-xs-12 col-md-4 padd0 textleft"><span class="qt_style">Qty</span><br><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><input type="button" value="-" class="minuses" onclick="itemcals(\''+counter+'-added-minus-'+checkid+'-'+partiamount+'\')"><input type="number" step="1" min="1" max="" name="quantity" value="'+partiquantity+'" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcals(\''+counter+'-added-plus-'+checkid+'-'+partiamount+'\')"></div></div><div class="col-xs-12 col-md-4 padd0 textleft"><span class="qt_style">Day(s)</span><br><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><input type="button" value="-" class="minuses" onclick="itemcals_days(\''+counter+'-added-minus-'+checkid+'-'+partiamount+'\')"><input type="number" step="1" min="1" max="" name="partidays" value="'+partidays+'" title="Days" class="input-text qty partidays added_days_data text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcals_days(\''+counter+'-added-plus-'+checkid+'-'+partiamount+'\')"></div></div></div><p class="pull-left" style="padding-top:10px;">₹&nbsp;<span class="calamount">'+cost+'</span>.00</p><input type="hidden" name="item_price" value="'+partiamount+'"><input type="hidden" name="particular" value="'+particularname+'"></div>';
        $("#mycart").append(cart);

        /*---------------Ends-----------------*/
        
        $("#test2 option:first").attr('selected','selected');
        $('#'+value+' .buttontoadd').attr("disabled", true);
        finalCalculation();
        emptyaddremove();
        getValues();
        //removemanual(value);
    }
}

function addfieldstocarts(value) 
{
    var particularname = $('#'+value +' #particularname').val();
    var partiwidth = $('#'+value +' #partiwidth').val();
    var partiheight = $('#'+value +' #partiheight').val();
    var partiamount = $('#'+value +' #partiamount').val();
    var partidays = $('#'+value +' #partidays').val();
    
    if(particularname == '') {
        alert('Please enter particular name');
        $('#'+value +' #particularname').focus();
        return false;
    }
    else if(partiwidth == '') {
        alert('Please enter particular width');
        $('#'+value +' #partiwidth').focus();
        return false;
    }
    else if(partiheight == '') {
        alert('Please enter particular height');
        $('#'+value +' #partiheight').focus();
        return false;
    }
    else if(partiamount == '') {
        alert('Please enter particular amount');
        $('#'+value +' #partiamount').focus();
        return false;
    }
    else if(partidays == '') {
        alert('Please enter particular days');
        $('#'+value +' #partidays').focus();
        return false;
    }
    else 
    {
        counter++;
        
        /*----------------------items added ----------------------------*/
        var d = new Date();
        var checkid = d.getTime();
        var cost = Math.round(partiwidth*partiheight*partiamount*partidays);
        
        var cart = '<div id="cartitems-'+counter+'" data-itemsort="'+value+'" class="specialitems '+value+' col-md-12 col-xs-12 padd0 '+counter+'-added-'+checkid+'"><p class="pull-left cr_cs savebrtabs">'+particularname+'</p><p class="pull-right" onclick="removeitems(\''+counter+'-added-'+checkid+'-'+value+'\')"><strong style="cursor: pointer;">X</strong></p><div class="col-xs-12 col-md-12 padd0"><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Sq.ft </span><br><input type="button" value="-" class="minuses" onclick="itemcalculate(\''+counter+'-added-minus-'+checkid+'-'+partiamount+'\')"><input type="number" step="2" min="2" max="" name="quantity" value="'+partiheight+'" title="Sq.ft" class="input-text qty text qty1" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculate(\''+counter+'-added-plus-'+checkid+'-'+partiamount+'\')"></div><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Sq.ft </span><br><input type="button" value="-" class="minuses" onclick="itemcalculates(\''+counter+'-added-minus-'+checkid+'-'+partiamount+'\')"><input type="number" step="2" min="2" max="" name="quantity" value="'+partiwidth+'" title="Sq.ft" class="input-text qty text qty2" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculates(\''+counter+'-added-plus-'+checkid+'-'+partiamount+'\')"></div><div class="quantity buttons_added pull-left textleft " style="margin-bottom:15px;margin-right:2px"><span class="qt_style">Days</span><br><input type="button" value="-" class="minuses" onclick="itemcalculates_days(\''+counter+'-added-minus-'+checkid+'-'+partiamount+'\')"><input type="number" step="1" min="1" max="" name="partidays" value="'+partidays+'" title="Days" class="input-text qty partidays added_days_data text" size="4" pattern="" inputmode="" readonly><input type="button" value="+" class="pluses" onclick="itemcalculates_days(\''+counter+'-added-plus-'+checkid+'-'+partiamount+'\')"></div><input type="hidden" name="item_price" value="'+partiamount+'"><input type="hidden" name="particular" value="'+particularname+'"></div><p class="pull-left" style="padding-top:10px;">₹&nbsp;<span class="calamount">'+cost+'</span>.00</p></div>';
        $("#mycart").append(cart);

        /*---------------Ends-----------------*/
        
        $("#test2 option:first").attr('selected','selected');
        $('#'+value+' .buttontoadd').attr("disabled", true);
        finalCalculation();
        emptyaddremove();
        getValues();
        //removemanual(value);
    }
}

/*Ends Code to add items to cart*/

function itemcalculate_led(value) 
{
    var divclass = value;
    var res = divclass.split("-");
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item1 > 2) { item1 = item1 - 2; }
    }
    if(actiontype == 'plus') {
        item1 = parseInt(item1) + 2;
    }
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculates_led(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item2 > 2) { item2 = item2 - 2; }
    }
    if(actiontype == 'plus') {
        item2 = parseInt(item2) + 2;
    }
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculate_sqft(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item1 > 4) { item1 = item1 - 4; }
    }
    if(actiontype == 'plus') {
        item1 = parseInt(item1) + 4;
    }
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);
 
    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculates_sqft(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item2 > 4) { item2 = item2 - 4; }
    }
    if(actiontype == 'plus') {
        item2 = parseInt(item2) + 4;
    }
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculate(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item1 > 2) { item1 = item1 - 2; }
    }
    if(actiontype == 'plus') {
        item1 = parseInt(item1) + 2;
    }
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var singleamount = parseInt(singleamount);
    var addded_days = parseInt(addded_days);

    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculates(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item2 > 2) { item2 = item2 - 2; }
    }
    if(actiontype == 'plus') {
        item2 = parseInt(item2) + 2;
    }
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcals(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item = $('div.'+divclass).find('.qty').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(item > 1) { item = item - 1; }
    }
    if(actiontype == 'plus') {
        item = parseInt(item) + 1;
    }
    
    var item = parseInt(item);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    var fillamount = item*singleamount*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcals_days(value) 
{
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item = $('div.'+divclass).find('.qty').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(addded_days > 1) { addded_days = addded_days - 1; }
    }
    if(actiontype == 'plus') {
        addded_days = parseInt(addded_days) + 1;
    }

    var item = parseInt(item);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    var fillamount = item*singleamount*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculates_days(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(addded_days > 1) { addded_days = addded_days - 1; }
    }
    if(actiontype == 'plus') {
        addded_days = parseInt(addded_days) + 1;
    }
    
    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculates_sqft_days(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(addded_days > 1) { addded_days = addded_days - 1; }
    }
    if(actiontype == 'plus') {
        addded_days = parseInt(addded_days) + 1;
    }
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function itemcalculates_led_days(value) {
    var divclass = value;
    var res = divclass.split("-");
    
    var actiontype = res[2];
    
    var singleamount = parseInt(res[4]);
    
    divclass = res[0]+'-'+res[1]+'-'+res[3];
    var item1 = $('div.'+divclass).find('.qty1').val();
    var item2 = $('div.'+divclass).find('.qty2').val();
    var addded_days = $('div.'+divclass).find('.added_days_data').val();

    if(actiontype == 'minus') {
        if(addded_days > 1) { addded_days = addded_days - 1; }
    }
    if(actiontype == 'plus') {
        addded_days = parseInt(addded_days) + 1;
    }
    
    var item1 = parseInt(item1);
    var item2 = parseInt(item2);
    var addded_days = parseInt(addded_days);
    var singleamount = parseInt(singleamount);

    //console.log('item1 : '+item1+ ' item2 : '+item2+ 'singleamount : '+singleamount);
    
    var fillamount = item1*singleamount*item2*addded_days;
    $('div.'+divclass).find('.calamount').text(parseInt(fillamount));
    finalCalculation();
}

function removeitem(value) {
    var value = value;
    $('.'+value).remove();
    finalCalculation();
    emptyaddremove();
}

function removeitems(value) {
    var value = value;
    value = value.split("-");
    
    var final_val_to_remove = value[0]+'-'+value[1]+'-'+value[2];
    
    $('.'+final_val_to_remove).remove();
    finalCalculation();
    emptyaddremove();
    
    var div_classes = value[3];
    enableaddbutton(div_classes);

}

function enableaddbutton(div_classes) {
    var item_id = div_classes;
    var div_id = '#'+item_id+' button.buttontoadd';
    $(div_id).prop("disabled", false);
}

function finalCalculation() 
{
    var total = 0;
    var new_amount = 0;

    var discount_percent = $('#discount_percent').val();
    var manual_discount = 0;

    $(".calamount").each(function() {
        var calamount = parseInt($(this).text());
        total = +total + +calamount;
    });

    if(discount_percent != '' && discount_percent > 0 && discount_percent != NaN && discount_percent != undefined) {
        
        new_amount = Math.round(total-(total*(discount_percent/100)));

        $('#discount_amnt').val(new_amount); 
        $('#discount_amounts').text(new_amount); 
        $('#dis_amounts').text('.00'); 
    }
    
    /*-----added for agent booking ---------*/
    var transport_cost = parseInt($('#transport_cost').val());
    var crew_cost = parseInt($('#crew_cost').val());

    if((isNaN(transport_cost)) || transport_cost == undefined) { transport_cost = 0; }
    if((isNaN(crew_cost)) || crew_cost == undefined) { crew_cost = 0; }

    total = crew_cost + transport_cost + total;
    /*-----added for agent booking ends ---------*/
    
    /*-----added for manual discount ---------*/
    
    if($("#manual_discount").length){
        manual_discount = $('#manual_discount').val();
        if((isNaN(manual_discount)) || manual_discount == undefined || manual_discount == '') { manual_discount = 0; }
        manual_discount = parseInt(manual_discount);
        total = total - manual_discount;
    }
    
    /*-----added for manual discount ---------*/
    
    $('#finaltotal').text(total+'.00');
    $('#total_price').val(total);
}

function wcqib_refresh_quantity_increments() {
    jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function(a, b) {
        var c = jQuery(b);
        c.addClass("buttons_added"), c.children().first().before('<input type="button" value="-" class="minuses" />'), c.children().last().after('<input type="button" value="+" class="pluses" />')
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
}), jQuery(document).on("click", ".pluses, .minuses", function() {
    var a = jQuery(this).closest(".quantity").find(".qty"),
        b = parseFloat(a.val()),
        c = parseFloat(a.attr("max")),
        d = parseFloat(a.attr("min")),
        e = a.attr("step");
    b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".pluses") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
});

function send_quotes()
{
    console.log('clicked');
    event.preventDefault();
    var itemscounts = $("div[id^='cartitems']").length;
	var event_date = $('#demos').val();
	var city = $('#city').val();
	//var city = $("#city option:selected").val();
	if(city.trim() == '' )
	{
	    $('html, body').animate({
        scrollTop: $("#city").offset().top
        }, 500);
		alert('Please Enter Venue');
		$('#city').focus();
		
	    return false;
	}
    else if(event_date.trim() == '' )
	{
		alert('Please Select Event date.');
		$('#demos').focus();
		return false;
	}
	else if(itemscounts <= 0)
	{
		alert('Please Select Equipments to Proceed');
		return false;
	}
	else
	{
		$('#myAgent').modal('show');
	}
}

$(document).on('click', "#btnSend", function(event) {           
    event.preventDefault();
    var mobile = $('#save_mobile').val();
    var name = $('#save_name').val();
    var email = $('#save_email').val();
    var description = $('#description').val();
	var emailReg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    //var terms_and_conditions = $('#terms_and_conditions').val();
    
    //var dataString = "mobile"+mobile&"name"+name&"email"+email;
    if(name.trim() == '' )
    {
    	alert('Please Enter your name.');
		$('#save_name').focus();
	    return false;
    }
    else if(email.trim() == '' )
    {
		alert('Please Enter Email Id.');
		$('#save_email').focus();
	    return false;
    }
    else if(!emailReg.test(email))
    {
		alert('Please Enter valid Email Id.');
		$('#save_email').focus();
	    return false;
    }
    else if(mobile.trim() == '' )
    {
		alert('Please Enter your Mobile Number.');
		$('#save_mobile').focus();
	    return false; 
    }
    /*else if($("#terms_and_conditions").is(":not(:checked)")){
		alert('Please agree to the Terms and Conditions.');
		$('#terms_and_conditions').focus();
	    return false;
	} */
	else
	{
        $.ajax
        ({
            url:'/agentsavecustomer',
            type: "post",
            dataType:"json",
            data: { mobile: $("#save_mobile").val(), name: $("#save_name").val(), email: $("#save_email").val(),description: $("#description").val() },
            success: function(ajaxresponse,status)
            {
                tossquote();
            }
        });
	}
});
       
function tossquote() 
{    
    var name = $('#save_name').val();
    var email = $('#save_email').val();
    var mobile = $('#save_mobile').val();
    var description = $('#description').val();
    var event_date = $('#demos').val();
    
    var total_price = $('#total_price').val();
    total_price = total_price.trim();
    total_price = parseInt(total_price);
    
    var discount_percent = $('#discount_percent').val();
    var discount_amnt = $('#discount_amnt').val();

    var cityid = $("#cityid").val();
    var cityname = $("#city").val();
    
    var transport_cost = $("#transport_cost").val();
    var crew_cost = $("#crew_cost").val();
    
    var cc_mails = $('#cc_mails').val();
    var bcc_mails = $('#bcc_mails').val();
    
    var manual_notes_added = $('#manual_notes_added').val();
    if(manual_notes_added != '' && manual_notes_added != undefined)
    {
        var manual_notes_added = manual_notes_added.replace(/(?:\r\n|\r|\n)/g, '<br />');
    }    

    var listitems = '';var listitems2 = '';
    var particular = '';var item_price = '';var item_qty = '';var net_price = '';var item_height = '';var item_width = '';var pack_desc = '';var partidays = '';
    var i = 0;
    
    var manual_discount = 0;
    if($("#manual_discount").length){
        manual_discount = $('#manual_discount').val();
        if((isNaN(manual_discount)) || manual_discount == undefined  || manual_discount == '') { manual_discount = ''; }
    }

    $("div[id^='cartitems']").each(function()
    {
        if ($(this).hasClass("generalitem")) {
		    //console.log("i have the generalitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = $(this).find('input[name=quantity]').val();
		    pack_desc = $(this).find('input[name=pack_desc]').val();
		    partidays = $(this).find('input[name=partidays]').val();
		    if(pack_desc == undefined) {
		        pack_desc = '';
		    }
		    net_price = $(this).find('.calamount').text();
		    net_price = parseInt(net_price);
		    item_height = '';
		    item_width = '';
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+pack_desc+'^'+partidays+'#';
		    listitems += arrayitems;
		    allowtrans = true;
	    }
	    else if ($(this).hasClass("specialitem") ) {
		    //console.log("i have the specialitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = '';
		    pack_desc = $(this).find('input[name=pack_desc]').val();
		    partidays = $(this).find('input[name=partidays]').val();
		    if(pack_desc == undefined) {
		        pack_desc = '';
		    }
		    net_price = $(this).find('.calamount').text();
		    net_price = parseInt(net_price);
		    item_height = parseInt($(this).find('.qty1').val());
		    item_width = parseInt($(this).find('.qty2').val());
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+pack_desc+'^'+partidays+'#';
		    listitems += arrayitems;
		    allowtrans = true;
	    }
	    else if ($(this).hasClass("generalitems")) {
		    //console.log("i have the generalitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = $(this).find('input[name=quantity]').val();
		    net_price = $(this).find('.calamount').text();
		    partidays = $(this).find('input[name=partidays]').val();
		    net_price = parseInt(net_price);
		    item_height = '';
		    item_width = '';
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+partidays+'#';
		    listitems2 += arrayitems;
		    allowtrans = true;
	    }
	    else if ($(this).hasClass("specialitems") ) {
		    //console.log("i have the specialitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = '';
		    net_price = $(this).find('.calamount').text();
		    net_price = parseInt(net_price);
		    item_height = parseInt($(this).find('.qty1').val());
		    item_width = parseInt($(this).find('.qty2').val());
		    partidays = $(this).find('input[name=partidays]').val();
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+partidays+'#';
		    listitems2 += arrayitems;
		    allowtrans = true;
	    }
    });    
	    
    /*-----------------Form Submission changed to ajax------------------------*/
    
    /*Added for GST */
    var add_gst = 'no';
    if($("#add_gst").is(':checked')) {
        add_gst = 'yes';
    }
    /*Ends Added for GST*/
        
    var send_quote = 'send_quote';
    var queryString =  '/completeagentcart';
    var passData = 'switchtype=quotesuccessown&name='+encodeURIComponent(name)+'&email='+encodeURIComponent(email)+'&mobile='+encodeURIComponent(mobile)+'&cc_mails='+encodeURIComponent(cc_mails)+'&bcc_mails='+encodeURIComponent(bcc_mails)+'&description='+encodeURIComponent(description)+'&event_date='+encodeURIComponent(event_date)+'&total_price='+encodeURIComponent(total_price)+'&manual_discount='+encodeURIComponent(manual_discount)+'&discount_percent='+encodeURIComponent(discount_percent)+'&discount_amnt='+encodeURIComponent(discount_amnt)+'&listitems='+encodeURIComponent(listitems)+'&send_quote='+encodeURIComponent(send_quote)+'&crew_cost='+encodeURIComponent(crew_cost)+'&transport_cost='+encodeURIComponent(transport_cost)+'&listitems2='+encodeURIComponent(listitems2)+'&cityid='+encodeURIComponent(cityid)+'&cityname='+encodeURIComponent(cityname)+'&add_gst='+encodeURIComponent(add_gst)+'&manual_notes_added='+encodeURIComponent(manual_notes_added)+'&dummy='+Math.floor(Math.random()*100032680100);

    //console.log(passData);return false;
	
	$.ajax({
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",async: false,
			success: function(ajaxresponse,status)
			{
		  	 	var response = ajaxresponse;
		  	 	var lastquoteid = response['lastquoteid'];
                window.location.href = lastquoteid;
            },
			error: function(jqXHR, exception) 
			{
				console.log('error in Pay link generation');
			}
    });

    /*---------------------Ends Form Submission changed to Ajax-----------------*/
}

function changeToUpperCases(t) {
   var eleVal = document.getElementById(t.id);
   eleVal.value= eleVal.value.toUpperCase();
}

function emptyaddremove() {
    var totalcartitems = $("div[id^='cartitems']").length;
    console.log(totalcartitems);
    if(totalcartitems == 0) {
        $('#emptycart').show();
    }
    else {
        $('#emptycart').hide();
    }
}

function showmydata() {
    var package_prices = parseInt($('span.package_price').text());
	var mytotal = package_prices;
	$('#chosenamountval').text('Event Package Total : Rs '+mytotal+'/-');
}

$(document).on('click', "#send_specail_event", function(event) {    
    event.preventDefault();
  	var name = $("#username").val();
	var email = $("#useremail").val();
	var event_date = $("#sel_date").val();
	var mobile = $("#usermobile").val();
	var description = $("#userdescription").val();
	var submit = $("#send_specail_event").val();
	
	name = name.trim();
	email = email.trim();
	mobile = mobile.trim();
	
	event_date = event_date.trim();
	description = description.trim();
	submit = submit.trim();
		
	if(name == '' || email == '' || mobile == '' || event_date == '' || description == '') 
	{
		if(name == '') 
		{ 
			$("#username").focus().css({ 'border': '1px solid red' }); 
			$('.error').text('Please Enter Name');
			$('.error').show();
		}
		else if(email == '') 
		{ 
			$("#useremail").focus().css({ 'border': '1px solid red' }); 
			$('.error').text('Please Enter Email');
			$('.error').show();
		}
		else if(mobile == '') 
		{ 
			$("#usermobile").focus().css({ 'border': '1px solid red' }); 
			$('.error').text('Please Enter Mobile');
			$('.error').show();
		}
		else if(event_date == '') 
		{ 
			$("#sel_date").focus().css({ 'border': '1px solid red' }); 
			$('.error').text('Please Enter Date');
			$('.error').show();
		}

		return false;
	}
	else 
	{	
		$('.error').hide();
		var dataString = 'switch=contactmail&name='+encodeURIComponent(name)+'&email='+encodeURIComponent(email)+'&mobile='+encodeURIComponent(mobile)+'&event_date='+encodeURIComponent(event_date)+'&description='+encodeURIComponent(description)+'&dummy='+Math.floor(Math.random()*100032680100);
			
		$.ajax({
					type: "POST",
					url: "ajax/specialevent.php",
					data: dataString,
					cache: false,
					success: function(result)
					{
						var response = result;
						var res = response.split("^");
						$('.errors').text(res[1]);
						$('.errors').show();
						//resetform();
						if(res[0] != '3') {
						    resetform();
						}
						if(res[0] == '3') {
						    $("#useremail").focus().css({ 'border': '1px solid red' });
						}
						setTimeout(function(){ $('a[href="#close-modal"]').click(); }, 3000);
					},
					error: function(jqXHR, exception) 
					{
						console.log('error in no ajax');
					}
		});
	}
});

$(".numberOnly").on("keypress keyup blur",function (event) {
   $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});


/*----------------Added for Agent Booking ----------*/
var internalcounter = 0;
function addfields() 
{
    var event_date = $('#demos').val();
    var no_of_guest_exp = $("#no_of_guest option:selected").val();

    var added = $("#test2 option:selected").val();
    if(added == '') { alert ('Please select item type'); $('#test2').focus(); return false;}
    
    //if($('#manuallyadded').length < 1){
    if(added == 'Qty'){
        internalcounter++;
        var useraddeditems = '<div id="manuallyadded'+internalcounter+'" data-itemsort="manuallyadded'+internalcounter+'" class="row myrow group"><div class="content"><div class="col-md-4 col-xs-12"><textarea style="height: 46px;" name="particularname" id="particularname" rows="2" cols="20" class="form-control style_box" Placeholder="Enter Name"></textarea></div><div class="col-md-2 col-xs-12"><input type="text" name="partiquantity" id="partiquantity" value="" class="form-control numberOnly style_box" Placeholder="Enter Quantity"></div><div class="col-md-2 col-xs-12"><input type="text" name="partiamount" id="partiamount" value="" class="form-control numberOnly style_box" Placeholder="Enter Amount"></div><div class="col-md-2 col-xs-12"><input type="text" name="partidays" id="partidays" value="" class="form-control numberOnly style_box" Placeholder="Enter Days"></div><div class="col-md-2 col-xs-12"><button class="btn btn-warning btn-lg db buttontoadd" onclick="addfieldstocart(\'manuallyadded'+internalcounter+'\');"><i class="fa fa-plus" aria-hidden="true"></i></button>&nbsp;&nbsp;<button class="btn btn-warning btn-lg db" onclick="removemanual(\'manuallyadded'+internalcounter+'\');"><i class="fa fa-times" aria-hidden="true"></i></button></div></div></div>';
        $("#useradded").append(useraddeditems);
    } 
    else if(added == 'Sq.ft'){
        internalcounter++;
        var useraddeditems = '<div id="manuallyadded'+internalcounter+'" data-itemsort="manuallyadded'+internalcounter+'" class="row myrow group"><div class="content"><div class="col-md-4 col-xs-12"><textarea style="height: 46px;" name="particularname" id="particularname" rows="2" cols="20" class="form-control style_box" Placeholder="Enter Name"></textarea></div><div class="col-md-1 col-xs-12"><input type="text" name="partiwidth" id="partiwidth" value="" class="form-control numberOnly style_box" Placeholder="Width"></div><div class="col-md-1 col-xs-12"><input type="text" name="partiheight" id="partiheight" value="" class="form-control numberOnly style_box" Placeholder="Height"></div><div class="col-md-2 col-xs-12"><input type="text" name="partiamount" id="partiamount" value="" class="form-control numberOnly style_box" Placeholder="Enter Amount"></div><div class="col-md-2 col-xs-12"><input type="text" name="partidays" id="partidays" value="" class="form-control numberOnly style_box" Placeholder="Enter Days"></div><div class="col-md-2 col-xs-12"><button class="btn btn-warning btn-lg db buttontoadd" onclick="addfieldstocarts(\'manuallyadded'+internalcounter+'\');"><i class="fa fa-plus" aria-hidden="true"></i></button>&nbsp;&nbsp;<button class="btn btn-warning btn-lg db" onclick="removemanual(\'manuallyadded'+internalcounter+'\');"><i class="fa fa-times" aria-hidden="true"></i></button></div></div></div>';
        $("#useradded").append(useraddeditems);
    } 
}

function removemanual(value) {
    $("#"+value).remove();
    $("#mycart ."+value).remove();
    $("#test2 option:first").attr('selected','selected');
    finalCalculation();
    emptyaddremove();
}

/*-------------on City change empty cart-------------------*/

function emptycart() {
    //var city = $("#city option:selected").val();
    var city = $("#city").val();
    if(city == '' || city === undefined) {
        console.log('Clicked City Change');
    }
    else {
        console.log('Clicked City : '+city);
    }
}

/*--------------ends --------------------*/

/*Add cart data only to admin */

$("#ordertosave").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#ordertosave_err").html("Digits Only").show().fadeOut("slow");
               return false;
    }
});
   
function savetempcart() {
    
    var ordertosave = $('#ordertosave').val();
    
    if(ordertosave == '' || ordertosave == undefined || ordertosave.length != 10) {
        
        alert('Please add mobile to proceed');
        return false;
    }
    
    /*Added for GST */
    var add_gst = 'no';
    if($("#add_gst").is(':checked')) {
        add_gst = 'yes';
    }
    /*Ends Added for GST*/
    
    var manual_notes_added = $('#manual_notes_added').val();
    if(manual_notes_added != '' && manual_notes_added != undefined)
    {
        var manual_notes_added = manual_notes_added.replace(/(?:\r\n|\r|\n)/g, '<br />');
    }    

    var listitems = '';var listitems2 = '';
    var particular = '';var item_price = '';var item_qty = '';var net_price = '';var item_height = '';var item_width = '';var pack_desc = '';var partidays = '';
    var i = 0;
    
    var manual_discount = 0;
    if($("#manual_discount").length){
        manual_discount = $('#manual_discount').val();
        if((isNaN(manual_discount)) || manual_discount == undefined  || manual_discount == '') { manual_discount = ''; }
    }
    
    var event_date = $('#demos').val();
    
    var total_price = $('#total_price').val();
    total_price = total_price.trim();
    total_price = parseInt(total_price);
    
    var discount_percent = $('#discount_percent').val();
    var discount_amnt = $('#discount_amnt').val();
    
    var cityid = $("#cityid").val();
    //var cityname = $("#city option:selected").val();
     var cityname = $("#city").val();
     
    var transport_cost = $("#transport_cost").val();
    var crew_cost = $("#crew_cost").val();
    
    $("div[id^='cartitems']").each(function()
    {
        if ($(this).hasClass("generalitem")) {
		    //console.log("i have the generalitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = $(this).find('input[name=quantity]').val();
		    pack_desc = $(this).find('input[name=pack_desc]').val();
		    partidays = $(this).find('input[name=partidays]').val();
		    if(pack_desc == undefined) {
		        pack_desc = '';
		    }
		    net_price = $(this).find('.calamount').text();
		    net_price = parseInt(net_price);
		    item_height = '';
		    item_width = '';
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+pack_desc+'^'+partidays+'#';
		    listitems += arrayitems;
		    allowtrans = true;
	    }
	    else if ($(this).hasClass("specialitem") ) {
		    //console.log("i have the specialitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = '';
		    pack_desc = $(this).find('input[name=pack_desc]').val();
		    partidays = $(this).find('input[name=partidays]').val();
		    if(pack_desc == undefined) {
		        pack_desc = '';
		    }
		    net_price = $(this).find('.calamount').text();
		    net_price = parseInt(net_price);
		    item_height = parseInt($(this).find('.qty1').val());
		    item_width = parseInt($(this).find('.qty2').val());
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+pack_desc+'^'+partidays+'#';
		    listitems += arrayitems;
		    allowtrans = true;
	    }
	    else if ($(this).hasClass("generalitems")) {
		    //console.log("i have the generalitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = $(this).find('input[name=quantity]').val();
		    net_price = $(this).find('.calamount').text();
		    partidays = $(this).find('input[name=partidays]').val();
		    net_price = parseInt(net_price);
		    item_height = '';
		    item_width = '';
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+partidays+'#';
		    listitems2 += arrayitems;
		    allowtrans = true;
	    }
	    else if ($(this).hasClass("specialitems") ) {
		    //console.log("i have the specialitem class");
		    
		    particular = $(this).find('input[name=particular]').val();
		    item_price = $(this).find('input[name=item_price]').val();
		    item_qty = '';
		    net_price = $(this).find('.calamount').text();
		    net_price = parseInt(net_price);
		    item_height = parseInt($(this).find('.qty1').val());
		    item_width = parseInt($(this).find('.qty2').val());
		    partidays = $(this).find('input[name=partidays]').val();
		    
		    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+partidays+'#';
		    listitems2 += arrayitems;
		    allowtrans = true;
	    }
    });

    var send_quote = 'save_temp_quote';
    var queryString =  '/savecartdetails';
    var passData = 'switchtype=savecartdetails&ordertosave='+encodeURIComponent(ordertosave)+'&event_date='+encodeURIComponent(event_date)+'&total_price='+encodeURIComponent(total_price)+'&manual_discount='+encodeURIComponent(manual_discount)+'&discount_percent='+encodeURIComponent(discount_percent)+'&discount_amnt='+encodeURIComponent(discount_amnt)+'&listitems='+encodeURIComponent(listitems)+'&crew_cost='+encodeURIComponent(crew_cost)+'&transport_cost='+encodeURIComponent(transport_cost)+'&listitems2='+encodeURIComponent(listitems2)+'&cityid='+encodeURIComponent(cityid)+'&cityname='+encodeURIComponent(cityname)+'&add_gst='+encodeURIComponent(add_gst)+'&manual_notes_added='+encodeURIComponent(manual_notes_added)+'&dummy='+Math.floor(Math.random()*100032680100);

    //console.log(passData);return false;
	
	$.ajax({
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",async: false,
			success: function(ajaxresponse,status)
			{
		  	 	var response = ajaxresponse;
		  	 	var lastquoteid = response['lastquoteid'];
		  	 	
                if(lastquoteid == '') 
                {
		  	 	    alert('Sorry the details could not be added. Check connection or try again');
		  	 	}
		  	 	else 
		  	 	{
                       alert('Cart details have been added');
                       //window.location.reload(); 
		  	 	}
			},
			error: function(jqXHR, exception) 
			{
				console.log('error in Pay link generation');
			}
    });
    
}

/*Ends Add cart data only to admin */
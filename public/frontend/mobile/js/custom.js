/*============================common js for packages , euipments,  weddings and marraiges coms here ====================*/
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

/*============================common js for packages , euipments,  weddings and marraiges ends here ====================*/

/*======================== Js functions written or edited by samar ======================*/

$(document).ready(function() {
    $("#item_event_equipmentss").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listvalss li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$(document).on('click', '#clrFilter', function() {
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

/*======================== /. Ends Js functions written or edited by samar ======================*/
/*======================== For homepage loader image ======================*/
$(".first").click(function(){
  $(".loads1").hide();
});
/*======================== ends For homepage loader image ======================*/

function rmvtextfunc() {
    $("#item_event_equipmentss").val('').focus(); 
}
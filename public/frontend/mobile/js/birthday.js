$(document).ready(function() {
    $("#item_event_equipmentss").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listvalss li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$('#clrFilter').click(function() {
    $(':checkbox').each(function() {
        $(this).removeAttr('checked');
        $("li label").removeClass("select");
    })

});
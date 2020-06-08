$(document).ready(function() {


    $('.count').prop('disabled', true);
    $(document).on('click', '.plus', function() {
        $('.count').val(parseInt($('.count').val()) + 1);
    });
    $(document).on('click', '.minus', function() {
        $('.count').val(parseInt($('.count').val()) - 1);
        if ($('.count').val() == 0) {
            $('.count').val(1);
        }
    });


    $('.count1').prop('disabled', true);
    $(document).on('click', '.plus1', function() {
        $('.count1').val(parseInt($('.count1').val()) + 1);
    });
    $(document).on('click', '.minus1', function() {
        $('.count1').val(parseInt($('.count1').val()) - 1);
        if ($('.count1').val() == 0) {
            $('.count1').val(1);
        }
    });

    $('.count2').prop('disabled', true);
    $(document).on('click', '.plus2', function() {
        $('.count2').val(parseInt($('.count2').val()) + 1);
    });
    $(document).on('click', '.minus2', function() {
        $('.count2').val(parseInt($('.count2').val()) - 1);
        if ($('.count2').val() == 0) {
            $('.count2').val(1);
        }
    });


    $('.count3').prop('disabled', true);
    $(document).on('click', '.plus3', function() {
        $('.count3').val(parseInt($('.count3').val()) + 1);
    });
    $(document).on('click', '.minus3', function() {
        $('.count3').val(parseInt($('.count3').val()) - 1);
        if ($('.count3').val() == 0) {
            $('.count3').val(1);
        }
    });

    $('.count4').prop('disabled', true);
    $(document).on('click', '.plus4', function() {
        $('.count4').val(parseInt($('.count4').val()) + 1);
    });
    $(document).on('click', '.minus4', function() {
        $('.count4').val(parseInt($('.count4').val()) - 1);
        if ($('.count4').val() == 0) {
            $('.count4').val(1);
        }
    });


    $('.count5').prop('disabled', true);
    $(document).on('click', '.plus5', function() {
        $('.count5').val(parseInt($('.count5').val()) + 1);
    });
    $(document).on('click', '.minus5', function() {
        $('.count5').val(parseInt($('.count5').val()) - 1);
        if ($('.count5').val() == 0) {
            $('.count5').val(1);
        }
    });

});
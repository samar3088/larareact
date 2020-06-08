$(document).on("click", "#home_footer_modal_btn", function(event)
{
    $('#form_result').html('');    
    event.preventDefault();
    var footerForm = document.getElementById('home_footer_modal');
    $.ajax
    ({
        url:'/homemodalmail',
        method:"POST",
        data: new FormData(footerForm),
        contentType: false,
        cache:false,
        processData: false,
        dataType:"json",
        success:function(data)
        {
            var html = '';
            if(data.errors)
            {
                html = '<div class="alert alert-danger">';
                for(var count = 0; count < data.errors.length; count++)
                {
                    html += '<p>' + data.errors[count] + '</p>';
                }
                html += '</div>';
            }
            if(data.success)
            {
                html = '<div class="alert alert-success">' + data.success + '</div>';
                $('#home_footer_modal')[0].reset();
                setTimeout(function(){ /* $("#myModal").modal("hide"); $(".modal-backdrop").remove(); $('body').removeClass('modal-open'); */ $("#closemodal").click(); }, 1500);
            }
            $('#form_result').html(html);
        }
     })
});

$(document).on("click", "#bday_footer_modal_btn", function(event)
{
    $('#form_result').html('');    
    event.preventDefault();
    var footerForm = document.getElementById('bday_footer_modal');
    $.ajax
    ({
        url:'/bdaymodalmail',
        method:"POST",
        data: new FormData(footerForm),
        contentType: false,
        cache:false,
        processData: false,
        dataType:"json",
        success:function(data)
        {
            var html = '';
            if(data.errors)
            {
                html = '<div class="alert alert-danger">';
                for(var count = 0; count < data.errors.length; count++)
                {
                    html += '<p>' + data.errors[count] + '</p>';
                }
                html += '</div>';
            }
            if(data.success)
            {
                html = '<div class="alert alert-success">' + data.success + '</div>';
                $('#bday_footer_modal')[0].reset();
                setTimeout(function(){ /* $("#myModal").modal("hide"); $(".modal-backdrop").remove(); $('body').removeClass('modal-open'); */ $("#closemodal").click(); }, 1500);
            }
            $('#form_result').html(html);            
        }
     })
});

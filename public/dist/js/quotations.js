var internalcounter = 0;
var added_items_counter = 0;
var quote_type_edit = '';

    $(document).ready(function() {

        /*--------------Manually added function to showpopup quote data in pop up-----------------*/

        $(document).on('click', '.showpopup', function(){
            var id = $(this).attr('id');
            quote_type_edit = 'general_quote';
            $('#customers_quote_data table tbody').empty();
            $('div.myrows').remove();
            $('#form_result').html('');
            $('#savemodaldetails').val(id);
            $.ajax
            ({
                url:"/admin/quotations/genquote/"+id,
                dataType:"json",
                cache: false,
                success: function(ajaxresponse,xhrstatus)
                {
                    var response = ajaxresponse;

                    /* var mailbody = response['mailbody'];
                    var transport_cost = response['transport_cost'];
                    var crew_cost = response['crew_cost'];
                    var manual_discount = response['manual_discount'];
                    var discount_percent = response['discount_percent'];
                    var discount_amount = response['discount_amount'];
                    var coupon_code = response['coupon_code'];
                    var coupon_discount = response['coupon_discount'];
                    var internalcounter = response['internalcounter'];
                    var added_gst = response['added_gst']; */

                    var mailbody = response.mailbody;
                    var transport_cost = response.transport_cost;
                    var crew_cost = response.crew_cost;
                    var manual_discount = response.manual_discount;
                    var discount_percent = response.discount_percent;
                    var discount_amount = response.discount_amount;
                    var coupon_code = response.coupon_code;
                    var coupon_discount = response.coupon_discount;
                    var internalcounter = response.internalcounter;
                    var added_gst = response.added_gst;

                    if(added_gst == 'yes')
                    {
                        $('#add_gst').prop('checked', true);
                        $('#add_gst').val(added_gst);
                        $('.show_gst_data').show();
                    }
                    else if(added_gst == 'no')
                    {
                        $('#add_gst').prop('checked', false);
                        $('#add_gst').val(added_gst);
                        $('.show_gst_data').hide();
                    }

                    $('#customers_quote_data table tbody').append(mailbody);
                    $("#form_result").hide().text('');

                    //$('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit quotation details");
                    $('#action_button').val("Edit");
                    $('#action').val("Edit");
                    $('#formModal').modal({backdrop: 'static', keyboard: false, show: true});
                },
                error: function(a,b)
                {
                    $("#form_result").show().text('Please refresh and try again');
                    makefieldempty();
                }
            });
        });

        /*--------------Manually added function to showpopup data in pop up Ends -----------------*/

        /*--------------Manually added function to delete data -----------------*/

        $(document).on('click', '.delete', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $.ajax({
                    url:"/admin/quotations/destroy/"+id,
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#user_table').DataTable().ajax.reload();
                    }
                });
            }
            else
            {
                return false;
            }
        });

        /*--------------Manually added function to delete data Ends -----------------*/

        /*--------------Manually added function update items in the quote -----------------*/

        $(document).on("keyup", "input.editfields" , function() {

            var item_id = encodeURIComponent($(this).prop("id"));
            var item_value = encodeURIComponent($(this).val());
            var quoteid = $('#savemodaldetails').val();

            if(item_value == '' || item_value == undefined)
            {
                item_value = 0;
            }

            $.ajax
            ({
                url:"/admin/quotations/editfields/"+quoteid+"/"+item_id+"/"+item_value,
                type: "GET",
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                data:{"quoteid":quoteid,"item_id":item_id,"item_value":item_value},
                success: function(ajaxresponse,xhrstatus)
                {
                    var response = ajaxresponse;
                },
                error: function(a,b)
                {
                    $(".modalerror").show().text('Please refresh and try again');
                }
            });

            finalCalculation(quote_type_edit);
        });

        /*--------------Manually added function update items in the quote ends -----------------*/

        $("#formModal").on("hide.bs.modal", function () {
            makefieldempty();
        });

        $(document).on("click", "span.edititem" , function() {

            var item = $(this).data("name");

            if ($('#'+item).is('[readonly]'))
            {
                $('#'+item).prop("readonly", false);
                $('#'+item).css({"border":"1px solid gray"});
            }
            else
            {
                $('#'+item).prop("readonly", true);
                $('#'+item).css({"border":"0px solid gray"});
            }
        });

        $(document).on("click", "span.removeitem" , function() {

            var item = $(this).data("name");
            $('tr.'+item).remove();

            if(item == 'package_row') {
                $('#package_detail_id').val('');
                $('#package_cost').val('');
            }

            finalCalculation(quote_type_edit);
        });

        $(document).on("click", "span.remove_item_row" , function() {
            var item = $(this).data("name");
            $('tr.'+item).remove();
            finalCalculation(quote_type_edit);
        });

        $(document).on("click", "span.remove_equipment_row" , function() {
            var item = $(this).data("name");
            //$('tr.'+item).remove();
            if($(this).closest("tr").prop('id'))
            {
                var parent_id = $(this).closest("tr").prop('id');
                console.log('parent_id : ' + parent_id);
                $('div#'+parent_id+' .buttontoadd').prop("disabled",false);
            }
            $(this).closest("tr").remove();
            finalCalculation(quote_type_edit);
        });

    });

     /*--------------Page related manual functions -----------------*/

    function updategst()
    {
        var add_gst = '';

        if($("#add_gst").is(':checked'))
        {
            add_gst = 'yes';
            
            if($('.show_gst_data').length == 0)
            {
                var transport_cost = 0;var crew_cost = 0;var discount_percent = 0;var discount_coupon_code = 0;var manual_discount = 0;

                if($('#discount_for_dealers_percent').length)
                {
                    discount_percent = $('#discount_for_dealers_percent').val();
                }

                if($('#discount_coupon_code').length)
                {
                    discount_coupon_code = $('#discount_coupon_code').val();
                }

                if($('#manual_discount').length)
                {
                    manual_discount = $('#manual_discount').val();
                }

                var no_of_days = $("#no_of_days").val();
                var fill_total_amount_pre = parseInt($('.fill_total_amount_pre').text());

                if(discount_percent != '' && discount_percent > 0 && !isNaN(discount_percent) && discount_percent != undefined)
                {
                    fill_total_amount_pre = Math.round(fill_total_amount_pre-(fill_total_amount_pre*(discount_percent/100)));
                }
                if(discount_coupon_code != '' && discount_coupon_code > 0 && !isNaN(discount_coupon_code) && discount_coupon_code != undefined)
                {
                    fill_total_amount_pre = fill_total_amount_pre - parseInt(discount_coupon_code);
                }
                if(manual_discount != '' && manual_discount > 0 && !isNaN(manual_discount) && manual_discount != undefined)
                {
                    fill_total_amount_pre = fill_total_amount_pre - parseInt(manual_discount);
                }

                if($('#transport_cost').length) { transport_cost = $('#transport_cost').val(); }
                if($('#crew_cost').length) { crew_cost = $('#crew_cost').val(); }

                var midtotal_amount = parseInt(fill_total_amount_pre) + parseInt(transport_cost) + parseInt(crew_cost);
                var cgst_data = Math.round(midtotal_amount*0.09);
                var sgst_data = Math.round(midtotal_amount*0.09);

                var manualgst_row = '<tr style="" class="show_gst_data"><td colspan="3" class="right" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Add CGST: 9%</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <span class="add_cgst_data">'+cgst_data+'</span></td></tr><tr class="show_gst_data"><td colspan="3" class="right" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Add SGST: 9%</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <span class="add_sgst_data">'+sgst_data+'</span></td></tr>';
                $('#amount_payable_row').before(manualgst_row);
            }
            else
            {
                console.log('gst exists');
            }
            $('.show_gst_data').show();
        }
        else
        {
            add_gst = 'no';
            $('.show_gst_data').hide();
        }

        var quoteid = $('#savemodaldetails').val();

        $.ajax
        ({
            url:"/admin/quotations/updategst/"+quoteid+"/"+add_gst,
            type: "GET",
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            data:{"quoteid":quoteid,"add_gst":add_gst},
            success: function(ajaxresponse,xhrstatus)
            {
                var response = ajaxresponse;
            },
            error: function(a,b)
            {
                $(".modalerror").show().text('Please refresh and try again');
            }
        });

        finalCalculation(quote_type_edit);
    }

    function makefieldempty()
    {
        $('#savemodaldetails').val('');
        $('#add_gst').prop('checked', false);
        internalcounter = 0;
        added_items_counter = 0;
        $('div.myrows').remove();
        quote_type_edit = '';
        $('#cc_mails').val('');
        $('#bcc_mails').val('');
    }

    function additems(value1,value2)
    {
        var item_name = value1;
        var item_row = value2;

        var item_title = '';var item = '';

        var finalamount = $('#finalamount').val();
        var no_of_days = $('#no_of_days').val();

        var element_id = '';

        if($('#added_final_items_coupon').length) {
            element_id = '#added_final_items_coupon';
        }
        else if($('#added_final_items_manual').length) {
            element_id = '#added_final_items_manual';
        }
        else if($('#added_final_items_dealers').length) {
            element_id = '#added_final_items_dealers';
        }
        else if($('#added_final_items').length) {
            element_id = '#added_final_items';
        }

        if(item_name == 'transport_cost') {

            if($('tr.transport_cost_row').length == 0)
            {
                item_title = 'Transportation Cost';
                item = '<tr class="'+item_row+'"><td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">'+item_title+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <input class="editfields" name="'+item_name+'" id="'+item_name+'" value="0" readonly><span data-name="'+item_row+'" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="'+item_name+'" class="edititem"><i class="fas fa-edit"></i> | </span></td></tr>';
                $(element_id).after(item);
            }
            else {
                alert('Please remove current transportation cost');
            }
        }
        else if(item_name == 'crew_cost') {

            if($('tr.crew_cost_row').length == 0)
            {
                item_title = 'Crew Transportation Cost';
                item = '<tr class="'+item_row+'"><td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">'+item_title+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <input class="editfields" name="'+item_name+'" id="'+item_name+'" value="0" readonly><span data-name="'+item_row+'" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="'+item_name+'" class="edititem"><i class="fas fa-edit"></i> | </span></td></tr>';
                $(element_id).after(item);
            }
            else {
                alert('Please remove current crew transportation cost');
            }
        }
        else if(item_name == 'manual_discount') {

            if($('tr.manual_discount_row').length == 0)
            {
                item_title = 'Discount (Manually given)';
                item = '<tr class="'+item_row+'"><td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">'+item_title+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <input class="editfields" name="'+item_name+'" id="'+item_name+'" value="0" readonly><span data-name="'+item_row+'" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="'+item_name+'" class="edititem"><i class="fas fa-edit"></i> | </span></td></tr>';
                $(element_id).after(item);
            }
            else {
                alert('Please remove current discount row');
            }
        }

        finalCalculation(quote_type_edit);
    }

    function additems_new(value1,value2)
    {
        var item_name = value1;
        var item_row = value2;

        var item_title = '';var item = '';

        //var quote_type_edit = quote_type_edit;

        if(item_name == 'Qty')
        {
            internalcounter++;
            var useraddeditems = '<div id="manuallyadded-'+internalcounter+'" class="row myrows"><div class="col-md-4 col-xs-12"><textarea style="height: 46px;" name="particularname" id="particularname" rows="2" cols="20" class="form-control style_box" Placeholder="Please enter Particular Name"></textarea></div><div class="col-md-2 col-xs-12"><input type="text" name="partiquantity" id="partiquantity" value="" class="form-control numberOnly style_box" Placeholder="Quantity"></div><div class="col-md-2 col-xs-12"><input type="text" name="partiamount" id="partiamount" value="" class="form-control numberOnly style_box" Placeholder="Amount"></div><div class="col-md-2 col-xs-12"><input type="text" name="partidays" id="partidays" value="" class="form-control numberOnly style_box" Placeholder="Days"></div><div class="col-md-2 col-xs-12"><button class="btn btn-warning db buttontoadd" onclick="addfieldstocart(\'manuallyadded-'+internalcounter+'\');"><i class="fas fa-check-double"></i></button>&nbsp;&nbsp;<button class="btn btn-warning db" onclick="removemanual(\'manuallyadded-'+internalcounter+'\');"><i class="fas fa-window-close" aria-hidden="true"></i></button></div></div>';
            $("#useradded").append(useraddeditems);
        }
        else if(item_name == 'Sq.ft')
        {
            internalcounter++;
            var useraddeditems = '<div id="manuallyadded-'+internalcounter+'" class="row myrows"><div class="col-md-4 col-xs-12"><textarea style="height: 46px;" name="particularname" id="particularname" rows="2" cols="20" class="form-control style_box" Placeholder="Name"></textarea></div><div class="col-md-1 col-xs-12"><input type="text" name="partiwidth" id="partiwidth" value="" class="form-control numberOnly style_box" Placeholder="W"></div><div class="col-md-1 col-xs-12"><input type="text" name="partiheight" id="partiheight" value="" class="form-control numberOnly style_box" Placeholder="H"></div><div class="col-md-2 col-xs-12"><input type="text" name="partiamount" id="partiamount" value="" class="form-control numberOnly style_box" Placeholder="Amount"></div><div class="col-md-2 col-xs-12"><input type="text" name="partidays" id="partidays" value="" class="form-control numberOnly style_box" Placeholder="Days"></div><div class="col-md-2 col-xs-12"><button class="btn btn-warning db buttontoadd" onclick="addfieldstocarts(\'manuallyadded-'+internalcounter+'\');"><i class="fas fa-check-double"></i></button>&nbsp;&nbsp;<button class="btn btn-warning db" onclick="removemanual(\'manuallyadded-'+internalcounter+'\');"><i class="fas fa-window-close" aria-hidden="true"></i></button></div></div>';
            $("#useradded").append(useraddeditems);
        }
    }

    function removemanual(value)
    {
        $("div#"+value).remove();
        if($("#customers_quote_data tr#"+value).length)
        {
            $("#customers_quote_data tr#"+value).remove();
        }
        finalCalculation(quote_type_edit);
    }

    function addfieldstocart(value) {

        var particularname = $('#'+value +' #particularname').val();
        var partiquantity = $('#'+value +' #partiquantity').val();
        var partiamount = $('#'+value +' #partiamount').val();
        var partidays = $('#'+value +' #partidays').val();

        particularname = particularname.replace(/(?:\r\n|\r|\n)/g, '<br />');

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
            added_items_counter++;

            var net_price = parseInt(partiquantity) * parseInt(partiamount) * parseInt(partidays);
            var mid_total = parseInt(net_price);

            /*----------------------items added ----------------------------*/

            var cart = '<tr id="'+value+'" class="items_from_manual_entry generalitems"><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:50%;">'+particularname+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;">'+partidays+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;" class="invoiceitems_details">'+partiquantity+'<input type="hidden" name="parti[]" value="'+particularname+'" class="manual_parti"><input type="hidden" name="qtt[]" value="'+partiquantity+'" class="manual_qtt"><input type="hidden" name="heightee[]" value="" class="manual_heightee"><input type="hidden" name="item_width[]" value="" class="manual_item_width"><input type="hidden" name="item_partidays[]" value="'+partidays+'" class="manual_partidays"><input type="hidden" name="ammt[]" value="'+partiamount+'" class="manual_ammt"><input type="hidden" name="calc[]" value="'+net_price+'" class="calamount"></td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;"> Rs. <span class="fill_item_total">'+mid_total+'</span><span data-name="items_from_manual_entry" class="remove_equipment_row"><i class="fas fa-window-close"></i></span></td></tr>';

            if($('#package_row_2').length)
            {
                $("#package_row_2").before(cart);
            }
            else
            {
                $("#added_final_items").before(cart);
            }
            /*---------------Ends-----------------*/

            $('#'+value+' .buttontoadd').attr("disabled", true);
            finalCalculation(quote_type_edit);
        }
    }

    function addfieldstocarts(value)
    {
        var particularname = $('#'+value +' #particularname').val();
        var partiwidth = $('#'+value +' #partiwidth').val();
        var partiheight = $('#'+value +' #partiheight').val();
        var partiamount = $('#'+value +' #partiamount').val();
        var partidays = $('#'+value +' #partidays').val();

        particularname = particularname.replace(/(?:\r\n|\r|\n)/g, '<br />');

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
        else
        {
            added_items_counter++;
            /*----------------------items added ----------------------------*/
            var net_price = parseInt(partiwidth) * parseInt(partiheight) * parseInt(partiamount) * parseInt(partidays);
            var mid_total = parseInt(net_price);

            var fulldata = partiwidth+'*'+partiheight;

            var cart = '<tr id="'+value+'" class="items_from_manual_entry specialitems"><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:50%;">'+particularname+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;">'+partidays+'</td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;" class="invoiceitems_details">'+fulldata+'<input type="hidden" name="parti[]" value="'+particularname+'" class="manual_parti"><input type="hidden" name="qtt[]" value="" class="manual_qtt"><input type="hidden" name="heightee[]" value="'+partiheight+'" class="manual_heightee"><input type="hidden" name="item_width[]" value="'+partiwidth+'" class="manual_item_width"><input type="hidden" name="item_partidays[]" value="'+partidays+'" class="manual_partidays"><input type="hidden" name="ammt[]" value="'+partiamount+'" class="manual_ammt"><input type="hidden" name="calc[]" value="'+net_price+'" class="calamount"></td><td style="border: 1px solid #d4603c;text-align: left;padding: 8px;"> Rs. <span class="fill_item_total">'+mid_total+'</span><span data-name="items_from_manual_entry" class="remove_equipment_row"><i class="fas fa-window-close"></i></span></td></tr>';

            if($('#package_row_2').length)
            {
                $("#package_row_2").before(cart);
            }
            else
            {
                $("#added_final_items").before(cart);
            }

            /*---------------Ends-----------------*/
            $('#'+value+' .buttontoadd').attr("disabled", true);

            finalCalculation(quote_type_edit);
        }
    }

    function finalCalculation(quote_type_edit)
    {
            var quote_type_edit = quote_type_edit;
            var total = 0;var mytotal = 0;var new_amount = 0;var package_price = 0;var discount_percent = 0;var discount_coupon_code = 0;var manual_discount = 0;
            var transport_cost = 0;var crew_cost = 0;
            var no_of_days = $("#no_of_days").val();

            if($('#package_cost').length)
            {
                package_price = $('#package_cost').val();
                package_price = (parseInt(package_price)*no_of_days);
                $('.fill_package_price').text(package_price);
            }

            if(isNaN(package_price) || (typeof package_price === "undefined") || package_price == 'undefined')
            {
                package_price = 0;
            }

            if($('#discount_for_dealers_percent').length)
            {
                discount_percent = $('#discount_for_dealers_percent').val();
            }

            if($('#discount_coupon_code').length)
            {
                discount_coupon_code = $('#discount_coupon_code').val();
            }

            if($('#manual_discount').length)
            {
                manual_discount = $('#manual_discount').val();
            }

            if($('#transport_cost').length)
            {
                transport_cost = $('#transport_cost').val();
            }

            if($('#crew_cost').length)
            {
                crew_cost = $('#crew_cost').val();
            }

            $(".calamount").each(function() {
                var calamount = parseInt($(this).val());
                total = +total + +calamount;
                $(this).closest('td').next('td').find('span.fill_item_total').text(calamount);
            });

            total = +total + +package_price;
            mytotal = total;

            $('.fill_total_amount_pre').text(mytotal);

            if(discount_percent != '' && discount_percent > 0 && !isNaN(discount_percent) && discount_percent != undefined)
            {
                total = Math.round(total-(total*(discount_percent/100)));
                var discount_percent_amount = new_amount;
                $('.discount_percent_amount').text(discount_percent_amount);
            }
            if(discount_coupon_code != '' && discount_coupon_code > 0 && !isNaN(discount_coupon_code) && discount_coupon_code != undefined)
            {
                total = total - parseInt(discount_coupon_code);
            }
            if(manual_discount != '' && manual_discount > 0 && !isNaN(manual_discount) && manual_discount != undefined)
            {
                total = total - parseInt(manual_discount);
            }

            if((isNaN(transport_cost)) || transport_cost == undefined) { transport_cost = 0; }
            if((isNaN(crew_cost)) || crew_cost == undefined) { crew_cost = 0; }

            total = parseInt(crew_cost) + parseInt(transport_cost) + parseInt(total);

            if($("#add_gst").is(':checked'))
            {
                var cgst_data = Math.round(total*0.09);
                var sgst_data = Math.round(total*0.09);

                total = parseInt(total) + parseInt(cgst_data) + parseInt(sgst_data);

                $('.add_cgst_data').text(cgst_data);
                $('.add_sgst_data').text(sgst_data);
            }
            else
            {
                var cgst_data = 0;
                var sgst_data = 0;
                $('.add_cgst_data').text(cgst_data);
                $('.add_sgst_data').text(sgst_data);
            }

            $('.fill_total_amount_pay').text(total);
            $('#finalamount').val(total);
    }

     /*--------------Page related manual functions Ends -----------------*/

    function saveremarks(value)
    {
        var quoteid = value;
        var remarks = $('#remarks-'+quoteid).val();
        //var remarks = remarks.replace(/(?:\r\n|\r|\n)/g, '<br />');

        remarks = encodeURIComponent(remarks);


        $.ajax
        ({
            url:"/admin/quotations/saveremarks/"+quoteid+"/"+remarks,
            type: "GET",
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            data:{"quoteid":quoteid,"remarks":remarks},
            success: function(ajaxresponse,xhrstatus)
            {
                var response = ajaxresponse;
                var lastquoteid = response.lastquoteid;

                if(lastquoteid == 'updated')
                {
                    alert('Cart updated have been updated');
                }
                else
                {
                    alert('Sorry the updated could not be updated. Check connection or try again');
                }
            },
            error: function(a,b)
            {
                alert('Sorry the details could not be updated. Check connection or try again');
            }
        });
    }

    function resendquote(value,quote_type)
    {
        var quoteid = value;
        var my_quote_type = quote_type;
        quote_type = quote_type;
        var url = "/admin/quotations/mailgenquote/"+quoteid+"/"+my_quote_type;

        $.ajax
        ({
            url:url,
            type: "GET",
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            data:{"quoteid":quoteid,"quote_type":my_quote_type},
            success: function(ajaxresponse,xhrstatus)
            {
                var response = ajaxresponse;
                var status = response.status;

                //console.log('status' + status);

                if(status == 1)
                {
                    alert('Quote details have been mailed to customer');
                }
                else
                {
                    alert('Error in sending detail. Please refresh and try again');
                }
            },
            error: function(a,b)
            {
                alert('Error in sending details. Please refresh and try again');
            }
        });
    }

    function setvalue(value)
    {
        var elementId = value;
        var valueItem = $('#selectid'+elementId).val();

        $.ajax
        ({
            url:"/admin/quotations/setvalue/"+elementId+"/"+valueItem,
            type: "GET",
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            //data:{"quoteid":elementId,"remarks":remarks},
            success: function(ajaxresponse,xhrstatus)
            {
                var response = ajaxresponse;
                alert('Quotation status changed to : ' +valueItem);
                console.log('Updated');
            },
            error: function(a,b)
            {
                console.log('Not Updated');
            }
        });
    }

    $( "#customers_quote_data table tbody" ).sortable();


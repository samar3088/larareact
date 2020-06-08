@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','All Enquiry')
@section('cardheading','All Enquiry')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
<link href="{{ asset('/dist/css/quotation.css') }}" rel="stylesheet">
@endpush

@section('content')

    {{--  <div align="right">
        <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Create Record</button>
    </div>
    <br />  --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="user_table">
            <thead>
                <tr>
                    <th class="newwi">#</th>
    				<th>Cus Details</th>
    				<th>Type</th>
    				<th>Cust Inp</th>
    				<th>Quote</th>
    				<th>Date</th>
    				<th>Action</th>
    				<th>Status</th>
    				<th>Remarks</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="formModal">
        <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">Please Update Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body custom_modal_body">
                                <div id="customers_quote_data">
                                    <table style="font-family: arial, sans-serif;border-collapse: collapse;width: 856px;">
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group" id="form_result"></div>

                                 {{--  Added for bday alone  --}}

                                <div style="margin-left:20px;margin-top:10px;">
                                    <button type="button" class="btn btn-primary" onclick="additems('transport_cost','transport_cost_row')">Add Transportation</button>
                                    <button type="button" class="btn btn-success" onclick="additems('crew_cost','crew_cost_row')">Add Crew</button>
                                    <button type="button" class="btn btn-warning" onclick="additems('manual_discount','manual_discount_row')">Add Discount</button>
                                    <button type="button" class="btn btn-dark" onclick="additems_new('Sq.ft','sqft_row')">Add Sq ft</button>
                                    <button type="button" class="btn btn-info" onclick="additems_new('Qty','qty_row')">Add Qty</button>
                                </div>

                                {{--  Added for bday alone  --}}

                                <div class="col-md-12 col-xs-12" id="useradded" style="margin-top: 15px;margin-bottom: 15px;padding: 0px;"></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div class="form-group" style="text-align: left;">
                                <label for="cc_mails">Cc Email address</label>
                                <input type="email" class="form-control" id="cc_mails" aria-describedby="cc_mails" placeholder="Enter comma separated Cc emails">
                            </div>
                            <div class="form-group" style="text-align: left;">
                                <label for="bcc_mails">BCc Email address</label>
                                <input type="email" class="form-control" id="bcc_mails" aria-describedby="bcc_mails" placeholder="Enter comma separated BCc emails">
                            </div>
                            <br><hr>
                            <div class="select_gst" style="display: inline-block;float: left;"><input type="checkbox" name="add_gst" id="add_gst" value="" onchange="updategst()"> Add GST</div>
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">Close</button>
                            <button type="button" class="btn btn-primary submitBtn" name="savemodaldetails" id="savemodaldetails">Update Quotation</button>
                        </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
      </div>
      <!-- /.modal -->

@endsection

@push('js')

<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script>

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /*--------------Initial Index details comes here-----------------*/

        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            ajax:{
                url: "{{ route('admin.quotations.index') }}",
            },
            columns:
            [
                {
                    "data": 'id', "name": 'id',
                    render: function (data, type, row, meta)
                    {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'cusdetails',
                    name: 'cusdetails',
                    searchable: true,
                },
                {
                    data: 'customertype',
                    name: 'customertype'
                },
                {
                    data: 'custimp',
                    name: 'custimp',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'quoterow',
                    name: 'quoterow',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'daterow',
                    name: 'daterow',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
                {
                    "data": "statusrow",
                    name: 'statusrow',
                    orderable:false,
                    searchable: false,
                },
                {
                    "data": "remarksrow",
                    name: 'remarksrow',
                    orderable:false,
                    searchable: false,
                },
            ]
        });

        /*--------------Initial Index details ends here-----------------*/

        /*--------------submission data details comes here-----------------*/

        $('#savemodaldetails').click(function(e) {

            e.preventDefault();
            var listitems = '';var listitems2 = '';var discount_percent = 0;var discount_coupon_code = 0;var manual_discount = 0;var add_gst = '';var amount_total = 0;
            var transport_cost = 0;var crew_cost = 0;
            var package_detail_id = $('#package_detail_id').val();
            var quoteid = $('#savemodaldetails').val();

            var no_of_days = $("#no_of_days").val();

            $(".modalerror").text('');

            $("tr[class^='items_from_db']").each(function()
            {
                if ($(this).hasClass("generalitems"))
                {
                    particular = $(this).find('td.invoiceitems_details input.manual_particular').val();
                    item_price = $(this).find('td.invoiceitems_details input.manual_item_price').val();
                    item_qty = $(this).find('td.invoiceitems_details input.manual_item_qty').val();
                    pack_desc = $(this).find('td.invoiceitems_details input.manual_pack_desc').val();
                    partidays = $(this).find('td.invoiceitems_details input.manual_partidays').val();
                    if(pack_desc == undefined) {
                        pack_desc = '';
                    }
                    net_price = $(this).find('td.invoiceitems_details input.calamount').val();
                    item_height = '';
                    item_width = '';

                    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+pack_desc+'^'+partidays+'#';
                    listitems += arrayitems;
                }
                else if ($(this).hasClass("specialitems"))
                {
                    particular = $(this).find('td.invoiceitems_details input.manual_particular').val();
                    item_price = $(this).find('td.invoiceitems_details input.manual_item_price').val();
                    item_qty = '';
                    pack_desc = $(this).find('td.invoiceitems_details input.manual_pack_desc').val();
                    partidays = $(this).find('td.invoiceitems_details input.manual_partidays').val();
                    if(pack_desc == undefined) {
                        pack_desc = '';
                    }
                    net_price = $(this).find('td.invoiceitems_details input.calamount').val();
                    item_height = $(this).find('td.invoiceitems_details input.manual_item_height').val();
                    item_width = $(this).find('td.invoiceitems_details input.manual_item_width').val();

                    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+pack_desc+'^'+partidays+'#';
                    listitems += arrayitems;
                }
            });

            $("tr[class^='items_from_manual_entry']").each(function()
            {
                if($(this).hasClass("generalitems"))
                {
                    particular = $(this).find('td.invoiceitems_details input.manual_parti').val();
                    item_price = $(this).find('td.invoiceitems_details input.manual_ammt').val();
                    item_qty = $(this).find('td.invoiceitems_details input.manual_qtt').val();
                    net_price = $(this).find('td.invoiceitems_details input.calamount').val();
                    partidays = $(this).find('td.invoiceitems_details input.manual_partidays').val();
                    item_height = '';
                    item_width = '';

                    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+partidays+'#';
                    listitems2 += arrayitems;
                }
                else if ($(this).hasClass("specialitems"))
                {
                    particular = $(this).find('td.invoiceitems_details input.manual_parti').val();
                    item_price = $(this).find('td.invoiceitems_details input.manual_ammt').val();
                    item_qty = '';
                    net_price = $(this).find('td.invoiceitems_details input.calamount').val();
                    partidays = $(this).find('td.invoiceitems_details input.manual_partidays').val();
                    item_height = $(this).find('td.invoiceitems_details input.manual_heightee').val();
                    item_width = $(this).find('td.invoiceitems_details input.manual_item_width').val();

                    var arrayitems = particular+'^'+item_qty+'^'+item_height+'^'+item_width+'^'+item_price+'^'+net_price+'^'+partidays+'#';
                    listitems2 += arrayitems;
                }
            });

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

            var add_gst = 'no';
            if($("#add_gst").is(':checked'))
            {
                add_gst = 'yes';
            }

            $(".calamount").each(function() {
                var calamount = parseInt($(this).val());
                amount_total = +amount_total + +calamount;
            });

            var cc_mails = $('#cc_mails').val();
            var bcc_mails = $('#bcc_mails').val();

            //var queryString = "ajax/fill_quotation.php";

            /* discount_percent = encodeURIComponent(discount_percent);
            discount_coupon_code = encodeURIComponent(discount_coupon_code);
            manual_discount = encodeURIComponent(manual_discount);
            amount_total = encodeURIComponent(amount_total);
            package_detail_id = encodeURIComponent(package_detail_id);
            listitems = encodeURIComponent(listitems);
            listitems2 = encodeURIComponent(listitems2);
            add_gst = encodeURIComponent(add_gst);
            transport_cost = encodeURIComponent(transport_cost);
            crew_cost = encodeURIComponent(crew_cost);
            quoteid = encodeURIComponent(quoteid);
            cc_mails = encodeURIComponent(cc_mails);
            bcc_mails = encodeURIComponent(bcc_mails); */

            $.ajax
            ({
                url:"{{ route('admin.quotations.updatequotations') }}",
                type:'POST',
                dataType:'json',
                data:{"_token" : $('meta[name=_token]').attr('content'),"discount_percent":discount_percent,"discount_coupon_code":discount_coupon_code,"manual_discount":manual_discount,"amount_total":amount_total
                ,"package_detail_id":package_detail_id,"listitems":listitems,"listitems2":listitems2,"add_gst":add_gst,"transport_cost":transport_cost,"crew_cost":crew_cost
                ,"quoteid":quoteid,"cc_mails":cc_mails,"bcc_mails":bcc_mails},
                beforeSend: function()
                {
                    //$('.submitBtn').attr("disabled","disabled");
                    //$('#sample_form').css("opacity",".5");
                },
                success: function(ajaxresponse,xhrstatus)
                {
                    var response = ajaxresponse;
                    var status = response.status;
                    var response_file_path = response.response_file_path;
                    var cus_name = response.cus_name;
                    var cus_email = response.cus_email;
                    var cus_mobile = response.cus_mobile;

                    if(status == 1)
                    {
                        $(".modalerror").show().text('');
                        $('#pdflink-'+quoteid).attr("href", response_file_path);
                        $('#name_val'+quoteid).text(cus_name);
                        $('#email_val'+quoteid).text(cus_email);
                        $('#mobile_val'+quoteid).text(cus_mobile);
                        makefieldempty();
                        $('#formModal').modal('hide');
                        alert('Quote details have been updated kindly check and send fresh quote to customer');
                    }
                    else
                    {
                        $(".modalerror").show().text('Error in updating detail. Please refresh and try again');
                        makefieldempty();
                    }

                    //$(".submitBtn").removeAttr("disabled");
                    //$('#sample_form').css("opacity","");
                    $('#user_table').DataTable().ajax.reload();
                },
                error: function(a,b)
                {
                    $(".modalerror").show().text('Please refresh and try again');
                }
            });

        });

        /*--------------submission data details ends here-----------------*/

    });

</script>
<script src="{{ asset('/dist/js/quotations.js')}}"></script>

@endpush

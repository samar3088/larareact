@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Completed Events Reports')
@section('cardheading','Completed Events Reports')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
<link href="{{ asset('/dist/css/quotation.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="user_table">
            <thead>
                <tr>
                    <th class="newwi">#</th>
    				<th>Name</th>
    				<th>Event Date</th>
    				<th>Quote / Invoice</th>
    				<th>Gross Amt</th>
    				<th>Expense</th>
    				<th>GST</th>
                    <th>Net Amount</th>
                    <th>P/L</th>
    				<th>Remarks</th>
                </tr>
            </thead>
            <tfoot align="right">
                <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
            </tfoot>
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

                                <div class="col-md-12 col-xs-12" id="useradded" style=""></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group" style="text-align: left;">
                                        <label for="event_expenses">Expenses</label>
                                        <input type="text" class="form-control" id="event_expenses" aria-describedby="event_expenses" placeholder="Enter event expenses">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" style="text-align: left;">
                                        <label for="event_gst">GST Charges</label>
                                        <input type="text" class="form-control" id="event_gst" aria-describedby="event_gst" placeholder="Enter event gst charges">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group" style="text-align: left;">
                                        <label for="event_remarks">Remarks</label>
                                        <input type="text" class="form-control" id="event_remarks" aria-describedby="event_remarks" placeholder="Enter Admin Remarks">
                                    </div>
                                </div>
                            </div>
                            <br><hr>
                            <div style="margin-left:20px;margin-top:10px;margin-bottom:30px;text-align: left;" id="quote_remarks">

                            </div>
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">Close</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary submitBtn" name="savemodaldetails" id="savemodaldetails">Update Quotation</button>&nbsp;&nbsp;&nbsp;
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
            "drawCallback": function (settings,api,data) {
            // Here the response
                var response = settings.json;

                var api = this.api(), data;

                var all_total_gst = response['all_total_gst'];
                var all_total_finalamount = response['all_total_finalamount'];
                var all_total_expense = response['all_total_expense'];
                var all_total_profit_loss = response['all_total_profit_loss'];

                //console.log(api);

                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 1 ).footer() ).html('');
                $( api.column( 2 ).footer() ).html('');
                $( api.column( 3 ).footer() ).html('');
                $( api.column( 4 ).footer() ).html(all_total_finalamount);
                $( api.column( 5 ).footer() ).html(all_total_expense);
                $( api.column( 6 ).footer() ).html(all_total_gst);
                $( api.column( 7 ).footer() ).html(all_total_profit_loss);
                $( api.column( 8 ).footer() ).html('');
            },
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "pageLength": 100,
            "stateSave": false,
            "lengthMenu": [ [10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"] ],
            ajax:{
                url: "{{ route('admin.reports.completed') }}",
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
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'cus_eventdate',
                    name: 'cus_eventdate'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'gross',
                    name: 'gross',
                },
                {
                    data: 'cus_event_expenses',
                    name: 'cus_event_expenses',
                },
                {
                    data: 'cus_event_gst',
                    name: 'cus_event_gst',
                },
                {
                    "data": "cus_net_profit_loss",
                    name: 'cus_net_profit_loss',
                },
                {
                    "data": "cus_net_profit_loss_status",
                    name: 'cus_net_profit_loss_status',
                },
                {
                    "data": "cus_event_remarks",
                    name: 'cus_event_remarks',
                },
            ]
        });

        /*--------------Initial Index details ends here-----------------*/

    });

 /*--------------submission data details comes here-----------------*/

 $('#savemodaldetails').click(function(e) {

    e.preventDefault();
    var event_expenses = 0;var event_gst = 0;var event_remarks = '';var item_selected = '';

    event_expenses = $("#event_expenses").val();
    event_gst = $("#event_gst").val();
    event_remarks = $("#event_remarks").val();
    item_selected = $("#savemodaldetails").val();

    $(".modalerror").text('');

    event_expenses = encodeURIComponent(event_expenses);
    event_gst = encodeURIComponent(event_gst);
    event_remarks = encodeURIComponent(event_remarks);
    item_selected = encodeURIComponent(item_selected);

    $.ajax
    ({
        url:"{{ route('admin.reports.updatecompleted') }}",
        type:'POST',
        data:{"_token" : $('meta[name=_token]').attr('content'),"event_expenses":event_expenses,"event_gst":event_gst,"event_remarks":event_remarks,"item_selected":item_selected},
        beforeSend: function()
        {
            //$('.submitBtn').attr("disabled","disabled");
            //$('#sample_form').css("opacity",".5");
        },
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;
            var status = response['status'];

            if(status == 1)
            {
                $(".modalerror").show().text('');
                makefieldsempty();
                $('#formModal').modal('hide');
                alert('Quote details have been updated');
            }
            else
            {
                $(".modalerror").show().text('Error in updating details. Please refresh and try again');
                makefieldsempty();
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
        success: function(ajaxresponse,status)
        {
            var response = ajaxresponse;
            var mailbody = response['mailbody'];
            var transport_cost = response['transport_cost'];
            var crew_cost = response['crew_cost'];
            var manual_discount = response['manual_discount'];
            var package_id = response['package_id'];
            var discount_percent = response['discount_percent'];
            var discount_amount = response['discount_amount'];
            var coupon_code = response['coupon_code'];
            var coupon_discount = response['coupon_discount'];
            var cc_mails = response['cc_mails'];
            var bcc_mails = response['bcc_mails'];

            var admin_event_expenses = response['admin_event_expenses'];
            var admin_event_gst = response['admin_event_gst'];
            var admin_event_remarks = response['admin_event_remarks'];

            $('#event_expenses').val(admin_event_expenses);
            $('#event_gst').val(admin_event_gst);
            $('#event_remarks').val(admin_event_remarks);

            var remarks_data = response['remarks_data'];
            remarks_data = remarks_data.split('^^');

            for (i = 0; i < remarks_data.length; i++) {
                var item_content  = remarks_data[i];
                $('#quote_remarks').append('<p>#'+item_content+'</p>');
            }

            internalcounter = response['internalcounter'];
            var added_gst = response['added_gst'];

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

            $('#cc_mails').val(cc_mails);
            $('#bcc_mails').val(bcc_mails);

            $(".modalerror").hide().text('');
            $('#formModal').modal({backdrop: 'static', keyboard: false, show: true});
        },
        error: function(a,b)
        {
            $("#form_result").show().text('Please refresh and try again');
            makefieldsempty();
        }
    })
});

/*--------------Manually added function to showpopup data in pop up Ends -----------------*/


$("#formModal").on("hide.bs.modal", function () {
    makefieldsempty();
});

function makefieldsempty()
{
    $('#savemodaldetails').val('');
    $('#add_gst').prop('checked', false);
    internalcounter = 0;
    added_items_counter = 0;
    $('div.myrows').remove();
    quote_type_edit = '';
    $('#cc_mails').val('');
    $('#bcc_mails').val('');

    $('#event_expenses').val('');
    $('#event_gst').val('');
    $('#event_remarks').val('');
    $('#quote_remarks').html('');
}

</script>

@endpush

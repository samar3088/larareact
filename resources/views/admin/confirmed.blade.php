@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','All Confirmed Events')
@section('cardheading','All Confirmed Events')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
<style>
.additionalimages {
    display: inline-block;
    float: left;
    margin: 10px;
    border: 1px solid gray;
    padding: 10px;
}
#store_images
{
    display: flex;
}
.remove_subimages
{
    cursor: pointer;
}
.footer_p{
    background: #000 !important;
    color: #fff !important;
    text-align: center !important;
    position: fixed !important;
    width: 100% !important;
    bottom: 0 !important;
    margin-bottom: 0 !important;
    padding: 9px 0px !important;
	z-index: 9999;
}
#example_filter{
	float:right;
}
#example_length{
	/* display:none; */
}
.pagination{
	float:right;
}
.modal-footer
{
    display: block;
}
.btnedit {
    background: #000;
    color: #fff;
    border: 0px solid;
    width: 100%;
    border-radius: 5px;
}
.add_confirm_details
{
    margin-top: 10px;
}
#user_table
{
    width:100% !important;
}
.manualbtn  {
    margin-bottom: 2px;
}
#savemodaldetails {
    float: right !important;
}
#customers_quote_data table {
    width:100% !important;
}
.btnedit { background: #000;color: #fff;border: 0px solid;width: 100%;border-radius: 5px; }
.btnsave { background: #337ab7;color: #fff;border: 0px solid;width: 100%;border-radius: 5px; }
.table-bordered > tbody > tr > td { font-size: 13px; }
.table-bordered > tbody > tr > td {
    font-size: 15px;
}
a.optionslink {
    display: block;
    cursor:pointer;
}
a.optionslink:before {
    content:'\25BA';
}
span.edititem,span.removeitem,span.remove_item_row,span.remove_equipment_row {
    margin-left: 10px;
    cursor:pointer;
    float: right;
}
.editfields {
    border:0px;
}
div.custom_modal_body {
    margin: 21px !important;
    padding: 0px !important;
}
.myrows {
    padding-top: 15px;
    clear: both;
    margin: 0px !important;
}
.myrows div {
    display:inline-block !important;
}
</style>
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

    <div class="modal fade" id="myModal">
        <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog modal-xs">
                <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">Please Update Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body custom_modal_body">

                                <div class="form-group">
                                    <label for="quote_by" class="col-form-label">Quote Taken By:</label>
                                    <input type="text" class="form-control" id="quote_by" name="quote_by" placeholder="Please Enter Quote Taken By">
                                </div>
                                <div class="form-group">
                                    <label for="payment_collected">Payment Collected</label>
                                    <select class="form-control" id="payment_collected" name="payment_collected" onchange="setbalance()">
                                        <option value="">Please Select Payment Collected</option>
                                        <option value="Full">Full</option>
                                        <option value="Partial">Partial</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="payment_received" class="col-form-label">Payment Received:</label>
                                    <input type="number" class="form-control" id="payment_received" name="payment_received" placeholder="Please Enter Payment Received">
                                </div>
                                <div class="form-group">
                                    <label for="payment_balance" class="col-form-label">Balance Payment:</label>
                                    <input type="number" class="form-control" id="payment_balance" name="payment_balance" value="0" placeholder="Please Enter Balance Payment">
                                </div>
                                <div class="form-group">
                                    <label for="quote_person" class="col-form-label">Contact Person :</label>
                                    <input type="text" class="form-control" id="quote_person" name="quote_person" placeholder="Please Enter Contact Person Name">
                                </div>
                                <div class="form-group">
                                    <label for="quote_contact" class="col-form-label">Contact Person Contact:</label>
                                    <input type="text" class="form-control" id="quote_contact" name="quote_contact" placeholder="Please Enter Contact Person Mobile">
                                </div>

                                <div class="form-group modalerror" id="form_result"></div>
                                <div class="col-md-12 col-xs-12" id="useradded" style="margin-top: 15px;margin-bottom: 15px;padding: 0px;"></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <input type="hidden" class="form-control" id="item_id" name="item_id">
                            <input type="hidden" class="form-control" id="item_type" name="item_type">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>&nbsp;&nbsp;
                            <input type="submit" name="savemodaldetails" id="savemodaldetails" class="btn btn-primary" value="Save & Send" />
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

        /*--------------Initial Index details comes here-----------------*/

        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            ajax:{
                url: "{{ route('admin.quotations.confirmed') }}",
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

        /*--------------updating details from pop up of confirmed quotations page -----------------*/

        $('#sample_form').on('submit', function(e){
            e.preventDefault();
            var quote_by = $('#quote_by').val();
            var payment_received = $('#payment_received').val();
            var payment_balance = $('#payment_balance').val();
            var quote_person = $('#quote_person').val();
            var quote_contact = $('#quote_contact').val();
            var payment_collected = $('#payment_collected option:selected').val();
            var item_id = $('#item_id').val();
            var item_type = $('#item_type').val();

            if(quote_by == '') { $('.modalerror').show().text('Please Enter Quote Taken By'); $('#quote_by').focus(); return false; }
            else if(payment_received == '') { $('.modalerror').show().text('Please Enter Payment Received'); $('#payment_received').focus(); return false; }
            else if(payment_balance == '') { $('.modalerror').show().text('Please Enter Balance Payment'); $('#payment_balance').focus(); return false; }
            else if(quote_person == '') { $('.modalerror').show().text('Please Enter Contact Person Name'); $('#quote_person').focus(); return false; }
            else if(quote_contact == '') { $('.modalerror').show().text('Please Enter Contact Person Contact'); $('#quote_contact').focus(); return false; }
            else if(payment_collected == '') { $('.modalerror').show().text('Please Select Payment Collecteed'); $('#payment_collected').focus(); return false; }
            else if(item_id == '') { $('.modalerror').show().text('Please refresh and try again'); return false; }
            else if(item_type == '') { $('.modalerror').show().text('Please refresh and try again'); return false; }
            else
            {
                if(payment_collected == 'Full') {
                    $('#payment_balance').val('0');
                }

                $.ajax({
                    url:"{{ route('admin.quotations.updateconfirmed') }}",
                    method:"POST",
                    data: new FormData(this),
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success: function(ajaxresponse,status)
                    {
                        var response = ajaxresponse;
                        var status = response['status'];
                        //console.log(status);

                        if(status == 1)
                        {
                            $(".modalerror").show().text('Details mailed to customer');
                            makefieldsempty();
                        }
                        else
                        {
                            $(".modalerror").show().text('Error in updating detail. Please refresh and try again');
                        }

                        //$('#myModal').modal('show');
                    },
                    error: function(a,b)
                    {
                        $(".modalerror").show().text('Please refresh and try again');
                    }
                });
            }
        });

        /*--------------updating details from pop up of confirmed quotations page ends -----------------*/

    });

    $("#myModal").on("hide.bs.modal", function () {
        makefieldsempty();
    });

    function showdetails(value,quote_type)
    {
        var quoteType = quote_type;
        var elementId = value;
        $('#item_id').val(elementId);
        $('#item_type').val(quoteType);

        $.ajax
        ({
                url:"/admin/quotations/confirmedpop/"+elementId,
                type: "GET",
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                data:{"quoteid":elementId},
                success: function(ajaxresponse,status)
                {
                    var response = ajaxresponse;
                    $('#quote_by').val(response['quote_by']);
                    $('#payment_received').val(response['payment_received']);
                    $('#payment_balance').val(response['payment_balance']);
                    $('#quote_person').val(response['quote_person']);
                    $('#quote_contact').val(response['quote_contact']);
                    $("#payment_collected option[value="+response['payment_collected']+"]").prop("selected",true);
                    if(response['payment_collected'] == 'Full') {
                        $('#payment_balance').val('0');
                    }
                    $(".modalerror").hide().text('');

                    $('#myModal').modal('show');
                },
                error: function(a,b)
                {
                    $(".modalerror").show().text('Please refresh and try again');
                    makefieldsempty();
                    //$('#myModal').modal('hide');
                }
        });
    }

    function makefieldsempty() {
        $('#quote_by').val('');
        $('#payment_received').val('');
        $('#payment_balance').val('');
        $('#quote_person').val('');
        $('#quote_contact').val('');
        $("#payment_collected option:first").attr('selected','selected');
        $('#item_id').val('');
        $('#item_type').val('');
    }

    function setbalance() {
        var payment_collected = $('#payment_collected option:selected').val();
        if(payment_collected == 'Full') {
            $('#payment_balance').val('0');
        }
    }

    </script>

    <script src="{{ asset('/dist/js/quotations.js')}}"></script>

@endpush

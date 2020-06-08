@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','All Completed Events')
@section('cardheading','All Completed Events')
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
    {{--  float: right !important;  --}}
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
    				<th>Remarks</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="myModal">
        <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">Please Update Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body custom_modal_body">
                                <div class="form-group">
                                    <label for="file">Invoice PDF</label>
                                    <input type="file" class="form-control" id="file" name="file" accept="application/pdf" required />
                                </div>
                                <div class="form-group">
                                    <label for="invoice_number">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label for="customer_gst">Customer GST</label>
                                    <input type="text" class="form-control" id="customer_gst" name="customer_gst" autocomplete="off" required />
                                </div>
                                <div class="form-group modalerror" id="form_result"></div>
                                <div class="col-md-12 col-xs-12" id="useradded" style="margin-top: 15px;margin-bottom: 15px;padding: 0px;"></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <input type="hidden" class="form-control" id="item_id" name="item_id">
                            <input type="hidden" class="form-control" id="item_type" name="item_type">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>
                            <input type="submit" name="savemodaldetail" id="savemodaldetail" class="btn btn-primary submitBtn" value="Upload" />
                            <button type="button" class="btn btn-primary submitBtns" name="savemodaldetails" id="savemodaldetails">Generate From Quote & Send</button>
                        </div>

                        <table class="table" id="invoicesTable">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Invoice Link</th>
                                <th scope="col">Invoice No</th>
                                <th scope="col">Customer GST</th>
                                <th scope="col">Options</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

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
                url: "{{ route('admin.quotations.completed') }}",
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
                    "data": "remarksrow",
                    name: 'remarksrow',
                    orderable:false,
                    searchable: false,
                },
            ]
        });

        /*--------------Initial Index details ends here-----------------*/

        /*--------------mailing data from existing completed quotations page -----------------*/

        $('#savemodaldetails').click(function(e)
        {
            e.preventDefault();
            var quoteid = $('#item_id').val();
            var quotetype = $('#item_type').val();
            var invoice_number = $('#invoice_number').val();
            var customer_gst = $('#customer_gst').val();

            if(quoteid == '') { $('.modalerror').show().text('Please refresh page and try again'); $('#quoteid').focus(); return false; }
            else if(invoice_number == '') { $('.modalerror').show().text('Please Enter Invoice Number'); $('#invoice_number').focus(); return false; }
            else
            {
                $(".modalerror").show().text('Please wait while we send details to customer');

                var quoteid = encodeURIComponent($('#item_id').val());
                var quotetype = encodeURIComponent($('#item_type').val());
                var invoice_number = encodeURIComponent($('#invoice_number').val());
                var customer_gst = encodeURIComponent($('#customer_gst').val());

                $.ajax
                ({
                    url:"/admin/quotations/completedgen/"+quoteid+"/"+invoice_number+"/"+customer_gst,
                    type: "GET",
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    data:{"quoteid":quoteid,"invoice_number":invoice_number,"customer_gst":customer_gst},
                    beforeSend: function()
                    {
                        $('.submitBtns').attr("disabled","disabled");
                    },
                    success: function(ajaxresponse,status)
                    {
                        var response = ajaxresponse;
                        //response = JSON.parse(response);

                        if(response.status == 1)
                        {
                            resetforms();
                            makefieldsempty();
                            fillinvoicetable(quoteid);
                            $('.modalerror').text(response.message).show();
                        }
                        else
                        {
                            $('.modalerror').text(response.message).show();
                        }
                        $('#sample_form').css("opacity","");
                        $(".submitBtns").removeAttr("disabled");
                    },
                    error: function(a,b)
                    {
                        $(".modalerror").show().text('Please refresh and try again');
                    }
                });

                /*Ends Send Details mail to customer*/
            }
        });

        /*--------------mailing data from existing completed quotations page ends-----------------*/

        /*--------------updating details from pop up of completed quotations page -----------------*/

        $('#sample_form').on('submit', function(e){
            e.preventDefault();

            var quoteid = $('#item_id').val();
            var quotetype = $('#item_type').val();
            var invoice_number = $('#invoice_number').val();
            var customer_gst = $('#customer_gst').val();
            var selected_file = $('#file').val();

            if(quoteid == '') { $('.modalerror').show().text('Please refresh page and try again'); $('#item_id').focus(); return false; }
            else if(invoice_number == '') { $('.modalerror').show().text('Please Enter Invoice Number'); $('#invoice_number').focus(); return false; }
            else if(selected_file == '') { $('.modalerror').show().text('Please select file to upload'); $('#file').focus(); return false; }
            else
            {
                $.ajax({
                    url:"{{ route('admin.quotations.updateconfirmed') }}",
                    method:"POST",
                    data: new FormData(this),
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    beforeSend: function()
                    {
                        $('.submitBtn').attr("disabled","disabled");
                        $('#sample_form').css("opacity",".5");
                    },
                    success: function(response)
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
                            resetforms();
                            makefieldsempty();
                            fillinvoicetable(quoteid);

                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#user_table').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html).show();
                        $('#sample_form').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                    },
                    error: function(a,b)
                    {
                        $(".modalerror").show().text('Please refresh and try again');
                    }
                });
            }
        });

        /*--------------updating details from pop up of completed quotations page ends -----------------*/

    });

        $("#myModal").on("hide.bs.modal", function () {
            makefieldsempty();
        });

        function showdetails(value,quote_type)
        {
            var quoteType = quote_type;
            quote_type = quote_type;
            var elementId = value;
            $('#item_id').val(elementId);
            $('#item_type').val(quoteType);

            $.ajax
            ({
                    url:"/admin/quotations/completedpop/"+elementId,
                    type: "GET",
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    data:{"quoteid":elementId},
                    success: function(ajaxresponse,status)
                    {
                        var response = ajaxresponse;
                        $("#invoicesTable tbody tr.manualtr").remove();

                        if(!jQuery.isEmptyObject(response))
                        {
                            console.log('List Of Invoices for the Quotation Comes here');
                            var counter = 0;

                            $.each(response, function(key, value)
                            {
                                counter++;
                                var item_id = value.item_id;
                                var quoteid = value.quoteid;
                                var invoice_path = value.invoice_path;
                                var invoice_number = value.invoice_number;
                                var customer_gst = value.customer_gst;

                                invoice_path = "{{ URL::to('/') }}+/storage/"+invoice_path;

                                var tableRow = '<tr class="manualtr"><th scope="row">'+counter+'.</th><td><a href="'+invoice_path+'" target="_blank">Invoice Link</a></td><td>'+invoice_number+'</td><td>'+customer_gst+'</td><td><span class="spanpointer" onclick="triggermail(\''+item_id+'\')">Mail</span> | <span class="spanpointer" onclick="deleteitem(\''+item_id+'\')">Delete</span></td></tr>';
                                $("#invoicesTable tbody").append(tableRow);
                            });
                        }
                        else
                        {
                            console.log('No Invoices for the Quotation Selected');
                        }

                        $(".modalerror").hide().text('');
                        $('#myModal').modal('show');
                    },
                    error: function(a,b)
                    {
                        $(".modalerror").show().text('Please refresh and try again');
                        makefieldsempty();
                    }
            });
        }

        function makefieldsempty()
        {
            $(".modalerror").hide().text('');
            $('#file').val('');
            $('#item_id').val('');
            $('#item_type').val('');
            $('#invoice_number').val('');
            $('#customer_gst').val('');
            $("#invoicesTable tbody tr.manualtr").remove();
        }

        $("#file").change(function() {
            var file = this.files[0];
            var fileType = file.type;
            var match = ['application/pdf'];
            if(!((fileType == match[0]))){
                alert('Sorry, only PDF files are allowed to upload.');
                $("#file").val('');
                return false;
            }
        });

        function fillinvoicetable(value)
        {
            var elementId = value;
            $('#item_id').val(elementId);

            $.ajax
            ({
                    url:"/admin/quotations/completedpop/"+elementId,
                    type: "GET",
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    data:{"quoteid":elementId},
                    success: function(ajaxresponse,status)
                    {
                        var response = ajaxresponse;
                        $("#invoicesTable tbody tr.manualtr").remove();

                        if(!jQuery.isEmptyObject(response))
                        {
                            console.log('List Of Invoices for the Quotation Comes here');
                            var counter = 0;

                            $.each(response, function(key, value)
                            {
                                counter++;
                                var item_id = value.item_id;
                                var quoteid = value.quoteid;
                                var invoice_path = value.invoice_path;
                                var invoice_number = value.invoice_number;
                                var customer_gst = value.customer_gst;

                                invoice_path = "{{ URL::to('/') }}+/storage/"+invoice_path;

                                var tableRow = '<tr class="manualtr"><th scope="row">'+counter+'.</th><td><a href="'+invoice_path+'" target="_blank">Invoice Link</a></td><td>'+invoice_number+'</td><td>'+customer_gst+'</td><td><span class="spanpointer" onclick="triggermail(\''+item_id+'\')">Mail</span> | <span class="spanpointer" onclick="deleteitem(\''+item_id+'\')">Delete</span></td></tr>';
                                $("#invoicesTable tbody").append(tableRow);

                            });
                        }
                        else {
                            console.log('No Invoices for the Quotation Selected');
                        }
                    },
                    error: function(a,b)
                    {
                        $(".modalerror").show().text('Please refresh and try again');
                        makefieldsempty();
                    }
            });
        }

        function deleteitem(value)
        {
            var item_id = value;
            var r = confirm("Confirm want to delete");
            if(r == true)
            {
                //AJAX Code To Refill Form.
                $.ajax
                ({
                        url:"/admin/quotations/delcompleteditem/"+item_id,
                        type: "GET",
                        dataType:"json",
                        success: function(response)
                        {
                            var response = response;
                            var quoteid = response.quoteid;
                            fillinvoicetable(quoteid);

                            $(".modalerror").show().text('Invoice removed successfully');
                        },
                        error: function(jqXHR, exception)
                        {
                            $(".modalerror").show().text('Please refresh and try again');
                        }
                });
            }
            else
            {
                alert('Thank you for not deleting');
            }
        }

        function triggermail(value)
        {
            var item_id = value;

            //AJAX Code To Refill Form.
            $.ajax
            ({
                url:"/admin/quotations/delcompleteditem/"+item_id,
                type: "GET",
                dataType:"json",
                success: function(response)
                {
                    var response = response;
                    var quoteid = response.quoteid;
                    $(".modalerror").show().text('Invoice mailed successfully');
                },
                error: function(jqXHR, exception)
                {
                    $(".modalerror").show().text('Error In Mail Sending. Please refresh and try again');
                }
            });
        }

    </script>

    <script src="{{ asset('/dist/js/quotations.js')}}"></script>

@endpush

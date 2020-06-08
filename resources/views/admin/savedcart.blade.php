@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','All Saved Carts')
@section('cardheading','All Saved Carts')
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
.modal-content
{
    padding: 0 7rem;
}
.modal-footer
{
    display: block;
}
#customers_quote_data table {
    width:100% !important;
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
                    <th>Sl No</th>
                    <th>Cart Id</th>
                    <th>City</th>
                    <th>Event Date</th>
                    <th>Remarks</th>
                    <th>Action</th>
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
                        <h4 class="modal-title">Saved Carts Details</h4>
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
                                <div class="col-md-12 col-xs-12" id="useradded" style="margin-top: 15px;margin-bottom: 15px;padding: 0px;"></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div class="form-group" style="text-align: left;">
                                <label for="cc_mails">Cc Email address</label>
                                <input type="email" class="form-control" id="cc_mails" aria-describedby="cc_mails" placeholder="Enter comma separated Cc emails" readonly disabled>
                            </div>
                            <div class="form-group" style="text-align: left;">
                                <label for="bcc_mails">BCc Email address</label>
                                <input type="email" class="form-control" id="bcc_mails" aria-describedby="bcc_mails" placeholder="Enter comma separated BCc emails" readonly disabled>
                            </div>
                            <br><hr>
                            <div class="select_gst" style="display: inline-block;float: left;"><input type="checkbox" name="add_gst" id="add_gst" value="" readonly> Add GST</div>
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">Close</button>
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

    var internalcounter = 0;
    var added_items_counter = 0;
    var quote_type_edit = '';

    $(document).ready(function() {

        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            ajax:{
                url: "{{ route('admin.savedcart.index') }}",
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
                    data: 'ordertosave',
                    name: 'ordertosave'
                },
                {
                    data: 'cityname',
                    name: 'cityname'
                },
                {
                    data: 'event_date',
                    name: 'event_date'
                },
                { "data": "checkbox", orderable:false, searchable: false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            var quote_type_edit = 'general_quote';
            $('#customers_quote_data table tbody').empty();
            $('div.myrows').remove();
            $('#form_result').html('');
            $.ajax
            ({
                url:"/admin/savedcart/"+id+"/edit",
                dataType:"json",
                cache: false,
                success: function(ajaxresponse,status)
                {
                    var response = ajaxresponse;

                    var mailbody = response['mailbody'];
                    var transport_cost = response['transport_cost'];
                    var crew_cost = response['crew_cost'];
                    var manual_discount = response['manual_discount'];
                    var discount_percent = response['discount_percent'];
                    var discount_amount = response['discount_amount'];
                    var coupon_code = response['coupon_code'];
                    var coupon_discount = response['coupon_discount'];
                    var internalcounter = response['internalcounter'];
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
                    $("#form_result").hide().text('');

                    //$('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Saved Carts Details");
                    $('#action_button').val("Edit");
                    $('#action').val("Edit");
                    $('#formModal').modal({backdrop: 'static', keyboard: false, show: true});
                },
                error: function(a,b)
                {
                    $("#form_result").show().text('Please refresh and try again');
                    makefieldempty();
                }
            })
        });

        $(document).on('click', '.delete', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $.ajax({
                    url:"/admin/savedcart/destroy/"+id,
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#user_table').DataTable().ajax.reload();
                    }
                })
            }
            else
            {
                return false;
            }
        });

        $(document).on('click', '.move', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to move this data?"))
            {
                $.ajax({
                    url:"/admin/savedcart/move/"+id,
                    type: "GET",
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    data:{"id":id},
                    success: function(ajaxresponse,status)
                    {
                        var response = ajaxresponse;
                        var lastquoteid = response['lastquoteid'];

                        if(lastquoteid == 'moved')
                        {
                            alert('Cart details have been moved');
                            location.reload();
                        }
                        else
                        {
                            alert('Sorry the details could not be moved. Check connection or try again');
                        }
                    },
                    error: function(a,b)
                    {
                        alert('Sorry the details could not be deleted. Check connection or try again');
                    }
                })
            }
            else
            {
                return false;
            }
        });

        $("#formModal").on("hide.bs.modal", function () {
            makefieldempty();
        });

    });

    function makefieldempty()
    {
    }

    function saveremarks(value)
    {
        var quoteid = value;
        var remarks = $('#'+quoteid).val();
        //var remarks = remarks.replace(/(?:\r\n|\r|\n)/g, '<br />');

        remarks = encodeURIComponent(remarks);

        $.ajax
        ({
            url:"/admin/savedcart/saveremarks/"+quoteid+"/"+remarks,
            type: "GET",
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            data:{"quoteid":quoteid,"remarks":remarks},
            success: function(ajaxresponse,status)
            {
                var response = ajaxresponse;
                var lastquoteid = response['lastquoteid'];

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

    $( "#customers_quote_data table tbody" ).sortable();

</script>

@endpush

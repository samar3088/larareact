@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Coupons')
@section('cardheading','Add Coupons')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
@endpush

@section('content')

    <div align="right">
        <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Create Record</button>
    </div>
    <br />
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="user_table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Coupon  Code</th>
                    <th>Discount Given</th>
                    <th>Min Amount</th>
                    <th>Trans Type</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="formModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    <div class="modal-header">
                    <h4 class="modal-title">Add New Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <span id="form_result"></span>
                        <div class="form-group">
                            <label>Enter Coupon  Code</label>
                            <input type="text" name="couponcode" id="couponcode" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter Discount Given</label>
                            <input type="text" name="discount" id="discount" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter Min Amount</label>
                            <input type="text" name="min_amount" id="min_amount" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Select Trans Type</label>
                            <select id="trans_type" name="trans_type" class="form-control">
                                <option value="">Choose...</option>
                                <option value="marriage">Marriage</option>
                                <option value="equipment">Equipment</option>
                                <option value="birthday">Birthday</option>
                                <option value="package">Packages</option>
                              </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="action" id="action" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add" />
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

@endsection

@push('js')

<script>

    $(document).ready(function() {
        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            ajax:{
                url: "{{ route('admin.coupons.index') }}",
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
                    data: 'couponcode',
                    name: 'couponcode'
                },
                {
                    data: 'discount',
                    name: 'discount'
                },
                {
                    data: 'min_amount',
                    name: 'min_amount'
                },
                {
                    data: 'trans_type',
                    name: 'trans_type'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

        $('#create_record').click(function(){
            $('.modal-title').text("Add New Record");
            resetforms();
            $('#action_button').val("Add");
            $('#action').val("Add");
            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Add')
            {
                $.ajax
                ({
                    url:"{{ route('admin.coupons.store') }}",
                    method:"POST",
                    data: new FormData(this),
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
                            resetforms();
                            $('#user_table').DataTable().ajax.reload();
                            setTimeout(function(){ $('#formModal').modal('hide'); }, 2500);
                        }
                        $('#form_result').html(html);                        
                    }
                 })
            }

            if($('#action').val() == "Update")
            {
                $.ajax
                ({
                    url:"{{ route('admin.coupons.update') }}",
                    method:"POST",
                    data:new FormData(this),
                    contentType: false,
                    cache: false,
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
                            resetforms();
                            $('#store_image').html('');
                            $('#user_table').DataTable().ajax.reload();
                            setTimeout(function(){ $('#formModal').modal('hide'); }, 2500);
                        }
                        $('#form_result').html(html);                        
                    }
                });
             }
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax
            ({
                url:"/admin/coupons/"+id+"/edit",
                dataType:"json",
                success:function(html)
                {
                    $('#couponcode').val(html.data.couponcode);
                    $('#discount').val(html.data.discount);
                    $('#min_amount').val(html.data.min_amount);
                    $("#trans_type option[value='"+data.trans_type+"']").attr("selected", "selected");
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Update New Record");
                    $('#action_button').val("Update");
                    $('#action').val("Update");
                    $('#formModal').modal('show');
                }
            })
        });

        $(document).on('click', '.delete', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $.ajax({
                    url:"/admin/coupons/destroy/"+id,
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

    });

</script>

@endpush

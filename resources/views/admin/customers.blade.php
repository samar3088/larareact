@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Dashboard')
@section('cardheading','Tosshead Customers List')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
@endpush

@section('content')

    <h3 align="center">Import Customers CSV File in System</h3>
    <br />
    @if(count($errors) > 0)
    <div class="alert alert-danger">
    Upload Validation Error<br><br>
    <ul>
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
    </ul>
    </div>
    @endif

    @if($message = Session::get('success'))
    <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <div align="center">
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.customers.import') }}">
            {{ csrf_field() }}
            <div class="form-group">
             <table class="table">
              <tr>
               <td width="40%" align="right"><label>Select File for Upload</label></td>
               <td width="30">
                <input type="file" name="select_file" />
               </td>
               <td width="30%" align="left">
                <input type="submit" name="upload" class="btn btn-primary" value="Upload">
               </td>
              </tr>
              <tr>
               <td width="40%" align="right"></td>
               <td width="30"><span class="text-muted">.csv only</span></td>
               <td width="30%" align="left"></td>
              </tr>
             </table>
            </div>
           </form>
    </div>
    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add Customer</button>
    </div>
    <br />
    <table id="customer_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Date & Time</th>
                <th>Lead Source</th>
                <th>Action</th>
                <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button></th>
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="customerModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" id="customer_form">
                    <div class="modal-header">
                    <h4 class="modal-title">Add Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <span id="form_output"></span>
                        <div class="form-group">
                            <label>Enter Name</label>
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Enter Email</label>
                            <input type="text" name="email" id="email" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter Lead Source</label>
                            <input type="text" name="lead_source" id="lead_source" class="form-control"/>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="customer_id" id="customer_id" value="" />
                        <input type="hidden" name="button_action" id="button_action" value="insert" />
                        <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<script type="text/javascript">
    $(document).ready(function() {

        $('#customer_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[ 0, "desc" ]],
            "ajax":{
                url :"{{ route('admin.customers.getdata') }}",
                type: "get",  // method  , by default get
                //data: {"_token": "{{ csrf_token() }}","id": id},
                error: function(){  // error handling
                  $(".customer_table-error").html("");
                  $("#customer_table").append('<tbody class="customer_table-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
                  $("#customer_table_processing").css("display","none");
                }
              },
            "columns":[
                {
                    "data": 'id', "name": 'id',
                    render: function (data, type, row, meta)
                    {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { "data": "name" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "created_at" },
                { "data": "lead_source" },
                { "data": "action", orderable:false, searchable: false},
                { "data":"checkbox", orderable:false, searchable:false}
            ]
         });

         $('#add_data').click(function(){
            $('#customerModal').modal('show');
            $('#customer_form')[0].reset();
            $('#form_output').html('');
            $('#button_action').val('insert');
            $('#action').val('Add');
        });

        $('#customer_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"{{ route('admin.customers.postdata') }}",
                method:"POST",
                data:form_data,
                dataType:"json",
                success:function(data)
                {
                    if(data.error.length > 0)
                    {
                        var error_html = '';
                        for(var count = 0; count < data.error.length; count++)
                        {
                            error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
                        }
                        $('#form_output').html(error_html);
                    }
                    else
                    {
                        $('#form_output').html(data.success);
                        $('#customer_form')[0].reset();
                        $('#action').val('Add');
                        $('.modal-title').text('Add Data');
                        $('#button_action').val('insert');
                        $('#customer_table').DataTable().ajax.reload(); 
                        setTimeout(function(){ $('#customerModal').modal('hide'); }, 2500);                
                    }                    
                }
            })
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{ route('admin.customers.fetchdata') }}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#mobile').val(data.mobile);
                    $('#lead_source').val(data.lead_source);
                    $('#customer_id').val(id);
                    $('#customerModal').modal('show');
                    $('#action').val('Edit');
                    $('.modal-title').text('Edit Data');
                    $('#button_action').val('update');
                }
            })
        });

        $(document).on('click', '.delete', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $.ajax({
                    url:"{{route('admin.customers.removedata')}}",
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#customer_table').DataTable().ajax.reload();
                    }
                })
            }
            else
            {
                return false;
            }
        });

        $(document).on('click', '#bulk_delete', function(){
            var id = [];
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $('.customer_checkbox:checked').each(function(){
                    id.push($(this).val());
                });
                if(id.length > 0)
                {
                    $.ajax({
                        url:"{{ route('admin.customers.massremove')}}",
                        method:"get",
                        data:{id:id},
                        success:function(data)
                        {
                            alert(data);
                            $('#customer_table').DataTable().ajax.reload();
                        }
                    });
                }
                else
                {
                    alert("Please select atleast one checkbox");
                }
            }
        });

    });

</script>

@endpush

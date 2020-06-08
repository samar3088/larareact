@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Dashboard')
@section('cardheading','Tosshead Foodbooking Inquiry List')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
<link href="{{ asset('/dist/css/multiselect-checkbox.css')}}" rel="stylesheet">
@endpush

@section('content')

    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add Foodbooking Inquiry</button>
    </div>
    <br />
    <table id="foodbooking_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>City</th>
                <th>Action</th>
                <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button></th>
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="foodbookingModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" id="foodbooking_form">
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
                            <label>Enter Date</label>
                            <input type="text" name="date" id="date" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter City</label>
                            <input type="text" name="city" id="city" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter People Invited</label>
                            <input type="text" name="peopleinvited" id="peopleinvited" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Select Food Items</label>
                            <select class="form-control" id="fooditems" name="fooditems[]" multiple>
                                <option value="Bengali">Bengali</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Continental">Continental</option>
                                <option value="Thai">Thai</option>
                                <option value="North Indian">North Indian</option>
                                <option value="South Indian">South Indian</option>
                                <option value="Italian">Italian</option>
                                <option value="Mexican">Mexican</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">Choose...</option>
                                <option value="0">Not Completed</option>
                                <option value="1">Completed</option>
                              </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="foodbooking_id" id="foodbooking_id" value="" />
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

<script src="{{ asset('/dist/js/multiselect-checkbox.js')}}"></script>

<script type="text/javascript">

    $(document).ready(function() {

        $(function() {
            $('#fooditems').multiSelect();
        });

         $('#foodbooking_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[ 0, "desc" ]],
            "ajax":{
                url :"{{ route('admin.foodbooking.getdata') }}",
                type: "get",  // method  , by default get
                //data: {"_token": "{{ csrf_token() }}","id": id},
                error: function(){  // error handling
                  $(".foodbooking_table-error").html("");
                  $("#foodbooking_table").append('<tbody class="foodbooking_table-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                  $("#foodbooking_table_processing").css("display","none");
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
                { "data": "city" },
                { "data": "action", orderable:false, searchable: false},
                { "data":"checkbox", orderable:false, searchable:false}
            ]
         });

         $('#add_data').click(function(){
            $('#foodbookingModal').modal('show');
            $('#foodbooking_form')[0].reset();
            $('#form_output').html('');
            $('#button_action').val('insert');
            $('#action').val('Add');
        });

        $('#foodbooking_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"{{ route('admin.foodbooking.postdata') }}",
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
                        $('#foodbooking_form')[0].reset();
                        $('#action').val('Add');
                        $('.modal-title').text('Add Data');
                        $('#button_action').val('insert');
                        $('#foodbooking_table').DataTable().ajax.reload();
                        setTimeout(function(){ $('#foodbookingModal').modal('hide'); }, 2500);        
                    }                    
                }
            })
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{ route('admin.foodbooking.fetchdata') }}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#mobile').val(data.mobile);
                    $('#city').val(data.city);
                    $('#date').val(data.date);
                    $('#peopleinvited').val(data.peopleinvited);
                    $("#status option[value='"+data.status+"']").attr("selected", "selected");

                    var fooditems = data.fooditems;
                    fooditems = fooditems.split("*");

                    jQuery.each( fooditems, function( i, val ) {
                        let itemval = val;
                        console.log(itemval);
                        $("#fooditems option[value='"+itemval+"']").prop("selected", true).trigger("change");
                    });

                    $('#foodbooking_id').val(id);
                    $('#foodbookingModal').modal('show');
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
                    url:"{{route('admin.foodbooking.removedata')}}",
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#foodbooking_table').DataTable().ajax.reload();
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
                $('.foodbooking_checkbox:checked').each(function(){
                    id.push($(this).val());
                });
                if(id.length > 0)
                {
                    $.ajax({
                        url:"{{ route('admin.foodbooking.massremove')}}",
                        method:"get",
                        data:{id:id},
                        success:function(data)
                        {
                            alert(data);
                            $('#foodbooking_table').DataTable().ajax.reload();
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

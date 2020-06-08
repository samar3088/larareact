@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Dashboard')
@section('cardheading','Tosshead Quote List')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
<link href="{{ asset('/dist/css/multiselect-checkbox.css')}}" rel="stylesheet">
@endpush

@section('content')

    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add Quotes List</button>
    </div>
    <br />
    <table id="quote_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Heading</th>
                <th>Description</th>
                <th>Page</th>
                <th>Action</th>
                <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button></th>
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="quoteModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" id="quote_form">
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
                            <label>Enter Page Type</label>
                            <select type="text" class="form-control" id="page_type" name="page_type" required>
                                <option value="">--Select--</option>
                                <option value="choose_package">Choose Package Page</option>
                                <option value="packages">Packages Page</option>
                                <option value="own_top">Pick your own(TOP) page</option>
                                <option value="own_bottom">Pick your own(BOTTOM) page</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Enter Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Enter Description</label>
                            <textarea type="text" class="form-control" id="description" name="description" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="quote_id" id="quote_id" value="" />
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

         $('#quote_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[ 0, "desc" ]],
            "ajax":{
                url :"{{ route('admin.quote.getdata') }}",
                type: "get",  // method  , by default get
                //data: {"_token": "{{ csrf_token() }}","id": id},
                error: function(){  // error handling
                  $(".quote_table-error").html("");
                  $("#quote_table").append('<tbody class="quote_table-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                  $("#quote_table_processing").css("display","none");
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
                { "data": "page_type" },
                { "data": "heading" },
                { "data": "description" },
                { "data": "action", orderable:false, searchable: false},
                { "data":"checkbox", orderable:false, searchable:false}
            ]
         });

         $('#add_data').click(function(){
            $('#quoteModal').modal('show');
            $('#quote_form')[0].reset();
            $('#form_output').html('');
            $('#button_action').val('insert');
            $('#action').val('Add');
        });

        $('#quote_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"{{ route('admin.quote.postdata') }}",
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
                        $('#quote_form')[0].reset();
                        $('#action').val('Add');
                        $('.modal-title').text('Add Data');
                        $('#button_action').val('insert');
                        $('#quote_table').DataTable().ajax.reload();
                        setTimeout(function(){ $('#quoteModal').modal('hide'); }, 2500);
                    }                    
                }
            })
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{ route('admin.quote.fetchdata') }}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#heading').val(data.heading);
                    $('#description').val(data.description);
                    $("#page_type option[value='"+data.page_type+"']").attr("selected", "selected");

                    $('#quote_id').val(id);
                    $('#quoteModal').modal('show');
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
                    url:"{{route('admin.quote.removedata')}}",
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#quote_table').DataTable().ajax.reload();
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
                $('.quote_checkbox:checked').each(function(){
                    id.push($(this).val());
                });
                if(id.length > 0)
                {
                    $.ajax({
                        url:"{{ route('admin.quote.massremove')}}",
                        method:"get",
                        data:{id:id},
                        success:function(data)
                        {
                            alert(data);
                            $('#quote_table').DataTable().ajax.reload();
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

@extends('layouts.backend.app')
@section('title')
    add {{ $theme_type }} themes
@endsection

@section('pageheading')
    {{ ucfirst($theme_type) }} Themes
@endsection

@section('cardheading')
Add {{ ucfirst($theme_type) }} Themes
@endsection

@section('breadcrum','Home')

@section('subheading')
add {{ $theme_type }} themes
@endsection

@push('css')
@endpush

@section('content')

    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add Theme</button>
    </div>
    <br />
    <table id="theme_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Theme Name</th>
                <th>Theme Type</th>
                <th>Action</th>
                {{--  <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button></th>  --}}
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="themeModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" id="theme_form">
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
                            <label>Enter Theme Name</label>
                            <input type="text" name="theme_name" id="theme_name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Enter Theme Type</label>
                            <input type="text" name="theme_type" id="theme_type" value="{{ $theme_type }}" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="theme_id" id="theme_id" value="" />
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

        var theme_type = $('#theme_type').val();

        $('#theme_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[ 0, "desc" ]],
            "ajax":{
                url :"{{ route('admin.themesdata.getdata') }}",
                "data": function ( d ) {
                          d.theme_type = theme_type;
                          // d.custom = $('#myInput').val();
                          // etc
                },
                type: "get",  // method  , by default get
                error: function(){  // error handling
                  $(".theme_table-error").html("");
                  $("#theme_table").append('<tbody class="theme_table-error"><tr><th colspan="4">No data found in the server</th></tr></tbody>');
                  $("#theme_table_processing").css("display","none");
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
                { "data": "theme_name" },
                { "data": "theme_type" },
                { "data": "action", orderable:false, searchable: false}
                //{ "data":"checkbox", orderable:false, searchable:false}
            ]
         });

         $('#add_data').click(function(){
            $('#themeModal').modal('show');
            $('#theme_form')[0].reset();
            $('#form_output').html('');
            $('#button_action').val('insert');
            $('#action').val('Add');
        });

        $('#theme_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"{{ route('admin.themesdata.postdata') }}",
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
                        $('#theme_form')[0].reset();
                        $('#action').val('Add');
                        $('.modal-title').text('Add Data');
                        $('#button_action').val('insert');
                        $('#theme_table').DataTable().ajax.reload();
                        setTimeout(function(){ $('#themeModal').modal('hide'); }, 2500);
                    }
                }
            })
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{ route('admin.themesdata.fetchdata') }}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#theme_name').val(data.theme_name);
                    $('#theme_type').val(data.theme_type);
                    $('#theme_id').val(id);
                    $('#themeModal').modal('show');
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
                    url:"{{route('admin.themesdata.removedata')}}",
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#theme_table').DataTable().ajax.reload();
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
                $('.theme_checkbox:checked').each(function(){
                    id.push($(this).val());
                });
                if(id.length > 0)
                {
                    $.ajax({
                        url:"{{ route('admin.themesdata.massremove')}}",
                        method:"get",
                        data:{id:id},
                        success:function(data)
                        {
                            alert(data);
                            $('#theme_table').DataTable().ajax.reload();
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

@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','All Cities')
@section('cardheading','All Cities')
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
</style>
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
                    <th>Name</th>
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
                            <label>Enter Name</label>
                            <input type="text" name="name" id="name" class="form-control" />
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
                url: "{{ route('admin.cities.index') }}",
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
                    name: 'name'
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
                    url:"{{ route('admin.cities.store') }}",
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
                    url:"{{ route('admin.cities.update') }}",
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
                            $('#store_images').html('');
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
                //url:"/admin/custombdays/"+id+"/edit",
                url:"/admin/cities/"+id+"/edit",
                dataType:"json",
                success:function(html)
                {
                    $('#name').val(html.data.name);
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
                    url:"/admin/cities/destroy/"+id,
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

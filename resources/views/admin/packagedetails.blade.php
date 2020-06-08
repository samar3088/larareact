@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Packages Sub Themes')
@section('cardheading','Add Sub Packages Themes')
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
                    <th>Package Name</th>
                    <th>No Of Pax</th>
                    <th>Indoor/Outdoor</th>
                    <th>Price</th>
                    <th>Package Include</th>
                    <th>City</th>
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
                            <label>Package Name</label>
                            <select id="package_name" name="package_name" class="form-control">
                                <option value="">Choose...</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->package_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Enter No Of Pax</label>
                            <input type="text" name="no_of_pax" id="no_of_pax" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Select Indoor/Outdoor</label>
                            <select id="indoor_outdoor" name="indoor_outdoor" class="form-control">
                                <option value="">Choose...</option>
                                <option value="Indoor">Indoor</option>
                                <option value="Outdoor">Outdoor</option>
                              </select>
                        </div>
                        <div class="form-group">
                            <label>Enter Price</label>
                            <input type="text" name="price" id="price" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Enter Package Include</label>
                            <input type="text" name="package_include" id="package_include" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Select City</label>
                            <select id="city" name="city" class="form-control">
                                <option value="">Choose...</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
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
                url: "{{ route('admin.packagedetails.index') }}",
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
                    data: 'packagename',
                    name: 'packagename'
                },
                {
                    data: 'no_of_pax',
                    name: 'no_of_pax'
                },
                {
                    data: 'indoor_outdoor',
                    name: 'indoor_outdoor'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'package_include',
                    name: 'package_include'
                },
                {
                    data: 'cityname',
                    name: 'cityname'
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
                    url:"{{ route('admin.packagedetails.store') }}",
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
                    url:"{{ route('admin.packagedetails.update') }}",
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
                url:"/admin/packagedetails/"+id+"/edit",
                dataType:"json",
                success:function(html)
                {
                    $("#package_name option[value='"+html.data.package_name+"']").attr("selected", "selected");
                    $("#indoor_outdoor option[value='"+html.data.indoor_outdoor+"']").attr("selected", "selected");
                    $("#city option[value='"+html.data.city+"']").attr("selected", "selected");

                    $('#no_of_pax').val(html.data.no_of_pax);
                    $('#price').val(html.data.price);
                    $('#package_include').val(html.data.package_include);

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
                    url:"/admin/packagedetails/destroy/"+id,
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

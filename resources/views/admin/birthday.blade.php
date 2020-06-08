@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Birthday Sub Themes')
@section('cardheading','Add Birthday Sub Themes')
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
                    <th>Item Code</th>
                    <th>Type</th>
                    <th>Theme</th>
                    <th>Image</th>
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
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Enter Theme Type</label>
                                <select id="theme_id" name="theme_id" class="form-control">
                                    <option value="">Choose...</option>
                                    @foreach ($themes as $theme)
                                        <option value="{{ $theme->id }}">{{ $theme->theme_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type">Type</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="">Choose...</option>
                                    <option value="Q">Qty</option>
							        <option value="S">Sq ft</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Enter Sub Theme Name</label>
                            <input type="text" name="sub_theme_name" id="sub_theme_name" class="form-control" />
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="actual_price">Old Price</label>
                              <input type="text" class="form-control" name="actual_price" id="actual_price">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="discounted_price">New Price</label>
                              <input type="text" class="form-control" name="discounted_price" id="discounted_price">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="views">Views</label>
                                <input type="text" class="form-control" name="views" id="views">
                              </div>
                            <div class="form-group col-md-6">
                              <label for="rating">Rating</label>
                              <input type="text" class="form-control" name="rating" id="rating">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="particular">Item Code</label>
                                <input type="text" class="form-control" name="particular" id="particular">
                              </div>
                            <div class="form-group col-md-6">
                              <label for="label">Label</label>
                              <input type="text" class="form-control" name="label" id="label">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Main Image</label>
                            <input type="file" name="file" id="file" class="form-control"/>
                            <span id="store_image"></span>
                        </div>
                        <div class="form-group">
                            <label>Additional Images Image</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple/>
                            <span id="store_images"></span>
                        </div>
                        <div class="form-group">
                            <label>Enter Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Enter What Included</label>
                            <textarea class="form-control" id="what_included" name="what_included" rows="3"  placeholder="Please enter # separated values for points"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Enter Need to Know</label>
                            <textarea class="form-control" id="need_to_know" name="need_to_know" rows="3"  placeholder="Please enter # separated values for points"></textarea>
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
                url: "{{ route('admin.bdaysubthemes.index') }}",
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
                    data: 'sub_theme_name',
                    name: 'sub_theme_name'
                },
                {
                    data: 'particular',
                    name: 'particular'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'themename',
                    name: 'themename'
                },
                {
                    data: 'file',
                    name: 'file',
                    render: function(data, type, full, meta)
                    {
                        //return "<img src={{ URL::to('/') }}/images/" + data + " width='70' class='img-thumbnail' />";
                        return "<img src={{ URL::to('/') }}/storage/" + data + " width='70' class='img-thumbnail' />";
                    },
                    orderable: false
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
                    url:"{{ route('admin.bdaysubthemes.store') }}",
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
                    url:"{{ route('admin.bdaysubthemes.update') }}",
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
                url:"/admin/bdaysubthemes/"+id+"/edit",
                dataType:"json",
                success:function(html)
                {
                    $("#theme_id option[value='"+html.data.theme_id+"']").attr("selected", "selected");
                    $("#type option[value='"+html.data.type+"']").attr("selected", "selected");
                    $('#sub_theme_name').val(html.data.sub_theme_name);
                    $('#actual_price').val(html.data.actual_price);
                    $('#discounted_price').val(html.data.discounted_price);
                    $('#label').val(html.data.label);
                    $('#particular').val(html.data.particular);
                    $('#rating').val(html.data.rating);
                    $('#views').val(html.data.views);
                    $('#description').val(html.data.description);
                    $('#what_included').val(html.data.what_included);
                    $('#need_to_know').val(html.data.need_to_know);

                    var imagefile = html.data.file;

                    if(imagefile != '' && imagefile != undefined)
                    {
                        $('#store_image').html("<img src={{ URL::to('/') }}/storage/" + html.data.file + " width='70' class='img-thumbnail' />");
                        $('#store_image').append("<input type='hidden' name='hidden_image' value='"+html.data.file+"' />");
                    }

                    var subimages_count = html.subimages.length;
                    //console.log('subimages_count : '+ subimages_count);

                    var subimages_images = html.subimages;

                    if(subimages_count > 0)
                    {
                        jQuery.each( subimages_images, function( i, val ) {
                            let itemval = val;
                            //console.log(itemval);
                            var subimage_path = itemval.path;
                            var subimage_id = itemval.id;
                            $('#store_images').append("<div class='additionalimages' id='additionalimages-"+subimage_id+"'><img src={{ URL::to('/') }}/storage/" + subimage_path + " width='70' class='img-thumbnail' /><br><span class='remove_subimages' id='"+subimage_id+"'>Del Image</span></div>");
                            //$('#store_images').append("<input type='hidden' name='hidden_image' value='"+subimage_path+"' />");
                        });
                    }

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
                    url:"/admin/bdaysubthemes/destroy/"+id,
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

        $(document).on('click', '.remove_subimages', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $.ajax({
                    url:"/admin/bdaysubthemes/delsubimage/"+id,
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#additionalimages-'+id).remove();
                        //$('#user_table').DataTable().ajax.reload();
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

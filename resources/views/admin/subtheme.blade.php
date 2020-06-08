@extends('layouts.backend.app')
@section('title')
    add {{ $theme_type }} sub themes
@endsection

@section('pageheading')
    {{ ucfirst($theme_type) }} Sub Themes
@endsection

@section('cardheading')
Add {{ ucfirst($theme_type) }} Sub Themes
@endsection

@section('breadcrum','Home')

@section('subheading')
add {{ $theme_type }} sub themes
@endsection

@push('css')
@endpush

@section('content')

    @if(count($errors) > 0)
    <div class="alert alert-danger">
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

<div align="left">
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.subthemes.postdata') }}" id="subtheme_form">
     {{ csrf_field() }}
     <div class="form-group row">
       <label for="sub_theme_name" class="col-sm-2 col-form-label">Sub Theme Name</label>
       <div class="col-sm-10">
         <input type="text" class="form-control" name="sub_theme_name" id="sub_theme_name" placeholder="Sub theme name">
       </div>
     </div>
     <div class="form-group row">
        <label for="theme_id" class="col-sm-2 col-form-label">Choose Theme</label>
        <div class="col-sm-10">
            <select id="theme_id" name="theme_id" class="form-control">
                <option value="">Choose...</option>
                @foreach ($themes as $theme)
                    <option value="{{ $theme->id }}">{{ $theme->theme_name }}</option>
                @endforeach
              </select>
        </div>
      </div>
     <div class="form-group row">
       <div class="col-sm-10">
         <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary" />
       </div>
     </div>
   </form>

   <br />
    <table id="subtheme_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Theme Name</th>
                <th>Subtheme Name</th>
                <th>Action</th>
                <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subthemes as $subtheme)
                <tr>
                    <td>{{ ++$counter }}</td>
                    <td>{{ $subtheme->theme_name }}</td>
                    <td>{{ $subtheme->sub_theme_name }}</td>
                    <td>
                        <a href="#" class="btn btn-xs btn-primary edit" id="{{ $subtheme->id }}" title="Edit"><i class="fas fa-edit"></i></a>
                            &nbsp;
                        <a href="#" class="btn btn-xs btn-danger delete" id="{{ $subtheme->id }}" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                    <td><input type="checkbox" name="subtheme_checkbox[]" class="subtheme_checkbox" value="{{ $subtheme->id }}" /></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

<div class="modal fade" id="themeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" id="sub_theme_form">
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
                        <label>Enter Sub Theme Name</label>
                        <input type="text" name="subtheme_name" id="subtheme_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Enter Theme Type</label>
                        <select id="themeid" name="themeid" class="form-control">
                            <option value="">Choose...</option>
                            @foreach ($themes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->theme_name }}</option>
                            @endforeach
                          </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" name="theme_type" id="theme_type" value="{{ $theme_type }}" />
                    <input type="hidden" name="sub_theme_id" id="sub_theme_id" />
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

        $('#subtheme_table').DataTable();

        $('#sub_theme_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"{{ route('admin.subthemes.updatesubtheme') }}",
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
                        //$('#theme_form')[0].reset();
                        $('#sub_theme_id').val('');
                        $('#subtheme_name').val('');
                        $("#themeid option:first").attr('selected','selected');
                        $('#action').val('Add');
                        $('.modal-title').text('Add Data');
                        $('#button_action').val('insert');
                        //$('#theme_table').DataTable().ajax.reload();

                        setTimeout(function(){ window.location.reload(); }, 1000);

                    }
                }
            })
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{ route('admin.subthemes.fetchdata') }}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#subtheme_name').val(data.sub_theme_name);
                    $('#sub_theme_id').val(data.sub_theme_id);
                    $('#theme_type').val(data.theme_type);
                    $("#themeid option[value='"+data.themeid+"']").attr("selected", "selected");
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
                    url:"{{route('admin.subthemes.muldelete')}}",
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        window.location.reload();
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
                            window.location.reload();
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

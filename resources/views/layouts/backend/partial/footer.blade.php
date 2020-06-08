<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.2
    </div>
    <strong>Copyright &copy; 2018-2020 <a href="https://tosshead.com" target="_blank">Tosshead</a>.</strong> All rights reserved.
  </footer>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/dist/js/adminlte.min.js') }}"></script>
<script>
function resetforms()
{
    $('#sample_form')[0].reset();
    $('#sample_form select option:selected').removeAttr('selected');
    $('#form_result').html('');
    $('#store_image').html('');
    $('#store_images').html('');
    //$("#sample_form select option:first").attr('selected','selected');
}

$('#formModal').on('hidden.bs.modal', function () {
    resetforms();
    console.log('modal');
})
</script>
<!-- page script -->
@stack('js')

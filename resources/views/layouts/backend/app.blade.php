@include('layouts.backend.partial.header')
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  @include('layouts.backend.partial.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layouts.backend.partial.sidebar')
  <!-- /.Main Sidebar Container Ends-->

  <!-- Page Breadcrums -->
  @include('layouts.backend.partial.breadcrums')
  <!-- /.Page Breadcrums  Ends-->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">@yield('cardheading')</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <!-- All data to be coming Here -->

                @yield('content')

                <!-- /.All data to be coming Here ends -->

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Page Footer -->

  @include('layouts.backend.partial.footer')

<!-- /.Page Footer Ends-->

</body>
</html>

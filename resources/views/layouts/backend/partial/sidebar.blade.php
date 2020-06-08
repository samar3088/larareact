<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Tosshead</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Welcome {{ Auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

         <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
              <p>
                Packages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.packagethemes.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Themes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.packagedetails.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Sub Themes</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Equipments
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.equipthemes') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Themes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.equipsubthemes.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Sub Themes</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
              <p>
                Marriage
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.marriagethemes') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add  Themes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.marriagesubthemes.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Sub Themes</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-gift"></i>
              <p>
                Birthdays
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.birthdaythemes') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Themes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.bdaysubthemes.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Add Sub Themes</p>
                    </a>
                </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
              <p>
                Quotations
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.savedcart.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Saved Carts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.quotations.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Quotation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.quotations.confirmed') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Confirmed</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.quotations.completed') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
              <p>
                Inquiries
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.banquet') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Banquets</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.foodbooking') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Food Booking</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.custombdays.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Custom Birthday</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
              <p>
                B2B
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.createdusers') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generated</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.createdusersunreg') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users From Registered</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.reports.completed') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed Events</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.reports.consolidated') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Consolidated</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.specialevent') }}" class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Enquiry (Special Event)</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.customers') }}" class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Customer Data</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
              <p>
                Site Utilities
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.cities.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Cities</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.eventcoordinate.index') }}" class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Event Co-ordinator</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.mainvendor.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vendor Supply Items</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.maincategory.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Artists Sub Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.subdetailsvendor.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vendor / Artists Items</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.vendordet.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registered Vendors</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
              <p>
                Admin Utilities
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link">
                      <i class="nav-icon far fa-circle"></i>
                      <p>Coupons Codes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.coupontext.index') }}" class="nav-link">
                      <i class="nav-icon far fa-circle"></i>
                      <p>Coupons Text</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.homeslider.index') }}" class="nav-link">
                      <i class="nav-icon far fa-circle"></i>
                      <p>Home Sliders</p>
                    </a>
                </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
              <p>
                Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.items.index') }}" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>Banner & Content</p>
                    </a>
                </li>
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

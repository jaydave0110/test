<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="{{asset('theme/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      @if(auth()->user()!="")
      @if(!auth()->user()->roles->pluck('name')->isEmpty())
      <span class="brand-text font-weight-light">{{ ucfirst(auth()->user()->roles->pluck('name')[0])}}</span>
      @endif
      @endif
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('theme/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          @if(auth()->user()!="")
          <a href="#" class="d-block">{{ ucfirst(auth()->user()->name)}}</a>
          @endif
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
           
          
            
        <li class="nav-item @if(Route::current()->getName() == 'home') active @endif">
          <a href="{{route('home')}}" class="nav-link @if(Route::current()->getName() == 'home') active @endif">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
            
          
           
          <li class="nav-item @if(Route::current()->getName() == 'sites.index' || Route::current()->getName() == 'sites.create' || Route::current()->getName() == 'sites.edit' ) menu-is-opening menu-open @endif">
            <a href="#" class="nav-link @if(Route::current()->getName() == 'sites.index' || Route::current()->getName() == 'sites.create' || Route::current()->getName() == 'sites.edit') active @endif">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                 Site Mangement
                <i class="fas fa-angle-left right"></i>
                 
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('sites.index')}}" class="nav-link @if(Route::current()->getName() == 'sites.index' || Route::current()->getName() == 'sites.create' || Route::current()->getName() == 'sites.edit')  active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Builder Sites</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item @if(Route::current()->getName() == 'users.index' 
                               || Route::current()->getName() == 'users.create' 
                               || Route::current()->getName() == 'users.edit'  
                      || Route::current()->getName() == 'companyrepresentative.create' 
                      || Route::current()->getName() == 'companyrepresentative.edit'  
                      || Route::current()->getName() == 'companyrepresentative.index' 
                      
                      || Route::current()->getName() == 'cityhead.create' 
                      || Route::current()->getName() == 'cityhead.edit'  
                      || Route::current()->getName() == 'cityhead.index'
                      ||  Route::current()->getName() == 'saleshead.index' 
                      ||  Route::current()->getName() == 'saleshead.create' 
                      || Route::current()->getName() == 'saleshead.edit'

                      
                      || Route::current()->getName() == 'brokers.index'         


                               ) menu-is-opening menu-open @endif">
            <a href="#" class="nav-link @if(Route::current()->getName() == 'users.index' || Route::current()->getName() == 'users.create' || Route::current()->getName() == 'users.edit' || Route::current()->getName() == 'companyrepresentative.create' 
                      || Route::current()->getName() == 'companyrepresentative.edit'  
                      || Route::current()->getName() == 'companyrepresentative.index'

                      || Route::current()->getName() == 'cityhead.create' 
                      || Route::current()->getName() == 'cityhead.edit'  
                      || Route::current()->getName() == 'cityhead.index'

                      ||  Route::current()->getName() == 'saleshead.index' 
                      ||  Route::current()->getName() == 'saleshead.create' 
                      || Route::current()->getName() == 'saleshead.edit'
                     
                       
                      || Route::current()->getName() == 'brokers.index'


                       ) active @endif">
              <i class="nav-icon fas fa-users"></i>
              <p>
                 Users Management 
                <i class="fas fa-angle-left right"></i>
                 
              </p>
            </a>
            <ul class="nav nav-treeview">
              
               <li class="nav-item">
                <a href="{{route('users.index')}}" class="nav-link @if(Route::current()->getName() == 'users.index' || Route::current()->getName() == 'users.create' || Route::current()->getName() == 'users.edit' ) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('companyrepresentative.index')}}" class="nav-link @if(Route::current()->getName() == 'companyrepresentative.index' || Route::current()->getName() == 'companyrepresentative.create' || Route::current()->getName() == 'companyrepresentative.edit' ) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Company Representative</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('cityhead.index')}}" class="nav-link @if(Route::current()->getName() == 'cityhead.index' || Route::current()->getName() == 'cityhead.create' || Route::current()->getName() == 'cityhead.edit' ) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>City / Channel Head</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('saleshead.index')}}" class="nav-link @if(Route::current()->getName() == 'saleshead.index' || Route::current()->getName() == 'saleshead.create' || Route::current()->getName() == 'saleshead.edit' ) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Head</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('brokers.index')}}" class="nav-link @if(Route::current()->getName() == 'brokers.index' || Route::current()->getName() == 'brokers.create' || Route::current()->getName() == 'brokers.edit' ) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Brokers</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">Bookings  Management</li>
          <li class="nav-item">
            <a href="{{ route('bookings.index') }}" class="nav-link @if(Route::current()->getName() == 'bookings.index' || Route::current()->getName() == 'bookings.edit' || Route::current()->getName() == 'bookings.create' || Route::current()->getName() == 'bookings.payments' || Route::current()->getName() == 'cashpaymentinfo' || Route::current()->getName() == 'directpaymentinfo'   ) active @endif">
              <i class="nav-icon fas fa-file"></i>
              <p>Bookings</p>
            </a> 
          </li> 

          <!-- <li class="nav-item">
            <a href="{{ route('inquiry.index') }}" class="nav-link @if(Route::current()->getName() == 'inquiry.index') active @endif">
              <i class="nav-icon fas fa-file"></i>
              <p>Inquiries</p>
            </a> 
          </li>  -->
 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
     <!-- <img src="/dist/img/AdminLTELogo.png"  class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      {{-- <span class="brand-text font-weight-light"><h3>{{auth()->user()->role}}</h3></span> --}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image mt-3">
          <img src="{{asset('assets/dist/img/avatar5.png')}}" class="img-circle " >
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
          <p><span class="brand-text font-weight-light" style="color:white">{{auth()->user()->role}}</span></p>
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
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item ">
            <a href="/home" class="nav-link {{ Request::is('home') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          @if (auth()->user()->role == 'Admin'||auth()->user()->role == 'Super Admin'||auth()->user()->role == 'NOC Admin'||auth()->user()->role == 'Sales Admin'||auth()->user()->role == 'Rate Admin'||auth()->user()->role == 'Billing Admin')
          <li class="nav-item ">
            <a href="{{url('/users')}}" class="nav-link {{ Request::is('users','users/create') ? 'active' : '' }} ">
                <i class="nav-icon fas fa-user-alt"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item ">
            <a href="{{url('/call')}}" class="nav-link {{ Request::is('call') ? 'active' : '' }} ">
                <i class="nav-icon fas fa-phone-alt"></i>
              <p>
                Call-history
              </p>
            </a>
          </li>

          <li class="nav-item ">
            <a href="{{url('/client')}}" class="nav-link {{ Request::is('client') ? 'active' : '' }} ">
                {{-- <i class="nav-icon fas fa-phone-alt"></i> --}}
                <i class="nav-icon fas fa-file-alt"></i>
              <p>
               Account
              </p>
            </a>
          </li>

          <li class="nav-item ">
            <a href="{{url('/crm')}}" class="nav-link {{ Request::is('crm','crm/create') ? 'active' : '' }} ">
                <i class="nav-icon fas fa-user-alt"></i>
              <p>
                CRM
              </p>
            </a>
          </li>

          <li class="nav-item ">
            <a href="{{url('/cron')}}" class="nav-link {{ Request::is('cron') ? 'active' : '' }} ">
                <i class="nav-icon fas fa-tasks"></i>
              <p>
                Cron Job
              </p>
            </a>  
          </li>

          <li class="nav-item">
            <a href="{{url('/setting')}}" class="nav-link {{ Request::is('setting') ? 'active' : '' }} ">
                <i class="nav-icon fa fa-cog"></i>
              <p>
                Settings
              </p>
            </a>
          </li>

          @if(Auth::user()->role  == 'Admin')
              @php
                $company = App\Models\Company::first();
              @endphp
                @if(!empty($company))
                  <li class="nav-item ">
                      <a href="{{route('company.edit',$company->id)}}" class="nav-link {{ Request::is('company') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-building"></i>
                          <p>
                          Company
                          </p>
                      </a>
                  </li>
                @endif
          @endif

            <li class="nav-item">
                <a class="nav-link " href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>{{ __('Logout') }}</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                    <p>Logout</p>
                </form>
            </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

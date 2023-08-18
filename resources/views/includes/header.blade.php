 <!-- Preloader -->
 {{-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('assets/dist/img/AdminLTELogo.png')}}"  height="60" width="60">
  </div> --}}

<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse order-3 justify-content-between" id="navbarCollapse">
            <div class="d-flex">
                <div class="image">
                    <img src="{{asset('assets/dist/img/download.png')}}" height="60" width="160" >
                </div>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item"> 
                    <a href="/home" class="nav-link {{ Request::is('home') ? 'active' : '' }}">Home</a>
                </li>
                @if (auth()->user()->role == 'Admin'||auth()->user()->role == 'Super Admin'||auth()->user()->role == 'NOC Admin'||auth()->user()->role == 'Sales Admin'||auth()->user()->role == 'Rate Admin'||auth()->user()->role == 'Billing Admin')   
                    <li class="nav-item">
                        <a href="{{url('/users')}}" class="nav-link {{ Request::is('users','users/*') ? 'active' : '' }} ">Users</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{route('cdr_show.index')}}" class="nav-link {{ Request::is('cdr-show','cdr-show/*','Upload-CDR') ? 'active' : '' }} ">Call-history</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('export.history')}}" class="nav-link {{ Request::is('export-history','export-history/*') ? 'active' : '' }}">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('export-csv.history')}}" class="nav-link {{ Request::is('export-csv-history') ? 'active' : '' }} ">Report</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('/client')}}" class="nav-link {{ Request::is('client','client/*','client-customer/*','client-vendor/*') ? 'active' : '' }}">Account</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('/crm')}}" class="nav-link {{ Request::is('crm','crm/*','getClient/*','comment/*') ? 'active' : '' }} ">CRM</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('/cron')}}" class="nav-link {{ Request::is('cron','cron/*') ? 'active' : '' }} ">Cron Job</a>
                </li>
                <li class="nav-item dropdown {{ Request::is('trunks','trunks/*','setting','setting/*') ? 'menu-is-opening menu-open' : '' }}">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Settings</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="{{url('/trunks')}}" class="dropdown-item {{ Request::is('trunks','trunks/*') ? 'active' : '' }}">Trunks </a></li>
                        <li><a href="{{url('/setting')}}" class="dropdown-item {{ Request::is('setting','setting/*') ? 'active' : '' }} ">Settings</a></li>
                        <li class="dropdown-divider"></li>
                        @if(Auth::user()->role  == 'Admin')
                            @php
                                $company = App\Models\Company::first();
                            @endphp
                            @if(!empty($company))
                                <li class="nav-item">
                                    <a href="{{route('company.edit',$company->id)}}" class="nav-link {{ Request::is('company','company/*') ? 'active' : ''}}">Company</a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-panel d-flex">
                            <div class="image">
                                <img src="{{asset('assets/dist/img/avatar5.png')}}" class="animation__shake" >
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ (auth()->user()->name) }}</h6>
                        </div>
                        <div class="dropdown-divider"></div> 
                        <a href="{{route('logout')}}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </a>
                    </div>
                </li>
                <!-- Navbar Search -->
            </ul>
        </div>
    </div>
     <!-- /.Container -->
</nav>
  <!-- /.navbar -->

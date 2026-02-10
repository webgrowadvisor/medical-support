    <!--! ================================================================ !-->
    <!--! [Start] Navigation Manu !-->
    <!--! ================================================================ !-->
    <nav class="nxl-navigation">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="#" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    <!-- <img src="#" alt="" class="logo logo-lg" />
                    <img src="#" alt="" class="logo logo-sm" /> -->
                    ADMIN Panel
                </a>
            </div>
            <div class="navbar-content">
            
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('admin.dashboard') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-house"></i></span>
                            <span class="nxl-mtext">Dashboard</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li> 

                    <li class="nxl-item nxl-caption">
                        <label>Users</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-user"></i></span>
                            <span class="nxl-mtext">Users</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('user.list') }}">Users</a></li>
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('seller.list') }}">Doctors</a></li>
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Payments (per-visit and subscription)</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-bag-shopping"></i>
                            </span>
                            <span class="nxl-mtext">Subscription</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item">
                                <a class="nxl-link" href="{{ route('admin.subscription.plans') }}">Subscription</a>
                            </li>
                            <li class="nxl-item">
                                <a class="nxl-link" href="{{ route('admin.commission.plans') }}">Commission</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Scheduling and Booking</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('admin.appointments') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-calendar"></i></span>
                            <span class="nxl-mtext">Doctor Appointments</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Service Catalog Subscriptions</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="#" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-cat"></i></span>
                            <span class="nxl-mtext">Service Catalog</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Payments</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="#" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-brands fa-paypal"></i></span>
                            <span class="nxl-mtext">Payments </span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Prescriptions</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-prescription"></i></span>
                            <span class="nxl-mtext">Prescriptions</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.prescriptions') }}">All Prescriptions</a></li>                            
                        </ul>
                    </li>
                    
                    <li class="nxl-item nxl-caption">
                        <label>Doctor Payouts</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-brands fa-amazon-pay"></i></span>
                            <span class="nxl-mtext">Payouts</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.doctor.payouts') }}">All Payouts</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Auditlogging</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('admin.auditlogging') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-computer"></i></span>
                            <span class="nxl-mtext">Auditlogging</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Announcements</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('announcements.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-bullhorn"></i></span>
                            <span class="nxl-mtext">Announcements</span><span class="nxl-arrow"></span>
                        </a>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Doctor Protocol</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('protocol.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-user-doctor"></i></span>
                            <span class="nxl-mtext">Doctor Protocol</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Logout</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a onclick="return confirm('Are you sure you want to log out?')" href="{{ route('admin.logout') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-power"></i></span>
                            <span class="nxl-mtext">Logout</span><span class="nxl-arrow"></span>
                        </a>                      
                    </li>
                    
                    
                </ul>
                
            </div>
        </div>
    </nav>
    <!--! ================================================================ !-->
    <!--! [End]  Navigation Manu !-->
    <!--! ================================================================ !-->
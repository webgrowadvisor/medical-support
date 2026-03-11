    <!--! ================================================================ !-->
    <!--! [Start] Navigation Manu !-->
    <!--! ================================================================ !-->
    <nav class="nxl-navigation">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="javascript:void(0);" class="b-brand">
                    <h4>Patient 😷</h4>
                </a>
            </div>
            <div class="navbar-content">
            
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('user.desh') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-house"></i></span>
                            <span class="nxl-mtext">Dashboards</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Scheduling and Booking</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-calendar"></i></span>
                            <span class="nxl-mtext">Scheduling and Booking</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('user.doctors') }}">Scheduling and Booking</a></li>
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('user.appointments') }}">Schedule Appointments</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Prescriptions</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="fa-solid fa-prescription"></i>
                            </span>
                            <span class="nxl-mtext">Prescriptions</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('user.prescriptions') }}">All Prescriptions</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Wallet</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="fa-solid fa-wallet"></i>
                            </span>
                            <span class="nxl-mtext">Wallet</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('user.wallet') }}">Wallet</a></li>                            
                        </ul>
                    </li>

                    
                    <li class="nxl-item nxl-caption">
                        <label>My Files</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('user.file.show') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-file"></i></span>
                            <span class="nxl-mtext">My Files</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                    </li>
                    
                    <li class="nxl-item nxl-caption">
                        <label>Service Catalog Subscriptions</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('user.service_catalog') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-cat"></i></span>
                            <span class="nxl-mtext">Service Catalog</span><span class="nxl-arrow"></span>
                        </a>
                    </li> 

                    <li class="nxl-item nxl-caption">
                        <label>Order Medication</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('user.medicines') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-briefcase-medical"></i></span>
                            <span class="nxl-mtext">Order Medication<span class="nxl-arrow"></span>
                        </a>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>My Orders</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('user.orders') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-cart-shopping"></i></span>
                            <span class="nxl-mtext">My Orders<span class="nxl-arrow"></span>
                        </a>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Library</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="fa-solid fa-book"></i>
                            </span>
                            <span class="nxl-mtext">Library</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('user.library') }}">Catalog</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Announcement</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('user.announcement') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="fa-solid fa-bullhorn"></i></span>
                            <span class="nxl-mtext">Announcement</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Logout</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a onclick="return confirm('Are you sure you want to log out?')" href="{{ route('user.logout') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-power"></i></span>
                            <span class="nxl-mtext">Logout</span><span class="nxl-arrow"></span>
                        </a>                      
                    </li>
                    
                    
                </ul>
                
            </div>
        </div>
    </nav>
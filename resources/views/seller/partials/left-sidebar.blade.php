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
                    @if (sellerinfo()->logo)
                    {!! variantImage(sellerinfo()->logo, 60, 60) !!}
                    @endif
                    Doctor Panel
                </a>
            </div>
            <div class="navbar-content">
            
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('seller.dashboard') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Dashboards</span><span class="nxl-arrow"></span>
                        </a>                        
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Availability & Appointments</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-calendar"></i></span>
                            <span class="nxl-mtext">Availability</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('doctor.availability.index') }}">Availability</a></li> 
                            <li class="nxl-item">
                                <a class="nxl-link" href="{{ route('doctor.appointments') }}">Appointments</a>
                            </li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Prescriptions</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-calendar"></i></span>
                            <span class="nxl-mtext">Prescriptions</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('doctor.prescriptions') }}">Prescriptions</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Wallet</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-calendar"></i></span>
                            <span class="nxl-mtext">Wallet</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('doctor.wallet') }}">Wallet</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Payout</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-calendar"></i></span>
                            <span class="nxl-mtext">Payout</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('doctor.payouts') }}">Payout</a></li>                            
                        </ul>
                    </li>

                    <li class="nxl-item nxl-caption">
                        <label>Review</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-calendar"></i></span>
                            <span class="nxl-mtext">Review</span><span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('doctor.reviews') }}">Reviews</a></li>                            
                        </ul>
                    </li>
                                                      

                    <li class="nxl-item nxl-caption">
                        <label>Logout</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a onclick="return confirm('Are you sure you want to log out?')" href="{{ route('seller.logout') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-power"></i></span>
                            <span class="nxl-mtext">Logout</span><span class="nxl-arrow"></span>
                        </a>                      
                    </li>
                    
                    
                </ul>
                
            </div>
        </div>
    </nav>
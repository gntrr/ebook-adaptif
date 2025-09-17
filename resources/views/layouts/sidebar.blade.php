<aside class="sidebar">
    <!-- sidebar close btn -->
     <button type="button" class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i class="ph ph-x"></i></button>
    <!-- sidebar close btn -->
    
    <a href="index.html" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="{{ asset('images/logo-black.png') }}" alt="Logo" style="max-width: 160px">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard</span>
                        <span class="link-badge">3</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="index.html" class="sidebar-submenu__link"> Dashboard One </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="index-2.html" class="sidebar-submenu__link"> Dashboard Two </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="index-3.html" class="sidebar-submenu__link"> Dashboard Three </a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-graduation-cap"></i></span>
                        <span class="text">Courses</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="student-courses.html" class="sidebar-submenu__link"> Student Courses </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="mentor-courses.html" class="sidebar-submenu__link"> Mentor Courses </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="create-course.html" class="sidebar-submenu__link"> Create Course </a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                <li class="sidebar-menu__item">
                    <a href="students.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users-three"></i></span>
                        <span class="text">Students</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="assignment.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-clipboard-text"></i></span>
                        <span class="text">Assignments</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="mentors.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Mentors</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="resources.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-bookmarks"></i></span>
                        <span class="text">Resources</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="message.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-chats-teardrop"></i></span>
                        <span class="text">Messages</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="analytics.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-chart-bar"></i></span>
                        <span class="text">Analytics</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="event.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-calendar-dots"></i></span>
                        <span class="text">Events</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="library.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-books"></i></span>
                        <span class="text">Library</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="pricing-plan.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-coins"></i></span>
                        <span class="text">Pricing</span>
                    </a>
                </li>
                
                <li class="sidebar-menu__item">
                    <span class="text-gray-300 text-sm px-20 pt-20 fw-semibold border-top border-gray-100 d-block text-uppercase">Settings</span>
                </li>
                <li class="sidebar-menu__item">
                    <a href="setting.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-gear"></i></span>
                        <span class="text">Account Settings</span>
                    </a>
                </li>

                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-shield-check"></i></span>
                        <span class="text">Authetication</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="sign-in.html" class="sidebar-submenu__link">Sign In</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="sign-up.html" class="sidebar-submenu__link">Sign Up</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="forgot-password.html" class="sidebar-submenu__link">Forgot Password</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="reset-password.html" class="sidebar-submenu__link">Reset Password</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="verify-email.html" class="sidebar-submenu__link">Verify Email</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="two-step-verification.html" class="sidebar-submenu__link">Two Step Verification</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
            </ul>
        </div>
    </div>

</aside>    
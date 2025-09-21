<aside class="sidebar">
    <!-- sidebar close btn -->
     <button type="button" class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i class="ph ph-x"></i></button>
    <!-- sidebar close btn -->
    
    <a href="index.html" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="{{ asset('images/logo-black.png') }}" alt="Logo" style="max-width: 160px">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            @php
                $isAdmin = Auth::user()?->is_admin ?? false;
            @endphp
            <ul class="sidebar-menu">
                <li class="sidebar-menu__item">
                    <a href="{{ route('dashboard') }}" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard Siswa</span>
                    </a>
                </li>

                @if ($isAdmin)
                    <li class="sidebar-menu__item">
                        <span class="text-gray-300 text-sm px-20 pt-20 fw-semibold border-top border-gray-100 d-block text-uppercase">Admin CMS</span>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-gauge"></i></span>
                            <span class="text">Dashboard Admin</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.materi.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-notebook"></i></span>
                            <span class="text">Manajemen Materi</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.evaluasi.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-list-checks"></i></span>
                            <span class="text">Bank Soal &amp; Evaluasi</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.rules.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-sliders"></i></span>
                            <span class="text">Aturan Adaptif</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.decision-tree.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-flow-arrow"></i></span>
                            <span class="text">Decision Tree</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.klasifikasi.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-users-three"></i></span>
                            <span class="text">Users &amp; Klasifikasi</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.reports.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-chart-pie"></i></span>
                            <span class="text">Laporan &amp; Ekspor</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="{{ route('admin.uat.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-clipboard"></i></span>
                            <span class="text">UAT</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-menu__item">
                    <span class="text-gray-300 text-sm px-20 pt-20 fw-semibold border-top border-gray-100 d-block text-uppercase">Pengaturan</span>
                </li>
                <li class="sidebar-menu__item">
                    <a href="{{ route('profile.edit') }}" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-gear"></i></span>
                        <span class="text">Account Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</aside>    

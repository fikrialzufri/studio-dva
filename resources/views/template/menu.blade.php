<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('home') }}">
            <div class="logo-img">
                <img height="30" src="{{ asset('img/fav.png') }}" class="header-brand-img"
                    title="STUDIO SENAM DVA NLY">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ $segment1 == '' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="ik ik-bar-chart-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
                @canany(['view-karyawan', 'view-jabatan', 'view-divisi', 'view-wilayah', 'view-departemen'])
                    <div class="nav-lavel">{{ __('Karyawan') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'jabatan' || $segment1 == 'karyawan' || $segment1 == 'divisi' || $segment1 == 'wilayah' || $segment1 == 'departemen' || $segment1 == 'divisi' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="fa fa-users dropdown-icon"></i><span>{{ __('Karyawan') }}</span></a>
                        <div class="submenu-content">

                            @can('view-karyawan')
                                <a href="{{ route('karyawan.index') }}"
                                    class="menu-item {{ $segment1 == 'karyawan' ? 'active' : '' }} ? 'active' : '' }}">
                                    Karyawan
                                </a>
                            @endcan
                            @can('view-jabatan')
                                <a href="{{ route('jabatan.index') }}"
                                    class="menu-item {{ $segment1 == 'jabatan' ? 'active' : '' }}">
                                    Jabatan
                                </a>
                            @endcan

                        </div>
                    </div>
                @endcan

                @can('view-anggota')
                    <div class="nav-lavel">{{ __('Anggota') }} </div>
                    <div class="nav-item  {{ $segment1 == 'anggota' ? 'active' : '' }}">
                        <a href="{{ route('anggota.index') }}">
                            <i class="fa fa-cog dropdown-icon"></i>
                            <span>Anggota</span>
                        </a>
                    </div>
                @endcan
                @can('view-pembayaran')
                    <div class="nav-item  {{ $segment1 == 'pembayaran' ? 'active' : '' }}">
                        <a href="{{ route('pembayaran.index') }}">
                            <i class="ik ik-shopping-cart dropdown-icon"></i>
                            <span>Pembayaran</span>
                        </a>
                    </div>
                @endcan
                @canany(['view-user', 'view-roles'])
                    <div class="nav-lavel">{{ __('User') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'user' || $segment1 == 'role' || $segment1 == 'task' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user dropdown-icon"></i><span>{{ __('Pengguna') }}</span></a>
                        <div class="submenu-content">
                            @can('view-user')
                                <a href="{{ route('user.index') }}"
                                    class="menu-item {{ $segment1 == 'user' ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            @endcan
                            @can('view-roles')
                                <a href="{{ route('role.index') }}"
                                    class="menu-item {{ $segment1 == 'role' ? 'active' : '' }}">
                                    Hak Akses
                                </a>
                            @endcan
                            @role('superadmin')
                                <a href="{{ route('task.index') }}"
                                    class="menu-item {{ $segment1 == 'task' ? 'active' : '' }}">
                                    Task
                                </a>
                            @endrole
                        </div>
                    </div>
                @endcan
            </nav>
        </div>
    </div>
</div>

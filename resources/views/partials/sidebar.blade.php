<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('parking') || Request::is('parking/checkout') ? '' : 'collapsed' }}"
                data-bs-target="#parkir-menu" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Parkir</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="parkir-menu"
                class="nav-content collapse {{ Request::is('parking') || Request::is('parking/checkout') ? 'show' : '' }} "
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="javascript:void(0)" id="get_parking" class="{{ Request::is('parking') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" id="checkout"
                        class="{{ Request::is('parking/checkout') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Keluar</span>
                    </a>
                </li>
            </ul>
        </li><!-- Parkir -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-grid"></i>
                <span>Laporan</span>
            </a>
        </li><!-- Laporan -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('category') ? '' : 'collapsed' }} " data-bs-target="#components-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse {{ Request::is('category') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="javascript:void(0)" id="get_category"
                        class="{{ Request::is('category') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Kategori</span>
                    </a>
                </li>
            </ul>
        </li><!-- Master Data -->
    </ul>

</aside>

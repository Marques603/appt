<aside class="sidebar">
    <!-- Sidebar Header Starts -->
    <a href="{{ route('home') }}">
        <div class="sidebar-header">
            <div class="sidebar-logo-icon">
                <x-application-logo class="w-20 h-15 fill-current text-gray-500" />
            </div>

            <div class="sidebar-logo-text">
                <h1 class="flex text-xl">
                    
                    <span class="whitespace-nowrap text-base  dark:text-slate-200"> INUSITTÁ </span>
                    
                </h1>

                <p class="whitespace-nowrap text-xs text-slate-400"> Ambientes Planejados Ltda</p>
            </div>
        </div>
    </a>
    <!-- Sidebar Header Ends -->

    <!-- Sidebar Menu Starts -->
    <ul class="sidebar-content">
        <!-- Dashboard -->

        <li>
            <a href="javascript:void(0);"
                class="sidebar-menu  {{ request()->routeIs('home', '#') ? 'active' : '' }}">
                <span class="sidebar-menu-icon">
                    <i data-feather="home"></i>
                </span>
                <span class="sidebar-menu-text">Home</span>
                <span class="sidebar-menu-arrow">
                    <i data-feather="chevron-right"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('home') ? 'active' : '' }}">Dashboard</a>
                </li>
               <!--  <li>
                    <a href="#  "
                        class="sidebar-submenu-item {{ request()->routeIs('#') ? 'active' : '' }}">Ecommerce</a>
                </li>-->
            </ul>
        </li>
        <div class="sidebar-menu-header">Aplicação</div>

        <!-- Menu Tecnologia -->
        
        <li>
        @can('view', App\Models\Menu::find(1)) 
            <a href="javascript:void(0);"
                class="sidebar-menu {{ request()->routeIs(['users.index', 'company.index','menus.index','sector.index','menus.index']) ? 'active' : '' }}">

                <span class="sidebar-menu-icon">
                    <i data-feather="cpu"></i>
                </span>
                <span class="sidebar-menu-text">Gestão Tecnologia</span>
                <span class="sidebar-menu-arrow">
                    <i data-feather="chevron-right"></i>
                </span>
            </a>
            <ul class="sidebar-submenu ">
                <li>
                    <a href="{{ route('users.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        Usuários</a>            
                </li>
                <li>
                    <a href="{{ route('sector.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('sector.index') ? 'active' : '' }}">
                        Setores</a>
                </li>
                <li>
                    <a href="{{ route('company.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('company.index') ? 'active' : '' }}">
                        Empresa</a>
                </li>
            </ul>
            @endcan
        </li>
               <!-- Menu Qualidade -->
        <li>
        @can('view', App\Models\Menu::find(2)) 
            <a href="javascript:void(0);"
                class="sidebar-menu {{ request()->routeIs(['documents.index', 'macro.index'])
                    ? 'active'
                    : '' }}">
                <span class="sidebar-menu-icon">
                    <i data-feather="archive"></i>
                </span>
                <span class="sidebar-menu-text">Gestão Documentos</span>
                <span class="sidebar-menu-arrow">
                    <i data-feather="chevron-right"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('documents.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                        Documentos </a>
                </li>
                <li>
                    <a href="{{ route('macro.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('macro.index') ? 'active' : '' }}">
                        Macros</a>
                </li>
                <li>
                    <a href="{{ route('macro.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('macro.index') ? 'active' : '' }}">
                        RNC</a>
                </li>
            </ul>
            @endcan
        </li>
        <!-- Recursos -->
                 <li>
            @can('view', App\Models\Menu::find(3))    
            <a href="javascript:void(0);"
                class="sidebar-menu {{ request()->routeIs(['cost_center.index','position.index']) ? 'active' : '' }}">

                <span class="sidebar-menu-icon">
                    <i data-feather="heart"></i>
                </span>
                <span class="sidebar-menu-text">Gestão HCM</span>
                <span class="sidebar-menu-arrow">
                    <i data-feather="chevron-right"></i>
                </span>
            </a>
            <ul class="sidebar-submenu ">
                <li>
                    <a href="{{ route('cost_center.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('cost_center.index') ? 'active' : '' }}">
                        Centro de Custo</a>
                </li>
                <li>
                    <a href="{{ route('position.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('position.index') ? 'active' : '' }}">
                        Cargos</a>
                </li>
           </ul>
              @endcan
        </li>
        <!-- Engenharia -->
                 <li>
            @can('view', App\Models\Menu::find(4))    
            <a href="javascript:void(0);"
                class="sidebar-menu {{ request()->routeIs(['folder.index','archives.index']) ? 'active' : '' }}">

                <span class="sidebar-menu-icon">
                    <i data-feather="folder"></i>
                </span>
                <span class="sidebar-menu-text">Gestão Engenharia</span>
                <span class="sidebar-menu-arrow">
                    <i data-feather="chevron-right"></i>
                </span>
            </a>
            <ul class="sidebar-submenu ">
                <li>
                    <a href="{{ route('folders.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('folder.index') ? 'active' : '' }}">
                        Pasta</a>
                </li>
                <li>
                    <a href="{{ route('archives.index') }}"
                        class="sidebar-submenu-item {{ request()->routeIs('archives.index') ? 'active' : '' }}">
                        Arquivos</a>
                </li>
           </ul>
              @endcan
        </li>


</aside>

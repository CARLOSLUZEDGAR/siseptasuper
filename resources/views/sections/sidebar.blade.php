 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="/img/fabprueba.png" class="brand-image">
        <span class="brand-text font-weight-light">SISEPTA - EPTA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-1 pb-1 mb-1 d-flex">
            <div class="info" style="max-width: 180px;">
                <router-link
                    id="nombre"
                    class="nav-link d-block"
                    to="/passwordChange"
                    style="white-space: normal; word-break: break-word;">
                </router-link>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                @can('side-carnetizacion', Model::class)
                  <li class="nav-header" style="padding-left: 1px;">DIPLOMADO</li>
                  <li class="nav-item">{{-- CARNETIZACION --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-id-card"></i>
                      <p>
                        DIPLOMADOS
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-gestion', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/Gestion'>
                          <i class="fas fa-user nav-icon"></i>
                          <p>Gestión</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-diplomados', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/Diplomados'>
                          <i class="fas fa-user nav-icon"></i>
                          <p>Diplomados</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-modulos', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/Modulos'>
                          <i class="fas fa-user nav-icon"></i>
                          <p>Módulos</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-materias', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/Materias'>
                          <i class="fas fa-user nav-icon"></i>
                          <p>Materias</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                  </li>  
                @endcan

                @can('side-carnetizacion', Model::class)
                  <li class="nav-header" style="padding-left: 1px;">PERSONAL</li>
                  <li class="nav-item">{{-- CARNETIZACION --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-id-card"></i>
                      <p>
                        CURSANTES
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-dat-carnet', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/DatosCursantes'>
                          <i class="fas fa-user nav-icon"></i>
                          <p>Registro</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                  </li>  
                @endcan

                @can('side-notas', Model::class)
                  <li class="nav-header" style="padding-left: 1px;">ACADEMICO</li>
                  <li class="nav-item">{{-- ACADEMICO --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-id-card"></i>
                      <p>
                        NOTAS
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-notas', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/Notas'>
                          <i class="fas fa-user nav-icon"></i>
                          <p>Registro</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                  </li> 
                @endcan
                                                                                                                                                              
                @can('side-administracion', Model::class)
                <li class="nav-header" style="padding-left: 1px;">ADMINISTRACIÓN</li>
                <li class="nav-item">{{-- ACCESO DEL SISTEMA --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                        ACCESO DEL SISTEMA
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-user', Model::class)
                       <li class="nav-item">
                        <router-link class="nav-link" to='/usuarios'>
                          <i class="nav-icon fas fa-users-cog"></i>
                          <p>Usuarios</p>
                        </router-link>
                      </li>
                      @endcan
                      @can('view-rol', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/roles'>
                          <i class="nav-icon fas fa-user-clock"></i>
                          <p>Roles</p>
                        </router-link>
                      </li>
                      @endcan
                      @can('view-permi', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/permisos'>
                          <i class="nav-icon fas fa-user-edit"></i>
                          <p>Permisos</p>
                        </router-link>
                      </li>
                      @endcan

                    </ul>
                </li>
                @endcan


                <li class="nav-item">{{-- AYUDAS --}}
                    <router-link class="nav-link" to='/ayuda'>
                      <i class="nav-icon fas fa-info-circle"></i>
                      <p>AYUDAS</p>
                    </router-link>
                </li>

                <li class="nav-item">{{-- ACERCA DE --}}
                    <router-link class="nav-link" to='/acercade'>
                      <i class="nav-icon fas fa-boxes"></i>
                      <p>ACERCA DE</p>
                    </router-link>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->

</aside>

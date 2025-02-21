<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Activofijo {{ config('app.marcaname', 'app') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="apple-touch-icon" href="{{asset('img/apple-touch-icon.png')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/alertify/alertify.css')}}">
    <link rel="stylesheet" href="{{asset('css/alertify/themes/default.min.css')}}"/>
    <script src="{{asset('js/jquery-3.7.1.js')}}"></script>
    @vite(['resources/js/app.js'])
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <a href="#" data-toggle="offcanvas" class="logo">
          <span class="logo-mini">
            <b>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </b>
          </span>
          <span class="logo-lg"><b>{{ config('app.marcaname', 'app') }}</b></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <small class="">bienvenido {{auth()->user()->n_empleado}}</small>
                  <span class="hidden-xs"></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
                    {{-- <form method="get" action="{{route('pass')}}">
                        {{csrf_field() }}
                        <button class="btn btn-success btn-xs btn-block">Cambiar Contraseña</button>
                    </form>
                    <br> --}}
                    <form method="POST" action="{{route('logout')}}">
                        {{csrf_field() }}
                        <button class="btn btn-danger btn-xs btn-block">Cerrar Sesión</button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
            <li class="header"></li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Categorias</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/admin/inventario/Tipo"><i class="fa fa-circle-o"></i> Tipos</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-automobile"></i>
                <span>Equipo de Transporte</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/admin/inventario/EquipoTransporte"><i class="fa fa-circle-o"></i> Equipos de transporte</a></li>
                <li><a href="/admin/inventario/EquipoTransporte/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                <li><a href="/admin/inventario/EquipoTransporte/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                <li><a href="/admin/inventario/CargarArchivoMasivaEQT"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Equipo de Computo</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/admin/inventario/EquipoComputo"><i class="fa fa-circle-o"></i>Equipos de computo</a></li>
                @if(auth()->user()->role_id < 3 || auth()->user()->role_comp ==2)
                  <li><a href="/admin/inventario/EquipoComputo/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                  <li><a href="/admin/inventario/EquipoComputo/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                  <li><a href="/admin/inventario/CargarArchivoMasivaEQC"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                @endif
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-folder-open"></i>
                <span>Mobiliario y <br> Equipo de Oficina</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/admin/inventario/MobiliarioEquipo"><i class="fa fa-circle-o"></i> Mobiliario y Equipo de oficina</a></li>
                <li><a href="/admin/inventario/MobiliarioEquipo/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                <li><a href="/admin/inventario/MobiliarioEquipo/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                <li><a href="/admin/inventario/CargarArchivoMasivaMOE"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                @if(auth()->user()->role_id < 3 || auth()->user()->role_mob==2)
                @endif
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="  fa fa-cogs"></i>
                <span>Maquinaria y Equipo</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/admin/inventario/MaquinariaEquipo"><i class="fa fa-circle-o"></i> Maquinaria  y Equipo</a></li>
                <li><a href="/admin/inventario/MaquinariaEquipo/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                <li><a href="/admin/inventario/MaquinariaEquipo/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                <li><a href="/admin/inventario/CargarArchivoMasivaMAE"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                <li><a href="{{route('mante',4)}}"><i class="fa fa-circle-o"></i> Mantenimientos</a></li>
                @if(auth()->user()->role_id < 3 || auth()->user()->role_maq ==2)
                @endif
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-wrench"></i>
                <span>Herramientas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="/admin/inventario/Herramientas"><i class="fa fa-circle-o"></i> Herramientas</a></li>
                @if(auth()->user()->role_id < 3 || auth()->user()->role_her == 2)
                  <li><a href="/admin/inventario/Herramientas/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                  <li><a href="/admin/inventario/Herramientas/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                  <li><a href="/admin/inventario/CargarArchivoMasivaHER"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                  <li><a href="{{route('mante',5)}}"><i class="fa fa-circle-o"></i> Mantenimientos</a></li>
                @endif
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-sliders"></i>
                <span>Configuración</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                @if(auth()->user()->role_id < 3 || auth()->user()->role_her == 2)
                  {{-- <li><a href="{{route('mante')}}"><i class="fa fa-circle-o"></i> Usuarios</a></li> --}}
                  <li><a href="/admin/inventario/Herramientas/Responsivas"><i class="fa fa-circle-o"></i> Roles</a></li>
                @endif
              </ul>
            </li>
            {{-- @if(auth()->user()->role_id ==1 )
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-laptop"></i>
                  <span>Categorias</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/admin/inventario/Tipo"><i class="fa fa-circle-o"></i> Tipos</a></li>
                </ul>
              </li>
            @endif
            @if(auth()->user()->role_id ==1 || auth()->user()->role_trans > 0)
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-automobile"></i>
                  <span>Equipo de Transporte</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/admin/inventario/EquipoTransporte"><i class="fa fa-circle-o"></i> Equipos de transporte</a></li>
                  @if(auth()->user()->role_id < 3 || auth()->user()->role_trans == 2)
                    <li><a href="/admin/inventario/EquipoTransporte/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                    <li><a href="/admin/inventario/EquipoTransporte/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                    <li><a href="/admin/inventario/CargarArchivoMasivaEQT"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                  @endif
                  
                </ul>
              </li>
            @endif
            @if(auth()->user()->role_id ==1 || auth()->user()->role_comp > 0)
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-laptop"></i>
                  <span>Equipo de Computo</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/admin/inventario/EquipoComputo"><i class="fa fa-circle-o"></i>Equipos de computo</a></li>
                  @if(auth()->user()->role_id < 3 || auth()->user()->role_comp ==2)
                    <li><a href="/admin/inventario/EquipoComputo/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                    <li><a href="/admin/inventario/EquipoComputo/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                    <li><a href="/admin/inventario/CargarArchivoMasivaEQC"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                  @endif
                </ul>
              </li>
            @endif
            @if(auth()->user()->role_id ==1 || auth()->user()->role_mob > 0)
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-folder-open"></i>
                  <span>Mobiliario y <br> Equipo de Oficina</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/admin/inventario/MobiliarioEquipo"><i class="fa fa-circle-o"></i> Mobiliario y Equipo de oficina</a></li>
                  @if(auth()->user()->role_id < 3 || auth()->user()->role_mob==2)
                    <li><a href="/admin/inventario/MobiliarioEquipo/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                    <li><a href="/admin/inventario/MobiliarioEquipo/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                    <li><a href="/admin/inventario/CargarArchivoMasivaMOE"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                  @endif
                </ul>
              </li>
            @endif
            @if(auth()->user()->role_id ==1 || auth()->user()->role_maq > 0)
              <li class="treeview">
                <a href="#">
                  <i class="  fa fa-cogs"></i>
                  <span>Maquinaria y Equipo</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/admin/inventario/MaquinariaEquipo"><i class="fa fa-circle-o"></i> Maquinaria  y Equipo</a></li>
                  @if(auth()->user()->role_id < 3 || auth()->user()->role_maq ==2)
                    <li><a href="/admin/inventario/MaquinariaEquipo/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                    <li><a href="/admin/inventario/MaquinariaEquipo/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                    <li><a href="/admin/inventario/CargarArchivoMasivaMAE"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                  @endif
                </ul>
              </li>
            @endif
            @if(auth()->user()->role_id ==1 || auth()->user()->role_her > 0)
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-wrench"></i>
                  <span>Herramientas</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/admin/inventario/Herramientas"><i class="fa fa-circle-o"></i> Herramientas</a></li>
                  @if(auth()->user()->role_id < 3 || auth()->user()->role_her == 2)
                    <li><a href="/admin/inventario/Herramientas/Reporte"><i class="fa fa-circle-o"></i> reportes</a></li>
                    <li><a href="/admin/inventario/Herramientas/Responsivas"><i class="fa fa-circle-o"></i> responsiva</a></li>
                    <li><a href="/admin/inventario/CargarArchivoMasivaHER"><i class="fa fa-files-o"></i> Carga Masiva</a></li>
                  @endif
                </ul>
              </li>
            @endif --}}
            <!--<li>
              <a href="http://192.168.2.7:8012/" target="_blank">
                <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                <small class="label pull-right bg-red">!</small>
              </a>
            </li> -->      
          </ul>
        </section>
      </aside>
       <!--Contenido-->
      <div class="content-wrapper">
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Sistema de Inventarios</h3>
                  <div class="box-tools pull-right">
                  </div>
                </div>
                <div class="box-body">
                  <div class="row">
	                  <div class="col-md-12">
                      <!--Contenido-->
                      @yield('contenido')
                      <!--Fin Contenido-->
                    </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Versión</b> 2.0
        </div>
        <strong>Grupo PRT &copy; {{date('Y')}} <a href="#">Grupo PRT</a>.</strong> All rights reserved.
      </footer>
    <!-- jQuery 2.1.4 -->
    {{-- <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script> --}}
    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/app.min.js')}}"></script>
    <script src="{{asset('js/funciones.js')}}"></script>
    <script src="{{asset('js/alertify.js')}}"></script>
  </body>
</html>
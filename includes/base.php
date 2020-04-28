<?php

/**
 * @author Saupurein Marcos
 * @copyright 2018
 */
date_default_timezone_set('America/Mexico_City');


$serviciosNoti = new ServiciosNotificaciones();

class BaseHTML extends ServiciosNotificaciones {

    private $navBar = '';
    private $sideBar = '';
    private $controlSideBar = '';
    private $jsAdminLTE = '';
    private $cssAdminLTE = '';
    private $footer = '';
    private $contentHeader = '';

    public function __construct(){    
        $usuario = new Usuario();  
        $usuario-> setUsuarioData();
        $usuario-> setUsuarioData(); 
        $this->cargarArchivosCssAdminLTE();        
        $this->cargaMainHeader($usuario);
        $this->cargaMainSideBar($usuario);
        $this->cargaControlSideBar($usuario);
        $this->cargaFooter();
        $this->cargarArchivosJavaScriptAdminLTE();        

    }

    public function getNavBar (){
        return $this->navBar;
    }

    public function getSideBar (){
        return $this->sideBar;
    }

    public function getControlSideBar (){
        return $this->controlSideBar;
    }

     public function getFooter (){
        return $this->footer;
    }

    public function getJsAdminLTE (){
        return $this->jsAdminLTE;
    }

    public function getCssAdminLTE (){
        return $this->cssAdminLTE;
    }

    public function getContentHeader (){
        return $this->contentHeader;
    }

    public function setContentHeader ($h1, $breadcrumb_items){
        $this->contentHeader($h1, $breadcrumb_items);
    }    

    function cargarArchivosCSS($altura,$ar = array()) {

        $arNuevo = array(0=>'<link href="'.$altura.'plugins/bootstrap/css/bootstrap.css" rel="stylesheet">',
                         1=>'<link href="'.$altura.'plugins/node-waves/waves.css" rel="stylesheet" />',
                         2=>'<link href="'.$altura.'plugins/animate-css/animate.css" rel="stylesheet" />',
                         3=>'<link href="'.$altura.'css/style.css" rel="stylesheet">',
                         4=>'<link href="'.$altura.'css/themes/all-themes.css" rel="stylesheet" />',
                         5=>'<link href="'.$altura.'plugins/sweetalert/sweetalert.css" rel="stylesheet" />',
                         6=>'<link href="'.$altura.'plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />',
                         7=>'<link href="'.$altura.'css/ventanaModal.css" rel="stylesheet" />');

        $cad = '';

        foreach($arNuevo as $var) {
            $cad .= $var.'<br>';
        }

        foreach($ar as $var) {
            $cad .= $var.'<br>';
        }

        echo $cad;
    }


    function cargarArchivosJS($altura ,$ar = array()) {

        $arNuevo = array(0=>'<script src="'.$altura.'plugins/jquery/jquery.min.js"></script>',
                         1=>'<script src="'.$altura.'plugins/bootstrap/js/bootstrap.js"></script>',
                         2=>'<script src="'.$altura.'plugins/bootstrap-select/js/bootstrap-select.js"></script>',
                         3=>'<script src="'.$altura.'plugins/jquery-slimscroll/jquery.slimscroll.js"></script>',
                         4=>'<script src="'.$altura.'plugins/node-waves/waves.js"></script>',
                         5=>'<script src="'.$altura.'js/admin.js"></script>',
                         6=>'<script src="'.$altura.'js/demo.js"></script>',
                         7=>'<script src="'.$altura.'plugins/bootstrap-notify/bootstrap-notify.js"></script>',
                         8=>'<script src="'.$altura.'js/pages/ui/notifications.js"></script>',
                         9=>'<script src="'.$altura.'plugins/jquery-validation/jquery.validate.js"></script>',
                         10=>'<script src="'.$altura.'plugins/jquery-steps/jquery.steps.js"></script>',
                         11=>'<script src="'.$altura.'plugins/sweetalert/sweetalert.min.js"></script>',
                         12=>'<script src="'.$altura.'js/pages/forms/form-validation.js"></script>',
                         13=>'<script src="'.$altura.'js/jquery.number.js"></script>');

        $cad = '';

        foreach($arNuevo as $var) {
            $cad .= $var.'<br>';
        }

        foreach($ar as $var) {
            $cad .= $var.'<br>';
        }

        echo $cad;
    }

    function cargarArchivosJS2($altura ,$ar = array()) {

        $arNuevo = array(1=>'<script src="'.$altura.'plugins/bootstrap/js/bootstrap.js"></script>',
                         2=>'<script src="'.$altura.'plugins/bootstrap-select/js/bootstrap-select.js"></script>',
                         3=>'<script src="'.$altura.'plugins/jquery-slimscroll/jquery.slimscroll.js"></script>',
                         4=>'<script src="'.$altura.'plugins/node-waves/waves.js"></script>',
                         5=>'<script src="'.$altura.'js/admin.js"></script>',
                         6=>'<script src="'.$altura.'js/demo.js"></script>',
                         7=>'<script src="'.$altura.'plugins/bootstrap-notify/bootstrap-notify.js"></script>',
                         8=>'<script src="'.$altura.'js/pages/ui/notifications.js"></script>',
                         9=>'<script src="'.$altura.'plugins/jquery-validation/jquery.validate.js"></script>',
                         10=>'<script src="'.$altura.'plugins/jquery-steps/jquery.steps.js"></script>',
                         11=>'<script src="'.$altura.'plugins/sweetalert/sweetalert.min.js"></script>',
                         12=>'<script src="'.$altura.'js/pages/forms/form-validation.js"></script>',
                         13=>'<script src="'.$altura.'js/jquery.number.js"></script>');

        $cad = '';

        foreach($arNuevo as $var) {
            $cad .= $var.'<br>';
        }

        foreach($ar as $var) {
            $cad .= $var.'<br>';
        }

        echo $cad;
    }

    function cargarNotificaciones($datos = null, $altura = '') {

      if ($_SESSION['idroll_sahilices'] == 4) {
         $datos = $this->traerNotificacionesPorUsuarios('rlinares@asesorescrea.com');
      } else {
         $datos = $this->traerNotificacionesPorUsuarios($_SESSION['usua_sahilices']);
      }
        $cad = '<ul class="menu lstNotificaciones">';

        while ($row = mysql_fetch_array($datos)) {
            $cad .= '<li>
                            <a class="itemNotificacion" href="javascript:void(0);" data-url="'.$altura.$row['url'].'" id="'.$row['idnotificacion'].'" data-altura="'.$altura.'">
                            <div class="icon-circle '.$row['estilo'].'">
                                <i class="material-icons">'.$row['icono'].'</i>
                            </div>
                            <div class="menu-info">
                                <h4>'.$row['mensaje'].'</h4>
                                <p>
                                    <i class="material-icons">access_time</i> '.$row['fecha'].'
                                </p>
                            </div>
                        </a>
                    </li>';
        }

        $cad .= '</ul>';
        //die(var_dump($cad));
        return $cad;
    }


   function cargarTareas($datos = null, $altura = '') {

      $cad = '<ul class="menu tasks">';

      while ($row = mysql_fetch_array($datos)) {
         $cad .= '<li>
            <a href="javascript:void(0);">
               <h4>
                  '.$row['titulo'].'
                  <small>'.$row['pocentaje'].'%</small>
               </h4>
               <div class="progress">
                  <div class="progress-bar '.$row['color'].'" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: '.$row['porcentaje'].'%">
                  </div>
               </div>
            </a>
         </li>';
      }

      $cad .= '</ul>';

      echo $cad;
   }

   function root_path(){
      $this_directory = dirname(__FILE__);
      $archivos = scandir($this_directory);
      $atras = "";
      $cuenta = 0;
      while (true) {
         foreach($archivos as $actual) {
            if ($actual == "root.path") {
               if ($cuenta == 0)
               return "./";
               return $atras;
            }
         }
         $cuenta++;
         $atras = $atras . "../";
         $archivos = scandir($atras);
      }
   }

private function contentHeader($h1, $breadcrumb_items){
  $breadcrumbItems = explode('/', $breadcrumb_items);
  $numeroItems = count($breadcrumbItems);
  $cadenaBreadCrumb = '';
  $n=1;
  foreach ($breadcrumbItems as $item) {    
    $active = ($n==$numeroItems)?'active':'';
    if($n==$numeroItems){
      $cadenaBreadCrumb .= '<li class="breadcrumb-item active">'.$item.'</li>';
    }else{
      $cadenaBreadCrumb .= '<li class="breadcrumb-item"><a href="#">'.$item.'</a></li>';
    }
    $n++;
  }

  $contentHeader = '
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="">'.$h1.'</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">'.
            $cadenaBreadCrumb.'              
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>';

    $this->contentHeader = $contentHeader;
}   

 function cargarArchivosJavaScriptAdminLTE( ) {
    /*<!-- jQuery -->
    <!-- Bootstrap 4 -->
    <!-- AdminLTE App -->*/
    $arNuevo = array(1=>'<script src="'.DIR_LOCAL.'AdminLTE/plugins/jquery/jquery.min.js"></script>',
                    2=>'<script src="'.DIR_LOCAL.'AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>',
                    3=>'<script src="'.DIR_LOCAL.'AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>',
                    4=>'<script src="'.DIR_LOCAL.'AdminLTE/dist/js/adminlte.min.js"></script>',
                    5=>'<script src="'.DIR_LOCAL.'plugins/sweetalert/sweetalert.min.js"></script>',
                    );

    

    $cad = '';
    foreach($arNuevo as $var) {
        $cad .= $var.'<br>';
    }
    
     $this->jsAdminLTE = $cad;
}   

private function cargarArchivosCssAdminLTE( ) {
    /*<!-- Font Awesome -->  
    <!-- Ionicons -->  
    <!-- Theme style --> 
    <!-- Google Font: Source Sans Pro -->*/
    $arNuevo = array(0=>'<link href="'.DIR_LOCAL.'AdminLTE/plugins/fontawesome-free/css/all.min.css" rel="stylesheet">',
                    1=>'<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />',
                    2=>'<link href="'.DIR_LOCAL.'AdminLTE/dist/css/adminlte.min.css" rel="stylesheet" />',
                    3=>'<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">',
                    4=>'<link href="'.DIR_LOCAL.'plugins/sweetalert/sweetalert.css" rel="stylesheet" />');

   
    

    $cad = '';
    foreach($arNuevo as $var) {
        $cad .= $var.'';
    }

    

    $this->cssAdminLTE =  $cad;
}   

private function cargaFooter(){
    $footer = '
    <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2020 <a href="http://financieracrea.com">Financiera CREA</a>.</strong> Todos los derechos reservados.
  </footer>';
  $this->footer = $footer;
} 

private function menuRed(){  
  
 
  $usuario = new Usuario();
  $usuario-> setUsuarioData();
  $rol = $usuario->getRol(); 
    $query = new Query();
  $sql = "select idmenu,url,icono, nombre, permiso from menu where permiso like '%".$usuario->getRol()."%' and grupo = 0 order by orden";
 
  $res = $this->queries($sql,0);
  
  
 

  $query->setQuery($sql);
  $res = $query->eject();
    #while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $cadmenu = "";
  $cadhover= "";
  $cant = 1;
  $titulo = '';
  $cadmenud = '';  

   while ($row = mysql_fetch_array($res,MYSQL_ASSOC)) {

    if ($titulo == $row['nombre']) {
      $nombre = $row['nombre'];
      $row['url'] = "index.php";
    }

    #echo  $row['nombre']."=>".$row['url']."\n";

    if (strpos($row['permiso'],$rol) !== false) {
      
      $cadmenud .= '<li>
        <a href="'.$row['url'].'">
          <i class="material-icons">'.$row['icono'].'</i>
            <span>'.$row['nombre'].'</span>
        </a>
      </li>';

      $cadmenu .='<li class="nav-item">
                <a href="'.DIR_LOCAL.$row['url'].'" class="nav-link">
                  <i class="nav-icon fas '.$row['icono'].'"></i>
                    <p>'.$row['nombre'].'</p>
                </a>
                </li>';
      $cant+=1;
    }
  }


  $sql = "select idmenu,url,icono, nombre, permiso from menu where permiso like '%".$rol."%' and grupo = 3 order by orden"; 
  $query->setQuery($sql);
  $res = $query->eject();

  if ( $query->numRows($res) > 0) {
    $cadmenu .= '<a href="javascript:void(0);" class="menu-toggle">
                              <i class="material-icons">build</i>
                              <span>General</span>
                          </a>
                          <ul class="ml-menu">';
    $cadhover= "";


    $cant = 1;
    while($row = $query->fetchArray($res)){
      if ($titulo == $row['nombre']) {
        $nombre = $row['nombre'];
        $row['url'] = "index.php";
      }

      if (strpos($row['permiso'],$rol) !== false) {     
        $cadmenu .= '<li>
        <a href="'.$row['url'].'">
          <i class="material-icons">'.$row['icono'].'</i>
            <span>'.$row['nombre'].'</span>
        </a>
        </li>';     
      }
      $cant+=1;
    }
    $cadmenu .= '</ul>';
  }
  /*location_on*/


  $menu = utf8_encode($cadmenu);
  return $menu;
}   

private function cargaControlSideBar($usuario)  {
    
    $sideBarRigth ='
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-light">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <div class="card card-info">
                <a href="#" class="d-block tex">'.$usuario->getNombre().'</a>
            </div>
            <div class="info">
                <a href="#" class="d-block tex">'.$usuario->getEmail().'</a>
            </div>
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>';
     $this->controlSideBar = $sideBarRigth;

} 



private function cargaMainSideBar($usuario){   
    $mainSideBar ='
    <!-- Main Sidebar Container --> 
    <aside class="main-sidebar elevation-4 sidebar-light-danger">
        <!-- Brand Logo -->
        <a href="../../index3.html" class="brand-link navbar-danger">
            <img src="'.DIR_LOCAL.'AdminLTE/dist/img/arbol3.png" alt="Financiera CREA Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text  font-weight-light" style="color: rgba(255,255,255,.9);">Financiera CREA</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image pt-2">
                    <img src="'.DIR_LOCAL.'AdminLTE/dist/img/userRed4.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">'.ucwords($usuario->getNombre()).'</a>
                    <spam class="a"><small>'.$usuario->getEmail().'</small></spam>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon . clas with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Menu
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                        </a>
                        <ul class="nav nav-treeview">
                          '.$this-> menuRed().'
                        </ul>
                </li>
                 '.$this-> menuRed().'

                <li class="nav-item">
                    <a href="../widgets.html" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Widgets
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
                
                    
                       
              
               
                            
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>';
      $this->sideBar = $mainSideBar;

}   

private function cargaMainHeader($usuario){
    $totalNotificaciones = 0;
    $notificacionesNoLeidas =0;

    $barraNavegacion = '
   <!-- Navbar -->
   <nav class="main-header navbar navbar-expand navbar-danger navbar-light ">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a  href="'.DIR_LOCAL.'dashboard" target="" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="'.DIR_LOCAL.'dashboard/contacto.php" class="nav-link">Contacto</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">      
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">5</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">'.$totalNotificaciones.' Notificaciones</span>
                    <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> '.$notificacionesNoLeidas.' Nuevas notificacíones
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>         
                        <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
                </div>
            </li>


            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div  class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="'.DIR_LOCAL.'AdminLTE/dist/img/userGray1.png" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    '.ucwords($usuario->getNombre()).'
                                    <span class="float-right text-sm text-success"><i class="fas fa-circle"></i></span>
                                </h3>
                                <p class="text-sm">'.$usuario->getEmpresa().'</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </div>          
                
                    <div class="dropdown-divider"></div>
                    <a href="'.DIR_LOCAL.'logout.php" class="dropdown-item dropdown-footer text-primary">Cerrar sesión</a>
                </div>
            </li>       
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>

      
        </ul>
    </nav>
    <!-- /.navbar -->';

    $this->navBar =  $barraNavegacion;
}


    function cargarNAV($breadCumbs, $notificaciones='', $tareas='', $altura = '', $lstTareas='') {
      if ($notificaciones == '') {
         #$notificaciones = $this->cargarNotificaciones('',($altura == '..' ? '' : '../'));
         #$cantidadNotificacionesNoLeidas = $this->traerNotificacionesNoLeidaPorUsuarios('rlinares@asesorescrea.com');
      }
        $cad = '<nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                            <a href="javascript:void(0);" class="bars"></a>
                            '.$breadCumbs.'
                        </div>
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <!-- Call Search -->
                                <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                                <!-- #END# Call Search -->
                                <!-- Notifications -->
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                        <i class="material-icons">notifications</i>
                                        <span class="label-count notificaciones-cantidad">'.$cantidadNotificacionesNoLeidas.'</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="header">Notificaciones</li>
                                        <li class="body">
                                           '.$notificaciones.'
                                        </li>
                                        <li class="footer">
                                            <a href="javascript:void(0);">Ver Todas</a>
                                        </li>
                                    </ul>
                                </li>
                                <!-- #END# Notifications -->
                                <!-- Tasks -->
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                        <i class="material-icons">flag</i>
                                        <span class="label-count tareas-cantidad">0</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="header">Tareas</li>
                                        <li class="body">
                                            <ul class="menu tasks">

                                            </ul>
                                        </li>
                                        <li class="footer">
                                            <a href="javascript:void(0);">Ver Todas</a>
                                        </li>
                                    </ul>
                                </li>
                                <!-- #END# Tasks -->
                                <li class="pull-right"><a href="javascript:void(0);" class="maximizar"><i class="material-icons icomarcos">aspect_ratio</i></a></li>

                            </ul>
                        </div>
                    </div>
                </nav>';
        echo $cad;
    }

    function cargarSECTION($usuario, $email, $menu, $altura = '') {
        $cad = '<section id="marcos">
                <!-- Left Sidebar -->
                <aside id="leftsidebar" class="sidebar">
                    <!-- User Info -->
                    <div class="user-info">
                        <div class="image">
                            <img src="'.$altura.'images/user.png" width="48" height="48" alt="User" />
                        </div>
                        <div class="info-container">
                            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$usuario.'</div>
                            <div class="email">'.$email.'</div>
                            <div class="btn-group user-helper-dropdown">
                                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                                <ul class="dropdown-menu pull-right">

                                    <li><a href="'.$altura.'logout.php"><i class="material-icons">input</i>Salir</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- #User Info -->
                    <!-- Menu -->
                    <div class="menu">
                        <ul class="list">
                            <li class="header">MENU</li>
                            '.$menu.'
                        </ul>
                    </div>
                    <!-- #Menu -->

                </aside>
                <!-- #END# Left Sidebar -->
                <!-- Right Sidebar -->
                <aside id="rightsidebar" class="right-sidebar">
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                        <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
                    </ul>

                </aside>
                <!-- #END# Right Sidebar -->
            </section>';

        echo $cad;
    }

    function modalHTML($id,$titulo,$aceptar,$contenido,$form,$formulario='',$idTabla,$tabla,$accion) {
        $cad = '<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title" id="largeModalLabel">'.$titulo.'</h4>
                    </div>

                    <form id="'.$form.'" method="POST">
                    <div class="modal-body">
                        <p>'.$contenido.'</p>

                        '.$formulario.'

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-link waves-effect" id="btn'.$id.'">'.$aceptar.'</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
                    </div>
                    <input type="hidden" ref="ref_'.$idTabla.'" :value="active'.ucwords($tabla).'.'.$idTabla.'" name="'.$idTabla.'" />
                    <input type="hidden" value="'.$accion.'" name="accion" id="accion" />
                    </form>

                </div>
            </div>
        </div>';

        echo $cad;
    }

    function queries($sql,$accion) {



    require_once 'appconfig.php';

    $appconfig  = new appconfig();
    $datos    = $appconfig->conexion();
    $hostname = $datos['hostname'];
    $database = $datos['database'];
    $username = $datos['username'];
    $password = $datos['password'];

    $conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

    mysql_select_db($database);

            $error = 0;
    mysql_query("BEGIN");
    $result=mysql_query($sql,$conex);
    if ($accion && $result) {
      $result = mysql_insert_id();
    }
    if(!$result){
      $error=1;
    }
    if($error==1){
      mysql_query("ROLLBACK");
      return false;
    }
     else{
      mysql_query("COMMIT");
      return $result;
    }

  }



}

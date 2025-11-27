<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/styleHeader.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/vendor/assets/fontawesome/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo BASE_URL; ?>public/js/script_show_menu.js"></script>  
<style>
  .oculto {
    display: none;
  }
</style>
<header class="header">
		<div class="container logo-nav-container">
			<h3><a href="<?php echo BASE_URL; ?>asistente/dashboard" class="logo"><span class="fa fa-home"></span>KIBSIS: Tú Expediente Digital</a></h3>
			<!--star nav-->
			<nav class="navigation">
				<!--star menu-->
				<ul>
					<!--star menu desplegable-->
					<ul>

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_periodoEscolar')"><span class="fa fa-calendar">PERIODO ESCOLAR</span></a>						
						<div id="submenu_periodoEscolar" class="oculto">
						<ul class="loadPeriodo">
							<li><a href="<?php echo BASE_URL;?>asistente/list_periodoEscolar" class="list">Lista periodo escolar</a></li>
						</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_lista')"><span class="fa fa-user">ESTUDIANTE</span></a>
						<div id="submenu_lista" class="oculto">
						<ul class="SubEstudiant">
							<li><a href="<?php echo BASE_URL;?>asistente/list_estudiante" class="list">Lista de estudiantes</a></li>
						</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_lista_p')"><span class="fa fa-user">PROFESORES</span></a>
						<div id="submenu_lista_p" class="oculto">
							<ul class="SubProfe">
								<li><a href="<?php echo BASE_URL;?>asistente/list_profesor" class="list">Lista de profesores</a></li>
							</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->
					<div class="container_submenu_m">	
						<li><a href="#" id="materias" class="list" onclick="mostrarOcultar('submenu_lista_m')"><span class="fa fa-book" >MATERIAS</span></a>
						<div id="submenu_lista_m" class="oculto">
							<ul class="SubMateria">
								<li><a href="<?php echo BASE_URL;?>asistente/list_materias" class="list">Lista de materias</a></li>
							</ul>
						</div>
						</li>
					</div>
						<!--end submenu-->
						
					</ul>
					<!--end menu desplegable-->
					
				</ul>
				<!--end menu-->
			</nav><li class="bExit"><a href="<?php echo BASE_URL;?>login/logout" class="exit"><span class="fa fa-sign-in" ></span>Cerrar sessión</a></li>
			<!--end nav-->
		</div>
</header>



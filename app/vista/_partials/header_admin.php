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
			<h3><a href="<?php echo BASE_URL; ?>admin/dashboard" class="logo"><span class="fa fa-home"></span>KIBSIS: Tú Expediente Digital</a></h3>
			<!--star nav-->
			<nav class="navigation">
				<!--star menu-->
				<ul>
					
					<!--star menu desplegable-->
					<ul>

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_asistente')"><span class="fa fa-magic">ASISTENTES</span></a>

						<div id="submenu_asistente" class="oculto">
						<ul class="SubAsisten">
							<li><a href="<?php echo BASE_URL?>admin/add_asistent" class="list">Agregar asistente</a></li>
							
						</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_registro')"><span class="fa fa-user-plus" >ESTUDIANTE</span></a>
						<div id="submenu_registro" class="oculto">
						<ul class="SubEstudiant">
							<li><a href="<?php echo BASE_URL;?>admin/add_estudiante" class="list">Agregar Estudiante</a></li>
						</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->					
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_gestionAcademica')"><span class="fa fa-user-plus" >Profesor</span></a>
							<div id="submenu_gestionAcademica" class="oculto">
							<ul class="SubMateria">
								<li><a href="<?php echo BASE_URL;?>admin/add_profesor" class="list">Agregar Profesor</a></li>
								<li><a href="<?php echo BASE_URL;?>admin/allocation_profesorMateria" class="list">Asignaciones de Profesores</a></li>
							</ul>
							</div>
						</li>
						<!--end submenu-->


						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_periodoEscolar')"><span class="fa fa-calendar">PERIODO ESCOLAR</span></a>
						<div id="submenu_periodoEscolar" class="oculto">
						<ul class="loadPeriodo">
							<li><a href="<?php echo BASE_URL;?>admin/add_periodoEscolar" class="list">Cargar periodo escolar</a></li>
						</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_lista')"><span class="fa fa-search" >EXPLORAR</span></a>
							<div id="submenu_lista" class="oculto">
							<ul class="SubProfe">
							<li><a href="<?php echo BASE_URL?>admin/list_estudiante" class="list">Lista de estudiantes</a></li>
							<li><a href="<?php echo BASE_URL?>admin/list_profesor" class="list">Lista de profesores</a></li>
							<li><a href="<?php echo BASE_URL?>admin/list_periodoEscolar" class="list">Lista de periodos escolares</a></li>
							<li><a href="<?php echo BASE_URL; ?>admin/list_asistent" class="list">Lista de asistentes</a></li>
							</ul>
							</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->					
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_materia')"><span class="fa fa-book" >MATERIAS</span></a>
							<div id="submenu_materia" class="oculto">
							<ul class="SubMateria">
								<li><a href="<?php echo BASE_URL;?>admin/add_materias" class="list">Agregar materia</a></li>
								<li><a href="<?php echo BASE_URL;?>admin/list_materia" class="list">Lista de materias</a></li>
							</ul>
							</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->					
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_comunidades')"><span class="fa fa-book" >COMUNIDADES</span></a>
							<div id="submenu_comunidades" class="oculto">
							<ul class="SubMateria">
								<li><a href="<?php echo BASE_URL;?>admin/add_comunidad" class="list">Agregar comunidad</a></li>
								<li><a href="<?php echo BASE_URL;?>admin/list_comunidad" class="list">Lista de comunidades</a></li>
							</ul>
							</div>
						</li>
						<!--end submenu-->

					</ul>
					<!--end menu desplegable-->
					

				</ul>
				<!--end menu-->

			</nav>
			<!--end nav-->
		</div>
			<ul>
				<li class="bExit"><a href="<?php echo BASE_URL?>login/logout" class="exit"><span class="fa fa-sign-in" ></span>Cerrar sessión</a></li>
			</ul>
</header>



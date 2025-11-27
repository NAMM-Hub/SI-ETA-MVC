<link rel="stylesheet" href="<?php echo BASE_URL;?>public/css/styleHeaderAsistentes.css">
<link rel="stylesheet" href="<?php echo BASE_URL;?>public/vendor/assets/fontawesome/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo BASE_URL;?>public/js/script_show_menu.js"></script> 
<style>
  .oculto {
    display: none;
  }
</style>
<header class="header">
		<div class="container logo-nav-container">
			<h3><a href="<?php echo BASE_URL;?>profesor/dashboard" class="logo"><s class="fa fa-home"></s>KIBSIS: Tú Expediente Digital</a></h3>
			<!--star nav-->
			<nav class="navigation">
				<!--star menu-->
				<ul>
					
					<!--star menu desplegable-->
					<ul>

						<!--star submenu-->
						<li><a href="#" class="list" onclick="mostrarOcultar('submenu_lista_p')"><span class="fa fa-user">Datos personales</span></a>
						<div id="submenu_lista_p" class="oculto">
							<ul class="SubProfe">
								<li><a href="<?php echo BASE_URL. 'profesor/info_profesor'?>" class="list">Ver datos personales</a></li>
							</ul>
						</div>
						</li>
						<!--end submenu-->

						<!--star submenu-->
					<div class="container_submenu_m">	
						<li><a href="#" id="materias" class="list" onclick="mostrarOcultar('submenu_lista_m')"><span class="fa fa-book" >ASIGNACIONES</span></a>
						<div id="submenu_lista_m" class="oculto">
							<ul class="SubMateria">
								<li><a href="<?php echo BASE_URL;?>profesor/allocation_materias" class="list">Lista de asignaciones</a></li>
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



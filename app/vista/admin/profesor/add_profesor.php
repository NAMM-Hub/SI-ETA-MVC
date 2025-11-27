<?php
$pageTitle = "Ingresar profesor";

require_once __DIR__ . '/../../_partials/_head.php';
?>

<div class="container_mainAddPersona">
    <header>
        <?php
            $rol = $_SESSION['rol_usuario'] ?? 'invitado';
            switch ($rol) {
                case 'administrador':
                    require_once __DIR__ . '/../../_partials/header_admin.php';
                    break;
                    case 'asistente':
                    require_once __DIR__ . '/../../_partials/header_asistente.php';
                    break;
                    case 'Profesor':
                    require_once __DIR__ . '/../../_partials/header_profesor.php';
                    break;
                
                default:
                    require_once __DIR__ . '/../../_partials/header_default.php';
                    break;
            }
        ?></header>
    <div class="dashboard-grid">

    <main class="main_addPersona">
    <div class="card">
        <h3 class="h3_tituloAddStud">Registrar profesor</h3>
    </div>
    
    <p class="aviso_obligtorio">Las asteriscos (*) son para los campos obligatorios</p>
<div class="container_tablaAddPersona" id="container_mainAddPersona">


    <form action="<?php echo BASE_URL?>admin/insertarProfesor" method="post">
        <?php $errores = $_SESSION['error'] ?? '';
                unset($_SESSION['error']);
            if (!empty($errores)) {
                echo "<table>";
                foreach ($errores as $error) {
                    echo "<th class='th_DatosIncorrect'>".$error."</th>";
                }
                echo "</table>";
            }
        ?><?php $success = $_SESSION['success'] ?? '';
                unset($_SESSION['success']);
            if (!empty($success)) {
                echo "<table>";
                foreach ($success as $succes) {
                    echo "<th class='th_DatosIncorrect'>".$succes."</th>";
                }
                echo "</table>";
            }
        ?> 
        <div class="card">
            <table class="table_persona">
                  <tr>
                    <th><p align="left">Primer Nombre (*)</p></th>
                    <th><p align="left">Primer Apellido (*)</p></th>
                    </tr>
                    <tr>
                <td><input type="text" name="nombre1" pattern="[A-Za-z]{1,15}" required placeholder="Primer Nombre" value="<?php echo htmlspecialchars($data['nombre1'] ?? ''); ?>"></td>
                <td><input type="text" name="apellido1" patter="[A-Za-z]{1,15}" required placeholder="Primer Apellido" value="<?php echo htmlspecialchars($data['apellido1'] ?? '');?>"></td>
                    </tr>

                    <tr>
                    <th><p align="left">Segundo Nombre (Opcional)</p></th>
                    <th><p align="left">Segundo Apellido (Opcional)</p></th>
                    </tr>
                    <tr>
                <td><input type="text" name="nombre2" pattern="[A-Za-z]{1,15}" placeholder="Segundo Nombre" value="<?php echo htmlspecialchars($data['nombre2'] ?? '');?>"></td>
                <td><input type="text" name="apellido2" pattern="[A-Za-z]{1,15}" placeholder="Segundo Apellido" value="<?php echo htmlspecialchars($data['apellido2'] ?? '');?>"></td>
                    </tr>

            
                <tr>
                <th><p align="center">Sexo (*)</p></th>
                <th align="left">
                <select name="sexo">
                <option value="">--Seleccionar--</option>
                <option value="M" <?= (($data['sexo'] ?? '') == 'M' ? 'selected' : '')?>>Masculino</option>';
                <option value="F"<?= (($data['sexo'] ?? '') == 'F' ? 'selected' : '')?>>Femenino</option>';
                </select>
                </th>
                </tr>
                
                <tr>
                <th><b><p class="subTitle">Fecha de nacimiento (*)</p></b></th>
                <td><input type="date" name="fechaNa" title="Ingresar fecha de nacimiento ej: 10/02/2012" placeholder="10/02/2012" value="<?php echo htmlspecialchars($data['fechaNa'] ?? '');?>"></td>
                </tr>
            
                <tr>
                	<th>
            <div class="correoEAndCedulaI">
							<p align="left">Cédula de identidad (*)</p>
                
                <td><input type="text" name="cedula" pattern="[0-9]{1,8}" required placeholder="Cédula de identidad o Cédula escolar" value="<?php echo htmlspecialchars($data['cedula'] ?? '')?>"></td>
            </div>

                    </tr>
                <tr>
                    <th>
                    	<div class="contenedor_estado">
                        <label for="estado">Estado(*)</label>
                        <select name="estado_id" id="estado" required>
                            <option value="">--Seleccionar--</option>
                            <?php
                            $estadosModelo = new EstadosModelo($this->db);
                            $dataEstados = $estadosModelo->obtener_listaEstados();
                            if (is_array($dataEstados)) {
                                foreach($dataEstados as $estado){
                                    $id_estados = $estado['id_estados'];
                                        $nombre_estados = $estado['nombre_estado'];
                                        echo '<option value="'.$id_estados.'">'.$nombre_estados.'</option>';
                                }
                            }
                            ?>
                        </select>
                    	</div>
                    </th>
                </tr>

                <tr>
                    <th>
                        <div id="contenedor_municipio_select" style="display:none;">
                            <label for="municipio">Municipio(*)</label>
                            <select name="municipio_id" id="municipio">
                                <option value="">Debe seleccionar un estado disponible</option>
                            </select>
                        </div>
                    </th>
                </tr>

                <tr>
                    <th>
                        <div id="contenedor_municipio_texto" style="display:none;">
                            <label for="municipio_texto">Municipio (manual) (*)</label>
                            <input type="text" name="municipio_texto" id="municipio_texto" maxlength="100" value="<?php echo htmlspecialchars($data['municipio_texto']?? '');?>">
                        </div>
                    </th>
                </tr>
                
                <tr>
                    <th>
                        <div id="contenedor_comunidad_select" style="display:none;">
                            <label for="comunidad">Comunidad(*)</label>
                            <select name="comunidad_id" id="comunidad">
                                <option value="">Debe seleccionar un municipio disponible</option>
                            </select>
                        </div>

                    </th>
                </tr>

<div id="dynamic_message_area" style="display:none" ></div>
                <tr>
                    <th>
                        <div id="contenedor_comunidad_texto" style="display:none;">
                            <label for="ciudad_texto">Ciudad (manual) (*)</label>
                            <input type="text" name="ciudad_texto" id="ciudad_texto" maxlength="100" value="<?php echo htmlspecialchars($data['ciudad_texto'] ?? '');?>">
                        </div>
                    </th>
                </tr>
                
                <tr>
                	<th>
                		<div class="estadCivil">
                    		<label for="estado_civil">Estado civil (*)</label>    
                				<select name="estadCivil" id="estado_civil" require>
                					<option value="">--Seleccionar--</option>
                					<option value="Casado" <?= (($data['estadCivil'] ?? '') == 'Casado' ? 'selected' : '')?>>Casado(a)</option>';
                					<option value="Soltero" <?= (($data['estadCivil'] ?? '') == 'Soltero' ? 'selected' : '')?>>Soltero(a)</option>';
                					<option value="Divorciado" <?= (($data['estadCivil'] ?? '') == 'Divorciado' ? 'selected' : '')?>>Divorciado(a)</option>';
                					<option value="Otro" <?= (($data['estadCivil'] ?? '') == 'Otro' ? 'selected' : '')?>>Otro</option>';
                				</select>               
                		</div>
					</th>
                </tr>
            </table>
            </div>
            <div class="container_InputSubmitPersona">
                <button type="submit" name="Guardar">Guardar</buttons>
            </div>
        </form>

    </div>

    </main>
    </div>
<footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>

</body>
<script> var BASE_URL_JS_GLOBAL = '<?php echo BASE_URL; ?>'</script>
<script src="<?php echo BASE_URL;?>public/js/scripts_reside.js"></script>
</html>
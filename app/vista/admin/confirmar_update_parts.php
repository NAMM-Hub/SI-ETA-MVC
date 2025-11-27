<?php
$pageTitle = "Actualizar datos";
require_once __DIR__ . '/../_partials/_headConfirm.php';
?>
<header><?php
        $rol = $_SESSION['rol_usuario'] ?? 'invitado';
            switch($rol){
            case 'administrador':
            require_once __DIR__ . '/../_partials/header_admin.php';
            break;
            case 'asistente':
            require_once __DIR__ . '/../_partials/header_asistente.php';
            break;
            case 'Profesor':
            require_once __DIR__ . '/../_partials/header_profesor.php';
            break;
            default:
            require_once __DIR__ . '/../_partials/header_default.php';
            break;
            }?></header>
    
        <main class="main">
        <div class="card">
        <h1><?php echo $data['title']; ?></h1>
        </div>
        <div class="card">
        <p><?php echo $data['message']; ?></p>
        </div>
        <?php

    ?>
        <div class="dashboard-grid"> 
        <form id="myForm" action="<?php echo BASE_URL . 'admin/' . $data['next_action'] . '/' .$data['ocupacion']. '/' .$data['seccion']. '/' . $data['id_persona']; ?>" method="post">
<?php    
    $errores = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
    if (!empty($errores)) {
        echo "<center><table>";
        foreach ($errores as $error) {
            echo "<th class='th_DatosIncorrect'>".htmlspecialchars($error)."</th>";
        }
        echo "</table></center>";
    }

    $message = $_SESSION['success'] ?? '';
    unset($_SESSION['success']);
    if (!empty($message)) {
        echo "<center><table>";
            echo "<th class='th_DatosActualizado'>".htmlspecialchars($message)."</th>";
        echo "</table></center>";
    }

?>
                <tr><?php
                $contador = 0;
                foreach ($data['form_data'] as $key): ?>

                <?php
                if ($data['ocupacion'] === 'estudiante') {
                    $estudianteModelo = new EstudianteModelo($this->db);
                }elseif($data['ocupacion'] === 'profesor'){
                    $profesorModelo = new ProfesorModelo($this->db);
                }
                
                $nombre1 = $key->nombre1;
                $apellido1 = $key->apellido1;
                $cedula = $key->cedula;
                echo "<div class='card'>";
                echo "<div class='container_table'>";
                echo "<table>";
                echo "<tr>";
                echo "<th colspan='4' class='head_details'>".$data['headTitle']."</th>";
                echo "</tr>";
                echo "<td><b>Cédula: </b></td>";
                echo "<td><strong>".$cedula. "</strong></td>";
                if ($data['seccion'] != 'persona') {
                    echo "<tr>";
                    echo "<td><b>Nombre y apellido: </b></td>";
                    echo "<td><strong>".$nombre1 ." ".$apellido1. "</strong></td>";
                    echo "</tr>";
                }
                
                echo "<tr>";;
                if($data['seccion'] === 'persona'){                    
                $nombre2 = $key->nombre2;
                $apellido2 = $key->apellido2;
                $sexo = $key->sexo;
                $fecha_nacimiento = $key->fecha_nacimiento;
                $estado_civil = $key->estado_civil;
                echo "<tr>";
                echo "<td><b><label for='id_nombre1'>Primer Nombre: <label><input type='text' name='nombre1' value='".$nombre1 ."' id='id_nombre1'></td>";
                echo "<td><b><label for='id_apellido1'>Primer Apellido: <label><input type='text' name='apellido1' value='".$apellido1 ."' id='id_apellido1'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td><b><label for='id_nombre2'>Segundo Nombre: <label><input type='text' name='nombre2' value='".$nombre2 ."' id='id_nombre2'></td>";
                echo "<td><b><label for='id_apellido2'>Segundo Apellido: <label><input type='text' name='apellido2' value='".$apellido2 ."' id='id_apellido2'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td><b><label for='id_sexo'>Sexo:</label></b></td>";
                echo "<td><select name='sexo' id='id_sexo'>";
                    echo "<option value=''>--Seleccionar--</option>";
                    foreach($data['select_generos'] as $selectSexo){
                        echo "<option value='".$selectSexo['valor']."' ".($sexo == $selectSexo['valor'] ? 'selected' : '').">".$selectSexo['texto']."</option>";
                    }
                echo "</select></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td><b><label for='id_sexo'>Fecha de Nacimiento:</label></b></td>";
                echo "<td><input type='date' name='fecha_nacimiento' value='".$fecha_nacimiento."'></td>";
                echo "</tr>";                   
                echo "<tr>";
                echo "<td><b><label for='id_estadoCivil'>Estado Civil:</label></b></td>";
                echo "<td><select name='estado_civil' id='id_estadoCivil'>";
                    echo "<option value=''>--Seleccionar--</option>";
                    foreach($data['select_estadoCivil'] as $estadoCivil){
                        echo "<option value='".$estadoCivil['valor']."' ".($estado_civil == $estadoCivil['texto'] ? 'selected' : '').">".$estadoCivil['texto']."</option>";
                    }
                echo "</select></td>";
                echo "</tr>";
                    }
                if ($data['ocupacion']==='estudiante') {
                    if($data['seccion'] === 'academico'){
                    $ano_grado = $key->ano_grado;
                    $estatus = $key->estatus;
                    $periodo_escolar = $key->periodo_escolar;
                    echo "<th colspan='4' class='head_details'>Datos academicos</th>";
                    echo "<tr>";
                    echo "<th><label for='id_anoGrado'>Ano/Grado: </label></th>";
                    echo "<th><select name='ano_grado' id='id_anoGrado'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        echo $estudianteModelo->generarOpcionAnoGrado($ano_grado);
                    echo "</select></th><tr>";
                    echo "<th><label for='id_estatus'>Estatus: </label></th>";
                    echo "<th><select name='estatus' id='id_estatus'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        $selectEstatus = $estudianteModelo->obtener_estatus_estudinate();
                        foreach($selectEstatus as $estatuSelect){
                            echo "<option value='".$estatuSelect['valor']."' ".($estatus == $estatuSelect['texto'] ? 'selected' : '').">".$estatuSelect['texto']."</option>";
                        }
                    echo "</select></th><tr>";
                    echo "<th><label for='id_periodo'>Periodo Escolar:</label></th>";
                    echo "<th><select name='periodo_escolar' id='id_periodo'>";
                    echo "<option value=''>--Seleccionar--</option>";
                    $periodoModelo = new PeriodoEscolarModelo($this->db);
                        $periodos = $periodoModelo->obtener_periodoEscolar();
                        foreach ($periodos as $lista) {
                            $selected = ($lista->periodo_escolar == $periodo_escolar) ? 'selected' : ''; 
                            $id_periodo_encrypt = encrypt_id($lista->id_perido_escolar);
                            $periodo_escolar_db = $lista->periodo_escolar;
                            echo "<option value='".$id_periodo_encrypt."' ".$selected.">".$periodo_escolar_db."</option>";
                        }
                    echo "</select></th><tr>";
                    }
                }elseif ($data['ocupacion'] === 'profesor') {
                    if ($data['seccion'] === 'academico') {
                        $estatus = $key->estatus;
                        echo "<th colspan='4' class='head_details'>Datos academicos</th>";
                        echo "<tr>";
                        echo "<th><label for='id_estatus'>Estatus: </label></th>";
                    
                        echo "<th><select name='estatus' id='id_estatus'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        
                        foreach($data['select_estatus_profesor'] as $estatuSelect){
                            echo "<option value='".$estatuSelect['valor']."' ".($estatus == $estatuSelect['texto'] ? 'selected' : '').">".$estatuSelect['texto']."</option>";
                        }
                        echo "</select></th><tr>";
                    }
                }


                if($data['seccion'] === 'ubicacion') {
                $id_estado = $key->id_estados;
                $nombre_estado = $key->nombre_estado;
                $municipio_texto = $key->municipio_texto;
                $id_municipio = $key->id_municipio;
                $nombre_municipio = $key->nombre_municipio;
                $comunidad_texto = $key->ciudad_comunidad_texto;
                $id_comunidad = $key->id_comunidad;
                $nombre_comunidad = $key->nombre_comunidad;
                        echo "<th class='head_details' colspan='5'>Ubicación</th>";
                        echo "<tr>";
                        echo "<th colspan='2'><div class='contenedor_estado'><label for='estado'>Estado: </label>";
                $estadosModelo = new EstadosModelo($this->db);
                $lista_estados = $estadosModelo->obtener_listaEstados();
                        echo "<select name='estado_id' id='estado'>";
                        echo "<option value=''>--Seleccionar--</option>";
                            foreach($lista_estados as $info){
                                $id_estadoDB = $info['id_estados'];
                                $selectedEstado = ($id_estadoDB == $id_estado ? 'selected' : '');
                                $estado = $info['nombre_estado'];
                                echo "<option value='".$id_estadoDB."' ".$selectedEstado.">".$estado."</option>";
                            }
                        echo "</select></div></th>";
                        echo "<tr>";
                                
                        echo "<th colspan='2'>";
                        echo "<div id='contenedor_municipio_texto' style='display:none;'>";
                        echo "<label for='municipio_texto'>Municipio: </label>";
                        echo "<input type='text' name='municipio_texto' value='".$nombre_municipio. "' id='municipio_texto' maxlength='100'>";
                        echo "</div>";

                        echo "<div id='contenedor_municipio_select' style='display:none;'>";
                        echo "<label for='municipio'>Municipio: </label>";
                        $municipioModelo = new MunicipioModelo($this->db);
                        $lista_municipios = $municipioModelo->obtener_listaMunicipios();
                        echo "<select name='municipio_id' id='municipio'>";
                        echo "<option value=''>--Seleccionar--</option>";
                            foreach($lista_municipios as $lista){
                                $id_municipio_encrypt = encrypt_id($lista->id_municipio);
                                $municipio = $lista->nombre_municipio;
                                echo "<option value='".$id_municipio_encrypt."' ".($id_municipio == $lista->id_municipio ? 'selected' : '').">".$municipio."</option>";
                            }
                        echo "</select>";
                        echo "</div>";
                        echo "</th>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<th colspan='2'>";
                        echo "<div id='contenedor_comunidad_texto' style='display:none;'>";
                        echo "<label for='comunidad_texto'>Comunidad/Ciudad: <input type='text' name='comunidad_texto' id='ciudad_texto' value='".$nombre_comunidad."'>";
                        echo "</div>";

                $comunidadModelo = new ComunidadModelo($this->db);
                $lista_comunidad = $comunidadModelo->obtener_listaComunidades();      
                        echo "<div id='contenedor_comunidad_select' style='display:none;'>";
                        echo "<label for='comunidad'>Comunidad/Ciudad: </label>";          
                        echo "<select name='comunidad_id' id='comunidad'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        foreach ($lista_comunidad as $info) {
                            $selectedComunidad = ($info->id_comunidad == $id_comunidad) ? 'selected' : '';
                            $id_comunidadDB = encrypt_id($info->id_comunidad);
                            $comunidad = $info->nombre_comunidad;
                            echo "<option value='".$id_comunidadDB."' ".$selectedComunidad.">".$comunidad."</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                        echo "</th>";
                        echo "<tr>";
                    }
                
                ?>

                <?php endforeach; ?>

                <?php if ($contador > 0): ?>
                </tr>    <?php endif; ?>

            </table>
            </div>
            </div>
            </div>
            <button type="reset">Restablecer Todo</button>    
            <button type="submit">Confirmar y Guardar</button>
        </form>
        <form action="<?php echo BASE_URL; ?>admin/cancelar" method="post">
            <button type="submit" name="cancelar">Cancelar</button>
        </form>
     
        </main>
        <script> var BASE_URL_JS_GLOBAL = '<?php echo BASE_URL;?>'</script>
        <script src="<?php echo BASE_URL; ?>public/js/scripts.js">
        </script>
        <script src="<?php echo BASE_URL; ?>public/js/scripts_reside.js">
        </script>
        </body>
        </html>

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


    $formData = $_SESSION['formulario_data'] ?? [];
    unset($_SESSION['formulario_data']);
    ?>
        <div class="dashboard-grid">        
        <form action="<?php echo BASE_URL . 'profesor/' . $data['next_action'] . '/' .$data['id_persona']; ?>" method="post">
        <?php $errores = $_SESSION['error'] ?? '';
                unset($_SESSION['error']);
            if (!empty($errores)) {
                echo "<table>";
                foreach ($errores as $error) {
                    echo "<th class='th_DatosIncorrect'>".$error."</th>";
                }
                echo "</table>";
            }
        ?>
        <?php $success = $_SESSION['success'] ?? '';
                unset($_SESSION['success']);
            if (!empty($success)) {
                echo "<table>";
                foreach ($success as $succes) {
                    echo "<th class='th_DatosActualizado'>".$succes."</th>";
                }
                echo "</table>";
            }
        ?>
            <div class="card">
            <div class="container_table">
            <table>
                <tr>
                    <th colspan="4" class="head_details"><?php echo $data['headTitle']?></th>
                </tr>
                <?php
                    $contador = 0;
                    $filaAbierta = false;
                
                ?>
                <?php if ($data['ocupacion']) : ?>

                <tr><?php foreach ($data['form_data'] as $key): ?>
                <?php
                $id =  $key->persona_id;
                $nombre1 = $key->nombre1;
                $nombre2 = $key->nombre2;
                $apellido1 = $key->apellido1;
                $apellido2 = $key->apellido2;
                $cedula = $key->cedula;
                $sexo = $key->sexo;
                $fecha_nacimiento = $key->fecha_nacimiento;
                $estado_civil = $key->estado_civil;
                $id_estado = $key->id_estados;
                $nombre_estado = $key->nombre_estado;
                $municipio_texto = $key->municipio_texto;
                $id_municipio = $key->id_municipio;
                $nombre_municipio = $key->nombre_municipio;
                $comunidad_texto = $key->ciudad_comunidad_texto;
                $id_comunidad = $key->id_comunidad;
                $nombre_comunidad = $key->nombre_comunidad;
                echo "<th>Primer nombre</th>";
                echo "<td><input type='text' value='".$nombre1 ."' name='nombre1'></td>";
                                    
                echo "<th>Primer apellido</th>";
                echo "<td><input type='text' value='".$apellido1. "' name='apellido1'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Segundo nombre</th>";
                echo "<td><input type='text' value='".$nombre2. "' name='nombre2'></td>";
                echo "<th>Segundo apellido</th>";
                echo "<td><input type='text' value='".$apellido2. "' name='apellido2'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Cédula</th>";
                echo "<td><strong>".$cedula. "</strong></td>";
                echo "<th>Sexo</th>";

                echo "<td><select name='sexo' required>";
                    echo "<option value=''>--Seleccionar--</option>";
                    foreach($data['select_generos'] as $genero){
                    echo "<option value='".$genero['valor']."' ".($sexo == $genero['valor'] ? 'selected' : '').">".$genero['texto']."</option>";
                }
                echo "</select></td>";

                echo "</tr>";
                echo "<tr>";
                echo "<th>Fecha de nacimiento</th>";
                echo "<td><input type='date' value='".$fecha_nacimiento. "' name='fecha_nacimiento' required></td>";

                echo "<th>Estado civil</th>";               
                echo "<td><select name='estado_civil' required>";
                    echo "<option value=''>--Seleccionar--</option>";
                    foreach($data['select_estadoCivil'] as $estadoCivil){
                        echo "<option value='".$estadoCivil['valor']."' ".($estado_civil == $estadoCivil['texto'] ? 'selected' : '').">".$estadoCivil['texto']."</option>";
                    }
                    
                echo "</select></td>";
                echo "</tr>";

                echo "</table>";
                echo "</div>";
                echo "</div>";

                echo "<div class='card'>";
                echo "<div class='container_table'>";
                echo "<table>";

                echo "<th class='head_details' colspan='5'>Ubicación</th>";
                        echo "<tr>";
                        echo "<th colspan='2'><div class='contenedor_estado'><label for='estado'>Estado: </label>";
                $estadosModelo = new EstadosModelo($this->db);
                $lista_estados = $estadosModelo->obtener_listaEstados();
                        echo "<select name='estado_id' id='estado'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        foreach($lista_estados as $info){
                            $selectedEstado = ($info['id_estados']) == $id_estado ? 'selected' : '';
                            $id_estadoDB = $info['id_estados'];
                            $estado = $info['nombre_estado'];
                            echo "<option value='".$id_estadoDB."' ".$selectedEstado.">".$estado."</option>";
                        }
                        echo "</select></div></th>";
                        echo "<tr>";
                        if (!empty($nombre_municipio) and !empty($nombre_comunidad)) {
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
                        echo "<th colspan='2'>";
                        echo "<div id='contenedor_municipio_texto' style='display:none;'>";
                        echo "<label for='municipio_texto'>Municipio: </label>";
                        echo "<input type='text' name='municipio_texto' value='".$municipio_texto. "' id='municipio_texto' maxlength='100'>";
                        echo "</div>";

                        echo "<div id='contenedor_municipio_select' style='display:none;'>";
                        echo "<label for='municipio'>Municipio: </label>";
                        echo "<select name='municipio_id' id='municipio'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        echo "</select>";
                        echo "</div>";
                        echo "</th>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<th colspan='2'>";
                        echo "<div id='contenedor_comunidad_texto' style='display:none;'>";
                        echo "<label for='comunidad_texto'>Comunidad/Ciudad: <input type='text' name='comunidad_texto' id='ciudad_texto' value='".$comunidad_texto."'>";
                        echo "</div>";     
                        echo "<div id='contenedor_comunidad_select' style='display:none;'>";
                        echo "<label for='comunidad'>Comunidad/Ciudad: </label>";          
                        echo "<select name='comunidad_id' id='comunidad'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        echo "</select>";
                        echo "</div>";
                        echo "</th>";
                        echo "<tr>";

                ?>
                <?php if(!empty($value)): ?>
                    <?php if($contador > 0 && $contador % 2 == 0): ?>
                    </tr><tr><?php endif; ?>

                        <th><?php echo ucfirst($key); ?>:</th>

                        <td> <?php echo htmlspecialchars($value); ?></td>
                    
                    
                    <?php $contador++; ?>
                <?php endif; ?>
                <?php endforeach; ?>

                    <?php if ($contador > 0): ?>
                    </tr>    <?php endif; ?>
            </table>
            </div>
            </div>

        <?php endif;?>
                    
            </div>   
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

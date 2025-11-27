<?php
$pageTitle = "Asignación profesor-materias";

require_once __DIR__ . '/../_partials/_head.php';

?>

    <div class="container_addMaterias">
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
    <div class="dashboard-grid">
        <div class="card">
            <h2>Asignar Materias a un Profesor</h2>
        </div>
    <?php if ($data['lista_profesor'] && $data['lista_materias']): ?>
        <main class="main_Materias">
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
        <div class="container_mainAgregarMaterias" id="container_mainAgregarMateria s">

        <form action="<?php echo BASE_URL;?>admin/insertar_allocation_profesorMaterias" method="post">

            <div class="card">
                <label for="profesor">Selecciona un Profesor:</label>

<?php
        echo '<select name="profesor_id" id="profesor" require>
                ';
        echo '<option value="">--Seleccionar--</option>';
        foreach($data['lista_profesor'] as $DatosProfesor){
            $persona_id = encrypt_id($DatosProfesor->persona_id);
            $nombre1 = $DatosProfesor->nombre1;
            $apellido1 = $DatosProfesor->apellido1;
            echo "<option value='".$persona_id."'>";
            echo htmlspecialchars($nombre1);
            echo " ";
            echo htmlspecialchars($apellido1);
            echo "</option>";
        }
        echo '</select>';

?>
    </div>
    <div class="card">            
            <label for="materias">Selecciona las Materias:</label>
<?php
        echo '<select name="materias_id[]" id="materias" multiple required>
                ';
            foreach($data['lista_materias'] as $DatosMaterias){
                $id_materias = encrypt_id($DatosMaterias->id_materias);
                $nombre_materias = $DatosMaterias->nombre_materias;
                $ano_grado = $DatosMaterias->ano_grado;
                echo "<option value='".$id_materias."'>".$nombre_materias." - ".$ano_grado."</option>";
            }
        echo '</select>';     
?>
    </div>
            
            <button name="Asignar_materia" type="submit">Asignar Materias</button>
        </form>
        
    <?php else: ?>
    
        <div style="color: red; border: 1px solid red; padding: 10px;">
            <?php if (!$data['lista_profesor']): ?>
                <p>⚠️ No hay profesores disponibles. Por favor, <a href="agregarProfesor.php">agrega profesores</a> antes de realizar asignaciones.</p>
            <?php endif; ?>
            <?php if (!$data['lista_materias']): ?>
                <p>⚠️ No hay materias disponibles. Por favor, <a href="agregarMateria.php">agrega materias</a> antes de realizar asignaciones.</p>
            <?php endif; ?>
        </div>
        
    <?php endif; ?>
        </div>
        </div>
        </main>
        <footer><?php require_once __DIR__ . '/../_partials/footer.php'?></footer>
    </div>
    </div>
</body>
</html>
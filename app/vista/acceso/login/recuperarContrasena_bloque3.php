<?php

$pageTitle = "Establecer nueva contraseña";

require_once __DIR__ . '/../../_partials/_headRecovery.php';
?>

    <div class="container_ingresarCedula1">
        <div class="container_ingresarCedula2">
            <h1>Establecer Nueva Contraseña</h1>
        </div>
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
<?php
    $errores = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);

    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<p style='line-height:0.1px;'>".htmlspecialchars($error)."</p></br>";
        }
    }
?>
    <form action="<?php echo BASE_URL. 'login/'. $data['next_action'] . '/' . $data['id_usuario']?>" method="POST">
    <div class="container_field_recovery">    
        <div class="container_ingresarCedula2">
            <label for="nueva_contrasena" class="contrasena_nueva">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena"  name="nueva_contrasena" autocomplete="off" required>
            <input type="checkbox" id="showPassword1"><label for="showPassword1">Mostrar Contraseña</label>
        </div>

        <div class="container_ingresarCedula2">
            <label for="confirmar_contrasena" class="contrasena_nueva">Confirmar Contraseña:</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" autocomplete="off" required>
            <input type="checkbox" id="showPassword2"><label for="showPassword2">Mostrar Contraseña</label>
        </div>

    </div>
        <p>La contraseña debe ser mayor o igual a ocho caracteres</p>
        <p>La contraseña debe tener al menos una letra mayúscula</p>
        <p>La contraseña debe tener al menos un número</p>
        <p>La contraseña debe tener al menos un # o *</p>
        <div class="container_buttons_recovery">
            <button type="submit" name="actualizar_contrasena">Actualizar Contraseña</button>  
        </div>
    </form>

    <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>
</div>
</body>
<script src="<?php echo BASE_URL;?>public/js/scripts_mostrar_respuestas.js"></script>
</html>
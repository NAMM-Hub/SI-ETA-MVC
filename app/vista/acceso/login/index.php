<?php 
session_start();

$pageTitle = "Iniciar sesión";
require_once __DIR__ . '/../../_partials/_headLogin.php';

?>
<div class="dashboard-grid">
    <div class="card">
        <?php require_once __DIR__ . '/../../_partials/header_login.php' ?>
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
<div class="container_table">
   
<form action="/SI-ETA-MVC/login/login" method="post">
     <div class="card">
        <h2>Iniciar sesión</h2>
    <input type="text" name="nombre_usuario" pattern="[A-Z-a-z0-9]{1,15}"  placeholder="Ingresar nombre de usuario" title="Escriba el nombre de usuario colocó al registrarse" required autocomplete="off">
    <input type="checkbox" id="showPassword"><label for="showPassword">Mostrar Contraseña</label>
    <input type="password" name="password" pattern="[A-Za-z0-9-#_-]{1,15}" placeholder="Ingresar contraseña" title="Escriba aquí la contraseña que colocó al registrarse" required id="contrasena">
    <input type="submit" name="bIngresar" value="Ingresar">

    </div>
    <div class="card">
        <a href="<?php echo BASE_URL;?>login/olvidoContrasena" class="recovery_a">Recuperar contraseña</a>
    </div>
    <div class="container_imagen_login">
        <img src="/SI-ETA-MVC/public/css/im-user-away.png" name="icon" class="image_login">
    </div>

</form>
</div>
<script src="<?php echo BASE_URL; ?>public/js/scripts.js"></script>
</body>
</html>
<?php
class LoginController {
    protected $db;
    
    public function __construct(Database $db){
        $this->db = $db;
    }
    
    private function renderView($path, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . '/../vista/acceso/login/' . $path . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo $viewPath. '<br>';
            echo "Error 404: Vista no encontrada.";
        }
    }

    protected function disableBrowserCache(){
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");   
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    protected function redirect(string $path){
        $url = BASE_URL . $path;

        header("Location: {$url}");
        exit();
    }

    public function index() {
        $this->renderView('index');
    }

    public function login() {        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $errores = [];
            $conexion = $this->db->getConexion();
            
            $usuario = trim($_POST['nombre_usuario'] ?? '');
            $contrasena = trim($_POST['password'] ?? '');

            if (empty($usuario)) {
                $errores[] = "Debe colocar sus datos(nombre de usuario)";
            }
            if (empty($contrasena)) {
                $errores[] = "Debe colocar sus datos(contraseña)";
            }

            if(!empty($errores)){
                $_SESSION['error'] = $errores;
                $this->redirect('index');
            }
            $data = ['nombre_usuario'=>$usuario,
                    'password'=>$contrasena];
            $usuarioModelo = new UsuarioModelo($this->db);
            $info_usuario = $usuarioModelo->check_usuario_login($data);
            
            if (is_array($info_usuario)) {
                $id_usuario = null;
                $contrasena_hash = null;
                $rol = null;
                $nombre_usuario = null;
                $preguntas_seguridad = null;

                foreach($info_usuario as $usuario){
                    $id_usuario = encrypt_id($usuario->persona_id);
                    $contrasena_hash = $usuario->password;
                    $rol = $usuario->rol_usuario;
                    $nombre_usuario = $usuario->nombre_usuario;
                    $preguntas_seguridad = $usuario->preguntas_seguridad_configuradas;
                }

                    $_SESSION['id'] = $id_usuario;
                    $_SESSION['nombre_usuario'] = $nombre_usuario;
                    $_SESSION['rol_usuario'] = $rol;
                    $_SESSION['preguntas_configuradas'] = $preguntas_seguridad;
                    $_SESSION['next_action'] = 'insert_preguntas_seguridad';

                if (password_verify($contrasena, $contrasena_hash) && $rol) { 

                    if($preguntas_seguridad === 0){
                        $this->redirect('login/gestionUsuario');
                    }
                    switch ($rol) {
                        case 'administrador':
                            $this->redirect('admin/dashboard');
                            break;
                        case 'asistente':

                            $this->redirect('asistente/dashboard');
                            break;
                        case 'Profesor':
                            $this->redirect('profesor/dashboard');
                            break;
                        default:
                            $this->redirect('login/index');
                        break;
                    }
                }else{
                    $errores[] = "Datos incorrectos";
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/index');
                }
            }else{
            // usuario de asistente = Hola123           
            // contrasena de asistente = Casioman28#           
                $errores[] = "Datos incorrectos o no se encuentra registrado";
                $_SESSION['error'] = $errores;
                $this->redirect('index');
            } 
        } else {
            $this->redirect('index');
        }
    }

    public function gestionUsuario() {
        session_start();

        if ($_SESSION['preguntas_configuradas'] === 1) {
            $this->redirect('login/index');
        }else{            
            $this->renderView('gestionUsuario');
        }
    }
    public function insert_preguntas_seguridad($id){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if(isset($_POST['Confirmar'])){
                $id_usuario_decrypt = decrypt_id($id);
                $preguntas_seguridad = $_POST['preguntas_seguridad'] ?? [];
                $errores = [];
                $preguntas = [];
                $respuestas = [];
                $success = true;
                if(empty($preguntas_seguridad) || count($preguntas_seguridad) !==3 ){
                    $success = false;
                    $errores[] = "Las preguntas y respuestas no son válidas";
                            $_SESSION['error'] = $errores;
                            $this->redirect('login/gestionUsuario');
                }else{
                    foreach($preguntas_seguridad as $request){
                        if (empty($request['pregunta']) || empty($request['respuesta'])) {
                            $success = false;
                            $errores[] = "Las preguntas y respuestas son obligatorias";
                            $_SESSION['error'] = $errores;
                            $this->redirect('login/gestionUsuario');
                        }                        
                    }
                }

                if(!$success){
                    $errores[] = "Debe seleccionar una pregunta y proporcionar una respuesta para cada campo";
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/gestionUsuario');
                }
                $preguntas_unity = array_unique($preguntas);
                $respuestas_unity = array_unique($respuestas);
                if(count($preguntas) !== count($preguntas_unity)){
                    $errores[] = "No se puede repetir la misma pregunta";
                }
                if(count($respuestas) !== count($respuestas_unity)){
                    $errores[] = "No se puede repetir la misma respuesta";
                }
                if(!empty($errores)){
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/gestionUsuario');
                }elseif($success === true){
                    $rol = $_SESSION['rol_usuario'];
                    $data = $preguntas_seguridad;
                    $estatus_pregunta = 1;
                    $_SESSION['preguntas_configuradas'] = $estatus_pregunta;
                    echo $_SESSION['preguntas_configuradas'];
                    exit();
                    $usuariosModelo = new UsuarioModelo($this->db);
                    $resultado = $usuariosModelo->insert_preguntas_seguridad($data,$estatus_pregunta,$id_usuario_decrypt);
                    if ($resultado) {
                        switch ($rol) {
                            case 'administrador':
                                $this->redirect('admin/dashboard');
                            break;
                            case 'asistente':
                                $this->redirect('asistente/dashboard');
                            break;
                            case 'Profesor':
                                $this->redirect('profesor/dashboard');
                            break;
                            default:
                                $this->redirect('index');
                            break;
                        }
                    }else{
                        $this->redirect('login/index');
                    }
                }elseif($success === false){
                    $errores[] = "Ocurrio un error al establecer las preguntas de seguridad";
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/gestionUsuario');
                }
            }else{
                $this->redirect('login?message=no_data');
            }
        }else{
            $this->redirect('login?message=no_data');
        }
    }
    
    public function olvidoContrasena(){
        $this->renderView('recuperarContrasenaForm');
    }

    public function check_usuario(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            $errores = [];
            if (empty($identificador = trim($_POST['identificador'] ?? ''))) {
                $errores[] = "La cédula es obligatoria";
            }
            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $this->redirect('olvidoContrasena');
            }
            if(empty($errores)){
                $datos = ['identificador'=>$identificador];
                $usuariosModelo = new UsuarioModelo($this->db);
                $info_usuario = $usuariosModelo->check_usuarioDB($datos);

                if (empty($info_usuario)) {
                    $errores[] = "No se ha encontrado usuario";
                }
                
                if (!empty($errores)) {
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/olvidoContrasena');
                }
                if(empty($errores) and is_array($info_usuario)){
                    $id_usuario = 0;
                    $nombre_usuario = '';
                    $preguntas_seguridad = '';
                    foreach ($info_usuario as $key) {
                        $id_usuario = encrypt_id($key->persona_id);
                        $nombre_usuario = $key->nombre_usuario;
                        $preguntas_seguridad = $key->preguntas_seguridad_configuradas;
                    }
                    if($preguntas_seguridad === 1){
                        $this->redirect('login/recovery_usuario/'.$id_usuario);
                    }else{
                        $errores[] = "no hay preguntas de seguridad configuradas";
                        $_SESSION['error'] = $errores;
                        $this->redirect('login/olvidoContrasena');
                    }
                }
            }
        }        
    }
 
    public function recovery_usuario($id_usuario){
        $preguntas_seguridad = '';
        if (!empty($id_usuario)) {
            $id_usuario_decrypt = decrypt_id($id_usuario);           
            $usuarioModelo = new UsuarioModelo($this->db);
            $preguntas_seguridad = $usuarioModelo->check_preguntasSeguridad($id_usuario_decrypt);
        }else{
            $this->redirect('login/olvidoContrasena?message=no_data');
        }
        if(empty($preguntas_seguridad) and !is_array($preguntas_seguridad)){
            $this->redirect('login/olvidoContrasena?message=no_array');
        }
        
        if(count($preguntas_seguridad) < 2){
            session_start();
            $errores[] = "No hay suficientes preguntas para este usuario";
            $_SESSION['error'] = $errores;
            $this->redirect('login/olvidoContrasena');
        }else{
            $preguntas_elegir = $preguntas_seguridad;
            shuffle($preguntas_elegir);
            $preguntas_listas = array_slice($preguntas_elegir, 0, 2);
        }
        
        $data = [
                'id_usuario'=>$id_usuario,
                'recovery_usuario'=>$preguntas_listas
                ];
        $this->renderView('recuperarContrasena_bloque2',$data);
    }

    public function recovery_usuario_check($id_usuario){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $errores = [];
            if(!empty($id_usuario)){
                $id_usuario_decrypt = decrypt_id($id_usuario);
            }
            if (!empty($_POST['clave'][0]) || !empty($_POST['clave'][1])) {
                $id_respuesta_decrypt1 = decrypt_id($_POST['clave'][0]);
                $id_respuesta_decrypt2 = decrypt_id($_POST['clave'][1]);
            }

            $preguntas = $_POST['preguntas'] ?? [];
            $respuestas = $_POST['respuestas'] ?? [];
            $respuestasDB1 = '';
            $respuestasDB2 = '';
            $respuestas_correctas = 0;
            if (count($respuestas) < 2 || empty($respuestas[0]) || empty($respuestas[1])) {
                echo "Debe responder todas las preguntas de seguridad";
            }else{
                $respuesta_1 = $respuestas[0];
                $respuesta_2 = $respuestas[1];
                $usuarioModelo = new UsuarioModelo($this->db);
                $respuestasData_1 = $usuarioModelo->check_preguntasSeguridad_form($id_respuesta_decrypt1);
                $respuestasData_2 = $usuarioModelo->check_preguntasSeguridad_form($id_respuesta_decrypt2);

                foreach($respuestasData_1 as $recovery){
                    $respuestasDB1 = $recovery->respuesta_hash;
                }

                foreach($respuestasData_2 as $recovery){
                    $respuestasDB2 = $recovery->respuesta_hash;
                }
                if (password_verify($respuesta_1, $respuestasDB1)) {
                    $respuestas_correctas++;
                }else{
                    $errores[] = "Las respuesta uno no coincide";
                }
                if (password_verify($respuesta_2, $respuestasDB2)) {
                    $respuestas_correctas++;

                }else{
                    $errores[] = "La respuesta dos no coincide";
                }
                if (!empty($errores)) {
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/olvidoContrasena');
                }
                if($respuestas_correctas === 2){
                    $token = bin2hex(random_bytes(32));
                    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                    $datos_token = ['token'=>$token,
                                    'expires_at'=>$expires_at];
                    $resultado = $usuarioModelo->prepare_tokens_recovery($id_usuario_decrypt, $datos_token);

                    if ($resultado){
                        $success[] = "Token generado con exito";
                        $_SESSION['token_temp'] = $token;
                        $_SESSION['success'] = $success;
                        $this->redirect('login/recovery_usuario_new_password/'.$id_usuario);
                    }else{
                        $this->redirect('login/olvidoContrasena');
                    }
                }                
            }
        }
    }

    public function recovery_usuario_new_password($id_usuario){
        if(!empty($id_usuario)){
            session_start();            
            $success = $_SESSION['success'] ?? '';
            unset($_SESSION['success']);

            $data = ['id_usuario'=>$id_usuario,
                    'next_action'=>'recovery_usuario_insert_password'];
            $this->renderView('recuperarContrasena_bloque3',$data);
        }else{
            $this->redirect('login/olvidoContrasena?message=no_data');
        }
    }

    public function recovery_usuario_insert_password($id_usuario){
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['actualizar_contrasena'])) {
                session_start();
                $errores = [];
                $token_id = '';
                if (!empty($id_usuario)) {
                    $id_usuario_decrypt = decrypt_id($id_usuario);
                }
                
                if (empty($password_post = trim($_POST['nueva_contrasena'])) || empty($password_verify_post = trim($_POST['confirmar_contrasena']))) {
                    $errores[] = "Ambos campos de contraseñas son obligatorias";
                }elseif ($password_post !== $password_verify_post) {
                    $errores[] = "Las contraseñas no coinciden";
                }

                if (strlen($password_post) < 8) {
                    $errores[] = "La contraseña debe tener al menos 8 caracteres"; 
                }
                if(!preg_match('/[A-Z]/', $password_post)){
                    $errores[] = 'La contraseña debe tener al menos un caracter mayúscula';
                }
                if (!preg_match('/[0-9]/', $password_post)) { 
                    $errores[] = 'La contraseña debe tener al menos un número.';                       
                }
                if (!preg_match('/[\#\*\.\_\-]/', $password_post)) { 
                    $errores[] = 'La contraseña debe tener al menos un #, *, o algún caracter especial.';
                }

                if (!empty($errores)) {
                    $_SESSION['error'] = $errores;
                    $this->redirect('login/recovery_usuario_new_password/'.$id_usuario);
                }else{
                    if(isset($_SESSION['token_temp'])){
                        $token_temp = $_SESSION['token_temp'];
                        $datos_token = ['token'=>$token_temp,
                                        'user_id'=>$id_usuario_decrypt];
                        $usuarioModelo = new UsuarioModelo($this->db);
                        $resultado = $usuarioModelo->check_tokens_recovery($datos_token);
                        if ($resultado) {
                            $used_at = null;
                            $expires_at = '';
                            foreach($resultado as $data){
                                $used_at = $data->used_at;
                                $expires_at =  $data->expires_at;
                                $token_id =  $data->id;
                            }
                            if($used_at !== null){
                                $errores[] = "Este enlace de recuperacion ya ha sido utilizado";
                            }elseif(date('Y-m-d H:i:s') > $expires_at){
                                $errores[] = "El tiempo limite ha expirado...";
                            }
                        }else{
                            $errores[] = "Error en validar tokens de recovery";
                        }    
                    }
                    foreach($errores as $error){
                        echo $error;
                        exit();
                    }
                    if(!empty($erroress)){
                        $_SESSION['error'] = $errores;
                        $this->redirect('login/olvidoContrasena');
                    }
                    $password_hash = password_hash($password_post, PASSWORD_DEFAULT);
                    $datos_password = [
                                    'password_new'=>$password_hash,
                                    'id_usuario'=>$id_usuario_decrypt,
                                    'id_token'=>$token_id];

                    $usuarioModelo = new UsuarioModelo($this->db);
                    $resultado_password = $usuarioModelo->update_password($datos_password);
                    if ($resultado_password) {
                        $success[] = "Nueva contraseña establecida";
                        $_SESSION['success'] = $success;
                        $this->redirect('login/index');
                    }else{
                        $errores[] = "Ocurrio un problema en intentar establecer la nueva contraseña";
                        $_SESSION[] = $errores;
                        $this->redirect('login/index');
                    }                   
                } 
            }            
        }
    }
    public function cancelar(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('login/index');
        }
    }

    public function cancelar_update(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('login/index');
        }
    }
    
    public function logout(){
        session_start();
        session_destroy();
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]);
        }

        $this->disableBrowserCache();
        $this->redirect('login/index');
        exit();
    }

    
}//--------Nueva contrasena Casioman962#------------ es necesario crear una variable que se temporal para borrarla luego de usarla------------------------

//--------Investigar donde colocar el la funcion unset para limpiar la variable despues de usarla------------------
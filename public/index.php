<?php
// Define la URL base del proyecto
// ¡DEBE SER LO PRIMERO EN EL ARCHIVO!
date_default_timezone_set('America/Caracas');
define('ROOT_PATH', __DIR__ . '/../');

require_once __DIR__ . '/../app/config/config.php';

spl_autoload_register(function($clase){
$rutas = [
            'controlador/',
            'modelo/',
            'helpers/',
        ];
    if($clase === 'FPDF'){
            $archivoPDF = __DIR__ .'/../app/lib/fpdf/fpdf.php';
    
            if (file_exists($archivoPDF)) {
                require_once $archivoPDF;
                return;
            }

    }


    foreach($rutas as $ruta){
        $archivo = __DIR__ .'/../app/' . $ruta . $clase . '.php';
    
        if (file_exists($archivo)) {
            require_once $archivo;
            return;
        }
    }
});

define('BASE_URL', '/SI-ETA-MVC/');
require_once __DIR__ . '/../app/helpers/UrlHelper.php';

// 1. Limpiamos la URL
$uri = $_SERVER['REDIRECT_URL'] ?? '/';
$base_path = '/SI-ETA-MVC/';

// Sanear y limpiar la URI. Usamos trim para eliminar / al inicio y final.
$clean_uri = str_replace($base_path, '', $uri);
$parts = explode('/', trim($clean_uri, '/'));

// 2. Determinamos el controlador y el método
$controlador = !empty($parts[0]) ? $parts[0] : 'login';
$metodo = !empty($parts[1]) ? $parts[1] : 'index';

// Tomamos todos los elementos del array $parts a partir del índice 2.
$parametros = array_slice($parts, 2);

// --- 2. MANEJO DE LOCATION (Se mantiene, pero se le añaden parámetros) ---
if ($controlador === 'location') {
    $nombre_clase = 'locationController';
    $controller_instance = new $nombre_clase();
    
    if (method_exists($controller_instance, $metodo)) {
        // 🎯 AÑADIR: Pasamos los parámetros al método
        call_user_func_array([$controller_instance, $metodo], $parametros);
        exit(); 
    } else {
        echo "Error 404: Método no encontrado en LocationController.";
        exit();
    }
}

$db_conexion = new Database();

// Ruta al controlador
$nombre_clase = ucfirst($controlador) . 'Controller';

// --- 3. MANEJO DEL CONTROLADOR PRINCIPAL ---
if (class_exists($nombre_clase)) {

    $controlador_instancia = new $nombre_clase($db_conexion);

    if (method_exists($controlador_instancia, $metodo)) {
        // 🎯 AÑADIR: Llamamos al método pasando los parámetros
        // Esto permite que el método reciba: $controlador_instancia->editarEstudiante('personal', 'XYZ123');
        call_user_func_array([$controlador_instancia, $metodo], $parametros);
    } else {
        echo "Error 404: Método ".$metodo." no encontrado.";
    }
} else {
    echo "Error 404: Controlador ".$nombre_clase." no encontrado.";
}
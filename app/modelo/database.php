<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "php_login_database"; 
    private $mysqli;

    public function __construct() {
        // Establece la conexión al crear un nuevo objeto de la clase Database
        $this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        // Verifica si la conexión falló y registra el error
        if ($this->mysqli->connect_error) {
            $error_msg = "Error de conexión a la base de datos: " . $this->mysqli->connect_error;
            self::log_error($error_msg); // Llama a la función de registro de errores
            die("Ha ocurrido un error inesperado.");
        }

        // Establece el conjunto de caracteres a UTF-8 para evitar problemas de codificación
        $this->mysqli->set_charset("utf8");
    }

    public function getConexion() {
        return $this->mysqli;
    }
    
    // Función estática para registrar errores en un archivo log
    public static function log_error($mensaje) {
        $fecha = date('Y-m-d H:i:s');
        $log_mensaje = "[$fecha] " . $mensaje . PHP_EOL;
        file_put_contents(__DIR__ . '/../errores.log', $log_mensaje, FILE_APPEND);
    }

    public function __destruct() {
        // Cierra la conexión cuando el objeto es destruido
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
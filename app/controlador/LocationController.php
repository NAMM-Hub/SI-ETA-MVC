<?php
class LocationController{
    private $db;

    public function __construct(Database $db){
        $this->db = $db;
    }
    public function getMunicipios() {
        $idEncryptEstado = $_GET['estado_id'] ?? 0;
        if(!empty($idEncryptEstado)){
            $estadoId = decrypt_id($idEncryptEstado);
        }
        if (!is_numeric($estadoId) || $estadoId <= 0) {
            echo json_encode([]);
            return;
        }

        $conexion = $this->db->getConexion();
        $municipios = [];
        if ($estadoId > 0) {

        $stmt = $conexion->prepare("SELECT id_municipio, nombre_municipio FROM municipios WHERE id_estado = ? ORDER BY nombre_municipio");

        $stmt->bind_param("i", $estadoId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $row['id_municipio'] = encrypt_id($row['id_municipio']);
            $municipios[] = $row;
            }
        $stmt->close();
        }
        header('Content-Type: application/json');
        echo json_encode($municipios);
    }

    public function getComunidades() {
        $idEncryptMunicipio = $_GET['municipio_id'] ?? 0;
        if(!empty($idEncryptMunicipio)){
            $municipioId = decrypt_id($idEncryptMunicipio);
        }
        if (!is_numeric($municipioId) || $municipioId <= 0) {
            echo json_encode([]);
            return;
        }
        
        $conexion = $this->db->getConexion();
        
        $comunidades = [];

        if ($municipioId > 0) {
        $stmt = $conexion->prepare("SELECT id_comunidad, nombre_comunidad FROM comunidades WHERE id_municipio = ? ORDER BY nombre_comunidad");

        $stmt->bind_param("i", $municipioId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $row['id_comunidad'] = encrypt_id($row['id_comunidad']);
            $comunidades[] = $row;
            }
        $stmt->close();
        }

        header('Content-Type: application/json');
        echo json_encode($comunidades);
    }
}
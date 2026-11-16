<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "¡Conexión exitosa a gestor_historias_db!";
} else {
    echo "Falló la conexión.";
}
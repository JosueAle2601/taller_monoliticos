<?php

class InformeController
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Mostrar informe general y por responsable
    public function index()
    {
        //Informe general
        $sqlGeneral = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'finalizada'
                    THEN 1 ELSE 0 END) AS finalizadas,
                SUM(CASE WHEN estado IN ('nueva', 'activa')
                    THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN estado = 'impedimento'
                    THEN 1 ELSE 0 END) AS impedimentos
            FROM historias
        ";

        $stmt = $this->conn->prepare($sqlGeneral);
        $stmt->execute();

        $general = $stmt->fetch();

        //Informe agrupado por responsable
        $sqlResponsables = "
            SELECT
                responsable,
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'finalizada'
                    THEN 1 ELSE 0 END) AS finalizadas,
                SUM(CASE WHEN estado IN ('nueva', 'activa')
                    THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN estado = 'impedimento'
                    THEN 1 ELSE 0 END) AS impedimentos
            FROM historias
            GROUP BY responsable
            ORDER BY responsable ASC
        ";

        $stmt = $this->conn->prepare($sqlResponsables);
        $stmt->execute();

        $responsables = $stmt->fetchAll();

        //Informe agrupado por sprint
        $sqlSprints = "
            SELECT
                s.id,
                s.nombre,
                COUNT(h.id) AS total,
                SUM(CASE WHEN h.estado = 'finalizada'
                    THEN 1 ELSE 0 END) AS finalizadas,
                SUM(CASE WHEN h.estado IN ('nueva', 'activa')
                    THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN h.estado = 'impedimento'
                    THEN 1 ELSE 0 END) AS impedimentos
            FROM sprints s
            LEFT JOIN historias h ON s.id = h.sprint_id
            GROUP BY s.id, s.nombre
            ORDER BY s.fecha_inicio DESC
        ";

        $stmt = $this->conn->prepare($sqlSprints);
        $stmt->execute();

        $sprints = $stmt->fetchAll();

        //Enviar información a la vista
        require_once __DIR__ . "/../views/informes/index.php";
    }
}

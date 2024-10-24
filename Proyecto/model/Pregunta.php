<?php
require_once "Usuario.php";
require_once "Tema.php";
class Pregunta{
    private $tabla = "Preguntas";
    private $connection;
    public $usuario;
    public $tema;

    public function __construct(){
        $this -> getConnection();
        $this->usuario = new Usuario();
        $this->tema = new Tema();
    }

    public function getConnection(){
        $dbObj = new db();
        $this -> connection = $dbObj ->connection;
    }

    public function getPreguntasPorTema($id_tema){
        //$this->tabla = "Preguntas";
        $sql = "SELECT * FROM ".$this->tabla. " WHERE id_tema = ?";
        $stmt = $this -> connection ->prepare($sql);

        $stmt->execute([$id_tema]);
        return $stmt ->fetchAll();
    }
    /*Esta linéa del FetchMode lo que hace es convertilo en objetos Usuario donde las columnas de la tabla son los atributos del mismo */
    //$stmt ->setFetchMode(PDO::FETCH_CLASS, 'Pregunta');

    public function getPreguntaById($id){
        $sql = "SELECT * FROM ".$this->tabla. " WHERE id = ?";
        $stmt = $this -> connection ->prepare($sql);

        $stmt->execute([$id]);
        return $stmt ->fetchAll();
    }

    public function getPreguntasPaginated($id_tema, $pagination, $page = 1){
        $limit=$pagination;
        $offset = ($page - 1) * $limit; // Calcula el desplazamiento
        $sql = "SELECT * FROM ".$this->tabla." WHERE id_tema= :id_tema LIMIT :limit OFFSET :offset";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':id_tema', $id_tema, PDO::PARAM_INT);
        $stmt->execute();
        $totalPages = $this->getNumberPages($id_tema, $pagination); //ceil($this->getNumperPages()/$limit);
        return [$stmt->fetchAll(), $page, $totalPages];
    }

    public function getNumberPages($id_tema, $pagination){
        $limit=$pagination;
        $sql = "SELECT COUNT(*) FROM ".$this->tabla. " WHERE id_tema=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id_tema]);
        $total = $stmt->fetchColumn();

        //$total=$this->connection->query("SELECT COUNT(*) FROM ".$this->tabla. " WHERE id_tema=?")->fetchColumn();
        return ceil($total/$limit);
    }

    public function save($param){
        /* Set default values */
        date_default_timezone_set('Europe/Madrid'); // Establece la zona horaria
        $id_tema = $titulo = $texto = $filePath = $id_usuario = "";

        if(isset($param['file_path'])) $filePath = $param['file_path'];
        // Sanitize POST
        $param = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        /* Received values */
        if(isset($param["id_tema"])) $id_tema = $param["id_tema"];
        if(isset($param["titulo"])) $titulo = $param["titulo"];
        if(isset($param["texto"])) $texto = $param["texto"];
        if(isset($param["id_usuario"])) $id_usuario = $param["id_usuario"];
        $votos = 0;
        $fecha_hora = new DateTime();
        $fecha_hora = $fecha_hora->format("Y-m-d H:i:s");

        /* Database operations */
        $sql = "INSERT INTO ".$this->tabla. " (id_tema, titulo, texto, votos, imagen, fecha_hora, id_usuario) 
                VALUES(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id_tema, $titulo, $texto, $votos, $filePath, $fecha_hora, $id_usuario]);
        $id = $this->connection->lastInsertId();

        return $id;
    }


}
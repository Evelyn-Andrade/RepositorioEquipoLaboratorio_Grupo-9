<?php

class M_Permisos{

    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection, Response $response){
        $this->connection = $connection;
        $this->response = $response;
    }

    function SELECT_PERMISOS(){
        $query = "SELECT id_permiso as id, nombre_permiso as nombre, descripcion_permiso as descripcion FROM permiso ORDER BY id_permiso DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->response->success("Permisos obtenidos correctamente", $result, 1);
    }

    function INSERT_PERMISO($arrayData){
        $nombre_permiso = $arrayData['nombre'];
        $descripcion_permiso = $arrayData['descripcion'];
        $query = "INSERT INTO permiso(nombre_permiso, descripcion_permiso) VALUES (:nombre, :descripcion)";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'nombre' => $nombre_permiso, 'descripcion' => $descripcion_permiso ]);
        $this->response->success("Permiso agregado correctamente", [], 1);
    }

    function UPDATE_PERMISO($arrayData){
        $id_permiso = $arrayData['id'];
        $nombre_permiso = $arrayData['nombre'];
        $descripcion_permiso = $arrayData['descripcion'];
        $query = "UPDATE permiso SET nombre_permiso = :nombre, descripcion_permiso = :descripcion WHERE id_permiso = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id' => $id_permiso, 'nombre' => $nombre_permiso, 'descripcion' => $descripcion_permiso ]);
        $this->response->success("Permiso actualizado correctamente", [], 1);
    }

    function DELETE_PERMISO($idPermiso){
        $query = "DELETE FROM permiso WHERE id_permiso = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id' => $idPermiso ]);
        $this->response->success("Permiso eliminado correctamente", [], 1);
    }
}
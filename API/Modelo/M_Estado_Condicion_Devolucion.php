<?php

class M_Estado_Condicion_Devolucion{

    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection, Response $response){
        $this->connection = $connection;
        $this->response = $response;
    }

    function SELECT_ESTADO_CONDICION_DEVOLUCION(){
        $query = "SELECT id_estado_condicion as id, nombre_estado_condicion_devolucion as nombre FROM estado_condicion_devolucion ORDER BY id_estado_condicion DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->response->success("Estados de condición de devolución obtenidos correctamente", $result, 1);
    }

    function INSERT_ESTADO_CONDICION_DEVOLUCION($arrayData){
        $nombre_estado_condicion_devolucion = $arrayData['nombre'];
        $query = "INSERT INTO estado_condicion_devolucion(nombre_estado_condicion_devolucion) VALUES (:nombre)";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'nombre' => $nombre_estado_condicion_devolucion ]);
        $this->response->success("Estado de condición de devolución agregado correctamente", [], 1);
    }

    function UPDATE_ESTADO_CONDICION_DEVOLUCION($arrayData){
        $id_estado_condicion = $arrayData['id'];
        $nombre_estado_condicion_devolucion = $arrayData['nombre'];
        $query = "UPDATE estado_condicion_devolucion SET nombre_estado_condicion_devolucion = :nombre WHERE id_estado_condicion = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id' => $id_estado_condicion, 'nombre' => $nombre_estado_condicion_devolucion ]);
        $this->response->success("Estado de condición de devolución actualizado correctamente", [], 1);
    }

    function DELETE_ESTADO_CONDICION_DEVOLUCION($idEstadoCondicion){
        $query = "DELETE FROM estado_condicion_devolucion WHERE id_estado_condicion = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id' => $idEstadoCondicion ]);
        $this->response->success("Estado de condición de devolución eliminado correctamente", [], 1);
    }
}
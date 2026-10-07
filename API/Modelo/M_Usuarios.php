<?php

class M_Usuarios{

    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection, Response $response){
        $this->connection = $connection;
        $this->response = $response;
    }

    function SELECT_USUARIOS(){
        $query = "SELECT id_usuario, id_perfil, carne_usuario, nombre, apellido, correo, activo, fecha_creacion FROM usuario ORDER BY id_usuario DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->response->success("Usuarios obtenidos correctamente", $result, 1);
    }

    function INSERT_USUARIO($arrayData){
        $id_perfil = $arrayData['perfil'];
        $carne_usuario = $arrayData['carne'];
        $nombre = $arrayData['nombre'];
        $apellido = $arrayData['apellido'];
        $correo = $arrayData['correo'];
        $contrasena = password_hash($arrayData['contrasena'], PASSWORD_BCRYPT);
        $activo = $arrayData['activo'];
        $query = "INSERT INTO usuario (id_perfil, carne_usuario, nombre, apellido, correo, contrasena, activo) VALUES (:perfil, :carne, :nombre, :apellido, :correo, :contrasena, :activo)";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'perfil' => $id_perfil, 'carne' => $carne_usuario, 'nombre' => $nombre, 'apellido' => $apellido, 'correo' => $correo, 'contrasena' => $contrasena, 'activo' => $activo ]);
        $this->response->success("Usuario agregado correctamente", [], 1);
    }

    function UPDATE_USUARIO($arrayData){
        $id_usuario = $arrayData['id'];
        $id_perfil = $arrayData['perfil'];
        $carne_usuario = $arrayData['carne'];
        $nombre = $arrayData['nombre'];
        $apellido = $arrayData['apellido'];
        $correo = $arrayData['correo'];
        $activo = $arrayData['activo'];
        $query = "UPDATE usuario SET id_perfil = :perfil, carne_usuario = :carne, nombre = :nombre, apellido = :apellido, correo = :correo, activo = :activo WHERE id_usuario = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id' => $id_usuario, 'perfil' => $id_perfil, 'carne' => $carne_usuario, 'nombre' => $nombre, 'apellido' => $apellido, 'correo' => $correo, 'activo' => $activo ]);
        $this->response->success("Usuario actualizado correctamente", [], 1);
    }

    function DELETE_USUARIO($idUsuario){
        $query = "DELETE FROM usuario WHERE id_usuario = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id' => $idUsuario ]);
        $this->response->success("Usuario eliminado correctamente", [], 1);
    }
}

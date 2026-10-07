<?php

class M_Usuarios{

    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection, Response $response){
        $this->connection = $connection;
        $this->response = $response;
    }

    function SELECT_USUARIOS(){
        $query = "SELECT id_usuario as id_usu, id_perfil as id_per, carne_usuario as carne, nombre_usuario as nombre, apellido_usuario as apellido, correo_usuario as correo, activo_usuario as activo, fecha_creacion_usuario as fecha_creacion FROM usuario ORDER BY id_usuario DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->response->success("Usuarios obtenidos correctamente", $result, 1);
    }

    function INSERT_USUARIO($arrayData){
        $id_perfil = $arrayData['id_per'];
        $carne_usuario = $arrayData['carne'];
        $nombre_usuario = $arrayData['nombre'];
        $apellido_usuario = $arrayData['apellido'];
        $correo_usuario = $arrayData['correo'];
        $contrasena_usuario = password_hash($arrayData['contrasena'], PASSWORD_BCRYPT);
        $activo_usuario = $arrayData['activo'];
        $query = "INSERT INTO usuario (id_perfil, carne_usuario, nombre_usuario, apellido_usuario, correo_usuario, contrasena_usuario, activo_usuario) VALUES (:id_per, :carne, :nombre, :apellido, :correo, :contrasena, :activo)";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 
            'id_per' => $id_perfil, 
            'carne' => $carne_usuario, 
            'nombre' => $nombre_usuario, 
            'apellido' => $apellido_usuario, 
            'correo' => $correo_usuario, 
            'contrasena' => $contrasena_usuario, 
            'activo' => $activo_usuario]);
        $this->response->success("Usuario agregado correctamente", [], 1);
    }

    function UPDATE_USUARIO($arrayData){
        $params = [
            'id' => $arrayData['id_usu'],
            'id_per' => $arrayData['id_per'],
            'carne' => $arrayData['carne'],
            'nombre' => $arrayData['nombre'],
            'apellido' => $arrayData['apellido'],
            'correo' => $arrayData['correo'],
            'activo' => $arrayData['activo']
        ];

        $setPassword = "";
        if (!empty($arrayData['contrasena'])) {
            $setPassword = ", contrasena_usuario = :contrasena";
            $params['contrasena'] = password_hash($arrayData['contrasena'], PASSWORD_BCRYPT);
        }

        $query = "UPDATE usuario SET id_perfil = :id_per, carne_usuario = :carne, nombre_usuario = :nombre, apellido_usuario = :apellido, correo_usuario = :correo, activo_usuario = :activo $setPassword WHERE id_usuario = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        $this->response->success("Usuario actualizado correctamente", [], 1);
    }

    function DELETE_USUARIO($idUsuario){
        $query = "DELETE FROM usuario WHERE id_usuario = :id_usu";
        $statement = $this->connection->prepare($query);
        $statement->execute([ 'id_usu' => $idUsuario ]);
        $this->response->success("Usuario eliminado correctamente", [], 1);
    }
}

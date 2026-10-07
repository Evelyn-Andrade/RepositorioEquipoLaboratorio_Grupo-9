<?php

class M_Usuarios
{
    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection, Response $response)
    {
        $this->connection = $connection;
        $this->response = $response;
    }

    function SELECT_USUARIOS()
    {
        $query = "SELECT u.id_usuario as IdUsuario, u.nombre_usuario as NombreUsuario, u.id_empleado as IdEmpleado, e.nombre_empleado as NombreEmpleado, e.apellido_empleado as ApellidoEmpleado, u.perfil_usuario as PerfilUsuario, u.descripcion_perfil as DescripcionPerfil, u.estado_usuario as EstadoUsuario FROM Usuarios u INNER JOIN Empleados e ON u.id_empleado = e.id_empleado ORDER BY u.id_usuario DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->response->success("Usuarios obtenidos correctamente", $result, 1);
    }

    function INSERT_USUARIO($arrayData)
    {
        $nombre_usuario    = $arrayData['nombre'];
        $id_empleado       = $arrayData['empleado'];
        $perfil_usuario    = $arrayData['perfil'];
        $descripcion_perfil = $arrayData['descripcion'];
        $contrasena_usuario = password_hash($arrayData['contrasena'], PASSWORD_BCRYPT);
        $estado_usuario    = $arrayData['estado'];

        $query = "INSERT INTO Usuarios (nombre_usuario, id_empleado, perfil_usuario, descripcion_perfil, contrasena_usuario, estado_usuario) VALUES (:nombre, :empleado, :perfil, :descripcion, :contrasena, :estado)";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'nombre'      => $nombre_usuario,
            'empleado'    => $id_empleado,
            'perfil'      => $perfil_usuario,
            'descripcion' => $descripcion_perfil,
            'contrasena'  => $contrasena_usuario,
            'estado'      => $estado_usuario
        ]);
        $this->response->success("Usuario agregado correctamente", [], 1);
    }

    function UPDATE_USUARIO($arrayData)
    {
        $id_usuario        = $arrayData['id'];
        $nombre_usuario    = $arrayData['nombre'];
        $id_empleado       = $arrayData['empleado'];
        $perfil_usuario    = $arrayData['perfil'];
        $descripcion_perfil = $arrayData['descripcion'];
        $estado_usuario    = $arrayData['estado'];

        $query = "UPDATE Usuarios SET nombre_usuario = :nombre, id_empleado = :empleado, perfil_usuario = :perfil, descripcion_perfil = :descripcion, estado_usuario = :estado WHERE id_usuario = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'id'          => $id_usuario,
            'nombre'      => $nombre_usuario,
            'empleado'    => $id_empleado,
            'perfil'      => $perfil_usuario,
            'descripcion' => $descripcion_perfil,
            'estado'      => $estado_usuario
        ]);
        $this->response->success("Usuario actualizado correctamente", [], 1);
    }

    function DELETE_USUARIO($id_usuario)
    {
        $query = "DELETE FROM Usuarios WHERE id_usuario = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $id_usuario]);
        $this->response->success("Usuario eliminado correctamente", [], 1);
    }
}

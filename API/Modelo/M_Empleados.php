<?php

class M_Empleados
{
    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection, Response $response)
    {
        $this->connection = $connection;
        $this->response = $response;
    }

    function SELECT_EMPLE()
    {
        $query = "SELECT id_empleado as IdEmpleado, nombre_empleado as NombreEmpleado, apellido_empleado as ApellidoEmpleado, telefono_empleado as TelefonoEmpleado, correo_empleado as CorreoEmpleado, estado_empleado as EstadoEmpleado FROM Empleados ORDER BY id_empleado DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->response->success("Empleados obtenidos correctamente", $result, 1);
    }

    function INSERT_EMPLE($arrayData)
    {
        $nombre_empleado = $arrayData['nombre'];
        $apellido_empleado = $arrayData['apellido'];
        $telefono_empleado = $arrayData['telefono'];
        $correo_empleado = $arrayData['correo'];
        $estado_empleado = $arrayData['estado'];

        $query = "INSERT INTO empleados (nombre_empleado, apellido_empleado, telefono_empleado, correo_empleado, estado_empleado) VALUES (:nombre, :apellido, :telefono, :correo, :estado)";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'nombre' => $nombre_empleado,
            'apellido' => $apellido_empleado,
            'telefono' => $telefono_empleado,
            'correo' => $correo_empleado,
            'estado' => $estado_empleado
        ]);
        $this->response->success("Empleado agregado correctamente", [], 1);
    }

    function UPDATE_EMPLE($arrayData)
    {
        $id_empleado = $arrayData['id'];
        $nombre_empleado = $arrayData['nombre'];
        $apellido_empleado = $arrayData['apellido'];
        $telefono_empleado = $arrayData['telefono'];
        $correo_empleado = $arrayData['correo'];
        $estado_empleado = $arrayData['estado'];

        $query = "UPDATE empleados SET nombre_empleado = :nombre, apellido_empleado = :apellido, telefono_empleado = :telefono, correo_empleado = :correo, estado_empleado = :estado WHERE id_empleado = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'id' => $id_empleado,
            'nombre' => $nombre_empleado,
            'apellido' => $apellido_empleado,
            'telefono' => $telefono_empleado,
            'correo' => $correo_empleado,
            'estado' => $estado_empleado
        ]);
        $this->response->success("Empleado actualizado correctamente", [], 1);
    }

    function DELETE_EMPLE($id_empleado)
    {
        $query = "DELETE FROM empleados WHERE id_empleado = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $id_empleado]);
        $this->response->success("Empleado eliminado correctamente", [], 1);
    }
}
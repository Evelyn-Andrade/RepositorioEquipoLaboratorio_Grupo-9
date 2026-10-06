<?php

require_once(__DIR__ . "/../Core/Connection.php");
require_once(__DIR__ . "/../Core/Response.php");
require_once(__DIR__ . "/../Modelo/M_Estado_Condicion_Devolucion.php");

$connection = new Connection();
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

parse_str(file_get_contents("php://input"), $_PUT);
parse_str(file_get_contents("php://input"), $_DELETE);

switch ($method){
    case 'GET':
        try {
            $user = new M_Estado_Condicion_Devolucion($connection, $response);
            $user->SELECT_ESTADO_CONDICION_DEVOLUCION();
        } catch (\Throwable $th) {
            $response->error("Error al obtener los estados de condición de devolución", 2001, 400);
        }
        break;

    case 'POST':
        $nombre_estado_condicion_devolucion = $_POST['nombre'] ?? NULL;
        try{
            $user = new M_Estado_Condicion_Devolucion($connection, $response);
            $user->INSERT_ESTADO_CONDICION_DEVOLUCION($_POST);
        } catch (\Throwable $th){
            $response->error("Error al ingresar el estado de condición de devolución: ". $th->getMessage(), 2002, 400);
        }
        break;

    case 'PUT':
        try {
            $user = new M_Estado_Condicion_Devolucion($connection, $response);
            $user->UPDATE_ESTADO_CONDICION_DEVOLUCION($_PUT);
        } catch (\Throwable $th){
            $response->error("Error al actualizar el estado de condición de devolución", 2003, 400);
        }
        break;

    case 'DELETE':
        try {
            $user = new M_Estado_Condicion_Devolucion($connection, $response);
            $user->DELETE_ESTADO_CONDICION_DEVOLUCION($_DELETE['id']);
        } catch (\Throwable $th){
            $response->error("Error al eliminar el estado de condición de devolución", 2004, 400);
        }
        break;
}
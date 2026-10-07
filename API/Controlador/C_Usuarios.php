<?php
require_once(__DIR__ . "/../Core/Connection.php");
require_once(__DIR__ . "/../Core/Response.php");
require_once(__DIR__ . "/../Modelo/M_Usuarios.php");

$connection = new Connection();
$response   = new Response();
$method     = $_SERVER['REQUEST_METHOD'];

parse_str(file_get_contents("php://input"), $_PUT);
parse_str(file_get_contents("php://input"), $_DELETE);

switch ($method) {
    case 'GET':
        try {
            $usuario = new M_Usuarios($connection, $response);
            $usuario->SELECT_USUARIOS();
        } catch (\Throwable $th) {
            $response->error("Error al obtener los usuarios", 2001, 400);
        }
        break;

    case 'POST':
        try {
            $usuario = new M_Usuarios($connection, $response);
            $usuario->INSERT_USUARIO($_POST);
        } catch (\Throwable $th) {
            $response->error("Error al agregar el usuario", 2002, 400);
        }
        break;

    case 'PUT':
        try {
            $usuario = new M_Usuarios($connection, $response);
            $usuario->UPDATE_USUARIO($_PUT);
        } catch (\Throwable $th) {
            $response->error("Error al actualizar el usuario", 2003, 400);
        }
        break;

    case 'DELETE':
        try {
            $usuario = new M_Usuarios($connection, $response);
            $usuario->DELETE_USUARIO($_DELETE['id']);
        } catch (\Throwable $th) {
            $response->error("Error al eliminar el usuario", 2004, 400);
        }
        break;
}

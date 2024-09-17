<?php
require_once "config.php";
header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'mensaje' => ''];

try {
    if ($_POST) {
        $action = $_REQUEST['action'];

        switch ($action) {
            case "registrar":
                $usuario = $_POST['usuario'];
                $password = md5($_POST['password']);
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
    
                $check = "SELECT * FROM datolo WHERE usuario='$usuario'";
                $res = $cx->query($check);
    
                if ($res->num_rows == 0) {
                    $sql = "INSERT INTO datolo (usuario, password, nombre, apellido, foto) VALUES ('$usuario', '$password', '$nombre', '$apellido', null)";
                    if ($cx->query($sql)) {
                        $response['success'] = true;
                        $response['mensaje'] = "guardado";
                    } else {
                        $response['mensaje'] = "no";
                    }
                } else {
                    $response['mensaje'] = "no esta";
                }
                break;

            case "login":
                $usuario = $_POST['usuario'];
                $password = md5($_POST['password']);

                $check = "SELECT * FROM datolo WHERE usuario='$usuario' AND password='$password'";
                $res = $cx->query($check);

                if ($res->num_rows > 0) {
                    $response['success'] = true;
                    $response['mensaje'] = "dentro";
                } else {
                    $response['mensaje'] = "correo o contraseña incorrecta";
                }
                break;

case "select":
    $usuario = $_POST['usuario'];
    $check = "SELECT * FROM datolo WHERE usuario='$usuario'";
    $res = $cx->query($check);

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $response['success'] = true;
        $response['mensaje'] = $row['nombre'];
        $response['foto'] = isset($row['foto']) ? $row['foto'] : ''; // Añade la foto al response
    } else {
        $response['mensaje'] = "no se encontro";
    }
    break;


            case "perfil":
                $usuario = $_POST['usuario'];
                $check = "SELECT * FROM datolo WHERE usuario='$usuario'";
                $res = $cx->query($check);  

                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $response['success'] = true;
                    $response['usuario'] = $row['usuario'];
                    $response['nombre'] = $row['nombre'];
                    $response['apellido'] = $row['apellido'];
                    $response['foto'] = isset($row['foto']) ? $row['foto'] : '';
                } else {
                    $response['mensaje'] = "no se encontro";
                }
                break;

            case "saveperfil":
                $usuario = $_POST['usuario'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $fotoPath = '';

                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['foto']['name'];
                    $fileTmpName = $_FILES['foto']['tmp_name'];
                    $uploadDirectory = 'img_profile/';

                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0755, true);
                    }

                    $filePath = $uploadDirectory . basename($fileName);

                    if (move_uploaded_file($fileTmpName, $filePath)) {
                        $fotoPath = $filePath;
                    } else {
                        $response['mensaje'] = "Error al subir la foto";
                        echo json_encode($response);
                        exit;
                    }
                }

                $sql = "UPDATE datolo SET nombre='$nombre', apellido='$apellido'";
                if ($fotoPath !== '') {
                    $sql .= ", foto='$fotoPath'";
                }
                $sql .= " WHERE usuario='$usuario'";

                if ($cx->query($sql)) {
                    $response['success'] = true;
                    $response['mensaje'] = "datos guardado";
                } else {
                    $response['mensaje'] = "Error";
                }
                break;

            default:
                $response['mensaje'] = "siisi";
                break;
        }
    } else {
        $response['success'] = false;
        $response['mensaje'] = "mal";
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['mensaje'] = "Excepción: " . $e->getMessage();
}

echo json_encode($response);
?>

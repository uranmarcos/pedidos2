<?php
    session_start();
    require("conexion.php");
    $user = new ApptivaDB();

    $accion = "mostrar";
    $res = array("error" => false);
    
    if (isset($_GET["accion"])) {
        $accion = $_GET["accion"];
    }


    switch ($accion) {
        case 'login':
            $usuario    = $_POST["usuario"];
            $password   = $_POST["password"];
            $u = $user -> login($usuario, $password);        
            
            if ($u !== false) { 
                if (empty($u)) {
                    $error = "El DNI ingresado no está registrado";
                    $res["mensaje"] = $error;
                    $res["error"] = true;
                    break;
                }  else {    
                    if ($password == $u[0]["password"]) {
                        $_SESSION["autenticado"] = true;
                        $_SESSION["nombre"] = $u[0]["nombre"];
                        $_SESSION["apellido"] = $u[0]["apellido"];
                        $_SESSION["rol"] = $u[0]["rol"];
                        $_SESSION["dni"] = $u[0]["dni"];
                        $_SESSION["sede"] = $u[0]["sede"];
                        $_SESSION["casa"] = $u[0]["casa"];

                        $mensaje = "login ok";
                        $res["mensaje"] = $mensaje;
                        $res["rol"] = $_SESSION["rol"];
                        $res["error"] = false;
                    } else {
                        $error = "Los datos ingresados sin incorrectos";
                        $res["mensaje"] = $error;
                        $res["error"] = true;
                        break;
                    }
                }
            } else {
                $res["mensaje"] = "Hubo un error de conexión. Intente nuevamente";
                $res["error"] = true;
            }         
        break;

        default:
            # code...
            break;
    }

    echo json_encode($res);
    die();
?>
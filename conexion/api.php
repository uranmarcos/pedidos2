<?php
    require("conexion.php");
    $user = new ApptivaDB();

    $accion = "mostrar";
    $res = array("error" => false);
    if(isset($_GET["accion"])) {
        $accion = $_GET["accion"];
    }

    switch ($accion) {

        case 'insertarSede':
        $provincia  = $_POST["provincia"];
        $localidad  = $_POST["localidad"];
        $casas      = $_POST["casas"];

        $dataValidar = " provincia LIKE '$provincia' and localidad LIKE '$localidad'"; 
        $validacion = $user -> existeSede("sedes", $dataValidar);  
            
        if ($validacion > 0) {
            $res["mensaje"] = "La sede ya existe";
            $res["error"] = true; 
            break;
        }
            
        if ($validacion == false) {
            $res["mensaje"] = "La creación no pudo realizarse";
            $res["error"] = true;
            break;
        }

        $data = "'" . $provincia . "', '" . $localidad . "', '" . $casas . "'";
        $u = $user -> insertar("sedes", $data);
            
        if ($u) { 
            $res["mensaje"] = "La creación se realizó correctamente";
        } else {
            $res["mensaje"] = "La creación no pudo realizarse";
            $res["error"] = true;
        } 
        break;

        case 'consultarSedes':
        $u = $user -> consultar("sedes", 1);
            
        if ($u) { 
            $res["sedes"] = $u;
            $res["mensaje"] = "La consulta se realizó correctamente";
        } else {
            $res["mensaje"] = "No se pudo recuperar las sedes";
            $res["error"] = true;
        } 
        break;

        case 'editarSede':
        $id     = $_POST["id"];
        $casas  = $_POST["casas"];
          
        $data="casas = " . $casas;
        $u = $user -> actualizar("sedes", $data, "id = " . $id);        
            
        if ($u) { 
            $res["mensaje"] = "La modificación se realizó correctamente";
        } else {
            $res["mensaje"] = "La modificación no pudo realizarse";
            $res["error"] = true;
        } 
        break;

        case 'eliminarSede':
        $id = $_POST["id"]; 
        
        $u = $user -> eliminar("sedes", "id = ". $id);
    
        if ($u) { 
            $res["mensaje"] = "La eliminación se realizó correctamente";
        } else {
            $res["mensaje"] = "La eliminación no pudo realizarse";
            $res["error"] = true;
        } 
        break;

        ////
        ////
        ////
        ////


        case 'mostrar':
            $u = $user -> buscar("paisajes", 1);
            if($u): 
                $res["paisajes"] = $u;
                $res["mensaje"] = "exito";
            else: 
                $res["mensaje"] = "Falló la consulta";
                $res["error"] = true;
            endif;
            # code...
            break;

        case 'editar':
            $id             = $_POST["eid"];
            $nombre         = $_POST["enombre"];
            $descripcion    = $_POST["edescripcion"];
            $foto           = "";

            if(isset($_FILES["efoto"]["name"])) {
                $foto           = $_FILES["efoto"]["name"];
                $target_dir = "img/";
                $target_file =$target_dir.basename($foto);
                move_uploaded_file($_FILES['efoto']['tmp_name'], $target_file);
                $foto = ", foto = '" . $_FILES["efoto"]["name"]."'";
            }


            $data="nombre='" . $nombre . "', descripcion='" . $descripcion . "'" . $foto;
            $u = $user -> actualizar("paisajes", $data, "id = " . $id);

            if($u): 
                $res["mensaje"] = "edicion exitosa";
            else: 
                $res["mensaje"] = "Falló la edicion";
                $res["error"] = true;
            endif;
            # code...
            break;


        case 'eliminar':
            $id             = $_POST["did"];

            $u = $user -> borrar("paisajes", "id = ". $id);

            if($u): 
                $res["mensaje"] = "eliminacion exitosa";
            else: 
                $res["mensaje"] = "Falló la eliminacion";
                $res["error"] = true;
            endif;
            # code...
            break;

        case 'insertar':
            $nombre         = $_POST["nombre"];
            $descripcion    = $_POST["descripcion"];
            $foto           = $_FILES["foto"]["name"];

            $target_dir = "img/";
            $target_file =$target_dir.basename($foto);
            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);

            $data="'" . $nombre . "', '" . $descripcion . "', '" . $foto . "'";
            $u = $user -> insertar("paisajes", $data);

            if($u): 
                $res["mensaje"] = "creacion exitosa";
            else: 
                $res["mensaje"] = "Falló el insert";
                $res["error"] = true;
            endif;
            # code...
            break;

        default:
            # code...
            break;
    }

    echo json_encode($res);
    die();
?>
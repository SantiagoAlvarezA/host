<?php
require_once "../DB/ClassConexion.php"; //FICHERO PHP DONDE SE ENCUENTRA LA CONFIGURACION DE CONECION A LA BASE DE DATOS

$action = $_POST['action']; //VARIABLE POR MEDIO DE LA CUAL SE IDENTIFICA QUE ACCION SE DEBE REALLIZAR
// (VARIABLE QUE NOS COMUNICA CON EL FICERO DE JAVASCRIPT)

function insert_country($country_name, $continent, $capital, $area, $number_habitants)
{
    $con = new Conexion(); //CREACION DE UN OBJETO DE LA CLASE CONEXION
    //(CLASE EN LA CUAL SE ENCUETRA LA CONFIGURACION DE CONECCION)

    $conexion = $con->getConexion(); //METODO QUE TRAE LAS CONFIGURACIONES DE CONNECCION
    $msg = '';

    $sql = "INSERT
    INTO country(country_name, continent, capital, area, number_habitants)
    VALUES(:country_name, :continent, :capital, :area, :number_habitants)";

    $query = $conexion->prepare($sql); //PREPARAMOS LA CONSULTA

    $query->bindParam(':country_name', $country_name, PDO::PARAM_STR);
    $query->bindParam(':continent', $continent, PDO::PARAM_STR);
    $query->bindParam(':capital', $capital, PDO::PARAM_STR);
    $query->bindParam(':area', $area, PDO::PARAM_INT);
    $query->bindParam(':number_habitants', $number_habitants, PDO::PARAM_INT);

    if (!$query) {
        $msg = 'No se pudo realizar el registro de este pais';
    } else {
        try {
            $query->execute(); //EJECUTA LA CUNSULTA PARA INSERTAR UN NUEVO PAIS EN LA BASE DE DATOS
            $msg = "Registro realizado con exito";
        } catch (PDOExeption $e) {
            $msg = $e->getMessage(); // SE RETORNA UN MENSAJE DE ERROR EN CASO DE
            // QUE EXISTA UN ERROR AL MOMENTO DE EJECUTAR LA CONSULTA
        }
    }
    return $msg;
}

function show_countries()
{
    $msg = '';
    $data = null;
    $con = new Conexion(); //CREACION DE UN OBJETO DE LA CLASE CONEXION
    //(CLASE EN LA CUAL SE ENCUETRA LA CONFIGURACION DE CONECCION)
    $conexion = $con->getConexion(); //METODO QUE TRAE LAS CONFIGURACIONES DE CONNECCION
    $sql = "SELECT
                id,
                country_name,
                continent,
                capital,
                area,
                number_habitants
             FROM country";

    $query = $conexion->prepare($sql); //PREPARAMOS LA CONSULTA
    $query->execute(); // EJECUTAMOS LA CONSULTA
    while ($result = $query->fetch()) {
        $rows[] = $result; //VARIABLE EN LA CUAL ALMACENAMOS LOS USUARIOS PARA LUEGO ENVIARLOS AL FICHERO
        // DE JAVASCRIPT ENCARGADO DE MOSTRAR LOS DATOS EN EL FORMULARIO DE HTML

    }

    if ($rows) {
        foreach ($rows as $row) {
            $array[] = array(
                'id' => $row['id'],
                'country_name' => $row['country_name'],
                'continent' => $row['continent'],
                'capital' => $row['capital'],
                'area' => $row['area'],
                'number_habitants' => $row['number_habitants'],
            );
        }
        $data = json_encode($array); //GUARDAMOS LOS DATOS EN FORMATO JSON PARA LUEGO INTERPRETARLOS DESDE JAVASCRIPT
        // Y SER MOSTRADOS EN EL FORMULARIO HTML
    } else {
        $msg = 'No hay usuarios registrados';
        $msg_array = array('msg' => $msg);
        $data = json_encode($array);

        return $data;
    }

//bloque para ejecutar las fucniones de acuerdo con la peticion que realize el cliente
    switch ($action) {
        case 'insert':
            echo insert_country(
                $_POST['country_name'],
                $_POST['continent'],
                $_POST['capital'],
                $_POST['area'],
                $_POST['number_habitants']
            );
            break;
        case 'show':
            echo show_countries();
            break;

        default:
            # code...
            break;
    }
}

<?php
require_once "../DB/ClassConexion.php"; //FICHERO PHP DONDE SE ENCUENTRA LA CONFIGURACION DE CONECION A LA BASE DE DATOS

$action = $_POST['action']; //VARIABLE POR MEDIO DE LA CUAL SE IDENTIFICA QUE ACCION SE DEBE REALLIZAR 
                            // (VARIABLE QUE NOS COMUNICA CON EL FICERO DE JAVASCRIPT)
function register_user($name, $lastname, $address, $phone, $document)
{
    $con = new Conexion(); //CREACION DE UN OBJETO DE LA CLASE CONEXION 
                            //(CLASE EN LA CUAL SE ENCUETRA LA CONFIGURACION DE CONECCION)
    $conexion = $con->getConexion();//METODO QUE TRAE LAS CONFIGURACIONES DE CONNECCION
    $msg = '';
    $sql = "INSERT
    INTO users(name, lastname, address, phone, document)
    VALUES(:name, :lastname, :address, :phone, :document)"; // CONSULTA SQL PARA INSERTAR DATOS EN LA TABLA DE USUARIOS

    $query = $conexion->prepare($sql); //PREPARAMOS LA CONSULTA 

    //VERIFICAMOS QUE LOS DATOS QUE SE VAN A INGRESAR A LA BASE DE DATOS ESTEN LIBRES DE CODIGO 
    //CON EL CUAL SE PRETENDA REALIZAR ATAQUES DE TIPO INJECCION SQL A LA BASE DE DATOS
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':phone', $phone, PDO::PARAM_STR);
    $query->bindParam(':document', $document, PDO::PARAM_INT);
    if (!$query) {
        $msg = "Error en el registro"; 
    } else {
        try {
            $query->execute();//EJECUTA LA CUNSULTA PARA INSERTAR UN NUEVO USUARIO EN LA BASE DE DATOS
            $msg = "Registro realizado con exito";
        } catch (PDOExeption $e) {
            $msg = $e->getMessage();// SE RETORNA UN MENSAJE DE ERROR EN CASO DE 
                                    // QUE EXISTA UN ERROR AL MOMENTO DE EJECUTAR LA CONSULTA
        }
    }
    return $msg;
}

function show_user() //FUNCION CON LA CUAL SE REALIZA LA CONSULTA PARA OPTENER TODOS LOS USUARIOS REGISTRADOS
{
    $con = new Conexion();
    $conexion = $con->getConexion();
    $data = null;
    $name = null;
    $lastname = null;
    $address = null;
    $phone = null;
    $document = null;
    $id = null;

    //CONSULTA SQL PARA OBTENER LOS USUARIOS REGISTRADOS
    $sql = 'SELECT
                name,
                lastname,
                address,
                phone,
                document,
                id
            FROM users';

    $query = $conexion->prepare($sql); 
    $query->execute(); // EJECUTAMOS LA CONSULTA
    while ($result = $query->fetch()) {
        $rows[] = $result; //VARIABLE EN LA CUAL ALMACENAMOS LOS USUARIOS PARA LUEGO ENVIARLOS AL FICHERO 
                            // DE JAVASCRIPT ENCARGADO DE MOSTRAR LOS DATOS EN EL FORMULARIO DE HTML
        
    }



    if ($rows ) {     

        foreach ($rows as $row) {
            $name = $row['name'];
            $lastname = $row['lastname'];
            $address = $row['address'];
            $phone = $row['phone'];
            $document = $row['document'];
            $id = $row['id'];


            $array[] = array(
                'name' => $name,
                'lastname' => $lastname,
                'address' => $address,
                'phone' => $phone,
                'document' => $document,
                'id' => $id
            );
            

        }
        $data = json_encode($array); //GUARDAMOS LOS DATOS EN FORMATO JSON PARA LUEGO INTERPRETARLOS DESDE JAVASCRIPT
                                    // Y SER MOSTRADOS EN EL FORMULARIO HTML
        

    } else {
        $msg = 'No hay usuarios registrados';
        $msg_array = array('msg'=>$msg);

        $data = json_encode( $array);
    }
    
    return $data;

}


function delete_user($id){//FUNCION USADA PARA ELIMINAR USUARIOS
    $con = new Conexion();
    $conexion = $con->getConexion();
    $msg = '';

    $sql = 'DELETE 
            FROM users
            WHERE id = :id';//CONSULTA SQL PARA ELIMINAR UN USUARIO DE ACUERDO AL ID

    $query = $conexion->prepare($sql);//SE PREPARA LA CONSULTA PARA EVITAR INJECCION SQL
    $query->bindParam(':id', $id, PDO::PARAM_INT); 

    if (!$query) {
        $msg = "Error al eliminar registro";
    } else {
        try {
            $query->execute();//SE EJECUTA LA CONSULTA
            $msg = "Registro eliminado con exito";
        } catch (PDOExeption $e) {
            $msg = $e->getMessage();// EN EL CASO DE QUE LA CONSULTA NO SE PUEDA EJECUTAR RETORNAMOS UN MENSAJE DE ERROR
        }
    }
    return $msg;

}












switch ($action) { //BLOQUE QUE NOS PERMITE REALIZAR LAS ACCIONES SEGUN LAS PETICIONES REALIZADAS POR EL CLIENTE 


    case 'register':

        echo register_user(
            $_POST['name'],
            $_POST['lastname'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['document']
        );
        break;
    case 'show':
        echo show_user();
    break;
    case 'delete':
        echo delete_user($_POST['id']);
    break;

    default:
        
        # code...
        break;
}


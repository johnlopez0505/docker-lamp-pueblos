<?php
require_once '../respuestas/response.php';
require_once '../modelos/restaurante.class.php';
require_once '../modelos/auth.class.php';

/**
 * endpoint para la gestión de datos con los restaurantes.
 * Get (para objeter todos los restaurantes)
 *  - token (para la autenticación y obtención del id usuario)
 * 
 * Post (para la creación de restaurante)
 *  - token (para la autenticación y obtención del id usuario)
 *  - datos del restaurante por body
 * 
 * Put (para la actualización del restaurante)
 *  *  - token (para la autenticación y obtención del id usuario)
 *  - id del restaurante por parámetro
 *  - datos nuevos del restaurante por body
 * 
 * Delete (para la eliminación del restaurante)
 *  *  - token (para la autenticación y obtención del id usuario)
 *  - id del restaurante por parámetro
 * 
 */


$auth = new Authentication();
//Compara que el token sea el correcto 
$auth->verify();



//hasta aquí, el token está perfectamente verificada. Creamos modelo para que pueda gestionar las peticiones
$restaurante = new restaurante();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$params = $_GET;  //aquí están todos los parámetros por url

       // $auth->insertarLog(); exit;
        //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
        if (isset($_GET['id_usuario']) && !empty($_GET['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($_GET['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
			    exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }


        //Recuperamos todos los restaurantes
        $restaurantes = $restaurante->get($params);
        //$auth->insertarLog('lleva a solicitud de restaurantes');
        $url_raiz_img = "http://".$_SERVER['HTTP_HOST']."/api-restaurantes/public/img";
		for($i=0; $i< count($restaurantes); $i++){
			if (!empty($restaurantes[$i]['imagen']))
				$restaurantes[$i]['imagen'] = $url_raiz_img ."/". $restaurantes[$i]['imagen'];
		}


        $response = array(
            'result'=> 'ok',
            'restaurantes'=> $restaurantes
        );
       // $auth->insertarLog('devuelve restaurantes'); 
        Response::result(200, $response);
        break;
    
    case 'POST':
       // $auth->insertaLog("Recibe petición de creacion de restaurante");

        /**
         * Recibimos el json con los datos a insertar, pero necesitamos
         * ogligatoriamente el id del usuario. Si no está, habrá un error.
         * El id del usuario verificado, deberá ser igual al id_usuario que
         * es la clave secundaria.
         * PUEDO SACAR TAMBIÉN LA id DEL USUARIO A PARTIR DE LA KEY.
         * ESTO LO HARÉ EN OTRA MODIFICACIÓN.
         */
        $params = json_decode(file_get_contents('php://input'), true);
     
       
            //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
        if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id pasado por body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
			    exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }


        $insert_id_restaurante = $restaurante->insert($params);
        //Debo hacer una consulta, para devolver tambien el nombre de la imagen.
        $id_param['id'] = $insert_id_restaurante;
        $restaurante = $restaurante->get($id_param);
        if($restaurante[0]['imagen'] !='')
            $name_file =  "http://".$_SERVER['HTTP_HOST']."/api-restaurantes/public/img/".$restaurante[0]['imagen'];
        else
            $name_file = '';

        $response = array(
			'result' => 'ok insercion',
			'insert_id' => $insert_id_restaurante,
            'file_img'=> $name_file
		);

		Response::result(201, $response);
        break;


    case 'PUT':
        /*
        Es totalmente necesario tener los parámetros del id del restaurante a modificar
        y también el id del usuario, aunque esto lo puedo sacar del token.
        */
		$params = json_decode(file_get_contents('php://input'), true);
      

        if (!isset($params) || !isset($_GET['id']) || empty($_GET['id'])  ){
            $response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud de actualización del restaurante. No has pasado el id del restaurante'
			);

			Response::result(400, $response);
			exit;
        }

         //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
         if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id del body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
			    exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }


        $restaurante->update($_GET['id'], $params);  //actualizo ese restaurante.
        $id_param['id'] = $_GET['id'];
        $restaurante = $restaurante->get($id_param);
       

        if($restaurante[0]['imagen'] !='')
            $name_file =  "http://".$_SERVER['HTTP_HOST']."/api-restaurantes/public/img/".$restaurante[0]['imagen'];
        else
            $name_file = '';
            
        $response = array(
			'result' => 'ok actualizacion',
            'file_img'=> $name_file
		);



		Response::result(200, $response);
        break;


    case 'DELETE':
        /*
        El id, también lo puedo sacar del token. Lo modificaré mas adelante.
        */
        if(!isset($_GET['id']) || empty($_GET['id'])){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}

		$restaurante->delete($_GET['id']);

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);
		break;

	default:
		$response = array(
			'result' => 'error'
		);

		Response::result(404, $response);

		break;


    }

?>
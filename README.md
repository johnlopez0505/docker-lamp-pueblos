# Documentación  `Api-Restaurante`

## Introducción

Este documento detalla la estructura y configuración de la API Restaurante, diseñada para gestionar información relacionada con usuarios y restaurantes. La API utiliza una base de datos MySQL y está implementada con Docker para facilitar la portabilidad y escalabilidad del sistema.

## Creación de las tablas de base de datos

## Creacion de la tabla `usuarios:`

```sql
   
CREATE TABLE `usuarios` (
  `id` int(4) PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(240) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='tabla de usuarios';

```


## Creacion de la tabla `restaurantes:`

```sql
CREATE TABLE `restaurantes` (
  `id` int(7) AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Creamos la relación entre el usuario y restaurante

```sql
    ALTER TABLE `restaurantes`
    ADD CONSTRAINT FK_id_usuario FOREIGN KEY (id_usuario)
    REFERENCES `usuarios` (id);
```

## Creamos el archivo .env: 

Este archivo de configuración debe estar en el gitinore para no subirlo cuando se hace el commit, por que contiene las credenciales de acceso.

```php
    MYSQL_DATABASE= aqui va el nombre de la base de datos
    MYSQL_PASSWORD=  aqui va la contraseña del usuario 
    MYSQL_ROOT_PASSWORD= aqui  va la contraseña del root
    MYSQL_USER= aqui va el username 
    MYSQL_PORT= aqui va el puerto al que se va a mapear
    PHPMYADMIN_PORT= puerto de  phpmyadmin
    PORT= puerto http

```

## Creamos el docker-compose

```code
version: '3'version: "3.1"
services:
    db:
        image: mysql    -Utiliza la imagen oficial de MySQL desde Docker Hub
        ports: 
            - "${MYSQL_PORT}:3306"   -Mapea el puerto del host al puerto del contenedor
        command: --default-authentication-plugin=mysql_native_password
        environment:
            MYSQL_DATABASE: ${MYSQL_DATABASE}   -Nombre de la base de datos
            MYSQL_PASSWORD: ${MYSQL_PASSWORD}   -Contraseña de la base de datos
            MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}  -Contraseña del usuario 
        volumes:
            - ./dump:/docker-entrypoint-initdb.d  - Monta el directorio 'dump' en la inicialización de la base de datos
            - ./conf:/etc/mysql/conf.d  -Monta el directorio 'conf' para configuraciones adicionales
            - persistent:/var/lib/mysql  -Monta el volumen 'persistent' para datos persistentes
        networks:
            - default  -Usa la red predeterminada
    www:
        build: .
        ports: 
            - "{PORT}:80"
        volumes:
            - ./www:/var/www/html
        links:
            - db
        networks:
            - default
    phpmyadmin:
        image: phpmyadmin/phpmyadmin
        links: 
            - db:db
        ports:
            - ${PHPMYADMIN_PORT}:80
        environment:
            MYSQL_USER: ${MYSQL_USER}
            MYSQL_PASSWORD: ${MYSQL_PASSWORD}
            MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
volumes:
    persistent:

```

## RestauranteClass

Se realizaron modificaciones en ciertas partes del codigo

Se cambiaron los parametros permitidos para hacer las consultas con los que necesitaba para mi modelo de datos.

```code

    class restaurante extends Database
    {
        private $table = 'restaurantes';

        //parámetros permitidos para hacer consultas selección.
        private $allowedConditions_get = array(
            'id',
            'id_usuario',
            'nombre',
            'ciudad',
            'provincia',
            'telefono',
            'imagen',
            'page'
        );


        //parámetros permitidos para la inserción.
        private $allowedConditions_insert = array(
            'id_usuario',
            'nombre',
            'ciudad',
            'provincia',
            'telefono',
            'imagen'
        );

        //parámetros permitidos para la actualización.
        private $allowedConditions_update = array(
            'id_usuario',
            'nombre',
            'ciudad',
            'provincia',
            'telefono',
            'imagen'
            
        );
    }
```

Se validan los nuevos parametros ingresados.

```code

    private function validate($data){

		if(!isset($data['id_usuario']) || empty($data['id_usuario'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo id del usuario es obligatorio'
			);
			Response::result(400, $response);
			exit;
		}

		if(!isset($data['nombre']) || empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		if(!isset($data['ciudad']) || empty($data['ciudad'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo ciudad es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		if(!isset($data['provincia']) || empty($data['provincia'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo provincia es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['telefono']) || empty($data['telefono'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo telefono es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
    }
```

## Auth Model

Modificamos para que las variables sean constantes, mas adelante utilizare mi archivo de configuracion .env para estos dato, para que queden ocultos.

```code
/**
 * Modelo para la authenticación.
 */
    class AuthModel{
        private $connection;
        const DB_HOST = 'db';
        const DB_USER = 'root';
        const DB_PASSWORD = 'john';
        const DB_NAME = 'restaurantDb';
        const DB_PORT = '3306';
        
        public function __construct(){
            $this->connection = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME, self::DB_PORT);

            if($this->connection->connect_errno){
                echo 'Error de conexión a la base de datos';
                exit;
            }
        }
    }
```

Se modifical las consultas a la base de datos para prevenir inyección sql

```code
    class AuthModel{
        public function login($email, $password)
        {
            $query = "SELECT id, nombre, email FROM usuarios WHERE email = ? AND password = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param('ss', $email, $password);
            $statement->execute();
            $result = $statement->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

    
        public function update($id, $token)
        {
            $query = "UPDATE usuarios SET token = ? WHERE id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param('si', $token, $id);
            $statement->execute();
            return $statement->affected_rows;
        }
    
        
        public function getById($id)
        {
            $query = "SELECT token FROM usuarios WHERE id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();
            $result = $statement->get_result();
            $resultArray = $result->fetch_all(MYSQLI_ASSOC);
            return $resultArray;
        }
    
        public function insertarLog($milog){
            $query = "INSERT INTO log (log) VALUES(?)";
            $statement = $this->connection->prepare($query);
            $statement->bind_param('s', $milog);
            $statement->execute();
        }
    
        public function devUserModel($id)
        {
            $query = "SELECT id, nombre, email, imagen FROM usuarios WHERE id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();
            $result = $statement->get_result();
            $resultArray = $result->fetch_all(MYSQLI_ASSOC);
            return $resultArray;
        }
    }
```

## Imagenes de consultas a la api con posmant

Hacemos login

![img1](./www/api-restaurantes/recursos/img1.png)

Listamos todos los restaurantes

![img2](./www/api-restaurantes/recursos/img2.png)

Buscamos un resturante por su id
![img3](./www/api-restaurantes/recursos/img3.png)

Editamos un restaurante por su id
![img4](./www/api-restaurantes/recursos/img4.png)


![img5](./www/api-restaurantes/recursos/img5.png)

Elimonamos un restaurante por su id

![img6](./www/api-restaurantes/recursos/img6.png)

![img7](./www/api-restaurantes/recursos/img7.png)

Listamos todos los usuarios

![img8](./www/api-restaurantes/recursos/img8.png)

Buscamos un usuario por su id

![img9](./www/api-restaurantes/recursos/img9.png)

[REPOSITORIO:https://github.com/johnlopez0505/docker-lamp-restaurantes.git](https://github.com/johnlopez0505/docker-lamp-restaurantes.git)




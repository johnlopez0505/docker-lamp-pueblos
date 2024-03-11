<?php

/**
 * Modelo para la authenticación.
 */
class AuthModel
{
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

	/**
	 * Este método, recibe el email y el password ya codificado.
	 * Realiza una query, devolviendo el id, nombres a partir del username y de la password codificada.
	 */

	 public function login($email, $password)
	 {

		$query = "SELECT id, nombre, email FROM usuarios WHERE email = ? AND password = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('ss', $email, $password);
        $statement->execute();
        $result = $statement->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
		//  $query = "SELECT id, nombre, email FROM usuarios WHERE email = '$email' AND password = '$password'";
 
		//  $results = $this->connection->query($query);
 
		//  $resultArray = array();
 
		//  if($results != false){
		// 	 foreach ($results as $value) {
		// 		 $resultArray[] = $value;
		// 	 }
		//  }
 
		//  //devuelve un array con el id, nombres y username.
		//  return $resultArray;
	 }
 
	 /**
	  * Setea el token a partir del id. Cada logeo, tenemos que actualizar el registro.
	  */
 
	 public function update($id, $token)
	 {

		$query = "UPDATE usuarios SET token = ? WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('si', $token, $id);
        $statement->execute();

        return $statement->affected_rows;
		//  $query = "UPDATE usuarios SET token = '$token' WHERE id = $id";
 
		//  $this->connection->query($query);
		 
		//  if(!$this->connection->affected_rows){
		// 	 return 0;
		//  }
 
		//  return $this->connection->affected_rows;
	 }
 
	 /**
	  * Retorna el token dado un id de usuario.
	  */
	 public function getById($id)
	 {
		$query = "SELECT token FROM usuarios WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();

        $result = $statement->get_result();
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);

        return $resultArray;
		//  $query = "SELECT token FROM usuarios WHERE id = $id";
 
		//  $results = $this->connection->query($query);
 
		//  $resultArray = array();
 
		//  if($results != false){
		// 	 foreach ($results as $value) {
		// 		 $resultArray[] = $value;
		// 	 }
		//  }
 
		//  return $resultArray;
	 }
 
 
 
	 public function insertarLog($milog){
		$query = "INSERT INTO log (log) VALUES(?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', $milog);
        $statement->execute();
		//  $query = "INSERT INTO log (log) VALUES('$milog')";
		//  //echo $query;exit;
		//  $this->connection->query($query);
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
		//  $query = "SELECT id, nombre, email, imagen FROM usuarios WHERE id = $id";
 
		//  $results = $this->connection->query($query);
 
		//  $resultArray = array();
 
		//  if($results != false){
		// 	 foreach ($results as $value) {
		// 		 $resultArray[] = $value;
		// 	 }
		//  }
 
		//  //devuelve un array con el id, nombres y username.
		//  return $resultArray;
	 }
 }
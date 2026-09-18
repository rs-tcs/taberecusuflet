<?php

	class Database {  
	
		private $link ; 									// apelarea bazei de date		
		private $rezultat;									// rezultatul interogarii
		//private $debug = 0;									
		
		
		public function __construct(){
			include("db_connect.php");
			
			$this->link = new mysqli(SERVER,USER,PASS,DB_NAME);
			
				if($this->link->connect_error){
					
					// eroare de conectare la serverul mysql
					
					throw new Exception("Eroare de conexiune la serverul Mysql");
					
				}
					
					
		}
	
	
	public function execute($query){
		
		$this->rezultat = $this->link->query($query);
		
		return $this->rezultat;
	}
	
	
	
	public function getResult(){
	
		return $this->rezultat;
	}
	
	public function getAffected(){
	
		return $this->link->affected_rows;
	}
	
	public function getCount($rezultat){
	
		return $rezultat->num_rows;
	}
	
	public function getRow($rezultat){
	
		return $rezultat->fetch_row();
	}	
	public function getAssoc($rezultat){
	
		return $rezultat->fetch_assoc();	
	}
	
	public function getObject($rezultat){
	
		return $rezultat->fetch_array();
	}
	
	public function escape($text){
	
		return mysqli_real_escape_string($this->link,$text);
	}
	
	
}



?>
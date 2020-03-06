<?php
	class Database extends PDO{
		public function __construct(){
			try{
				parent::__construct('mysql:host=localhost;dbname=gebnet;charset=utf8','root','');
				parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			}catch (Exception $e){
				print "¡Error!: " . $e->getMessage() . "<br/>";
    			die();
			}
		}
	}
?>